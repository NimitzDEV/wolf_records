<?php

require_once('./fetch_village.php');

define('COUNTRY',11);  //国ID
define('VID',6367);       //villageテーブルの開始ID

//お祭り騒ぎのガチリスト
$GACHI = array(
  455,448,446,445,438,437,429,426,416,388,379,340,339,334,322,319,307,297,287,281,280,275,265,256,248,244,243,241,202,201,186,182,172,170,156,154,153,136,127,124,114,112,108,63,60,41,37,33,16
);
//秘話村リスト
$SECRET = array(
421,378,258,199,197,177,169,153,122,92,78,64,60,52,50,49,44
);

$fetch = new fetch_Village(COUNTRY);

//裏切りの陣営としてカウントするレギュレーション 深い霧は別途分岐を書く
$TM_EVIL = array($fetch::RGL_E,$fetch::RGL_S_E,$fetch::RGL_EFB,$fetch::RGL_ETC);

//陣営リスト
$TM_NORMAL = array(
   "村人"=>$fetch::TM_VILLAGER
  ,"人狼"=>$fetch::TM_WOLF
  ,"妖精"=>$fetch::TM_FAIRY
  ,"恋人"=>$fetch::TM_LOVERS
  ,"一匹"=>$fetch::TM_LWOLF
  ,"笛吹"=>$fetch::TM_PIPER
  ,"邪気"=>$fetch::TM_EFB
  ,"裏切"=>$fetch::TM_EVIL
  ,"据え"=>$fetch::TM_FISH
  ,"勝利"=>$fetch::TM_NONE
  );
$TM_AMBER = array(
   "町人"=>$fetch::TM_VILLAGER
  ,"魔術"=>$fetch::TM_WOLF
  ,"琥珀"=>$fetch::TM_FAIRY
  ,"星に"=>$fetch::TM_LOVERS
  ,"星の"=>$fetch::TM_LOVERS //エピ表記
  ,"はぐ"=>$fetch::TM_LWOLF
  ,"吟遊"=>$fetch::TM_PIPER
  ,"賭博"=>$fetch::TM_EFB
  ,"不和"=>$fetch::TM_EVIL
  ,"据え"=>$fetch::TM_FISH
);

//役職
//http://www3.marimo.or.jp/~fgmaster/cabala/sow.cgi?cmd=trsdiff
$SKL_NORMAL = array(
   "村人"=>$fetch::SKL_VILLAGER
  ,"聖痕者"=>$fetch::SKL_STIGMA
  ,"結社員"=>$fetch::SKL_MASON
  ,"共有者"=>$fetch::SKL_MASON
  ,"共鳴者"=>$fetch::SKL_TELEPATH
  ,"占い師"=>$fetch::SKL_SEER
  ,"信仰占師"=>$fetch::SKL_SEERWIN
  ,"気占師"=>$fetch::SKL_SEERAURA
  ,"賢者"=>$fetch::SKL_SAGE
  ,"狩人"=>$fetch::SKL_HUNTER
  ,"守護者"=>$fetch::SKL_HUNTER
  ,"霊能者"=>$fetch::SKL_MEDIUM
  ,"信仰霊能者"=>$fetch::SKL_MEDIWIN
  ,"導師"=>$fetch::SKL_PRIEST
  ,"降霊者"=>$fetch::SKL_NECRO
  ,"追従者"=>$fetch::SKL_FOLLOWER
  ,"煽動者"=>$fetch::SKL_AGITATOR
  ,"賞金稼"=>$fetch::SKL_BOUNTY
  ,"人犬"=>$fetch::SKL_WEREDOG
  ,"王子様"=>$fetch::SKL_PRINCE
  ,"狼血族"=>$fetch::SKL_LINEAGE
  ,"憑かれた人"=>$fetch::SKL_LINEAGE
  ,"医師"=>$fetch::SKL_DOCTOR
  ,"呪人"=>$fetch::SKL_CURSED
  ,"預言者"=>$fetch::SKL_PROPHET
  ,"病人"=>$fetch::SKL_SICK
  ,"錬金術師"=>$fetch::SKL_ALCHEMIST
  ,"魔女"=>$fetch::SKL_WITCH
  ,"少女"=>$fetch::SKL_GIRL
  ,"生贄"=>$fetch::SKL_SG
  ,"長老"=>$fetch::SKL_ELDER
  ,"邪魔之民"=>$fetch::SKL_JAMMER
  ,"念波之民"=>$fetch::SKL_LUNAPATH
  ,"コウモリ人間"=>$fetch::SKL_LUNAPATH
  ,"狂人"=>$fetch::SKL_LUNATIC
  ,"狂信者"=>$fetch::SKL_FANATIC
  ,"人形使い"=>$fetch::SKL_MUPPETER
  ,"囁き狂人"=>$fetch::SKL_LUNAWHS
  ,"Ｃ国狂人"=>$fetch::SKL_LUNAWHS
  ,"半狼"=>$fetch::SKL_HALFWOLF
  ,"魔神官"=>$fetch::SKL_LUNAPRI
  ,"魔術師"=>$fetch::SKL_LUNASAGE
  ,"首無騎士"=>$fetch::SKL_HEADLESS
  ,"首なし騎士"=>$fetch::SKL_HEADLESS
  ,"人狼"=>$fetch::SKL_WOLF
  ,"智狼"=>$fetch::SKL_WISEWOLF
  ,"呪狼"=>$fetch::SKL_CURSEWOLF
  ,"白狼"=>$fetch::SKL_WHITEWOLF
  ,"仔狼"=>$fetch::SKL_CHILDWOLF
  ,"衰狼"=>$fetch::SKL_DYINGWOLF
  ,"黙狼"=>$fetch::SKL_SILENT
  ,"栗鼠妖精"=>$fetch::SKL_FAIRY
  ,"ハムスター人間"=>$fetch::SKL_FAIRY
  ,"擬狼妖精"=>$fetch::SKL_MIMIC
  ,"風花妖精"=>$fetch::SKL_SNOW
  ,"悪戯妖精"=>$fetch::SKL_PIXY
  ,"ピクシー"=>$fetch::SKL_PIXY
  ,"邪気悪魔"=>$fetch::SKL_EFB
  ,"恋愛天使"=>$fetch::SKL_QP
  ,"キューピッド"=>$fetch::SKL_QP
  ,"片想い"=>$fetch::SKL_PASSION
  ,"弟子"=>$fetch::SKL_PUPIL
  ,"神話マニア"=>$fetch::SKL_PUPIL
  ,"盗賊"=>$fetch::SKL_THIEF
  ,"一匹狼"=>$fetch::SKL_LWOLF
  ,"笛吹き"=>$fetch::SKL_PIPER
  ,"鱗魚人"=>$fetch::SKL_FISH
  ,"遊び人"=>$fetch::SKL_PLAYBOY
);
//ミラーズホロウ
$SKL_MILLERS = array(
   "村人"=>$fetch::SKL_VILLAGER
  ,"預言者"=>$fetch::SKL_SAGE
  ,"守護者"=>$fetch::SKL_HUNTER
  ,"狩人"=>$fetch::SKL_BOUNTY
  ,"魔女"=>$fetch::SKL_WITCH
  ,"少女"=>$fetch::SKL_GIRL
  ,"スケープゴート"=>$fetch::SKL_SG
  ,"長老"=>$fetch::SKL_ELDER
  ,"人狼"=>$fetch::SKL_WOLF
  ,"キューピッド"=>$fetch::SKL_QP
  ,"盗賊"=>$fetch::SKL_THIEF
  ,"笛吹き"=>$fetch::SKL_PIPER
);
//昏き宵闇の琥珀
$SKL_AMBER = array(
   "町人"=>$fetch::SKL_VILLAGER
  ,"不在証明アリ"=>$fetch::SKL_STIGMA
  ,"刑事"=>$fetch::SKL_MASON
  ,"警部"=>$fetch::SKL_TELEPATH
  ,"真名探り"=>$fetch::SKL_SEER
  ,"見鬼"=>$fetch::SKL_SEERWIN
  ,"異能探知"=>$fetch::SKL_SEERAURA
  ,"魔術名鑑"=>$fetch::SKL_SAGE
  ,"護符職人"=>$fetch::SKL_HUNTER
  ,"好事家"=>$fetch::SKL_MEDIUM
  ,"琥珀研究科"=>$fetch::SKL_MEDIWIN
  ,"安楽椅子探偵"=>$fetch::SKL_PRIEST
  ,"ラヂオマニア"=>$fetch::SKL_NECRO
  ,"怯える者"=>$fetch::SKL_FOLLOWER
  ,"遺志を託す者"=>$fetch::SKL_AGITATOR
  ,"呪術マニア"=>$fetch::SKL_BOUNTY
  ,"魔力耐性者"=>$fetch::SKL_WEREDOG
  ,"無原罪の者"=>$fetch::SKL_PRINCE
  ,"魔力保持者"=>$fetch::SKL_LINEAGE
  ,"癒し手"=>$fetch::SKL_DOCTOR
  ,"呪われた者"=>$fetch::SKL_CURSED
  ,"琥珀病患者"=>$fetch::SKL_PROPHET
  ,"魔封じの者"=>$fetch::SKL_SICK
  ,"魔術師殺し"=>$fetch::SKL_ALCHEMIST
  ,"呪薬師"=>$fetch::SKL_WITCH
  ,"夢歩き"=>$fetch::SKL_GIRL
  ,"怨霊憑き"=>$fetch::SKL_SG
  ,"強き魔封じの者"=>$fetch::SKL_ELDER
  ,"真名隠し"=>$fetch::SKL_JAMMER
  ,"成り代わり"=>$fetch::SKL_SNATCH
  ,"念話術師"=>$fetch::SKL_LUNAPATH
  ,"悪徳琥珀商人"=>$fetch::SKL_LUNATIC
  ,"魔術師を目撃した者"=>$fetch::SKL_FANATIC
  ,"死人遣い"=>$fetch::SKL_MUPPETER
  ,"魔術師の愛弟子"=>$fetch::SKL_LUNAWHS
  ,"見習い魔術師"=>$fetch::SKL_HALFWOLF
  ,"故売屋"=>$fetch::SKL_LUNAPRI
  ,"見通す目"=>$fetch::SKL_LUNASAGE
  ,"欲深き魔術師"=>$fetch::SKL_HEADLESS
  ,"魔術師"=>$fetch::SKL_WOLF
  ,"秘術師"=>$fetch::SKL_WISEWOLF
  ,"呪術師"=>$fetch::SKL_CURSEWOLF
  ,"魔導師"=>$fetch::SKL_WHITEWOLF
  ,"付与魔術師"=>$fetch::SKL_CHILDWOLF
  ,"瀕死の魔術師"=>$fetch::SKL_DYINGWOLF
  ,"新人魔術師"=>$fetch::SKL_SILENT
  ,"琥珀妖精"=>$fetch::SKL_FAIRY
  ,"欺く琥珀妖精"=>$fetch::SKL_MIMIC
  ,"年老いた妖精"=>$fetch::SKL_SNOW
  ,"悪意ある妖精"=>$fetch::SKL_PIXY
  ,"賭博屋"=>$fetch::SKL_EFB
  ,"占星術師"=>$fetch::SKL_QP
  ,"懸想する者"=>$fetch::SKL_PASSION
  ,"物真似師"=>$fetch::SKL_PUPIL
  ,"生ける屍"=>$fetch::SKL_THIEF
  ,"はぐれ魔術師"=>$fetch::SKL_LWOLF
  ,"吟遊詩人"=>$fetch::SKL_PIPER
  ,"人魚"=>$fetch::SKL_FISH
  ,"占星術フリーク"=>$fetch::SKL_PLAYBOY
);

//レギュレーション
$RGL = array(
   '標準'=>$fetch::RGL_LEO
  ,'新標準'=>$fetch::RGL_LEO
  ,'深い霧の夜'=>$fetch::RGL_MIST
  ,'人狼審問 試験壱型'=>$fetch::RGL_TES1
  ,'人狼審問 試験弐型'=>$fetch::RGL_TES2
  ,'自由設定'=>$fetch::RGL_ETC
);
//特殊ルール
$RGL_SP = array(
  'ミラーズホロウ'=>$fetch::RGL_MILL
  ,'死んだら負け'=>$fetch::RGL_DEATH
  ,'Trouble☆Aliens'=>$fetch::RGL_TA
  ,'深い霧の夜'=>$fetch::RGL_MIST
);
//自由設定でも特殊レギュにしない編成
$RGL_FREE = array(
    '村人x7 結社員x2 占い師x1 守護者x1 霊能者x1 囁き狂人x1 人狼x3'=>$fetch::RGL_C
   ,'村人x8 聖痕者x1 占い師x1 狩人x1 霊能者x1 囁き狂人x1 人狼x3'=>$fetch::RGL_C_ST
   ,'村人x8 聖痕者x1 占い師x1 守護者x1 霊能者x1 囁き狂人x1 人狼x3'=>$fetch::RGL_C_ST
   ,'村人x8 聖痕者x1 占い師x1 守護者x1 霊能者x1 Ｃ国狂人x1 人狼x3'=>$fetch::RGL_C_ST
   ,'村人x9 占い師x1 守護者x1 霊能者x1 狂人x1 人狼x3'=>$fetch::RGL_G
   ,'村人x8 聖痕者x1 占い師x1 狩人x1 霊能者x1 狂人x1 人狼x3'=>$fetch::RGL_G_ST
   ,'村人x8 占い師x1 守護者x1 霊能者x1 狂人x1 人狼x3 栗鼠妖精x1'=>$fetch::RGL_E
   ,'村人x8 占い師x1 狩人x1 霊能者x1 狂人x1 人狼x3 ハムスター人間x1'=>$fetch::RGL_E
   ,'村人x6 共鳴者x2 占い師x1 狩人x1 霊能者x1 狂人x1 人狼x3 ハムスター人間x1'=>$fetch::RGL_E
   ,'村人x7 共有者x2 占い師x1 狩人x1 霊能者x1 囁き狂人x1 人狼x3 ハムスター人間x1'=>$fetch::RGL_E
   ,'村人x7 聖痕者x1 占い師x1 狩人x1 霊能者x1 狂人x1 人狼x3 ハムスター人間x1'=>$fetch::RGL_E
   ,'村人x7 聖痕者x1 占い師x1 狩人x1 霊能者x1 狂人x1 人狼x3'=>$fetch::RGL_TES1
   ,'村人x7 占い師x1 守護者x1 霊能者x1 狂人x1 人狼x3'=>$fetch::RGL_S_3
   ,'村人x5 占い師x1 霊能者x1 人狼x2'=>$fetch::RGL_S_2
   ,'村人x5 占い師x1 狩人x1 霊能者x1 狂人x1 人狼x2'=>$fetch::RGL_S_2
   ,'村人x5 占い師x1 守護者x1 霊能者x1 狂人x1 人狼x2'=>$fetch::RGL_S_2
   ,'村人x4 占い師x1 守護者x1 霊能者x1 狂人x1 人狼x2'=>$fetch::RGL_S_2
   ,'村人x6 占い師x1 守護者x1 霊能者x1 囁き狂人x1 人狼x2'=>$fetch::RGL_S_C2
   ,'村人x8 占い師x1 狩人x1 霊能者x1 囁き狂人x1 人狼x2'=>$fetch::RGL_S_C2
   ,'村人x5 占い師x1 人狼x1'=>$fetch::RGL_S_1
   ,'村人x6 占い師x1 霊能者x1 人狼x2'=>$fetch::RGL_S_L0
   ,'村人x5 占い師x1 霊能者x1 人狼x2 栗鼠妖精x1'=>$fetch::RGL_S_E
   ,'村人x6 聖痕者x1 占い師x1 狩人x1 霊能者x1 狂人x1 人狼x3 ハムスター人間x1'=>$fetch::RGL_S_E
   ,'村人x4 占い師x1 狩人x1 霊能者x1 狂人x1 人狼x2 ハムスター人間x1'=>$fetch::RGL_S_E
   ,'村人x6 占い師x1 狩人x1 霊能者x1 狂人x1 人狼x2 ハムスター人間x1'=>$fetch::RGL_S_E
   ,'村人x4 聖痕者x1 占い師x1 守護者x1 狂人x1 人狼x2 栗鼠妖精x1'=>$fetch::RGL_S_E
   ,'村人x4 占い師x1 狂信者x1 人狼x1 栗鼠妖精x1'=>$fetch::RGL_S_E
   ,'村人x7 占い師x1 守護者x1 霊能者x1 囁き狂人x1 人狼x2 恋愛天使x1'=>$fetch::RGL_LOVE
   ,'村人x9 占い師x1 霊能者x1 人狼x3 恋愛天使x1'=>$fetch::RGL_LOVE
   ,'村人x6 占い師x1 守護者x1 霊能者x1 狂人x1 人狼x2 恋愛天使x1'=>$fetch::RGL_LOVE
   ,'村人x6 共鳴者x2 占い師x1 守護者x1 霊能者x1 囁き狂人x1 首無騎士x3 恋愛天使x1'=>$fetch::RGL_LOVE
   ,'村人x7 占い師x1 狩人x1 霊能者x1 狂信者x1 人狼x2 キューピッドx1'=>$fetch::RGL_LOVE
   ,'村人x5 狂信者x1 人狼x1'=>$fetch::RGL_HERO
   ,'村人x4 狂信者x1 人狼x1'=>$fetch::RGL_HERO
   ,'村人x5 人狼x1'=>$fetch::RGL_HERO
   ,'村人x1 霊能者x6 人狼x2'=>$fetch::RGL_ROLLER
   ,'村人x4 占い師x1 霊能者x1 人狼x2 遊び人x1 '=>$fetch::RGL_LPLY
   ,'村人x5 占い師x1 守護者x1 霊能者x1 狂人x1 人狼x2 遊び人x1'=>$fetch::RGL_LPLY
   ,'村人x4 占い師x1 守護者x1 霊能者x1 狂人x1 人狼x3 遊び人x1'=>$fetch::RGL_LPLY
   ,'村人x9 占い師x1 霊能者x1 囁き狂人x1 人狼x3 遊び人x1'=>$fetch::RGL_LPLY
);

//結末
$DES_NORMAL = array(
   "生存者"=>$fetch::DES_ALIVE
  ,"突然死"=>$fetch::DES_RETIRED
  ,"処刑死"=>$fetch::DES_HANGED
  ,"襲撃死"=>$fetch::DES_EATEN
  ,"呪詛死"=>$fetch::DES_CURSED
  ,"衰退死"=>$fetch::DES_DROOP
  ,"後追死"=>$fetch::DES_SUICIDE
  ,"恐怖死"=>$fetch::DES_FEARED
);
$DES_AMBER = array(
   "生存者"=>$fetch::DES_ALIVE
  ,"突然死"=>$fetch::DES_RETIRED//不明
  ,"殺害"=>$fetch::DES_HANGED
  ,"琥珀化"=>$fetch::DES_EATEN
  ,"呪詛死"=>$fetch::DES_CURSED //そのまま
  ,"衰退死"=>$fetch::DES_DROOP  //不明
  ,"後追死"=>$fetch::DES_SUICIDE//不明
  ,"恐怖死"=>$fetch::DES_FEARED//不明
);

$RSL = array(
   "勝利"=>$fetch::RSL_WIN
  ,"敗北"=>$fetch::RSL_LOSE
  ,""=>$fetch::RSL_INVALID //無効(突然死)
);

$base_list = $fetch->read_list();

$fetch->open_list('village');
$fetch->open_list('users');

foreach($base_list as $val_vil=>$item_vil)
{
  //初期化
  $village = array(
             'vno'  =>$item_vil[0]
            ,'name' =>$item_vil[1]
            ,'date' =>""
            ,'nop'  =>(int)$item_vil[2]
            ,'rglid'=>""
            ,'days' =>$item_vil[4]
            ,'wtmid'=>""
  );

  //情報欄取得
  $html = $fetch->fetch_url($item_vil[6]);

  //レギュレーション挿入
  $rglid= trim($html->find('dl.mes_text_report dt',1)->plaintext);
  if(array_key_exists($rglid,$RGL_SP))
  {
    //特殊ルールがあるならレギュレーション扱いで挿入する
    $village['rglid'] = $RGL_SP[$rglid];
    echo "#".$village['vno'].' is '.$rglid.".Should check evil team.##";
  }
  else if(in_array($village['vno'],$SECRET))
  {
    //秘話村を挿入
    $village['rglid'] = $fetch::RGL_SECRET;
  }
  else if($item_vil[5] === '自由設定')
  {
    //自由設定でも特定の編成はレギュレーションを指定する
    $free = trim($html->find('dl.mes_text_report dd',3)->plaintext);
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
          $village['rglid'] = $fetch::RGL_S_1;
        }
        else
        {
          $village['rglid'] = $fetch::RGL_LEO;
        }
        break;
      case "深い霧の夜":
        $village['rglid'] = $RGL[$item_vil[5]];
        break;
      case "人狼審問 試験壱型":
        switch(true)
        {
          case ($village['nop']  >= 13):
            $village['rglid'] = $fetch::RGL_TES1;
            break;
          case ($village['nop'] <=12 && $village['nop'] >= 8):
            $village['rglid'] = $fetch::RGL_S_2;
            break;
          default:
            $village['rglid'] = $fetch::RGL_S_1;
            break;
        }
        break;
      case "人狼審問 試験弐型":
        switch(true)
        {
          case ($village['nop']  >= 10):
            $village['rglid'] = $fetch::RGL_TES2;
            break;
          case ($village['nop']  === 8 || $village['nop']  === 9):
            $village['rglid'] = $fetch::RGL_S_2;
            break;
          default:
            $village['rglid'] = $fetch::RGL_S_1;
            break;
        }
        break;
      case "人狼BBS C国":
        switch(true)
        {
          case ($village['nop']  >= 16):
            $village['rglid'] = $fetch::RGL_C;
            break;
          case ($village['nop']  === 15):
            $village['rglid'] = $fetch::RGL_S_C3;
            break;
          case ($village['nop'] <=14 && $village['nop'] >= 10):
            $village['rglid'] = $fetch::RGL_S_C2;
            break;
          case ($village['nop']  === 8 || $village['nop'] === 9):
            $village['rglid'] = $fetch::RGL_S_2;
            break;
          default:
            $village['rglid'] = $fetch::RGL_S_1;
            break;
        }
        break;
      case "人狼BBS F国":
        switch(true)
        {
          case ($village['nop']  >= 16):
            $village['rglid'] = $fetch::RGL_F;
            break;
          case ($village['nop']  === 15):
            $village['rglid'] = $fetch::RGL_S_3;
            break;
          case ($village['nop'] <=14 && $village['nop'] >= 8):
            $village['rglid'] = $fetch::RGL_S_2;
            break;
          default:
            $village['rglid'] = $fetch::RGL_S_1;
            break;
        }
        break;
      case "人狼BBS G国":
        switch(true)
        {
          case ($village['nop']  >= 16):
            $village['rglid'] = $fetch::RGL_G;
            break;
          case ($village['nop']  <= 15 && $village['nop'] >= 13):
            $village['rglid'] = $fetch::RGL_S_3;
            break;
          case ($village['nop'] <=12 && $village['nop'] >= 8):
            $village['rglid'] = $fetch::RGL_S_2;
            break;
          default:
            $village['rglid'] = $fetch::RGL_S_1;
            break;
        }
        break;
    }
  }

  //言い換え
  $rp= trim($html->find('dl.mes_text_report dt',0)->plaintext);
  //ガチ村のみ勝利陣営を挿入
  $wtmid = $html->find('p.multicolumn_left',1)->plaintext;
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
      if(in_array($village['vno'],$GACHI))
      {
        $village['wtmid'] = $TM_NORMAL[mb_substr($item_vil[3],0,2)];
        break;
      }
      else
      {
        echo '#'.$village['vno'].'. '.$village['name'].' is '.$wtmid.'#';
      }
    default:
      $village['wtmid'] = $fetch::TM_RP;
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
  $is_ID = $html->find('div.mes_maker',2)->find('p.multicolumn_left',4)->plaintext;

  //初日取得
  $html->clear();
  $url = preg_replace("/cmd=vinfo/","turn=0&row=10&mode=all&move=page&pageno=1",$item_vil[6]);
  $html = $fetch->fetch_url($url);

  //開始日(プロローグ第一声)
  $date = $html->find('p.mes_date',0)->plaintext;
  //ID公開村では取得方法を変える
  if($is_ID === "公開する")
  {
    $date = $html->find('p.mes_date',0)->plaintext;
    $date = mb_substr($date,mb_strpos($date,"2"),10);
  }
  else
  {
    $date = mb_substr($html->find('p.mes_date',0)->plaintext,5,10);
  }
  //MySQL用に日付の区切りを/から-に変換
  $village['date'] = preg_replace('/(\d{4})\/(\d{2})\/(\d{2})/','\1-\2-\3',$date);

  //エピローグ取得
  $html->clear();
  $url = preg_replace("/0&row=10/",$village['days']."&row=50",$url);
  $html = $fetch->fetch_url($url);
  $cast = $html->find('tbody tr.i_active');
  
  //村を書き込む
  $fetch->write_list('village',$village,$val_vil+1,count($cast));

  foreach($cast as $val_cast => $item_cast)
  {
    $users = array(
               'vid'    =>$val_vil + VID
              ,'persona'=>trim($item_cast->find("td",0)->plaintext)
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
      $users['dtid'] = $fetch::DES_ONLOOKER;
      $users['end'] = 1;
      $users['sklid'] = $fetch::SKL_ONLOOKER;
      $users['tmid'] = $fetch::TM_ONLOOKER;
      $users['life'] = 0;
      $users['rltid'] = $fetch::RSL_ONLOOKER;
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
        $users['rltid'] = $fetch::RSL_JOIN;
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
      if($users['tmid'] === $fetch::TM_EVIL && $is_evil === false)
      {
        $users['tmid'] = $fetch::TM_WOLF;
      }

      //生存係数挿入
      if($users['dtid'] === $fetch::DES_ALIVE)
      {
        $users['life'] = 1.00;
      }
      else
      {
        $users['life'] = round(($users['end']-1) / $village['days'],2);
      }

    }

    $fetch->write_list('users',$users,$val_cast+1);

    $item_cast->clear();
    unset($item_cast);
  }

  $html->clear();
  unset($html);
  echo $village['vno']. ' is end.'.PHP_EOL;
}
