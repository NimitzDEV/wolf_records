<?php

trait AR_Guta
{
  public $TM_AMBER = [
     "町人"=>Data::TM_VILLAGER
    ,"魔術"=>Data::TM_WOLF
    ,"琥珀"=>Data::TM_FAIRY
    ,"星に"=>Data::TM_LOVERS
    ,"星の"=>Data::TM_LOVERS //エピ表記
    ,"はぐ"=>Data::TM_LWOLF
    ,"吟遊"=>Data::TM_PIPER
    ,"賭博"=>Data::TM_EFB
    ,"不和"=>Data::TM_EVIL
    ,"据え"=>Data::TM_FISH
    ];
  public $SKILL = [
     "共有者"=>Data::SKL_MASON
    ,"狩人"=>Data::SKL_HUNTER
    ,"コウモリ人間"=>Data::SKL_LUNAPATH
    ,"Ｃ国狂人"=>Data::SKL_LUNAWHS
    ,"首なし騎士"=>Data::SKL_HEADLESS
    ,"ハムスター人間"=>Data::SKL_FAIRY
    ,"ピクシー"=>Data::SKL_PIXY
    ,"キューピッド"=>Data::SKL_QP
    ,"神話マニア"=>Data::SKL_PUPIL  //上書き分ここまで
    ,"村人"=>Data::SKL_VILLAGER
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
    ,"弟子"=>Data::SKL_PUPIL
    ,"盗賊"=>Data::SKL_THIEF
    ,"一匹狼"=>Data::SKL_LWOLF
    ,"笛吹き"=>Data::SKL_PIPER
    ,"鱗魚人"=>Data::SKL_FISH
    ,"遊び人"=>Data::SKL_PLAYBOY
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
    ,"盗賊"=>Data::SKL_THIEF
    ,"笛吹き"=>Data::SKL_PIPER
    ];
  public $SKL_AMBER = [//昏き宵闇の琥珀
     "町人"=>Data::SKL_VILLAGER
    ,"不在証明アリ"=>Data::SKL_STIGMA
    ,"刑事"=>Data::SKL_MASON
    ,"警部"=>Data::SKL_TELEPATH
    ,"真名探り"=>Data::SKL_SEER
    ,"見鬼"=>Data::SKL_SEERWIN
    ,"異能探知"=>Data::SKL_SEERAURA
    ,"魔術名鑑"=>Data::SKL_SAGE
    ,"護符職人"=>Data::SKL_HUNTER
    ,"好事家"=>Data::SKL_MEDIUM
    ,"琥珀研究科"=>Data::SKL_MEDIWIN
    ,"安楽椅子探偵"=>Data::SKL_PRIEST
    ,"ラヂオマニア"=>Data::SKL_NECRO
    ,"怯える者"=>Data::SKL_FOLLOWER
    ,"遺志を託す者"=>Data::SKL_AGITATOR
    ,"呪術マニア"=>Data::SKL_BOUNTY
    ,"魔力耐性者"=>Data::SKL_WEREDOG
    ,"無原罪の者"=>Data::SKL_PRINCE
    ,"魔力保持者"=>Data::SKL_LINEAGE
    ,"癒し手"=>Data::SKL_DOCTOR
    ,"呪われた者"=>Data::SKL_CURSED
    ,"琥珀病患者"=>Data::SKL_PROPHET
    ,"魔封じの者"=>Data::SKL_SICK
    ,"魔術師殺し"=>Data::SKL_ALCHEMIST
    ,"呪薬師"=>Data::SKL_WITCH
    ,"夢歩き"=>Data::SKL_GIRL
    ,"怨霊憑き"=>Data::SKL_SG
    ,"強き魔封じの者"=>Data::SKL_ELDER
    ,"真名隠し"=>Data::SKL_JAMMER
    ,"成り代わり"=>Data::SKL_SNATCH
    ,"念話術師"=>Data::SKL_LUNAPATH
    ,"悪徳琥珀商人"=>Data::SKL_LUNATIC
    ,"魔術師を目撃した者"=>Data::SKL_FANATIC
    ,"死人遣い"=>Data::SKL_MUPPETER
    ,"魔術師の愛弟子"=>Data::SKL_LUNAWHS
    ,"見習い魔術師"=>Data::SKL_HALFWOLF
    ,"故売屋"=>Data::SKL_LUNAPRI
    ,"見通す目"=>Data::SKL_LUNASAGE
    ,"欲深き魔術師"=>Data::SKL_HEADLESS
    ,"魔術師"=>Data::SKL_WOLF
    ,"秘術師"=>Data::SKL_WISEWOLF
    ,"呪術師"=>Data::SKL_CURSEWOLF
    ,"魔導師"=>Data::SKL_WHITEWOLF
    ,"付与魔術師"=>Data::SKL_CHILDWOLF
    ,"瀕死の魔術師"=>Data::SKL_DYINGWOLF
    ,"新人魔術師"=>Data::SKL_SILENT
    ,"琥珀妖精"=>Data::SKL_FAIRY
    ,"欺く琥珀妖精"=>Data::SKL_MIMIC
    ,"年老いた妖精"=>Data::SKL_SNOW
    ,"悪意ある妖精"=>Data::SKL_PIXY
    ,"賭博屋"=>Data::SKL_EFB
    ,"占星術師"=>Data::SKL_QP
    ,"懸想する者"=>Data::SKL_PASSION
    ,"物真似師"=>Data::SKL_PUPIL
    ,"生ける屍"=>Data::SKL_THIEF
    ,"はぐれ魔術師"=>Data::SKL_LWOLF
    ,"吟遊詩人"=>Data::SKL_PIPER
    ,"人魚"=>Data::SKL_FISH
    ,"占星術フリーク"=>Data::SKL_PLAYBOY
    ];
  public $DES_AMBER = [
     "生存者"=>Data::DES_ALIVE
    ,"突然死"=>Data::DES_RETIRED//不明
    ,"殺害"=>Data::DES_HANGED
    ,"琥珀化"=>Data::DES_EATEN
    ,"呪詛死"=>Data::DES_CURSED //そのまま
    ,"衰退死"=>Data::DES_DROOP  //不明
    ,"後追死"=>Data::DES_SUICIDE//不明
    ,"恐怖死"=>Data::DES_FEARED//不明
    ];
  public $WTM_AMBER = [
     "師を全て殺すことが出来た。"=>Data::TM_VILLAGER
    ,"師達は、残った人々を琥珀に"=>Data::TM_WOLF
    ,"師を殺し終わった後。仲間を"=>Data::TM_FAIRY
    ,"も、魔術師も、琥珀妖精でさ"=>Data::TM_LOVERS
    ];
}
