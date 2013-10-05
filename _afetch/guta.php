<?php

require_once('../../lib/simple_html_dom.php');
require_once('./data.php');
require_once('./check_village.php');
require_once('./insert_db.php');

mb_internal_encoding("UTF-8");

define("COUNTRY","guta");
define("CID",11);
define("URL_VIL","http://www3.marimo.or.jp/~fgmaster/cabala/sow.cgi?vid=");
define("URL_LOG","http://www3.marimo.or.jp/~fgmaster/cabala/sow.cgi?cmd=oldlog");

$db    = new Insert_DB(CID);
$check = new Check_Village(COUNTRY,CID,URL_VIL,URL_LOG);
$check->check_queue();
$check->check_fetch_vno();
if($check->get_village())
{
  $check->fetch_detail();
  $fetched_v = $check->get_village();
}
else
{
  echo 'not fetch.';
  exit;
}

$fetch = new simple_html_dom();
$data  = new Data();

//裏切りの陣営としてカウントするレギュレーション 深い霧は別途分岐を書く
$TM_EVIL = array($data::RGL_E,$data::RGL_S_E,$data::RGL_EFB,$data::RGL_ETC);

//陣営リスト
$TM_NORMAL = array(
   "村人"=>$data::TM_VILLAGER
  ,"人狼"=>$data::TM_WOLF
  ,"妖精"=>$data::TM_FAIRY
  ,"恋人"=>$data::TM_LOVERS
  ,"一匹"=>$data::TM_LWOLF
  ,"笛吹"=>$data::TM_PIPER
  ,"邪気"=>$data::TM_EFB
  ,"裏切"=>$data::TM_EVIL
  ,"据え"=>$data::TM_FISH
  ,"勝利"=>$data::TM_NONE
  );
$TM_AMBER = array(
   "町人"=>$data::TM_VILLAGER
  ,"魔術"=>$data::TM_WOLF
  ,"琥珀"=>$data::TM_FAIRY
  ,"星に"=>$data::TM_LOVERS
  ,"星の"=>$data::TM_LOVERS //エピ表記
  ,"はぐ"=>$data::TM_LWOLF
  ,"吟遊"=>$data::TM_PIPER
  ,"賭博"=>$data::TM_EFB
  ,"不和"=>$data::TM_EVIL
  ,"据え"=>$data::TM_FISH
);

//役職
//http://www3.marimo.or.jp/~fgmaster/cabala/sow.cgi?cmd=trsdiff
$SKL_NORMAL = array(
   "村人"=>$data::SKL_VILLAGER
  ,"聖痕者"=>$data::SKL_STIGMA
  ,"結社員"=>$data::SKL_MASON
  ,"共有者"=>$data::SKL_MASON
  ,"共鳴者"=>$data::SKL_TELEPATH
  ,"占い師"=>$data::SKL_SEER
  ,"信仰占師"=>$data::SKL_SEERWIN
  ,"気占師"=>$data::SKL_SEERAURA
  ,"賢者"=>$data::SKL_SAGE
  ,"狩人"=>$data::SKL_HUNTER
  ,"守護者"=>$data::SKL_HUNTER
  ,"霊能者"=>$data::SKL_MEDIUM
  ,"信仰霊能者"=>$data::SKL_MEDIWIN
  ,"導師"=>$data::SKL_PRIEST
  ,"降霊者"=>$data::SKL_NECRO
  ,"追従者"=>$data::SKL_FOLLOWER
  ,"煽動者"=>$data::SKL_AGITATOR
  ,"賞金稼"=>$data::SKL_BOUNTY
  ,"人犬"=>$data::SKL_WEREDOG
  ,"王子様"=>$data::SKL_PRINCE
  ,"狼血族"=>$data::SKL_LINEAGE
  ,"憑かれた人"=>$data::SKL_LINEAGE
  ,"医師"=>$data::SKL_DOCTOR
  ,"呪人"=>$data::SKL_CURSED
  ,"預言者"=>$data::SKL_PROPHET
  ,"病人"=>$data::SKL_SICK
  ,"錬金術師"=>$data::SKL_ALCHEMIST
  ,"魔女"=>$data::SKL_WITCH
  ,"少女"=>$data::SKL_GIRL
  ,"生贄"=>$data::SKL_SG
  ,"長老"=>$data::SKL_ELDER
  ,"邪魔之民"=>$data::SKL_JAMMER
  ,"念波之民"=>$data::SKL_LUNAPATH
  ,"コウモリ人間"=>$data::SKL_LUNAPATH
  ,"狂人"=>$data::SKL_LUNATIC
  ,"狂信者"=>$data::SKL_FANATIC
  ,"人形使い"=>$data::SKL_MUPPETER
  ,"囁き狂人"=>$data::SKL_LUNAWHS
  ,"Ｃ国狂人"=>$data::SKL_LUNAWHS
  ,"半狼"=>$data::SKL_HALFWOLF
  ,"魔神官"=>$data::SKL_LUNAPRI
  ,"魔術師"=>$data::SKL_LUNASAGE
  ,"首無騎士"=>$data::SKL_HEADLESS
  ,"首なし騎士"=>$data::SKL_HEADLESS
  ,"人狼"=>$data::SKL_WOLF
  ,"智狼"=>$data::SKL_WISEWOLF
  ,"呪狼"=>$data::SKL_CURSEWOLF
  ,"白狼"=>$data::SKL_WHITEWOLF
  ,"仔狼"=>$data::SKL_CHILDWOLF
  ,"衰狼"=>$data::SKL_DYINGWOLF
  ,"黙狼"=>$data::SKL_SILENT
  ,"栗鼠妖精"=>$data::SKL_FAIRY
  ,"ハムスター人間"=>$data::SKL_FAIRY
  ,"擬狼妖精"=>$data::SKL_MIMIC
  ,"風花妖精"=>$data::SKL_SNOW
  ,"悪戯妖精"=>$data::SKL_PIXY
  ,"ピクシー"=>$data::SKL_PIXY
  ,"邪気悪魔"=>$data::SKL_EFB
  ,"恋愛天使"=>$data::SKL_QP
  ,"キューピッド"=>$data::SKL_QP
  ,"片想い"=>$data::SKL_PASSION
  ,"弟子"=>$data::SKL_PUPIL
  ,"神話マニア"=>$data::SKL_PUPIL
  ,"盗賊"=>$data::SKL_THIEF
  ,"一匹狼"=>$data::SKL_LWOLF
  ,"笛吹き"=>$data::SKL_PIPER
  ,"鱗魚人"=>$data::SKL_FISH
  ,"遊び人"=>$data::SKL_PLAYBOY
);
//ミラーズホロウ
$SKL_MILLERS = array(
   "村人"=>$data::SKL_VILLAGER
  ,"預言者"=>$data::SKL_SAGE
  ,"守護者"=>$data::SKL_HUNTER
  ,"狩人"=>$data::SKL_BOUNTY
  ,"魔女"=>$data::SKL_WITCH
  ,"少女"=>$data::SKL_GIRL
  ,"スケープゴート"=>$data::SKL_SG
  ,"長老"=>$data::SKL_ELDER
  ,"人狼"=>$data::SKL_WOLF
  ,"キューピッド"=>$data::SKL_QP
  ,"盗賊"=>$data::SKL_THIEF
  ,"笛吹き"=>$data::SKL_PIPER
);
//昏き宵闇の琥珀
$SKL_AMBER = array(
   "町人"=>$data::SKL_VILLAGER
  ,"不在証明アリ"=>$data::SKL_STIGMA
  ,"刑事"=>$data::SKL_MASON
  ,"警部"=>$data::SKL_TELEPATH
  ,"真名探り"=>$data::SKL_SEER
  ,"見鬼"=>$data::SKL_SEERWIN
  ,"異能探知"=>$data::SKL_SEERAURA
  ,"魔術名鑑"=>$data::SKL_SAGE
  ,"護符職人"=>$data::SKL_HUNTER
  ,"好事家"=>$data::SKL_MEDIUM
  ,"琥珀研究科"=>$data::SKL_MEDIWIN
  ,"安楽椅子探偵"=>$data::SKL_PRIEST
  ,"ラヂオマニア"=>$data::SKL_NECRO
  ,"怯える者"=>$data::SKL_FOLLOWER
  ,"遺志を託す者"=>$data::SKL_AGITATOR
  ,"呪術マニア"=>$data::SKL_BOUNTY
  ,"魔力耐性者"=>$data::SKL_WEREDOG
  ,"無原罪の者"=>$data::SKL_PRINCE
  ,"魔力保持者"=>$data::SKL_LINEAGE
  ,"癒し手"=>$data::SKL_DOCTOR
  ,"呪われた者"=>$data::SKL_CURSED
  ,"琥珀病患者"=>$data::SKL_PROPHET
  ,"魔封じの者"=>$data::SKL_SICK
  ,"魔術師殺し"=>$data::SKL_ALCHEMIST
  ,"呪薬師"=>$data::SKL_WITCH
  ,"夢歩き"=>$data::SKL_GIRL
  ,"怨霊憑き"=>$data::SKL_SG
  ,"強き魔封じの者"=>$data::SKL_ELDER
  ,"真名隠し"=>$data::SKL_JAMMER
  ,"成り代わり"=>$data::SKL_SNATCH
  ,"念話術師"=>$data::SKL_LUNAPATH
  ,"悪徳琥珀商人"=>$data::SKL_LUNATIC
  ,"魔術師を目撃した者"=>$data::SKL_FANATIC
  ,"死人遣い"=>$data::SKL_MUPPETER
  ,"魔術師の愛弟子"=>$data::SKL_LUNAWHS
  ,"見習い魔術師"=>$data::SKL_HALFWOLF
  ,"故売屋"=>$data::SKL_LUNAPRI
  ,"見通す目"=>$data::SKL_LUNASAGE
  ,"欲深き魔術師"=>$data::SKL_HEADLESS
  ,"魔術師"=>$data::SKL_WOLF
  ,"秘術師"=>$data::SKL_WISEWOLF
  ,"呪術師"=>$data::SKL_CURSEWOLF
  ,"魔導師"=>$data::SKL_WHITEWOLF
  ,"付与魔術師"=>$data::SKL_CHILDWOLF
  ,"瀕死の魔術師"=>$data::SKL_DYINGWOLF
  ,"新人魔術師"=>$data::SKL_SILENT
  ,"琥珀妖精"=>$data::SKL_FAIRY
  ,"欺く琥珀妖精"=>$data::SKL_MIMIC
  ,"年老いた妖精"=>$data::SKL_SNOW
  ,"悪意ある妖精"=>$data::SKL_PIXY
  ,"賭博屋"=>$data::SKL_EFB
  ,"占星術師"=>$data::SKL_QP
  ,"懸想する者"=>$data::SKL_PASSION
  ,"物真似師"=>$data::SKL_PUPIL
  ,"生ける屍"=>$data::SKL_THIEF
  ,"はぐれ魔術師"=>$data::SKL_LWOLF
  ,"吟遊詩人"=>$data::SKL_PIPER
  ,"人魚"=>$data::SKL_FISH
  ,"占星術フリーク"=>$data::SKL_PLAYBOY
);

//レギュレーション
$RGL = array(
   '標準'=>$data::RGL_LEO
  ,'新標準'=>$data::RGL_LEO
  ,'深い霧の夜'=>$data::RGL_MIST
  ,'人狼審問 試験壱型'=>$data::RGL_TES1
  ,'人狼審問 試験弐型'=>$data::RGL_TES2
  ,'自由設定'=>$data::RGL_ETC
);
//特殊ルール
$RGL_SP = array(
  'ミラーズホロウ'=>$data::RGL_MILL
  ,'死んだら負け'=>$data::RGL_DEATH
  ,'Trouble☆Aliens'=>$data::RGL_TA
  ,'深い霧の夜'=>$data::RGL_MIST
);
//自由設定でも特殊レギュにしない編成
$RGL_FREE = array(
    '村人x7 結社員x2 占い師x1 守護者x1 霊能者x1 囁き狂人x1 人狼x3'=>$data::RGL_C
   ,'村人x8 聖痕者x1 占い師x1 狩人x1 霊能者x1 囁き狂人x1 人狼x3'=>$data::RGL_C_ST
   ,'村人x8 聖痕者x1 占い師x1 守護者x1 霊能者x1 囁き狂人x1 人狼x3'=>$data::RGL_C_ST
   ,'村人x8 聖痕者x1 占い師x1 守護者x1 霊能者x1 Ｃ国狂人x1 人狼x3'=>$data::RGL_C_ST
   ,'村人x9 占い師x1 守護者x1 霊能者x1 狂人x1 人狼x3'=>$data::RGL_G
   ,'村人x8 聖痕者x1 占い師x1 狩人x1 霊能者x1 狂人x1 人狼x3'=>$data::RGL_G_ST
   ,'村人x8 占い師x1 守護者x1 霊能者x1 狂人x1 人狼x3 栗鼠妖精x1'=>$data::RGL_E
   ,'村人x8 占い師x1 狩人x1 霊能者x1 狂人x1 人狼x3 ハムスター人間x1'=>$data::RGL_E
   ,'村人x6 共鳴者x2 占い師x1 狩人x1 霊能者x1 狂人x1 人狼x3 ハムスター人間x1'=>$data::RGL_E
   ,'村人x7 共有者x2 占い師x1 狩人x1 霊能者x1 囁き狂人x1 人狼x3 ハムスター人間x1'=>$data::RGL_E
   ,'村人x7 聖痕者x1 占い師x1 狩人x1 霊能者x1 狂人x1 人狼x3 ハムスター人間x1'=>$data::RGL_E
   ,'村人x7 聖痕者x1 占い師x1 狩人x1 霊能者x1 狂人x1 人狼x3'=>$data::RGL_TES1
   ,'村人x7 占い師x1 守護者x1 霊能者x1 狂人x1 人狼x3'=>$data::RGL_S_3
   ,'村人x5 占い師x1 霊能者x1 人狼x2'=>$data::RGL_S_2
   ,'村人x5 占い師x1 狩人x1 霊能者x1 狂人x1 人狼x2'=>$data::RGL_S_2
   ,'村人x5 占い師x1 守護者x1 霊能者x1 狂人x1 人狼x2'=>$data::RGL_S_2
   ,'村人x4 占い師x1 守護者x1 霊能者x1 狂人x1 人狼x2'=>$data::RGL_S_2
   ,'村人x6 占い師x1 守護者x1 霊能者x1 囁き狂人x1 人狼x2'=>$data::RGL_S_C2
   ,'村人x8 占い師x1 狩人x1 霊能者x1 囁き狂人x1 人狼x2'=>$data::RGL_S_C2
   ,'村人x5 占い師x1 人狼x1'=>$data::RGL_S_1
   ,'村人x6 占い師x1 霊能者x1 人狼x2'=>$data::RGL_S_L0
   ,'村人x5 占い師x1 霊能者x1 人狼x2 栗鼠妖精x1'=>$data::RGL_S_E
   ,'村人x6 聖痕者x1 占い師x1 狩人x1 霊能者x1 狂人x1 人狼x3 ハムスター人間x1'=>$data::RGL_S_E
   ,'村人x4 占い師x1 狩人x1 霊能者x1 狂人x1 人狼x2 ハムスター人間x1'=>$data::RGL_S_E
   ,'村人x6 占い師x1 狩人x1 霊能者x1 狂人x1 人狼x2 ハムスター人間x1'=>$data::RGL_S_E
   ,'村人x4 聖痕者x1 占い師x1 守護者x1 狂人x1 人狼x2 栗鼠妖精x1'=>$data::RGL_S_E
   ,'村人x4 占い師x1 狂信者x1 人狼x1 栗鼠妖精x1'=>$data::RGL_S_E
   ,'村人x7 占い師x1 守護者x1 霊能者x1 囁き狂人x1 人狼x2 恋愛天使x1'=>$data::RGL_LOVE
   ,'村人x9 占い師x1 霊能者x1 人狼x3 恋愛天使x1'=>$data::RGL_LOVE
   ,'村人x6 占い師x1 守護者x1 霊能者x1 狂人x1 人狼x2 恋愛天使x1'=>$data::RGL_LOVE
   ,'村人x5 狂信者x1 人狼x1'=>$data::RGL_HERO
   ,'村人x4 狂信者x1 人狼x1'=>$data::RGL_HERO
   ,'村人x5 人狼x1'=>$data::RGL_HERO
   ,'村人x1 霊能者x6 人狼x2'=>$data::RGL_ROLLER
   ,'村人x4 占い師x1 霊能者x1 人狼x2 遊び人x1 '=>$data::RGL_LPLY
   ,'村人x5 占い師x1 守護者x1 霊能者x1 狂人x1 人狼x2 遊び人x1'=>$data::RGL_LPLY
   ,'村人x4 占い師x1 守護者x1 霊能者x1 狂人x1 人狼x3 遊び人x1'=>$data::RGL_LPLY
   ,'村人x9 占い師x1 霊能者x1 囁き狂人x1 人狼x3 遊び人x1'=>$data::RGL_LPLY
);

//結末
$DES_NORMAL = array(
   "生存者"=>$data::DES_ALIVE
  ,"突然死"=>$data::DES_RETIRED
  ,"処刑死"=>$data::DES_HANGED
  ,"襲撃死"=>$data::DES_EATEN
  ,"呪詛死"=>$data::DES_CURSED
  ,"衰退死"=>$data::DES_DROOP
  ,"後追死"=>$data::DES_SUICIDE
  ,"恐怖死"=>$data::DES_FEARED
);
$DES_AMBER = array(
   "生存者"=>$data::DES_ALIVE
  ,"突然死"=>$data::DES_RETIRED//不明
  ,"殺害"=>$data::DES_HANGED
  ,"琥珀化"=>$data::DES_EATEN
  ,"呪詛死"=>$data::DES_CURSED //そのまま
  ,"衰退死"=>$data::DES_DROOP  //不明
  ,"後追死"=>$data::DES_SUICIDE//不明
  ,"恐怖死"=>$data::DES_FEARED//不明
);

$RSL = array(
   "勝利"=>$data::RSL_WIN
  ,"敗北"=>$data::RSL_LOSE
  ,""=>$data::RSL_INVALID //無効(突然死)
);

foreach($fetched_v as $item_vil)
{
  //初期化
  $village = array(
             'cid'  =>CID
            ,'vno'  =>$item_vil[0]
            ,'name' =>$item_vil[1]
            ,'date' =>""
            ,'nop'  =>(int)$item_vil[2]
            ,'rglid'=>""
            ,'days' =>$item_vil[4]
            ,'wtmid'=>""
  );

  //情報欄取得
  $fetch->load_file($item_vil[6]);

  //レギュレーション挿入
  $rglid= trim($fetch->find('dl.mes_text_report dt',1)->plaintext);
  if(array_key_exists($rglid,$RGL_SP))
  {
    //特殊ルールがあるならレギュレーション扱いで挿入する
    $village['rglid'] = $RGL_SP[$rglid];
    echo "#".$village['vno'].' is '.$rglid.".Should check evil team.##";
  }
  else if(preg_match("/秘話/",$village['name']))
  {
    //秘話村を挿入
    echo 'NOTICE: '.$vno.' may be 秘話村.';
    //$village['rglid'] = $data::RGL_SECRET;
  }
  else if($item_vil[5] === '自由設定')
  {
    //自由設定でも特定の編成はレギュレーションを指定する
    $free = trim($fetch->find('dl.mes_text_report dd',3)->plaintext);
    if(array_key_exists($free,$RGL_FREE))
    {
      $village['rglid'] = $RGL_FREE[$free];
    }
    else
    {
      echo "#".$village['vno'].' has '.$free."#";
      $village['rglid'] = $RGL[$item_vil[5]];
    }
  }
  else
  {
    switch($item_vil[5])
    {
      case "新標準":
      case "標準":
        if($village['nop'] <= 7)
        {
          $village['rglid'] = $data::RGL_S_1;
        }
        else
        {
          $village['rglid'] = $data::RGL_LEO;
        }
        break;
      case "深い霧の夜":
        $village['rglid'] = $RGL[$item_vil[5]];
        break;
      case "人狼審問 試験壱型":
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
      case "人狼審問 試験弐型":
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
      case "人狼BBS C国":
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
      case "人狼BBS F国":
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
      case "人狼BBS G国":
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

  //言い換え
  $rp= trim($fetch->find('dl.mes_text_report dt',0)->plaintext);
  //ガチ村のみ勝利陣営を挿入
  $wtmid = $fetch->find('p.multicolumn_left',1)->plaintext;
  switch($wtmid)
  {
    case "ガチ推理":
    case "推理＆RP":
      switch($rp)
      {
      case "昏き宵闇の琥珀":
        $village['wtmid'] = $TM_AMBER[mb_substr($item_vil[3],0,2)];
        break;
      default:
        $village['wtmid'] = $TM_NORMAL[mb_substr($item_vil[3],0,2)];
        break;
      }
      break;
    case "お祭り騒ぎ":
    case "未設定":
        echo '#'.$village['vno'].'. '.$village['name'].' is '.$wtmid.'#';
    default:
      $village['wtmid'] = $data::TM_RP;
      break;
  }

  //狂人は裏切りの陣営かどうか
  //ルールではなく編成が深い霧の夜なら人数によって可変
  if(in_array($village['rglid'],$TM_EVIL) || ($item_vil[5] === "深い霧の夜" && ($village['nop'] <8 || $village['nop'] >18)))
  {
    $is_evil = true;
  }
  else
  {
    $is_evil = false;
  }

  //ID公開村かどうか
  $is_ID = $fetch->find('div.mes_maker',2)->find('p.multicolumn_left',4)->plaintext;

  //初日取得
  $fetch->clear();
  $url = preg_replace("/cmd=vinfo/","turn=0&row=10&mode=all&move=page&pageno=1",$item_vil[6]);
  $fetch->load_file($url);

  //開始日(プロローグ第一声)
  $date = $fetch->find('p.mes_date',0)->plaintext;
  //ID公開村では取得方法を変える
  if($is_ID === "公開する")
  {
    $date = $fetch->find('p.mes_date',0)->plaintext;
    $date = mb_substr($date,mb_strpos($date,"2"),10);
  }
  else
  {
    $date = mb_substr($fetch->find('p.mes_date',0)->plaintext,5,10);
  }
  //MySQL用に日付の区切りを/から-に変換
  $village['date'] = preg_replace('/(\d{4})\/(\d{2})\/(\d{2})/','\1-\2-\3',$date);

  //エピローグ取得
  $fetch->clear();
  $url = preg_replace("/0&row=10/",$village['days']."&row=50",$url);
  $fetch->load_file($url);
  $cast = $fetch->find('tbody tr.i_active');
  

  //キャスト表を配列にする
  $list_users = array();
  foreach($cast as $item_cast)
  {
    $users = array(
              'persona'=>trim($item_cast->find("td",0)->plaintext)
              ,'player' =>trim($item_cast->find("td",1)->plaintext)
              ,'role'   =>""
              ,'dtid'   =>""
              ,'end'    =>""
              ,'sklid'  =>""
              ,'tmid'   =>""
              ,'life'   =>""
              ,'rltid'  =>""
    );
    
    $result = $item_cast->find("td",3)->plaintext;
    $result = mb_substr($result,0,mb_strpos($result,"\n")-1);
    $result = explode(' ',$result);

    //陣営と役職を取得
    $users['role'] = mb_substr($result[2],mb_strpos($result[2],'：')+1);

    if(mb_substr($users['role'],-2) === "居た")
    {
      //見物人設定
      $users['role'] = '見物人';
      $users['dtid'] = $data::DES_ONLOOKER;
      $users['end'] = 1;
      $users['sklid'] = $data::SKL_ONLOOKER;
      $users['tmid'] = $data::TM_ONLOOKER;
      $users['life'] = 0;
      $users['rltid'] = $data::RSL_ONLOOKER;
    }
    else
    {
      //日数
      if($result[0] === '生存者')
      {
        $users['end'] = $village['days'];
      }
      else
      {
        $users['end'] = (int)preg_replace("/(.+)日/","$1",$item_cast->find("td",2)->plaintext);
      }

      //非ガチ村は勝敗をつけずに「参加」にする
      if($village['wtmid'] === 0)
      {
        $users['rltid'] = $data::RSL_JOIN;
      }
      else
      {
        $users['rltid'] = $RSL[$result[1]];
      }

      //役職欄に絆などついている場合
      if(mb_strpos($users['role'],"、") === false)
      {
        $sklid = $users['role'];
      }
      else
      {
        $sklid = mb_substr($users['role'],0,mb_strpos($users['role'],"、"));
      }
      switch($rp)
      {
      case "昏き宵闇の琥珀":
        $users['sklid']= $SKL_AMBER[$sklid];
        $users['dtid'] = $DES_AMBER[$result[0]];
        $users['tmid'] = $TM_AMBER[mb_substr($result[2],0,2)];
        break;
      case "ミラーズホロウ":
        $users['sklid']= $SKL_MILLERS[$sklid];
        $users['dtid'] = $DES_NORMAL[$result[0]];
        $users['tmid'] = $TM_NORMAL[mb_substr($result[2],0,2)];
        break;
      default:
        $users['sklid'] = $SKL_NORMAL[$sklid];
        $users['dtid'] = $DES_NORMAL[$result[0]];
        $users['tmid'] = $TM_NORMAL[mb_substr($result[2],0,2)];
        break;
      }

      //第三陣営がいない村では裏切りの陣営を人狼陣営扱いにする
      if($users['tmid'] === $data::TM_EVIL && $is_evil === false)
      {
        $users['tmid'] = $data::TM_WOLF;
      }

      //生存係数挿入
      if($users['dtid'] === $data::DES_ALIVE)
      {
        $users['life'] = 1.00;
      }
      else
      {
        $users['life'] = round(($users['end']-1) / $village['days'],2);
      }

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
    echo $village['vno']. ' is all inserted.'.PHP_EOL;
    $check->remove_queue($village['vno']);
  }
  $db->disconnect();
}
unset($fetch);
