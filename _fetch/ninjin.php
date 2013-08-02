<?

//
//ライブラリ読込
//
require_once('common.php');
require_once('simple_html_dom.php');


//取得する国のアルファベットを入力;
$c = 'old';
//villageの開始idを入力
$vid = 6195;


/*--------------------------------------------------------------------------------

  0. 定数・変数

--------------------------------------------------------------------------------*/
// 後で別ファイルにする　国共通
// MYSQL側のテーブルを変更した際に再確認する
// G国自動取得用に後でリファインする
define ('CNT_A',3);
define ('CNT_B',4);
define ('CNT_C',5);
define ('CNT_D',6);
define ('CNT_E',7);
define ('CNT_F',8);
define ('CNT_G',9);
define ('CNT_0',2);
define ('CNT_OLD',10);

define('URL_OLD','http://ninjinix.x0.com/wolf');
define('URL_F','http://ninjin002.x0.com/wolff/');
define('URL_G','http://www.wolfg.x0.com/');



switch($c)
{
case 'f':
  $url_vil = URL_F;
  $country = CNT_F;
  break;
case 'g':
  $url_vil  = URL_G;
  $country = CNT_G;
  break;
case 'old':
  $url_vil = URL_OLD.'/';
  $country = CNT_OLD;
  break;
default: //abcde国
  $url_vil = URL_OLD.$c.'/';
  switch($c)
  {
  case 'a':
    $country = CNT_A;
    break;
  case 'b':
    $country = CNT_B;
    break;
  case 'c':
    $country = CNT_C;
    break;
  case 'd':
    $country = CNT_D;
    break;
  case 'e':
    $country = CNT_E;
    break;
  case '0':
    $country = CNT_0;
    break;
  default:
    break;
  }
  break;
}
 /*--------------------------------------------------------------------------------

   1. 村一覧読込

 --------------------------------------------------------------------------------*/

//村リストの読み込み
$baseVilList = readList('ninjin_'.$c);
$usrList = array();

//クラス作成
$html = new simple_html_dom();

//
//各村詳細
//
foreach($baseVilList as $item)
{
  //
  //プロローグから取得
  //
  $html->load_file($item[2]);

  //開始日時取得　ゲルト第一声から
  if($country === CNT_G)
  {
    $mesTime = $html->find('div.ch1',0)->find('a',1)->name;
  } else {
    $mesTime = $html->find('div.message',2)->find('a',0)->name;
  }
  $epoch = preg_replace('/mes(.+)/','$1',$mesTime);
  $vilDate = date("y-m-d",$epoch);


  //エピローグURL取得
  $epiURL = $url_vil.$html->find('p a',-2)->href;

  //
  //エピローグから取得
  //
  //エピローグ取得
  $html->load_file($epiURL);


  //.announceを取得
  $announceEpi = $html->find('div.announce');

  //エピが壊れている村は取得できない
  if(empty($announceEpi))
  {
    echo '*STOP* ERROR: VilNo.'.$item[0].' epilogue is broken. log didnt save.=EOL='.PHP_EOL;
    continue;
  }

  //突然死がエピで3人以上出ている村は注意を出す
  $retired_count = mb_substr_count(implode($announceEpi),"突然死した");
  if($retired_count >= 3)
  {
    echo '*NOTICE* VilNo.'.$item[0].' is '.$retired_count.' retirer.=>';
    //continue; データは入れておく。手動で取得する場合、キャラ名は登録済の方が楽なので
  }



  //総日数取得　エピローグURLから
  if($country === CNT_G)
  {
    $days = preg_replace("/.+=0(\d{2})_party/", "$1", $epiURL) + 1;
  } else{
    $days = preg_replace("/.+_party_(\d+)/", "$1", $epiURL) + 1;
  }

  $rawCast = $html->find('div.announce',-1)->plaintext;

  //simple_html_domを抜けてきたタグを削除
  //IDに{}があるとbrやaが残る
  $modTag = array('/<br \/>/','/<a href=[^>]+>/','/<\/a>/');
  $modTagNo = array('','','');
  $rawCast = preg_replace($modTag,$modTagNo,$rawCast);


  //参加人数
  $nop = count(explode('だった。',$rawCast)) -1; //最後の空白分を減らす

  //人数から編成を確定
  switch ($nop)
  {
  case 17://E国
    $rgl = RGL_E;
    break;
  case 16:
    switch($country)
    {
      case CNT_G:
        $rgl = RGL_G;
        break;
      case CNT_C:
        $rgl = RGL_C;
        break;
      default:
        $rgl = RGL_F;
        break;
    }
    break;
  case 15:
    if ($country === CNT_C)
    {
      $rgl = RGL_S_C3;
    } else {
      $rgl = RGL_S_3;
    }
    break;
  case 14:
  case 13:
      switch($country)
      {
      case CNT_G: //G国は13人以上で狼3
        $rgl = RGL_S_3;
        break;
      case CNT_C:
        $rgl = RGL_S_C2;
        break;
      default:
        $rgl = RGL_S_2;
        break;
      }
    break;
  default:
    if($country === CNT_C)
    {      
      $rgl = RGL_S_C2;
    } else {
      $rgl = RGL_S_2;
    }
    break;
  }

  //勝敗
  $resAnnounce = $html->find('div.announce',-2)->plaintext;
  if($country === CNT_G)
  {
    $resAnnounce = mb_substr($resAnnounce,0,3);
  } else {
    $resAnnounce = mb_substr($resAnnounce,1,3);
  }
  switch($resAnnounce)
  {
  case '全ての': //村勝利
    $result = TM_VILLAGER;
    break;
  case 'もう人': //狼勝利
    $result = TM_WOLF;
    break;
  case '全ては': //ハム勝利
    $result = TM_FAIRY;
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
  //キャスト表の値のキー
  $cast_value_k = array("persona","player","role");

  foreach($cast as $castItem)
  {
    //ペルソナ名、プレイヤーID、役職に分ける
    $castArray = explode('##SEPARATOR##',preg_replace("/ ?(.+) （(.+)）、(生存|死亡)。(.+)$/", "$1##SEPARATOR##$2##SEPARATOR##$4", $castItem));

    //E国ならID@右のTypekeyを削除
    if($country === CNT_E)
    {
      $castArray[1] = preg_replace("/([^@]+)@.+/", "$1", $castArray[1]);
    }

    //得た情報にラベルがわりのキーを付ける
    $castArray = array_combine($cast_value_k,$castArray);
    //値の配列に入れる
    $cast_value[] = $castArray;
    //キー用配列にペルソナ名を入れる
    $cast_keys[] = $castArray["persona"];
  }
  //ペルソナ名をキーに詳細情報を引き出す配列を作る
  $cast = array_combine($cast_keys,$cast_value);

  unset($castItem,$castArray,$cast_value_k,$cast_keys,$cast_value);

  //死因と寿命を入れる配列を作成
  //この辺あとで直す->ひとまとめの入れ子配列にする
  //どうせ共用関数に入れる
  $cast_retired = array();
  $cast_hanged = array();
  $cast_eaten = array();
  $cast_cursed = array();

  $announce = $html->find('div.announce');

  sortDestiny($announce,$days,$cast_retired,$cast_hanged,$cast_eaten,$cast_cursed,$cast,$country);


  //進行中のURLを作成
  if($country === CNT_G)
  {
    $prgURL = mb_substr($item[2],0,-7); //末尾の0_readyを削除(000_ready)

  } else {
    $prgURL = mb_substr($item[2],0,-7); //末尾の"ready_0"を削除
    $prgURL = $prgURL.'progress_';

  }
  //
  //進行中のdestinyを取得
  //
  for($i=1, $prgDays=$days-1; $i<=$prgDays; $i++)
  {
    if($country === CNT_G)
    {
      if($i >= 10) //進行日数が二桁になる場合
      {
        $prgURL10 = mb_substr($prgURL,0,-1); //末尾のゼロを削除
        $url = $prgURL10.$i.'_progress';
      } else{
        $url = $prgURL.$i.'_progress';
      }
    } else {
      $url = $prgURL.$i;
    }
    $html->load_file($url);
    //$htmlPrg = file_get_html($url);
    $announcePrg = $html->find('div.announce');

    sortDestiny($announcePrg,$i+1,$cast_retired,$cast_hanged,$cast_eaten,$cast_cursed,$cast,$country);
    //$html->clear;
    unset($announcePrg);

  }

  //
  //取得したdestinyとendを配列に格納する
  //
  makeCastDestiny($cast,$cast_retired,DES_RETIRED);

  makeCastDestiny($cast,$cast_hanged,DES_HANGED);

  makeCastDestiny($cast,$cast_eaten,DES_EATEN);

  makeCastDestiny($cast,$cast_cursed,DES_CURSED);


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
    case 'ハムスター人間':
      $skill = array('skill'=>SKL_FAIRY);
      $team  = array('team'=>TM_FAIRY);
      break;
    case '人狼':
      $skill = array('skill'=>SKL_WOLF);
      $team = array('team'=>TM_WOLF);
      break;
    case '狂人':
      if($country === CNT_C)
      {//C国は囁き狂人
        $skill = array('skill'=>SKL_LUNAWHS);
      } else {
        $skill= array('skill'=>SKL_LUNATIC);
      }
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
      case '共有者':
        $skill = array('skill'=>SKL_MASON);
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
  unset($miscItem,$destiny,$end,$skill,$team,$castResult,$life);

  //書き込み関数に渡す配列に挿入
  $vilList[] = array(
    'vno'=>$item[0],      //村番号
    'name'=>$item[1],  //村名
    'date'=>$vilDate,   //開始日
    'nop'=>$nop,        //参加者人数
    'rglid'=>$rgl,           //編成
    'days'=>$days,      //総日数
    'wtmid'=>$result,   //勝利陣営
  );
  $usrList[] = $cast;

  echo 'Got Vno.'.$item[0].'.'.PHP_EOL;

}
unset($item);

/*--------------------------------------------------------------------------------

  2. SQL文に加工して保存

--------------------------------------------------------------------------------*/

writeList($vilList,'village',$country);
writeList($usrList,'users',$vid);

$html->clear;
unset($html);


function writeList($data,$type,$prefix)
{

  //INSERT文用意
  if ($type === 'village')
  {
    $sql = 'INSERT INTO '.$type." (cid,vno,name,date,nop,rglid,days,wtmid) VALUES\n"; 
  }
  //switch($type)
  //{
  //case 'village':
    //$sql = 'INSERT INTO '.$type." (cid,vno,name,date,nop,rglid,days,wtmid) VALUES\n"; 
    //break;
  //case 'users':
    //$sql = 'INSERT INTO '.$type." (vid,persona,player,role,dtid,end,sklid,tmid,life,rltid) VALUES\n"; 
    //break;
  //}


  $fp = fopen($type.'.sql','w+');//ファイルを新たに作って書き込む
  if($fp){
    if(flock($fp,LOCK_EX))
    {
      $endKey = count($data) - 1; //一番最後の配列番号を記憶

      //usersは後ほど分割するので$sql文をつけない
      if($type === 'village')
      {
        fwrite($fp,$sql);
      }

      switch($type)
      {
      case 'village':
        foreach($data as $key=>$item)
        {
          makeSQL($fp,$prefix,$item);
          //fwrite($fp,$sql);

          //最終行なら;、それ以外なら,で改行して次へ
          if($key !== $endKey)
          {
            $sql = ",\n";
          } else {
            $sql = ';';
          }
          fwrite($fp,$sql);
        }
        echo 'village.sql done.'.PHP_EOL;
        break;
      case 'users':
        //後でシェルスクリプトで分割編集する
        $sql = ",\n";
        foreach($data as $item)
        {
          foreach($item as $cast)
          {
            makeSQL($fp,$prefix,$cast);
            fwrite($fp,$sql);

          }
          ++$prefix;

        }
        echo 'users.sql done.';

        //foreach($data as $key=>$item)
        //{
          //if($key !== $endKey) //一番最後の村かどうか
          //{
            //foreach($item as $cast)
            //{
              //makeSQL($fp,$prefix,$cast);
              //$sql = ",\n";
              //fwrite($fp,$sql);
            //}
          //} else {
            //$endKeyDetail = count($item) -1;
            //$i = 0;
            //foreach($item as $cast)
            //{
              //makeSQL($fp,$prefix,$cast);

              //if($i !== $endKeyDetail)
              //{
                //$sql = ",\n";
              //} else {
                //$sql = ';';
              //}
              //fwrite($fp,$sql);
              //$i++;

            //}
          //}
          //++$prefix;  //vidを増やす
        //}
        break;
      }
    } else {
      echo 'ERROR: cannot lock file.';
      fclose($fp);
      exit;
    }
  } else {
    echo 'ERROR: cannot open file.';
    exit;
  }

  fclose($fp);

}


function makeSQL($fp,$prefix,$values)
{
  $sql= '("'.$prefix.'","'.implode('","',array_values($values)).'")';
  fwrite($fp,$sql);

}



function sortDestiny($announce,$desDay,&$cast_retired,&$cast_hanged,&$cast_eaten,&$cast_cursed,$cast,$country)
{
  foreach($announce as $annItem)
  {
    $strswt = trim($annItem->plaintext);
    $strswt = mb_substr($strswt,-6);
    $strswt = preg_replace("/\r\n/","",$strswt);


    //echo $strswt;
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
      if($country === CNT_E)
      {
        $ann = preg_replace("/.+朝、(.+) が無残.+ /", "$1", $annItem->plaintext);
        if (mb_ereg_match('.*と',$ann))//二人無残の場合
        {
          $ann = explode(" と ",$ann);
          foreach($ann as $ishamu)
          {
            if($cast[$ishamu]['role'] == 'ハムスター人間')
            {
              $cast_cursed += array($ishamu=>$desDay);
            } else {
              $cast_eaten += array($ishamu=>$desDay);
            }

          }

        } else if($cast[$ann]['role'] == 'ハムスター人間'){ //一人無残の場合
          $cast_cursed += array($ann=>$desDay);
        } else {
          $cast_eaten += array($ann=>$desDay);
        }
      } else {
        $ann = preg_replace("/.+朝、(.+) が無残.+\r\n ?/", "$1", $annItem->plaintext);
        $cast_eaten += array($ann=>$desDay);
      }
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
