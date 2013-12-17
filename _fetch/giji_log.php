<?php
$CIDS = array(19,20,21,22,23,24);

if(!isset($argv) || !in_array($argv[1],$CIDS))
{
  echo 'ERROR: insert country ID into parameter.';
  exit(1);
}

require_once('../_afetch/data.php');
require_once('./txt_list.php');
require_once('../../lib/simple_html_dom.php');
mb_internal_encoding("UTF-8");

define('VID',8179);    //villageテーブルの開始ID

$fetch = new simple_html_dom();
$country = $argv[1];
$list  = new Txt_List($country);
$data  = new Data();

//AS
$RULE_SP = [
   41=>$data::RGL_DEATH
  ,49=>$data::RGL_DEATH
  ,62=>$data::RGL_MIST
  ,85=>$data::RGL_MIST
  ,95=>$data::RGL_MIST
];

$RGL_FREE = [
    '"villager","villager","villager","villager","wolf","wolf","seer","medium"'=>$data::RGL_S_2
   ,'"villager","villager","villager","villager","villager","villager","seer","medium","wolf","wolf"'=>$data::RGL_S_2
   ,'"villager","villager","villager","villager","villager","villager","seer","wolf","wolf"'=>$data::RGL_S_2
   ,' "villager","villager","villager","villager","villager","villager","wolf","wolf","seer","medium","possess","guard"'=>$data::RGL_S_2
   ,'"villager","villager","villager","villager","villager","seer","wolf","wolf"'=>$data::RGL_S_2
   ,'"villager","villager","villager","villager","villager","wolf","wolf","seer","medium"'=>$data::RGL_S_2
   ,'"villager","villager","villager","villager","villager","seer","guard","medium","possess","wolf","wolf"'=>$data::RGL_S_2
   ,'"villager","villager","villager","villager","seer","guard","medium","possess","wolf","wolf"'=>$data::RGL_S_2
   ,'"villager","villager","villager","villager","villager","seer","medium","possess","wolf","wolf"'=>$data::RGL_S_2
   ,'"villager","villager","villager","villager","villager","villager","villager","wolf","wolf","seer","medium","possess","guard"'=>$data::RGL_S_2
   ,'"villager","villager","villager","villager","villager","villager","villager","seer","guard","medium","possess","wolf","wolf"'=>$data::RGL_S_2
   ,'"villager","villager","villager","villager","wolf","wolf","seer","guard"'=>$data::RGL_S_2
   ,'"villager","villager","villager","villager","villager","wolf","wolf","seer","guard"'=>$data::RGL_S_2
   ,'"villager","villager","villager","villager","villager","villager","villager","villager","seer","guard","medium","possess","wolf","wolf","wolf"'=>$data::RGL_S_3
   ,'"villager","villager","villager","villager","villager","seer","guard","medium","wisper","wolf","wolf"'=>$data::RGL_S_C2
   ,'"villager","villager","villager","villager","villager","villager","villager","villager","wolf","wolf","seer","medium","guard","cpossess"'=>$data::RGL_S_C2
   ,'"villager","villager","villager","villager","villager","villager","seer","guard","medium","wisper","wolf","wolf"'=>$data::RGL_S_C2
   ,'"villager","villager","villager","villager","villager","seer","guard","medium","wisper","wolf","wolf"'=>$data::RGL_S_C2
   ,'"villager","villager","villager","villager","villager","villager","villager","wolf","guard"'=>$data::RGL_S_1
   ,'"villager","villager","villager","villager","villager","villager","villager","fm","fm","seer","guard","medium","possess","wolf","wolf","wolf"'=>$data::RGL_F
   ,'"villager","villager","villager","villager","villager","villager","villager","villager","villager","seer","guard","medium","possess","wolf","wolf","wolf"'=>$data::RGL_G
   ,'"villager","villager","villager","villager","villager","villager","villager","villager","stigma","seer","guard","medium","possess","wolf","wolf","wolf"'=>$data::RGL_G_ST
   ,'"villager","villager","villager","villager","villager","villager","villager","stigma","seer","guard","medium","wisper","wolf","wolf","wolf"'=>$data::RGL_TES1
   ,'"villager","villager","villager","villager","villager","wolf","wolf","seer","medium","guard","fanatic"'=>$data::RGL_TES2
   ,'"villager","villager","villager","villager","villager","villager","seer","guard","medium","fanatic","wolf","wolf"'=>$data::RGL_TES2
   ,'"villager","villager","villager","villager","villager","villager","villager","wolf","wolf","wolf","seer","medium","guard","fm","fm","fanatic"'=>$data::RGL_TES2
   ,'"villager","villager","villager","villager","villager","villager","villager","villager","villager","wolf","wolf","wolf","seer","medium","guard","fm","fm","hamster","cpossess"'=>$data::RGL_E
   ,'"villager","villager","villager","villager","villager","villager","villager","villager","wolf","wolf","wolf","seer","medium","possess","guard","hamster","stigma","stigma"'=>$data::RGL_E
   ,'"villager","villager","villager","villager","villager","villager","wolf","wolf","wolf","seer","medium","possess","guard","fm","fm","hamster'=>$data::RGL_E
   ,'"villager","villager","villager","villager","villager","villager","villager","villager","villager","seer","guard","medium","possess","wolf","wolf","wolf","hamster"'=>$data::RGL_E
   ,'"villager","villager","villager","wolf","seer","medium","possess","guard","hamster"'=>$data::RGL_S_E
   ,'"villager","villager","villager","villager","villager","seer","guard","medium","wolf","wolf","wolf","hamster"'=>$data::RGL_S_E
   ,'"villager","villager","villager","villager","villager","villager","villager","villager","villager","seer","guard","medium","possess","wolf","wolf","loveangel"'=>$data::RGL_LOVE
   ,'"villager","villager","villager","villager","villager","villager","villager","fm","fm","seer","guard","medium","wisper","wolf","wolf","wolf","loveangel"'=>$data::RGL_LOVE
   ,'"villager","villager","villager","villager","villager","villager","fm","fm","seer","guard","medium","fanatic","wolf","wolf","wolf","loveangel"'=>$data::RGL_LOVE
   ,'"villager","villager","villager","villager","villager","villager","fm","fm","seer","guard","medium","possess","wolf","wolf","wolf","hatedevil"'=>$data::RGL_EFB
];

$RP_20 = [
  114,110,104,78,77,74,73,69,67,62,58,57,55,48,46,43,40,38,37,36,35,32,31,30,29,28,27,26,23,21,20,18,17,12,9,8,6
];
$RP_21 = [
  134,113,99,98,62,61
];
$RP_24 = [
  71,68,66,65,64,62,60,59,58,57,56,55,53,51,41,33,13,7,5,4,3,1
];

//陣営リスト
$TEAM = array(
   "WIN_HUMAN"=>$data::TM_VILLAGER
  ,"WIN_WOLF"=>$data::TM_WOLF
  ,"WIN_PIXI"=>$data::TM_FAIRY
  ,"WIN_LOVER"=>$data::TM_LOVERS
  ,"WIN_LONEWOLF"=>$data::TM_LWOLF
  ,"WIN_GURU"=>$data::TM_PIPER
  ,"WIN_HATER"=>$data::TM_EFB
  ,"WIN_EVIL"=>$data::TM_EVIL
  ,"WIN_DISH"=>$data::TM_FISH
  ,"WIN_NONE"=>$data::TM_ONLOOKER
);
$BAND = array(
   "love"=>"恋人"
  ,"hate"=>"邪気"
);

//結末
$DESTINY = array(
   "live"=>$data::DES_ALIVE
  ,"suddendead"=>$data::DES_RETIRED
  ,"executed"=>$data::DES_HANGED
  ,"victim"=>$data::DES_EATEN
  ,"cursed"=>$data::DES_CURSED
  ,"droop"=>$data::DES_DROOP
  ,"suicide"=>$data::DES_SUICIDE
  ,"feared"=>$data::DES_FEARED
  ,"mob"=>$data::DES_ONLOOKER
);

//役職
$ROLES=[
 '"villager"'=>['村人',$data::SKL_VILLAGER,$data::TM_VILLAGER]
,'"stigma"'=>['聖痕者',$data::SKL_STIGMA,$data::TM_VILLAGER]
,'"fm"'=>['結社員',$data::SKL_MASON,$data::TM_VILLAGER]
,'"sympathy"'=>['共鳴者',$data::SKL_TELEPATH,$data::TM_VILLAGER]
,'"seer"'=>['占い師',$data::SKL_SEER,$data::TM_VILLAGER]
,'"seerwin"'=>['信仰占師',$data::SKL_SEERWIN,$data::TM_VILLAGER]
,'"oura"'=>['気占師',$data::SKL_SEERAURA,$data::TM_VILLAGER]
,'"aura"'=>['気占師',$data::SKL_SEERAURA,$data::TM_VILLAGER]
,'"seeronce"'=>['夢占師',$data::SKL_SEERONCE,$data::TM_VILLAGER]
,'"seerrole"'=>['賢者',$data::SKL_SAGE,$data::TM_VILLAGER]
,'"guard"'=>['守護者',$data::SKL_HUNTER,$data::TM_VILLAGER]
,'"medium"'=>['霊能者',$data::SKL_MEDIUM,$data::TM_VILLAGER]
,'"mediumwin"'=>['信仰霊能者',$data::SKL_MEDIWIN,$data::TM_VILLAGER]
,'"mediumrole"'=>['導師',$data::SKL_PRIEST,$data::TM_VILLAGER]
,'"necromancer"'=>['降霊者',$data::SKL_NECRO,$data::TM_VILLAGER]
,'"follow"'=>['追従者',$data::SKL_FOLLOWER,$data::TM_VILLAGER]
,'"fan"'=>['煽動者',$data::SKL_AGITATOR,$data::TM_VILLAGER]
,'"hunter"'=>['賞金稼',$data::SKL_BOUNTY,$data::TM_VILLAGER]
,'"weredog"'=>['人犬',$data::SKL_WEREDOG,$data::TM_VILLAGER]
,'"prince"'=>['王子様',$data::SKL_PRINCE,$data::TM_VILLAGER]
,'"rightwolf"'=>['狼血族',$data::SKL_LINEAGE,$data::TM_VILLAGER]
,'"doctor"'=>['医師',$data::SKL_DOCTOR,$data::TM_VILLAGER]
,'"curse"'=>['呪人',$data::SKL_CURSED,$data::TM_VILLAGER]
,'"dying"'=>['預言者',$data::SKL_PROPHET,$data::TM_VILLAGER]
,'"invalid"'=>['病人',$data::SKL_SICK,$data::TM_VILLAGER]
,'"alchemist"'=>['錬金術師',$data::SKL_ALCHEMIST,$data::TM_VILLAGER]
,'"witch"'=>['魔女',$data::SKL_WITCH,$data::TM_VILLAGER]
,'"girl"'=>['少女',$data::SKL_GIRL,$data::TM_VILLAGER]
,'"scapegoat"'=>['生贄',$data::SKL_SG,$data::TM_VILLAGER]
,'"elder"'=>['長老',$data::SKL_ELDER,$data::TM_VILLAGER]
,'"jammer"'=>['邪魔之民',$data::SKL_JAMMER,$data::TM_EVIL]
,'"snatch"'=>['宿借之民',$data::SKL_SNATCH,$data::TM_EVIL]
,'"bat"'=>['念波之民',$data::SKL_LUNAPATH,$data::TM_EVIL]
,'"possess"'=>['狂人',$data::SKL_LUNATIC,$data::TM_EVIL]
,'"fanatic"'=>['狂信者',$data::SKL_FANATIC,$data::TM_EVIL]
,'"muppeting"'=>['人形使い',$data::SKL_MUPPETER,$data::TM_EVIL]
,'"wisper"'=>['囁き狂人',$data::SKL_LUNAWHS,$data::TM_EVIL]
,'"cpossess"'=>['囁き狂人',$data::SKL_LUNAWHS,$data::TM_EVIL]
,'"semiwolf"'=>['半狼',$data::SKL_HALFWOLF,$data::TM_EVIL]
,'"oracle"'=>['魔神官',$data::SKL_LUNAPRI,$data::TM_EVIL]
,'"sorcerer"'=>['魔術師',$data::SKL_LUNASAGE,$data::TM_EVIL]
,'"headless"'=>['首無騎士',$data::SKL_HEADLESS,$data::TM_WOLF]
,'"wolf"'=>['人狼',$data::SKL_WOLF,$data::TM_WOLF]
,'"intwolf"'=>['智狼',$data::SKL_WISEWOLF,$data::TM_WOLF]
,'"cursewolf"'=>['呪狼',$data::SKL_CURSEWOLF,$data::TM_WOLF]
,'"cwolf"'=>['呪狼',$data::SKL_CURSEWOLF,$data::TM_WOLF]
,'"whitewolf"'=>['白狼',$data::SKL_WHITEWOLF,$data::TM_WOLF]
,'"childwolf"'=>['仔狼',$data::SKL_CHILDWOLF,$data::TM_WOLF]
,'"dyingwolf"'=>['衰狼',$data::SKL_DYINGWOLF,$data::TM_WOLF]
,'"silentwolf"'=>['黙狼',$data::SKL_SILENT,$data::TM_WOLF]
,'"hamster"'=>['栗鼠妖精',$data::SKL_FAIRY,$data::TM_FAIRY]
,'"mimicry"'=>['擬狼妖精',$data::SKL_MIMIC,$data::TM_FAIRY]
,'"werebat"'=>['コウモリ人間',$data::SKL_BAT,$data::TM_FAIRY]
,'"dyingpixi"'=>['風花妖精',$data::SKL_SNOW,$data::TM_FAIRY]
,'"trickster"'=>['悪戯妖精',$data::SKL_PIXY,$data::TM_FAIRY]
,'"hatedevil"'=>['邪気悪魔',$data::SKL_EFB,$data::TM_EFB]
,'"loveangel"'=>['恋愛天使',$data::SKL_QP,$data::TM_LOVERS]
,'"passion"'=>['片想い',$data::SKL_PASSION,$data::TM_LOVERS]
,'"lover"'=>['弟子',$data::SKL_PUPIL,$data::TM_NONE]
,'"robber"'=>['盗賊',$data::SKL_THIEF,$data::TM_NONE]
,'"lonewolf"'=>['一匹狼',$data::SKL_LWOLF,$data::TM_LWOLF]
,'"guru"'=>['笛吹き',$data::SKL_PIPER,$data::TM_PIPER]
,'"dish"'=>['鱗魚人',$data::SKL_FISH,$data::TM_FISH]
,'"bitch"'=>['遊び人',$data::SKL_PLAYBOY,$data::TM_LOVERS]
,'null'=>['見物人',$data::SKL_ONLOOKER,$data::TM_ONLOOKER]
,'34'=>['囁き狂人',$data::SKL_LUNAWHS,$data::TM_EVIL]
,'38'=>['魔術師',$data::SKL_LUNASAGE,$data::TM_EVIL]
,'51'=>['栗鼠妖精',$data::SKL_FAIRY,$data::TM_FAIRY]
,'53'=>['コウモリ人間',$data::SKL_BAT,$data::TM_FAIRY]
,'54'=>['悪戯妖精',$data::SKL_PIXY,$data::TM_FAIRY]
,'55'=>['邪魔妖精',$data::SKL_JAMFAIRY,$data::TM_FAIRY]
,'56'=>['擬狼妖精',$data::SKL_MIMIC,$data::TM_FAIRY]
,'57'=>['宿借妖精',$data::SKL_SNAFAIRY,$data::TM_FAIRY]
,'58'=>['風花妖精',$data::SKL_SNOW,$data::TM_FAIRY]
,'99'=>['見物人',$data::SKL_ONLOOKER,$data::TM_ONLOOKER]
,'"walpurgis"'=>['首無騎士',$data::SKL_HEADLESS,$data::TM_WOLF]
];

//恩恵
$GIFTS = [
   '"lost"'=>'喪失'
  ,'"shield"'=>'光の輪'
  ,'"glass"'=>'魔鏡'
  ,'"ogre"'=>'悪鬼'
  ,'"fairy"'=>'妖精の子'
  ,'"fink"'=>'半端者'
  ,'"decide"'=>'決定者'
  ,'"seeronce"'=>'夢占師'
  ,'"dipsy"'=>'酔払い'
  ,'4'=>'喪失'
];
$BANDS = [
   '"love"'=>["恋人",$data::TM_LOVERS]
  ,'"hate"'=>["邪気",$data::TM_EFB]
];

$EVILS = [
  140,133,132,78,77,68,66,61,45,44,40,3,1
];

$base_list = $list->read_list();
$list->open_list('village');
$list->open_list('users');

foreach($base_list as $val_vil=>$item_vil)
{
  //情報欄取得
  $fetch->load_file($item_vil[2]);
  $base_array = explode('gon.',$fetch->find('script',-2)->innertext);

  //日数
  if(preg_match('/"turn":(\d+),"name":"エピローグ"/',$base_array[4],$days))
  {
    //廃村チェック
    if($days[1] == 1)
    {
      echo $item_vil[0].' is ruined.'.PHP_EOL;
      continue;
    }
  }
  else
  {
    //エピローグが壊れている場合
    echo $item_vil[0].' Epilogue is broken.Must check days.';
    $days[1] = 99;
  }

  //初期化
  $village = array(
             'vno'  =>$item_vil[0]
            ,'name' =>$item_vil[1]
            ,'date' =>""
            ,'nop'  =>""
            ,'rglid'=>""
            ,'days' =>$days[1]
            ,'wtmid'=>""
  );
  //村建て日
  $date = preg_replace('/.+"updateddt":"(\d{4}-\d{2}-\d{2})T(\d{2}:\d{2}:\d{2})Z",.+/',"$1 $2",$base_array[2]);
  //GMTなので+9時間する
  $village['date'] = date('Y-m-d',strtotime($date.' +9 hours'));

  //キャスト表
  //サイズを小さくしないとpreg_replaceに失敗する
  $cast = explode('"_id":{',$base_array[3]);
  array_shift($cast);

  //参加人数
  $nop_all = count($cast);
  //見物人カウント
  preg_match_all('/"role":\[null\]/',$base_array[3],$onlooker);
  $village['nop'] = $nop_all - count($onlooker[0]);

  //レギュレーション挿入
  $rglid = preg_replace('/.+"roletable":"([^"]*)",.+/',"$1",$base_array[2]);
  if(($country == 19 || $country == 22) && $rglid === "default")
  {
    //標準国の標準編成はF
    $rglid = "wbbs_f";
  }

  if($country == 21 && array_key_exists($village['vno'],$RULE_SP))
  {
    $village['rglid'] = $RULE_SP[$village['vno']];
  }
  else
  {
    switch($rglid)
    {
      case "custom":
        $free = preg_replace('/.+"config":\[([^]]*)\].+/',"$1",$base_array[2]);
        //見物人を削除
        $free = preg_replace('/,"mob"/',"",$free);
        if(array_key_exists($free,$RGL_FREE))
        {
          $village['rglid'] = $RGL_FREE[$free];
        }
        else
        {
          $village['rglid'] = $data::RGL_ETC;
        }
        break;
      case "default":
        echo 'NOTICE: '.$village['vno'].' is DEFAULT regulation.';
        if($village['nop'] <= 7)
        {
          $village['rglid'] = $data::RGL_S_1;
        }
        else
        {
          $village['rglid'] = $data::RGL_LEO;
        }
        break;
      case "mistery":
        $village['rglid'] = $data::RGL_MIST;
        break;
      case "test1st":
        switch(true)
        {
          case ($village['nop']  >= 13):
            $village['rglid'] = $data::RGL_TES1;
            break;
          case ($village['nop'] <=12 && $village['nop'] >= 8):
            $village['rglid'] = $data::RGL_S_2;
            break;
          default:
            $village['rglid'] = $data::RGL_S_1;
            break;
        }
        break;
      case "test2nd":
        switch(true)
        {
          case ($village['nop']  >= 10):
            $village['rglid'] = $data::RGL_TES2;
            break;
          case ($village['nop']  === 8 || $village['nop']  === 9):
            $village['rglid'] = $data::RGL_S_2;
            break;
          default:
            $village['rglid'] = $data::RGL_S_1;
            break;
        }
        break;
      case "wbbs_c":
        switch(true)
        {
          case ($village['nop']  >= 16):
            $village['rglid'] = $data::RGL_C;
            break;
          case ($village['nop']  === 15):
            $village['rglid'] = $data::RGL_S_C3;
            break;
          case ($village['nop'] <=14 && $village['nop'] >= 10):
            $village['rglid'] = $data::RGL_S_C2;
            break;
          case ($village['nop']  === 8 || $village['nop'] === 9):
            $village['rglid'] = $data::RGL_S_2;
            break;
          default:
            $village['rglid'] = $data::RGL_S_1;
            break;
        }
        break;
      case "wbbs_f":
        switch(true)
        {
          case ($village['nop']  >= 16):
            $village['rglid'] = $data::RGL_F;
            break;
          case ($village['nop']  === 15):
            $village['rglid'] = $data::RGL_S_3;
            break;
          case ($village['nop'] <=14 && $village['nop'] >= 8):
            $village['rglid'] = $data::RGL_S_2;
            break;
          default:
            $village['rglid'] = $data::RGL_S_1;
            break;
        }
        break;
      case "wbbs_g":
        switch(true)
        {
          case ($village['nop']  >= 16):
            $village['rglid'] = $data::RGL_G;
            break;
          case ($village['nop']  <= 15 && $village['nop'] >= 13):
            $village['rglid'] = $data::RGL_S_3;
            break;
          case ($village['nop'] <=12 && $village['nop'] >= 8):
            $village['rglid'] = $data::RGL_S_2;
            break;
          default:
            $village['rglid'] = $data::RGL_S_1;
            break;
        }
        break;
      case "hamster":
        switch(true)
        {
          case ($village['nop']  >= 16):
            $village['rglid'] = $data::RGL_E;
            break;
          case ($village['nop']  === 15):
            $village['rglid'] = $data::RGL_S_3;
            break;
          case ($village['nop'] <=14 && $village['nop'] >= 8):
            $village['rglid'] = $data::RGL_S_2;
            break;
          default:
            $village['rglid'] = $data::RGL_S_1;
            break;
        }
        break;
    }
  }

  //勝敗
  switch($country)
  {
    case 19://標準
      $village['wtmid'] = $TEAM[preg_replace('/.+"winner":"([^"]*)".+/',"$1",$base_array[5])];
      break;
    case 20:
    case 21:
    case 24:
      if(in_array($village['vno'],${'RP_'.$country}))
      {
        $village['wtmid'] = $data::TM_RP;
      }
      else
      {
        $village['wtmid'] = $TEAM[preg_replace('/.+"winner":"([^"]*)".+/',"$1",$base_array[5])];
      }
      break;
    case 22://RP
    case 23://RPA
      $village['wtmid'] = $data::TM_RP;
      break;
  }

  //肩書き抽出準備
  //
  $dummy_base = explode('"mestype":"INFONOM"',$base_array[5]);
  $dummy_base = preg_replace('/.*?"face_id":"([a-z]{1,2}\d{2}|\d+)","log":"([^"]*)","logid":"(I|S)[^"]*".+/',"$2",$dummy_base[1]);
  $dummy_base = mb_substr($dummy_base,-8);
  
  switch($dummy_base)
  {
    case "やってきました。":
      preg_match_all('#"log":"\d+人目、(\\\\u003Cb\\\\u003E|)([^"\\\\]*)(\\\\u003C/b\\\\u003E|\\\\u003C/b\\\\u003E | )がやってきました。"#',$base_array[5],$title_base);
      break;
    case "ったかなあ？）。":
      preg_match_all('/"log":"([^"]*) がきたらしいよ（\d+人目……だったかなあ？）。"/',$base_array[5],$title_base);
      //title_baseをずらす
      array_unshift($title_base,'');
      break;
    default: //入村メッセージがない場合
      $dummy_name = preg_replace('/.*?"logid":"SS00000","mestype":"SAY","name":"([^"]*)".+/',"$1",$base_array[5]);
      preg_match_all('#"log":"(\\\\u003Cb\\\\u003E|)([^"\\\\]*)(\\\\u003C/b\\\\u003E| )が参加しました。"#',$base_array[5],$title_base);
      array_unshift($title_base[2],$dummy_name);
      break;
  }
  //名前だけの配列を作る
  $title_name = [];
  foreach($title_base[2] as $item_title)
  {
    $name = preg_replace('/.+ (.+)/',"$1",$item_title);
    //ひらがな「へ」クターを直す
    if($name === 'へクター')
    {
      $name = 'ヘクター';
    }
    $title_name[] = $name;
  }
  $title = array_combine($title_name,$title_base[2]);
  if($country == 24)
  {
    foreach($title as $title_item)
    {
      echo $title_item.PHP_EOL;
    }
  }

  //村を書き込む
  //見物人込みの人数を参加者行数として送る
  $list->write_list('village',$village,$val_vil+1,$nop_all);


  foreach($cast as $val_cast => $item_cast)
  {
    $users = [
       'vid'    =>$val_vil + VID
      ,'persona'=>""
      ,'player' =>preg_replace('/.*?"sow_auth_id":"([^"]*)".+/',"$1",$item_cast)
      ,'role'   =>""
      ,'dtid'   =>$DESTINY[preg_replace('/.+"live":"([^"]*)",.+/',"$1",$item_cast)]
      ,'end'    =>""
      ,'sklid'  =>""
      ,'tmid'   =>""
      ,'life'   =>""
      ,'rltid'  =>""
    ];

    //名前と肩書き
    $persona = preg_replace('/.+,"name":"([^"]*)","overhear".+/',"$1",$item_cast);
    //ペルソナ欄が伏せ字ならジェレミーにする
    if($country != 24 && $persona === "***")
    {
      $persona = "ジェレミー";
    }
    //肩書きを挿入する
    if(array_key_exists($persona,$title))
    {
      $users['persona'] = $title[$persona];
      unset($title[$persona]);
    }
    else
    {
      echo 'NOTICE: '.$village['vno'].'.'.$persona. ' not found.';
      $users['persona'] = $persona;
    }

    //役職
    $role = preg_replace('/.+"role":\[([^]]*)\],.+/','$1',$item_cast);
    if(mb_strpos($role,',') !== false)
    {
      //恩恵がある場合
      $gift = explode(',',$role);
      $role = $gift[0];
      $users['role'] = $ROLES[$role][0].'、'.$GIFTS[$gift[1]];
      $users['sklid'] = $ROLES[$role][1];
      //恩恵による陣営変化
      switch($gift[1])
      {
        case '"ogre"':
          $users['tmid'] = $data::TM_WOLF;
          break;
        case '"fairy"':
          $users['tmid'] = $data::TM_FAIRY;
          break;
        case '"fink"':
          $users['tmid'] = $data::TM_EVIL;
          break;
        default:
          $users['tmid'] = $ROLES[$role][2];
          break;
      }
    }
    else
    {
      $users['role'] = $ROLES[$role][0];
      $users['sklid'] = $ROLES[$role][1];
      $users['tmid'] = $ROLES[$role][2];
    }

    //絆チェック
    $band = preg_replace('/.+"love":([^,]*),.+/','$1',$item_cast);
    if($band !== 'null')
    {
      $users['role'] = $users['role'].'、'.$BANDS[$band][0];
      $users['tmid'] = $BANDS[$band][1];
    }
    //裏切りの陣営かどうか
    if($users['tmid'] === $data::TM_EVIL && !($country == 21 && in_array($village['vno'],$EVILS)))
    {
      $users['tmid'] = $data::TM_WOLF;
    }

    //死亡日
    $end = (int)preg_replace('/.+"deathday":(-*\d+),.+/',"$1",$item_cast);
    switch($end)
    {
      case -2: //見物人
        $users['end'] = 1;
        break;
      case -1: //生存者
        $users['end'] = $village['days'];
        break;
      default:
        $users['end'] = $end;
        break; 
    }
    //生存係数
    switch($users['dtid'])
    {
      case $data::DES_ALIVE:
        $users['life'] = 1.00;
        break;
      case $data::DES_ONLOOKER:
        $users['life'] = 0;
        break;
      default:
        $users['life'] = round(($users['end']-1) / $village['days'],2);
        break;
    }
    //勝敗
    if($users['dtid'] === $data::DES_RETIRED)
    {
      //突然死者は無効
      $users['rltid'] = $data::RSL_INVALID;
    }
    else if($village['wtmid'] === $data::TM_RP)
    {
      $users['rltid'] = $data::RSL_JOIN;
    }
    else
    {
      switch($users['tmid'])
      {
        case $data::TM_ONLOOKER:
          $users['rltid'] = $data::RSL_ONLOOKER;
          break;
        case $village['wtmid']:
          $users['rltid'] = $data::RSL_WIN;
          break;
        case $data::TM_EVIL:
          if($village['wtmid'] !== $data::TM_VILLAGER && $village['wtmid'] !== $data::TM_LOVERS)
          {
            $users['rltid'] = $data::RSL_WIN;
          }
          else
          {
            $users['rltid'] = $data::RSL_LOSE;
          }
          break;
        case $data::TM_FISH:
          if($users['dtid'] === $data::DES_EATEN)
          {
            $users['rltid'] = $data::RSL_WIN;
          }
          else
          {
            $users['rltid'] = $data::RSL_LOSE;
          }
          break;
        default:
          $users['rltid'] = $data::RSL_LOSE;
          break;
      }
    }

    //ユーザを書き込む
    $list->write_list('users',$users,$val_cast+1);

    unset($item_cast);
  }
  $fetch->clear();
  echo $village['vno']. ' is end.'.PHP_EOL;
}
unset($fetch);
