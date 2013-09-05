<?

require_once('../lib/DBAdapter.php');
require_once('./common.php');
require_once('../../lib/simple_html_dom.php');

define ("URL_LOG","http://www.wolfg.x0.com/index.rb?cmd=log");
define ("URL_VIL","http://www.wolfg.x0.com/index.rb?vid=");
define ("URL_EPI","http://www.wolfg.x0.com/");


//キャスト表の値のキー
$cast_value_k = array("persona","player","role");
$fetchArray = array();

//
//保留していた番号の村が終了したかチェック
//
$fp = fopen('q_ninjin_g.txt','r');

if(flock($fp,LOCK_SH))
{
  $org_queue = fgets($fp);
  $queue     = $org_queue;

  if ($org_queue != null){
    //queueの村番号を配列にする
    $preArray= explode(',',$org_queue);
    array_pop($preArray);
    foreach ($preArray as $vno)
    {
      if (checkVilEnd($vno))
      {
        //終了済の村は後でqueueから消す
        $queue = preg_replace('/'.$vno.',/',"",$queue);
        echo 'Queue is '.$queue.PHP_EOL;
        $fetchArray[] = $vno;
      } else {
        echo 'NOTICE: No.'.$vno.'is still proceeding.'.PHP_EOL;
      }
    }
  } 
} else {
  echo 'ERROR: Cannot lock queue.'.PHP_EOL;
}
fclose($fp);



//
//新規終了村チェック
//
//終了した村一覧から一番上の村番号取得
$html = new simple_html_dom();
$html->load_file(URL_LOG);

$lastNum = $html->find('a',1)->plaintext;
$lastNum =(int) preg_replace('/G(\d+) .+/','$1',$lastNum);

$html->clear;
unset($html);


//DBから一番最後に取得した村番号を取得
$pdo = connect();
$sql = $pdo->prepare("SELECT MAX(vno) FROM village where cid=9");
$sql->execute();
$dbLastNum = $sql->fetch(PDO::FETCH_NUM);
$dbLastNum = (int) $dbLastNum[0];
$pdo = null;

//取得した番号の村が終了しているかチェック
if ($lastNum > $dbLastNum)
{
  $fetchNum = $lastNum - $dbLastNum;
  for($fetchI = 1;$fetchI<=$fetchNum;$fetchI++)
  {
    $vno = 0;
    $vno = $dbLastNum + $fetchI;

    if (checkVilEnd($vno))
    {
      $fetchArray[] = $vno;
    } else {
      //終了していない村は一旦村番号をメモ
      $fp = fopen('q_ninjin_g.txt','a+');
      if(flock($fp,LOCK_SH))
      {
        if(fwrite($fp,$vno.','))
        {
          echo 'NOTICE: '.$vno.' is proceeding. Inserted queue.'.PHP_EOL;
        } else {
          echo 'ERROR:'.$vno.' Cannot write queue.'.PHP_EOL;
        }
      } else {
        echo 'ERROR:'.$vno.' Cannot lock queue.'.PHP_EOL;
      }
      fclose($fp);
    }
  }
}



//取得していない番号を取得
if (!empty($fetchArray))
{
  $html = new simple_html_dom();
  foreach($fetchArray as $vno)
  {
    //
    //プロローグから取得
    //
    //プロローグのURL作成
    $proURL = URL_VIL.$vno."&meslog=000_ready";
    $html->load_file($proURL);

    //ゲルト第一声から開始日時取得
    $vilDate = $html->find('div.ch1',0)->find('a',1)->name;
    $vilDate = preg_replace('/mes(.+)/','$1',$vilDate);
    $vilDate = date("y-m-d",$vilDate);

    //村名取得
    $vilName = $html->find('title',0)->plaintext;
    $vilName = preg_replace('/人狼.+\d+ (.+)/','$1',$vilName);

    //
    //エピローグから取得
    //
    //エピローグ取得
    $epiURL = URL_EPI.$html->find('p a',-2)->href;
    $html->load_file($epiURL);

    //エピが壊れている村は取得できない

    //.announceを取得
    $announceEpi = $html->find('div.announce');

    if(empty($announceEpi))
    {
      echo '*STOP* ERROR: VilNo.'.$vno.' epilogue is broken. log didnt save.=EOL='.PHP_EOL;
      continue;
    }

    //突然死がエピで3人以上出ている村は注意を出す
    $retired_count = mb_substr_count(implode($announceEpi),"突然死した","utf-8");
    if($retired_count >= 3)
    {
      echo '*NOTICE* VilNo.'.$item[0].' is '.$retired_count.' retirer.=>';
      //continue; データは入れておく。手動で取得する場合、キャラ名は登録済の方が楽なので
    }


    //総日数取得
    $days = preg_replace("/.+=0(\d{2})_party/", "$1", $epiURL) + 1;

    //名簿作成準備
    $rawCast = null;
    $rawCast = $html->find('div.announce',-1)->plaintext;
    //simple_html_domを抜けてきたタグを削除(IDに{}があるとbrやaが残る/simple~要改造)
    $modTag = array('/<br \/>/','/<a href=[^>]+>/','/<\/a>/');
    $modTagNo = array('','','');
    $rawCast = preg_replace($modTag,$modTagNo,$rawCast);

    //参加人数
    $nop = count(explode('だった。',$rawCast)) -1; //最後の空白分を減らす

    //人数から編成を確定
    switch($nop)
    {
      case 16:
        $rgl = RGL_G;
        break;
      case 15:
      case 14:
      case 13:
        $rgl = RGL_S_3;
        break;
      default:
        $rgl = RGL_S_2;
        break;
    }


    //勝敗
    $resAnnounce = $html->find('div.announce',-2)->plaintext;
    $resAnnounce = mb_substr($resAnnounce,0,3,"utf-8");
    switch($resAnnounce)
    {
      case '全ての': //村勝利
        $result = TM_VILLAGER;
        break;
      case 'もう人': //狼勝利
        $result = TM_WOLF;
        break;
      default:
        break;
    }

    //
    //参加者の情報を登録
    //
    $rawCast = preg_replace("/\r\n/","",$rawCast);
    $cast = explode('だった。',$rawCast);
    //最後のスペース削除
    array_pop($cast);

    $cast_value = array();
    $cast_keys = array();

    foreach($cast as $castItem)
    {
      //ペルソナ名、プレイヤーID、役職に分ける
      $castArray = explode('##SEPARATOR##',preg_replace("/ ?(.+) （(.+)）、(生存|死亡)。(.+)$/", "$1##SEPARATOR##$2##SEPARATOR##$4", $castItem));

      //得た情報にラベルがわりのキーを付ける
      $castArray = array_combine($cast_value_k,$castArray);
      
      //末尾に半角スペースがある場合は、読み込めるように変換する
      if(mb_substr($castArray['player'],-1,1,"utf-8")==' ')
      {
        $castArray['player'] = preg_replace("/ /","&amp;nbsp;",$castArray['player']);
        echo 'ID has &nbsp:'.$castArray['player']."=>";
      }
      //値の配列に入れる
      $cast_value[] = $castArray;
      //キー用配列にペルソナ名を入れる
      $cast_keys[] = $castArray["persona"];
    }

    //ペルソナ名をキーに詳細情報を引き出す配列を作る
    $cast = array_combine($cast_keys,$cast_value);
    unset($castItem,$castArray,$cast_keys,$cast_value);

    //死因と寿命を入れる配列を作成
    //この辺あとで直す->ひとまとめの入れ子配列にする
    //どうせ共用関数に入れる
    $cast_retired = array();
    $cast_hanged = array();
    $cast_eaten = array();

    $announce = $html->find('div.announce');

    //最終日の吊り襲撃を入れる
    sortDestiny($announce,$days,$cast_retired,$cast_hanged,$cast_eaten,$cast);
    //進行中のURLを作成
    $prgURL = mb_substr($proURL,0,-7,"utf-8"); //末尾の0_readyを削除(000_ready)
    //
    //進行中のdestinyを取得
    //
    for($i=1, $prgDays=$days-1; $i<=$prgDays; $i++)
    {
      if($i >= 10) //進行日数が二桁になる場合
      {
        $prgURL10 = mb_substr($prgURL,0,-1,"utf-8"); //末尾のゼロを削除
        $url = $prgURL10.$i.'_progress';
      } else{
        $url = $prgURL.$i.'_progress';
      }
      $html->load_file($url);
      $announcePrg = $html->find('div.announce');

      sortDestiny($announcePrg,$i+1,$cast_retired,$cast_hanged,$cast_eaten,$cast);
      unset($announcePrg);
    }

    //
    //取得したdestinyとendを配列に格納する
    //
    makeCastDestiny($cast,$cast_retired,DES_RETIRED);
    makeCastDestiny($cast,$cast_hanged,DES_HANGED);
    makeCastDestiny($cast,$cast_eaten,DES_EATEN);

    //生存処理、skill,team,life,result
    $destiny = array("destiny"=>DES_ALIVE);
    $end = array("end"=>$days);

    foreach($cast as $miscItem)
    {
      $persona = $miscItem['persona'];

      //destinyが埋まっていない人を生存とする
      if(empty($miscItem['destiny']))
      {
        $cast[$miscItem['persona']] += $destiny;
        $cast[$miscItem['persona']] += $end;
      }

      //役職によって能力と陣営を挿入する
      switch($cast[$persona]['role'])
      {
      case '人狼':
        $skill = array('skill'=>SKL_WOLF);
        $team = array('team'=>TM_WOLF);
        break;
      case '狂人':
        $skill= array('skill'=>SKL_LUNATIC);
        $team = array('team'=>TM_WOLF);
        break;
      default:
        $team = array('team'=>TM_VILLAGER);
        switch($cast[$persona]['role'])
        {
        case '村人':
          $skill = array('skill'=>SKL_VILLAGER);
          break;
        case '占い師':
          $skill = array('skill'=>SKL_SEER);
          break;
        case '霊能者':
          $skill = array('skill'=>SKL_MEDIUM);
          break;
        case '狩人':
          $skill = array('skill'=>SKL_HUNTER);
          break;
        }
        break;
      }

      $cast[$miscItem['persona']] += $skill;
      $cast[$miscItem['persona']] += $team;

      //生存係数を挿入
      if($cast[$miscItem['persona']]['destiny'] === DES_ALIVE)
      { 
        //生存者は1
        $life = 1.00;
      } else {
        //死亡者は死亡日の前日(=生きていた日)/全日程
        $life = round(($cast[$persona]['end']-1) / $days,2);
      }
      $life = array('life'=>$life);
      $cast[$miscItem['persona']] += $life;

      //勝敗を挿入
      if($cast[$persona]['team'] === $result)
      {
        $castResult = array('result'=>RSL_WIN);
      } else {
        $castResult = array('result'=>RSL_LOSE);
      }
      $cast[$miscItem['persona']] += $castResult;
    }

    //書き込み関数に渡す配列に挿入
    $vilList[] = array(9,$vno,$vilName,$vilDate,$nop,$rgl,$days,$result);
    //$vilList[] = array(
      //'cid'=>9,
      //'vno'=>$vno,      //村番号
      //'name'=>$vilName,  //村名
      //'date'=>$vilDate,   //開始日
      //'nop'=>$nop,        //参加者人数
      //'rglid'=>$rgl,           //編成
      //'days'=>$days,      //総日数
      //'wtmid'=>$result,   //勝利陣営
    //);
    $usrList[] = $cast;

    //echo 'Got Vno.'.$vno.'.'.PHP_EOL;

  }
  $html->clear;
  unset($html);


  //
  //バックアップを取る
  //
  $fp = fopen('ninjin.bak','w+');
  if($fp)
  {
    if(flock($fp,LOCK_EX))
    {
      fwrite($fp,print_r($vilList,true));
      fwrite($fp,print_r($usrList,true));
    } else {
      echo 'ERROR: cannot lock file.';
      fclose($fp);
      exit;
    }
  } else {
    echo 'ERROR cannot open file.';
    exit;
  }

  //
  //DBに入力
  //
  $pdo = connect();


  foreach($vilList as $key=>$item)
  {
    $vilID = insertVillage($pdo,$item);
    if ($vilID != 0)
    {
      insertUser($pdo,$vilID,$usrList[$key]);
    } else {
      continue;
    }
  }

  $pdo = null;
  echo 'complete insert.';


  //queueファイルを更新する
  if ($org_queue !== $queue)
  {
    $fp = fopen('q_ninjin_g.txt','r+');
    if(flock($fp,LOCK_SH))
    {
      //ファイル内容を削除する
      ftruncate($fp,0);
      fseek($fp, 0);
      //変更内容を書き込む
      fwrite($fp,$queue);
    } else {
      echo 'ERROR: Cannot lock queue.'.PHP_EOL;
    }

    fclose($fp);
  }

} else {
  echo 'not fetch.';
  unset($html);
}



function connect()
{
  try{
    $pdo = new DBAdapter();
    return $pdo;
  } catch (pdoexception $e){
    var_dump($e->getMessage());
    exit;
  }

}

function checkVilEnd($vno)
{
  $fetchURL = URL_VIL.$vno;
  $html = new simple_html_dom();
  $html->load_file($fetchURL);
  $endFlg = $html->find('span.time',0)->plaintext;

  $html->clear;
  unset($html);

  if ($endFlg ==='終了 ')
  {
    return true;
  } else {
    return false;
  }

}

function insertVillage($pdo,$vilList)
{
  $vno = $vilList[1];
  //村を入力
  $sql = $pdo->prepare("INSERT INTO village(cid,vno,name,date,nop,rglid,days,wtmid) VALUES (?,?,?,?,?,?,?,?)");
  $sqlBool = $sql->execute($vilList);

  if($sqlBool)
  {
    $stmt = $pdo->prepare("SELECT id FROM village WHERE cid=9 AND vno=?");
    $stmt->bindColumn(1,$vilID);
    $stmt->execute(array($vno));
    $stmt->fetch(PDO::FETCH_BOUND);

    //echo 'No. '.$vno.' is inserted. vilID is'.$vilID.'=>USER'.PHP_EOL;
    return $vilID;
  } else {
    echo 'ERROR: No. '.$vno.' not inserted.=EOL='.PHP_EOL;
    return 0;
  }
}


function insertUser($pdo,$vilID,$cast)
{
  foreach($cast as $item)
  {
    $sql = $pdo->prepare("
      INSERT INTO users (vid,persona,player,role,dtid,end,sklid,tmid,life,rltid) values (?,?,?,?,?,?,?,?,?,?)");

    //連想配列ではexecuteできない
    $item= array_values($item);
    array_unshift($item,$vilID);
    $sqlBool = $sql->execute($item);

    if($sqlBool)
    {
      //echo '>>'.$item[1].' ('.$item[1].') in vilID:'.$vilID.' inserted.'.PHP_EOL;
    }else{
      echo '>>ERROR:'.$item[1].'/'.$item[2].' in vilID:'.$vilID.' was NOT inserted'.PHP_EOL;
    }
  }
}



function sortDestiny($announce,$desDay,&$cast_retired,&$cast_hanged,&$cast_eaten,$cast)
{
  foreach($announce as $annItem)
  {
    $strswt = trim($annItem->plaintext);
    //負の値から取得を始める場合、文字数も指定しないと取得できない
    $strswt = mb_substr($strswt,-6,6,"utf-8");
    $strswt = preg_replace("/\r\n/","",$strswt);

    switch($strswt)
    {
    case "突然死した。":
      $ann = preg_replace("/^ ?(.+) は、突然死した。 ?/", "$1", $annItem->plaintext);
      $cast_retired += array($ann=>$desDay);
      break;
    case "処刑された。":
      $ann = preg_replace("/(.+\r\n){1,}\r\n(.+) は村人達の手により処刑された。 ?/", "$2", $annItem->plaintext);
      $cast_hanged += array($ann=>$desDay);
      break;
    case "発見された。":
        $ann = preg_replace("/.+朝、(.+) が無残.+\r\n ?/", "$1", $annItem->plaintext);
        $cast_eaten += array($ann=>$desDay);
      break;
    default:
      break;
    }   
  }   
}

function makeCastDestiny(&$cast,&$cast_destiny,$desID)
{
  if(empty($cast_destiny))
  {
    return;
  }
  $destiny = array("destiny"=>$desID);
  foreach($cast_destiny as $persona=>$endDay)
  {
    $end = array("end"=>$endDay);
    $cast[$persona] += $destiny;
    $cast[$persona] += $end;
  }
}

