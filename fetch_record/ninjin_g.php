<?php
ini_set('display_errors','on');
define ("COUNTRY","ninjin_g");
define ("CID",9);
define ("URL_LOG","http://www.wolfg.x0.com/index.rb?cmd=log");
define ("URL_VIL","http://www.wolfg.x0.com/index.rb?vid=");

require_once('simple_html_dom.php');
require_once('data.php');
require_once('check_village.php');
require_once('insert_destiny.php');
require_once('insert_db.php');

mb_internal_encoding("UTF-8");


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
  return;
}

$data  = new Data();

$CAST_VALUE_KEY = array("persona","player","role");
$DOPPEL = array(
   "asaki"=>"asaki&lt;G国&gt;"
  ,"motimoti"=>"motimoti&lt;G薔薇国&gt;"
);

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
  $fetch = new simple_html_dom();
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
  unset($item_cast,$cast_keys,$cast_value,$fetch);

  //destiny, end
  $destiny = new Insert_Destiny(URL_VIL,$village['vno'],$village['days']);
  $destiny->make_destiny($cast);
  unset($destiny);

  //skill,team,life,result
  foreach($cast as $item_cast)
  {
    $persona = $item_cast['persona'];

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

    $cast[$item_cast['persona']] += $skill;
    $cast[$item_cast['persona']] += $team;

    //生存係数を挿入
    if($cast[$item_cast['persona']]['destiny'] === $data::DES_ALIVE)
    { 
      //生存者は1
      $life = 1.00;
    } else {
      //死亡者は死亡日の前日(=生きていた日)/全日程
      $life = round(($cast[$persona]['end']-1) / $village['days'],2);
    }
    $life = array('life'=>$life);
    $cast[$item_cast['persona']] += $life;

    //勝敗を挿入
    if($cast[$persona]['team'] === $village['wtmid'])
    {
      $result = array('result'=>$data::RSL_WIN);
    } else {
      $result = array('result'=>$data::RSL_LOSE);
    }
    $cast[$item_cast['persona']] += $result;
  }

  //村を書き込む
  $db->connect();
  if($db->insert_db($village,$cast))
  {
    echo $village['vno']. ' is all inserted.'.PHP_EOL;
    $check->remove_queue($village['vno']);
  }
  $db->disconnect();
}

