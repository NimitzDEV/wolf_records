<?php

require_once('../../lib/simple_html_dom.php');
require_once('./data.php');
require_once('./check_village.php');
require_once('./insert_db.php');

mb_internal_encoding("UTF-8");

define ("COUNTRY","ninjin_g");
define ("CID",9);
define ("URL_LOG","http://www.wolfg.x0.com/index.rb?cmd=log");
define ("URL_VIL","http://www.wolfg.x0.com/index.rb?vid=");

$db    = new Insert_DB(CID);
$check = new Check_Village(COUNTRY,CID,URL_VIL,URL_LOG);
$check->check_queue();
$check->check_fetch_vno();
if($check->get_village())
{
  $fetched_v = $check->get_village();
}
else
{
  echo 'not fetch.';
  exit;
}

$fetch = new simple_html_dom();
$data  = new Data();

$CAST_VALUE_KEY = array("persona","player","role");
$DOPPEL = array(
   "asaki"=>"asaki&lt;G国&gt;"
  ,"motimoti"=>"motimoti&lt;G薔薇国&gt;"
);

//var_dump($fetched_v);

//キャスト表の値のキー
//$cast_value_k = array("persona","player","role");
//$fetchArray = array();


//取得していない番号を取得
foreach($fetched_v as $vno)
{
  //初期化
  $village = array(
             'cid'  =>CID
            ,'vno'  =>$vno
            ,'name' =>""
            ,'date' =>""
            ,'nop'  =>""
            ,'rglid'=>""
            ,'days' =>""
            ,'wtmid'=>""
  );

  //プロローグから取得
  $proURL = URL_VIL.$vno."&meslog=000_ready";
  $fetch->load_file($proURL);

  //ゲルト第一声から開始日時取得
  $date = $fetch->find('div.ch1',0)->find('a',1)->name;
  $village['date'] = date("y-m-d",preg_replace('/mes(.+)/','$1',$date));

  //村名取得
  $name = $fetch->find('title',0)->plaintext;
  $village['name'] = preg_replace('/人狼.+\d+ (.+)/','$1',$name);


  //エピローグ取得
  $url_epi = preg_replace("/index\.rb\?vid=/","",URL_VIL);
  $url_epi = $url_epi.$fetch->find('p a',-2)->href;

  $fetch->clear();
  $fetch->load_file($url_epi);

  //エピが壊れている村は取得できない
  if(!$fetch->find('div.announce'))
  {
    echo '*STOP* ERROR: VilNo.'.$vno.' epilogue is broken. log didnt save.=EOL='.PHP_EOL;
    continue;
  }

  //総日数取得
  $village['days'] = preg_replace("/.+=0(\d{2})_party/", "$1", $url_epi) + 1;

  //名簿作成準備
  $cast = preg_replace("/\r\n/","",$fetch->find('div.announce',-1)->plaintext);
  //simple_html_domを抜けてきたタグを削除(IDに{}があるとbrやaが残る)
  $cast = preg_replace(array('/<br \/>/','/<a href=[^>]+>/','/<\/a>/'),array('','',''),$cast);
  $cast = explode('だった。',$cast);
  //最後のスペース削除
  array_pop($cast);

  //参加人数
  $village['nop'] = count($cast);

  //人数から編成を確定
  switch($village['nop'])
  {
    case 16:
      $village['rglid'] = $data::RGL_G;
      break;
    case 15:
    case 14:
    case 13:
      $village['rglid'] = $data::RGL_S_3;
      break;
    default:
      $village['rglid']= $data::RGL_S_2;
      break;
  }

  //勝敗
  $wtmid = mb_substr($fetch->find('div.announce',-2)->plaintext,0,3);
  switch($wtmid)
  {
    case '全ての': //村勝利
      $village['wtmid'] = $data::TM_VILLAGER;
      break;
    case 'もう人': //狼勝利
      $village['wtmid'] = $data::TM_WOLF;
      break;
    default:
      break;
  }

  $cast_value = array();
  $cast_keys = array();

  foreach($cast as $item_cast)
  {
    //ペルソナ名、プレイヤーID、役職に分ける
    $item_cast = preg_replace("/ ?(.+) （(.+)）、(生存|死亡)。(.+)$/", "$1#SP#$2#SP#$4", $item_cast);
    $item_cast = explode('#SP#',$item_cast);

    //得た情報にラベルがわりのキーを付ける
    $item_cast = array_combine($CAST_VALUE_KEY,$item_cast);
    
    //末尾に半角スペースがある場合は、読み込めるように変換する
    if(mb_substr($item_cast['player'],-1,1)==' ')
    {
      $item_cast['player'] = preg_replace("/ /","&amp;nbsp;",$item_cast['player']);
      echo 'ID has &nbsp:'.$item_cast['player']."=>";
    }

    //重複IDがあれば変更する
    if(array_key_exists($item_cast['player'],$DOPPEL))
    {
      echo 'This ID is DOPPEL. '.$item_cast['player'].'->'.$DOPPEL[$item_cast['player']].' =>';
      $item_cast['player'] = $DOPPEL[$item_cast['player']];
    }

    //値の配列に入れる
    $cast_value[] = $item_cast;
    //キー用配列にペルソナ名を入れる
    $cast_keys[] = $item_cast["persona"];
  }

  //ペルソナ名をキーに詳細情報を引き出す配列を作る
  $cast = array_combine($cast_keys,$cast_value);
  unset($item_cast,$cast_keys,$cast_value);

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
  makeCastDestiny($cast,$cast_retired,$data::DES_RETIRED);
  makeCastDestiny($cast,$cast_hanged,$data::DES_HANGED);
  makeCastDestiny($cast,$cast_eaten,$data::DES_EATEN);

  //生存処理、skill,team,life,result
  $destiny = array("destiny"=>$data::DES_ALIVE);
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
      $skill = array('skill'=>$data::SKL_WOLF);
      $team = array('team'=>$data::TM_WOLF);
      break;
    case '狂人':
      $skill= array('skill'=>$data::SKL_LUNATIC);
      $team = array('team'=>$data::TM_WOLF);
      break;
    default:
      $team = array('team'=>$data::TM_VILLAGER);
      switch($cast[$persona]['role'])
      {
      case '村人':
        $skill = array('skill'=>$data::SKL_VILLAGER);
        break;
      case '占い師':
        $skill = array('skill'=>$data::SKL_SEER);
        break;
      case '霊能者':
        $skill = array('skill'=>$data::SKL_MEDIUM);
        break;
      case '狩人':
        $skill = array('skill'=>$data::SKL_HUNTER);
        break;
      }
      break;
    }

    $cast[$miscItem['persona']] += $skill;
    $cast[$miscItem['persona']] += $team;

    //生存係数を挿入
    if($cast[$miscItem['persona']]['destiny'] === $data::DES_ALIVE)
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
      $castResult = array('result'=>$data::RSL_WIN);
    } else {
      $castResult = array('result'=>$data::RSL_LOSE);
    }
    $cast[$miscItem['persona']] += $castResult;
  }

  //書き込み関数に渡す配列に挿入
  $vilList[] = array(9,$vno,$vilName,$date,$nop,$rgl,$days,$result);
  $usrList[] = $cast;
}
$fetch->clear;
unset($fetch);
exit;


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
  //echo 'complete insert.';


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

