<?php

require_once('../../lib/simple_html_dom.php');
require_once('./data.php');
require_once('./check_village.php');
require_once('./insert_db.php');

mb_internal_encoding("UTF-8");

$COUNTRYS = array(14,18);
$data  = new Data();

//特殊ルール
$RGL_SP = [
   'ミラーズホロウ'=>$data::RGL_MILL
  ,'ミラーズホロウ（死んだら負け）'=>$data::RGL_MILL
  ,'タブラの人狼（死んだら負け）'=>$data::RGL_DEATH
  ,'Trouble☆Aliens'=>$data::RGL_TA
  ,'深い霧の夜'=>$data::RGL_MIST
  ,'陰謀に集う胡蝶'=>$data::RGL_PLOT
];

//裏切りの陣営としてカウントするレギュレーション
$TM_EVIL = array($data::RGL_E,$data::RGL_S_E,$data::RGL_EFB,$data::RGL_ETC);

//自由設定でも特殊レギュにしない編成
$RGL_FREE = [
   'villager/villager/villager/villager/seer/possess/wolf'=>$data::RGL_S_1
  ,'villager/villager/villager/seer/possess/wolf'=>$data::RGL_S_1
  ,'villager/villager/villager/villager/seer/guard/wolf/wolf'=>$data::RGL_S_2
  ,'villager/villager/villager/villager/seer/medium/possess/wolf/wolf'=>$data::RGL_S_2
  ,'villager/villager/villager/villager/villager/seer/guard/medium/possess/wolf/wolf'=>$data::RGL_S_2
  ,'villager/villager/villager/villager/villager/villager/seer/medium/possess/wolf/wolf'=>$data::RGL_S_2
  ,'villager/villager/villager/villager/seer/guard/medium/possess/wolf/wolf'=>$data::RGL_S_2
  ,'villager/villager/villager/villager/villager/villager/villager/villager/seer/guard/medium/possess/wolf/wolf/wolf'=>$data::RGL_S_3
  ,'villager/villager/villager/villager/villager/villager/villager/seer/guard/medium/possess/wolf/wolf/hamster'=>$data::RGL_S_E
  ,'villager/villager/villager/villager/villager/stigma/seer/guard/medium/possess/possess/wolf/wolf'=>$data::RGL_TES1
  ,'villager/villager/villager/villager/villager/seer/guard/medium/fanatic/wolf/wolf'=>$data::RGL_TES2
];

//勝利陣営
$WTM = [
   1=>$data::TM_VILLAGER
  ,2=>$data::TM_WOLF
  ,3=>$data::TM_PIPER
  ,4=>$data::TM_FAIRY
  ,5=>$data::TM_FAIRY
  ,6=>$data::TM_LWOLF
  ,7=>$data::TM_LOVERS
  ,8=>$data::TM_EFB
  ,9=>$data::TM_NONE
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
//勝敗
$RSL = array(
   "勝利"=>$data::RSL_WIN
  ,"敗北"=>$data::RSL_LOSE
  ,""=>$data::RSL_INVALID //無効(突然死)
);

//役職
$SKILL = array(
   1=>$data::SKL_VILLAGER
  ,2=>$data::SKL_STIGMA
  ,3=>$data::SKL_MASON
  ,4=>$data::SKL_TELEPATH
  ,5=>$data::SKL_SEER
  ,6=>$data::SKL_SEERWIN
  ,7=>$data::SKL_SEERAURA
  ,8=>$data::SKL_SAGE
  ,9=>$data::SKL_HUNTER
  ,10=>$data::SKL_MEDIUM
  ,11=>$data::SKL_MEDIWIN
  ,12=>$data::SKL_PRIEST
  ,13=>$data::SKL_NECRO
  ,14=>$data::SKL_FOLLOWER
  ,15=>$data::SKL_AGITATOR
  ,16=>$data::SKL_BOUNTY
  ,17=>$data::SKL_WEREDOG
  ,18=>$data::SKL_PRINCE
  ,19=>$data::SKL_LINEAGE
  ,20=>$data::SKL_DOCTOR
  ,21=>$data::SKL_CURSED
  ,22=>$data::SKL_PROPHET
  ,23=>$data::SKL_SICK
  ,24=>$data::SKL_ALCHEMIST
  ,25=>$data::SKL_WITCH
  ,26=>$data::SKL_GIRL
  ,27=>$data::SKL_SG
  ,28=>$data::SKL_ELDER
  ,31=>$data::SKL_JAMMER
  ,32=>$data::SKL_SNATCH
  ,33=>$data::SKL_LUNAPATH
  ,41=>$data::SKL_LUNATIC
  ,42=>$data::SKL_FANATIC
  ,43=>$data::SKL_MUPPETER
  ,44=>$data::SKL_LUNAWHS
  ,45=>$data::SKL_HALFWOLF
  ,47=>$data::SKL_LUNAPRI
  ,48=>$data::SKL_LUNASAGE
  ,52=>$data::SKL_HEADLESS
  ,61=>$data::SKL_WOLF
  ,63=>$data::SKL_WISEWOLF
  ,64=>$data::SKL_CURSEWOLF
  ,65=>$data::SKL_WHITEWOLF
  ,66=>$data::SKL_CHILDWOLF
  ,67=>$data::SKL_DYINGWOLF
  ,68=>$data::SKL_SILENT
  ,81=>$data::SKL_FAIRY
  ,86=>$data::SKL_MIMIC
  ,88=>$data::SKL_SNOW
  ,89=>$data::SKL_PIXY
  ,90=>$data::SKL_EFB
  ,91=>$data::SKL_QP
  ,92=>$data::SKL_PASSION
  ,93=>$data::SKL_PUPIL
  ,94=>$data::SKL_THIEF
  ,96=>$data::SKL_LWOLF
  ,97=>$data::SKL_PIPER
  ,98=>$data::SKL_FISH
  ,101=>$data::SKL_PLAYBOY
  ,999=>$data::SKL_ONLOOKER
);

$ROLE = array(
   $data::SKL_VILLAGER=>'村人'
  ,$data::SKL_STIGMA=>'聖痕者'
  ,$data::SKL_MASON=>'結社員'
  ,$data::SKL_TELEPATH=>'共鳴者'
  ,$data::SKL_SEER=>'占い師'
  ,$data::SKL_SEERWIN=>'信仰占師'
  ,$data::SKL_SEERAURA=>'気占師'
  ,$data::SKL_SAGE=>'賢者'
  ,$data::SKL_HUNTER=>'守護者'
  ,$data::SKL_MEDIUM=>'霊能者'
  ,$data::SKL_MEDIWIN=>'信仰霊能者'
  ,$data::SKL_PRIEST=>'導師'
  ,$data::SKL_NECRO=>'降霊者'
  ,$data::SKL_FOLLOWER=>'追従者'
  ,$data::SKL_AGITATOR=>'煽動者'
  ,$data::SKL_BOUNTY=>'賞金稼'
  ,$data::SKL_WEREDOG=>'人犬'
  ,$data::SKL_PRINCE=>'王子様'
  ,$data::SKL_LINEAGE=>'狼血族'
  ,$data::SKL_DOCTOR=>'医師'
  ,$data::SKL_CURSED=>'呪人'
  ,$data::SKL_PROPHET=>'預言者'
  ,$data::SKL_SICK=>'病人'
  ,$data::SKL_ALCHEMIST=>'錬金術師'
  ,$data::SKL_WITCH=>'魔女'
  ,$data::SKL_GIRL=>'少女'
  ,$data::SKL_SG=>'生贄'
  ,$data::SKL_ELDER=>'長老'
  ,$data::SKL_JAMMER=>'邪魔之民'
  ,$data::SKL_SNATCH=>'宿借之民'
  ,$data::SKL_LUNAPATH=>'念波之民'
  ,$data::SKL_LUNATIC=>'狂人'
  ,$data::SKL_FANATIC=>'狂信者'
  ,$data::SKL_MUPPETER=>'人形使い'
  ,$data::SKL_LUNAWHS=>'囁き狂人'
  ,$data::SKL_HALFWOLF=>'半狼'
  ,$data::SKL_LUNAPRI=>'魔神官'
  ,$data::SKL_LUNASAGE=>'魔術師'
  ,$data::SKL_HEADLESS=>'首無騎士'
  ,$data::SKL_WOLF=>'人狼'
  ,$data::SKL_WISEWOLF=>'智狼'
  ,$data::SKL_CURSEWOLF=>'呪狼'
  ,$data::SKL_WHITEWOLF=>'白狼'
  ,$data::SKL_CHILDWOLF=>'仔狼'
  ,$data::SKL_DYINGWOLF=>'衰狼'
  ,$data::SKL_SILENT=>'黙狼'
  ,$data::SKL_FAIRY=>'栗鼠妖精'
  ,$data::SKL_MIMIC=>'擬狼妖精'
  ,$data::SKL_SNOW=>'風花妖精'
  ,$data::SKL_PIXY=>'悪戯妖精'
  ,$data::SKL_EFB=>'邪気悪魔'
  ,$data::SKL_QP=>'恋愛天使'
  ,$data::SKL_PASSION=>'片想い'
  ,$data::SKL_PUPIL=>'弟子'
  ,$data::SKL_THIEF=>'盗賊'
  ,$data::SKL_LWOLF=>'一匹狼'
  ,$data::SKL_PIPER=>'笛吹き'
  ,$data::SKL_FISH=>'鱗魚人'
  ,$data::SKL_PLAYBOY=>'遊び人'
  ,$data::SKL_ONLOOKER=>'見物人'
);

$GIFT = array(
   2=>'喪失'
  ,3=>'感染'
  ,5=>'光の輪'
  ,6=>'魔鏡'
  ,7=>'悪鬼'
  ,8=>'妖精の子'
  ,9=>'半端者'
  ,11=>'決定者'
  ,12=>'夢占師'
  ,13=>'酔払い'
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

foreach($COUNTRYS as $cid)
{
  switch($cid)
  {
    case 14:
      $country = 'plot';
      $url_vil = 'http://cabala.halfmoon.jp/cafe/sow.cgi?vid=';
      $url_log = 'http://cabala.halfmoon.jp/cafe/sow.cgi?cmd=oldlog';
      break;
    case 18:
      $country = 'ciel';
      $url_vil = 'http://ciel.moo.jp/cheat/sow.cgi?vid=';
      $url_log = 'http://ciel.moo.jp/cheat/sow.cgi?cmd=oldlog';
      break;
  }
  $db    = new Insert_DB($cid);
  $check = new Check_Village($country,$cid,$url_vil,$url_log);
  $check->check_queue();
  $check->check_fetch_vno();
  if($check->get_village())
  {
    $fetched_v = $check->get_village();
  }
  else
  {
    echo $country.' not fetch.'.PHP_EOL;
    continue;
  }

  $fetch = new simple_html_dom();
  foreach($fetched_v as $item_vil)
  {
    //初期化
    $village = array(
               'cid'  =>$cid
              ,'vno'  =>$item_vil
              ,'name' =>""
              ,'date' =>""
              ,'nop'  =>""
              ,'rglid'=>""
              ,'days' =>""
              ,'wtmid'=>""
    );

    //情報欄取得
    $url = $url_vil.$item_vil."#mode=info_open_player";
    $fetch->load_file($url);
    $base = mb_convert_encoding($fetch->find('script',-2)->innertext,"UTF-8","SJIS");

    //村名
    $village['name'] = preg_replace('/.*?\),.*?"name":    "([^"]*)",.+/s',"$1",$base);

    //村建て日
    $date = preg_replace('/.+"updateddt":    new Date\(1000 \* (\d+)\),.+/s',"$1",$base);
    $village['date'] = date('Y-m-d',$date);

    //日数
    $village['days'] = (int)preg_replace('/.+"turn": (\d+).+/s',"$1",$base);

    //キャスト表
    $cast = explode("gon.potofs",$base);
    array_shift($cast);
    array_pop($cast);

    //参加人数
    $nop_all = count($cast);
    //見物人カウント
    preg_match_all('/SOW_RECORD\.CABALA\.roles\[999\],/',$base,$onlooker);
    $village['nop'] = $nop_all - count($onlooker[0]);

    //レギュレーション挿入
    $rule = preg_replace('/.+"game_name": "([^"]*)",.+/s',"$1",$base);
    $rglid = preg_replace('/.+"roletable": "([^"]*)",.+/s',"$1",$base);

    if($rule !== 'タブラの人狼')
    {
      //特殊ルールがあるならレギュレーション扱いで挿入する
      $village['rglid'] = $RGL_SP[$rule];
      echo "#".$village['vno'].' is '.$rule.".Should check evil team.##";
    } 
    else if(preg_match("/秘話/",$village['name']))
    {
      echo 'NOTICE: '.$village['vno'].' may be 秘話村.';
      $village['rglid'] = $data::RGL_SECRET;
    }
    else if($rglid === 'custom')
    {
      $free = preg_replace('/.+"config":  "([^"]*)".+/s',"$1",$base);
      if(array_key_exists($free,$RGL_FREE))
      {
        $village['rglid'] = $RGL_FREE[$free];
      }
      else
      {
        echo "#".$village['vno'].' has '.$free."#";
        $village['rglid'] = $data::RGL_ETC;
      }
    }
    else
    {
      switch($rglid)
      {
        case "default":
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
      }
    }

    if($country === 'plot')
    {
      //狂人は裏切りの陣営かどうか
      //編成が深い霧の夜なら人数によって可変
      if(in_array($village['rglid'],$TM_EVIL) || ($village['rglid'] === $data::RGL_MIST && ($village['nop'] <8 || $village['nop'] >18)))
      {
        $is_evil = true;
      }
      else
      {
        $is_evil = false;
      }
      //村の方針でガチ/RPを分岐
      $policy = preg_replace('/.+"rating": "([^"]*)".+/s',"$1",$base);
      switch($policy)
      {
        case "とくになし":
        case "[言] 殺伐、暴言あり":
        case "[遖] あっぱれネタ風味":
        case "[張] うっかりハリセン":
        case "[全] 大人も子供も初心者も、みんな安心":
        case "[危] 無茶ぶり上等":
          //勝利陣営
          $village['wtmid'] = $WTM[preg_replace('/.+SOW_RECORD.CABALA.winners\[(\d+)\],.+/s',"$1",$base)];
          echo $village['vno'].'.'.$village['name'].' is guessed GACHI.->';
          break;
        default:
          $village['wtmid'] = $data::TM_RP;
          echo $village['vno'].'.'.$village['name'].' is guessed RP.->';
          break;
      }
    }
    else
    {
      //cielは裏切り陣営なし、RP専用
      $village['wtmid'] = $data::TM_RP;
    }

    //キャスト表
    foreach($cast as $item_cast)
    {
      $users = array(
         'persona'=>preg_replace('/.+"longname": "([^"]*)",.+/s',"$1",$item_cast)
        ,'player' =>preg_replace('/.+sow_auth_id = "([^"]*)".+/s',"$1",$item_cast)
        ,'role'   =>""
        ,'dtid'   =>$DESTINY[preg_replace('/.+"live": "([^"]*)",.+/s',"$1",$item_cast)]
        ,'end'    =>""
        ,'sklid'  =>$SKILL[preg_replace('/.+SOW_RECORD.CABALA.roles\[(\d+)\],.+/s',"$1",$item_cast)]
        ,'tmid'   =>$TEAM[preg_replace('/.+visible: "([^"]*)",.+/s',"$1",$item_cast)]
        ,'life'   =>""
        ,'rltid'  =>""
      );

      //裏切り陣営チェック
      if($country === "plot" && $users['tmid'] === $data::TM_EVIL && $is_evil  === false)
      {
        $users['tmid'] = $data::TM_WOLF;
      }

      //役職(表示名)
      $gift = (int)preg_replace('/.+SOW_RECORD.CABALA.gifts\[(-*\d+)\].+/s',"$1",$item_cast);
      $love = preg_replace('/.+pl\.love = "([^"]*)".+/s',"$1",$item_cast);
      //恩恵か恋邪気絆があれば追加
      if($gift >= 2 || $love !== '')
      {
        $after_role = array();
        if($gift >= 2)
        {
          $after_role[] = $GIFT[$gift];
        }
        if($love !== '')
        {
          $after_role[] = $BAND[$love];
        }
        $users['role'] = $ROLE[$users['sklid']].'、'.implode('、',$after_role);
      }
      else
      {
        $users['role'] = $ROLE[$users['sklid']];
      }

      //死亡日
      $end = (int)preg_replace('/.+"deathday": (-*\d+),.+/s',"$1",$item_cast);
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
      $rltid = preg_replace('/.+result:  "([^"]*)".+/s',"$1",$item_cast);
      if($users['sklid'] === $data::SKL_ONLOOKER)
      {
        //見物人は勝敗をつけない
        $users['rltid'] = $data::RSL_ONLOOKER;
      }
      else if($village['wtmid'] === $data::TM_RP)
      {
        //RP村は「参加」扱いにする
        $users['rltid'] = $data::RSL_JOIN;
      }
      else
      {
        $users['rltid'] = $RSL[$rltid];
      }

      $list_users[] = $users;
      $item_cast->clear();
      unset($item_cast);
    }
    $fetch->clear();
    //村を書き込む
    $db->connect();
    if($db->insert_db($village,$list_users))
    {
      echo $country.$village['vno']. ' is all inserted.'.PHP_EOL;
      $check->remove_queue($village['vno']);
    }
    $db->disconnect();
  }
unset($fetch);
}
