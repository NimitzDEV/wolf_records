<?php

trait TRS_Giji_Old
{
  public $TEAM = [
     "村人"=>Data::TM_VILLAGER
    ,"市民"=>Data::TM_VILLAGER //三日月国
    ,"人狼"=>Data::TM_WOLF
    ,"妖精"=>Data::TM_FAIRY
    ,"恋人"=>Data::TM_LOVERS
    ,"誓い"=>Data::TM_LOVERS //象牙国
    ,"一匹"=>Data::TM_LWOLF
    ,"笛吹"=>Data::TM_PIPER
    ,"宣教"=>Data::TM_PIPER //象牙国
    ,"邪気"=>Data::TM_EFB
    ,"裏切"=>Data::TM_EVIL
    ,"据え"=>Data::TM_FISH
    ,"吸血"=>Data::TM_VAMPIRE //深海国
    ,"--"  =>Data::TM_NONE //2d突然死の片想い
    ,"勝利"=>Data::TM_NONE
    ];
  public $EVIL = [Data::RGL_E,Data::RGL_S_E,Data::RGL_ETC];
  public $SKILL = [
     "村人"=>Data::SKL_VILLAGER
    ,"聖痕者"=>Data::SKL_STIGMA
    ,"結社員"=>Data::SKL_MASON
    ,"共鳴者"=>Data::SKL_TELEPATH
    ,"占い師"=>Data::SKL_SEER
    ,"信仰占師"=>Data::SKL_SEERWIN
    ,"気占師"=>Data::SKL_SEERAURA
    ,"賢者"=>Data::SKL_SAGE
    ,"守護者"=>Data::SKL_HUNTER
    ,"霊能者"=>Data::SKL_MEDIUM
    ,"信仰霊能者"=>Data::SKL_MEDIWIN
    ,"導師"=>Data::SKL_PRIEST
    ,"降霊者"=>Data::SKL_NECRO
    ,"追従者"=>Data::SKL_FOLLOWER
    ,"煽動者"=>Data::SKL_AGITATOR
    ,"賞金稼"=>Data::SKL_BOUNTY
    ,"人犬"=>Data::SKL_WEREDOG
    ,"王子様"=>Data::SKL_PRINCE
    ,"狼血族"=>Data::SKL_LINEAGE
    ,"医師"=>Data::SKL_DOCTOR
    ,"呪人"=>Data::SKL_CURSED
    ,"預言者"=>Data::SKL_PROPHET
    ,"病人"=>Data::SKL_SICK
    ,"錬金術師"=>Data::SKL_ALCHEMIST
    ,"魔女"=>Data::SKL_WITCH
    ,"少女"=>Data::SKL_GIRL
    ,"生贄"=>Data::SKL_SG
    ,"長老"=>Data::SKL_ELDER
    ,"邪魔之民"=>Data::SKL_JAMMER
    ,"宿借之民"=>Data::SKL_SNATCH
    ,"念波之民"=>Data::SKL_LUNAPATH
    ,"狂人"=>Data::SKL_LUNATIC
    ,"狂信者"=>Data::SKL_FANATIC
    ,"人形使い"=>Data::SKL_MUPPETER
    ,"囁き狂人"=>Data::SKL_LUNAWHS
    ,"半狼"=>Data::SKL_HALFWOLF
    ,"魔神官"=>Data::SKL_LUNAPRI
    ,"魔術師"=>Data::SKL_LUNASAGE
    ,"首無騎士"=>Data::SKL_HEADLESS
    ,"人狼"=>Data::SKL_WOLF
    ,"智狼"=>Data::SKL_WISEWOLF
    ,"呪狼"=>Data::SKL_CURSEWOLF
    ,"白狼"=>Data::SKL_WHITEWOLF
    ,"仔狼"=>Data::SKL_CHILDWOLF
    ,"衰狼"=>Data::SKL_DYINGWOLF
    ,"黙狼"=>Data::SKL_SILENT
    ,"栗鼠妖精"=>Data::SKL_FAIRY
    ,"擬狼妖精"=>Data::SKL_MIMIC
    ,"風花妖精"=>Data::SKL_SNOW
    ,"悪戯妖精"=>Data::SKL_PIXY
    ,"邪気悪魔"=>Data::SKL_EFB
    ,"恋愛天使"=>Data::SKL_QP
    ,"片想い"=>Data::SKL_PASSION
    ,"一匹狼"=>Data::SKL_LWOLF
    ,"笛吹き"=>Data::SKL_PIPER
    ,"鱗魚人"=>Data::SKL_FISH
    ,"遊び人"=>Data::SKL_PLAYBOY
    //人狼物語用言い換え
    ,"共有者"=>Data::SKL_MASON
    ,"狩人"=>Data::SKL_HUNTER
    ,"コウモリ人間"=>Data::SKL_LUNAPATH
    ,"Ｃ国狂人"=>Data::SKL_LUNAWHS
    ,"首なし騎士"=>Data::SKL_HEADLESS
    ,"ハムスター人間"=>Data::SKL_FAIRY
    ,"ピクシー"=>Data::SKL_PIXY
    ,"キューピッド"=>Data::SKL_QP
    ];
  public $RGL_SP = [
     'ミラーズホロウ' =>Data::RGL_MILL
    ,'死んだら負け'   =>Data::RGL_DEATH
    ,'死んだら負け(ミラーズホロウ)'=>Data::RGL_MILL
    ,'Trouble☆Aliens'=>Data::RGL_TA
    ,'深い霧の夜'     =>Data::RGL_MIST
    ];
  //Orbital☆Starでは村人→乗客なので最初の二文字を削る 13文字
  public $WTM = [
     "の人物が消え失せた時、其処"=>Data::TM_NONE
    ,"の人狼を退治した……。人狼"=>Data::TM_VILLAGER
    ,"が去り、まぶしい光が降り注"=>Data::TM_VILLAGER
    ,"達は自らの過ちに気付いた。"=>Data::TM_WOLF
    ,"村を覆い、村人達は自らの過"=>Data::TM_WOLF
    ,"人狼に抵抗できるほど村人は"=>Data::TM_WOLF
    ,"の人狼を退治した……。だが"=>Data::TM_FAIRY
    ,"時、人狼は勝利を確信し、そ"=>Data::TM_FAIRY
    ,"じ風が舞い、村人達は凱歌を"=>Data::TM_FAIRY
    ,"じ風が舞い、村中に人狼達の"=>Data::TM_FAIRY
    ,"も、人狼も、妖精でさえも、"=>Data::TM_LOVERS
    ,"達は、そして人狼達も自らの"=>Data::TM_LWOLF
    ,"達は気付いてしまった。もう"=>Data::TM_PIPER
    ,"はたった独りだけを選んだ。"=>Data::TM_EFB
    ];
  public $RSL = [
     "勝利"=>Data::RSL_WIN
    ,"敗北"=>Data::RSL_LOSE
    ,""=>Data::RSL_INVALID //突然死
    ];
  public $DESTINY = [
     "生存者"=>Data::DES_ALIVE
    ,"突然死"=>Data::DES_RETIRED
    ,"処刑死"=>Data::DES_HANGED
    ,"襲撃死"=>Data::DES_EATEN
    ,"呪詛死"=>Data::DES_CURSED
    ,"衰退死"=>Data::DES_DROOP
    ,"後追死"=>Data::DES_SUICIDE
    ,"恐怖死"=>Data::DES_FEARED
    ];
  public $WTM_MILLERS = [
     "の人物が消え失せた時、其処"=>Data::TM_NONE
    ,"の人狼を退治した……。人狼"=>Data::TM_VILLAGER
    ,"人狼に抵抗できるほど村人は"=>Data::TM_WOLF
    ,"の人狼を退治した……。だが"=>Data::TM_FAIRY
    ,"時、人狼は勝利を確信し、そ"=>Data::TM_FAIRY
    ,"も、人狼も、妖精でさえも、"=>Data::TM_LOVERS
    ,"達は、そして人狼達も自らの"=>Data::TM_LWOLF
    ,"達は気付いてしまった。もう"=>Data::TM_PIPER
    ,"はたった独りだけを選んだ。"=>Data::TM_EFB
    ];
  public $SKL_MILLERS = [//ミラーズホロウ
     "村人"=>Data::SKL_VILLAGER
    ,"預言者"=>Data::SKL_SAGE
    ,"守護者"=>Data::SKL_HUNTER
    ,"狩人"=>Data::SKL_BOUNTY
    ,"魔女"=>Data::SKL_WITCH
    ,"少女"=>Data::SKL_GIRL
    ,"スケープゴート"=>Data::SKL_SG
    ,"長老"=>Data::SKL_ELDER
    ,"人狼"=>Data::SKL_WISEWOLF
    ,"キューピッド"=>Data::SKL_QP
    ];
}
