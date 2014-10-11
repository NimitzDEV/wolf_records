<?php

trait TRS_Crescent
{
  public $SKL_BM = [
    //村
     "村人"=>Data::SKL_VILLAGER
    ,"聖痕者"=>Data::SKL_STIGMA
    ,"共有者"=>Data::SKL_FM
    ,"共鳴者"=>Data::SKL_FM_WIS
    ,"占い師"=>Data::SKL_SEER
    ,"信仰占師"=>Data::SKL_SEER_TM
    ,"気占師"=>Data::SKL_SEER_AURA
    ,"賢者"=>Data::SKL_SEER_ROLE
    ,"啓示者"=>Data::SKL_SEER_DELAY
    ,"狩人"=>Data::SKL_GUARD
    ,"霊能者"=>Data::SKL_MEDIUM
    ,"信仰霊能者"=>Data::SKL_MEDI_TM
    ,"導師"=>Data::SKL_MEDI_ROLE
    ,"降霊者"=>Data::SKL_MEDI_READ_G
    ,"追従者"=>Data::SKL_FOLLOWER
    ,"煽動者"=>Data::SKL_AGITATOR
    ,"賞金稼"=>Data::SKL_HUNTER
    ,"人犬"=>Data::SKL_DOG
    ,"王子様"=>Data::SKL_PRINCE
    ,"狼血族"=>Data::SKL_LINEAGE
    ,"医師"=>Data::SKL_DOCTOR
    ,"呪人"=>Data::SKL_CURSED
    ,"預言者"=>Data::SKL_DYING
    ,"病人"=>Data::SKL_SICK
    ,"錬金術師"=>Data::SKL_ALCHEMIST
    ,"魔女"=>Data::SKL_WITCH
    ,"少女"=>Data::SKL_READ_W
    ,"生贄"=>Data::SKL_SG
    ,"長老"=>Data::SKL_IRON_ONCE_SICK
    ,"退魔師"=>Data::SKL_COUNTER
    ,"野生児"=>Data::SKL_WILD
    ,"鉄人"=>Data::SKL_IRON
    ,"陽動者"=>Data::SKL_DEMO
    ,"守護霊"=>Data::SKL_GRD_G
    ,"霊感少年"=>Data::SKL_READ_G
    ,"暗殺者"=>Data::SKL_ASS_CRESCENT
    ,"運命読み"=>Data::SKL_SEER_BAND
    ,"猫又"=>Data::SKL_CAT
    ,"狐好き"=>Data::SKL_MISTAKE_FRY
    ,"妄想家"=>Data::SKL_MISTAKE_LOVE
    ,"転生者"=>Data::SKL_RANDOM_DEAD
    ,"狼気纏"=>Data::SKL_SUS_LINEAGE
    //裏切り
    ,"邪魔之民"=>Data::SKL_JAMMER
    ,"宿借之民"=>Data::SKL_SNATCH
    ,"念波之民"=>Data::SKL_LUNA_WIS
    ,"狂人"=>Data::SKL_LUNATIC
    ,"狂信者"=>Data::SKL_FANATIC
    ,"人形使い"=>Data::SKL_MUPPETER
    ,"囁き狂人"=>Data::SKL_WHISPER
    ,"半狼"=>Data::SKL_HALFWOLF
    ,"魔神官"=>Data::SKL_LUNA_MEDI
    ,"魔術師"=>Data::SKL_LUNA_SEER_ROLE
    ,"怨嗟狂人"=>Data::SKL_LUNA_SICK_EXE
    ,"騒霊"=>Data::SKL_LUNA_EXE_G
    ,"幻惑者"=>Data::SKL_DAZZLE
    ,"倒錯者"=>Data::SKL_PERVERT
    ,"狂学者"=>Data::SKL_MAD
    ,"南瓜提灯"=>Data::SKL_LUNA_WITCH
    ,"祈祷師"=>Data::SKL_LUNA_SEER_DELAY
    //人狼
    ,"凶狼"=>Data::SKL_HEADLESS
    ,"人狼"=>Data::SKL_WOLF
    ,"智狼"=>Data::SKL_WISEWOLF
    ,"呪狼"=>Data::SKL_WOLF_CURSED
    ,"白狼"=>Data::SKL_WHITEWOLF
    ,"仔狼"=>Data::SKL_CHILDWOLF
    ,"衰狼"=>Data::SKL_WOLF_DYING
    ,"黙狼"=>Data::SKL_WOLF_NOTALK
    ,"餓狼"=>Data::SKL_WOLF_HUNGRY
    ,"忘狼"=>Data::SKL_SLEEPER_BLACK
    ,"嗅狼"=>Data::SKL_WOLF_FAN
    ,"擬狼"=>Data::SKL_WWOLF_BLACK_G
    ,"蛮狼"=>Data::SKL_RECKLESS
    ,"古狼"=>Data::SKL_WOLF_ELDER
    ,"蠱狼"=>Data::SKL_WOLF_DELAY
    //妖魔
    ,"妖狐"=>Data::SKL_FAIRY
    ,"化狐"=>Data::SKL_FRY_SNATCH
    ,"念波妖狐"=>Data::SKL_FRY_WIS
    ,"野狐"=>Data::SKL_FRY_POISON
    ,"九尾"=>Data::SKL_FRY_READ_ALL_P
    ,"囁き妖狐"=>Data::SKL_FRY_MIMIC_W
    ,"響狐"=>Data::SKL_FRY_WIS
    ,"風花妖狐"=>Data::SKL_FRY_DYING
    ,"悪戯妖狐"=>Data::SKL_PIXY
    ,"謀狐"=>Data::SKL_FRY_GRD
    ,"祟狐"=>Data::SKL_FRY_CAT
    ,"管狐"=>Data::SKL_FRY_ADD_SICK
    ,"雪女"=>Data::SKL_FRY_SEAL
    ,"妖兎"=>Data::SKL_FRY_READ_G
    ,"月兎"=>Data::SKL_FRY_READ_G_DOG
    ,"妖花"=>Data::SKL_FRY_ADD_MRT
    //恋
    ,"恋愛天使"=>Data::SKL_QP
    ,"片想い"=>Data::SKL_PASSION
    ,"遊び人"=>Data::SKL_BITCH
    ,"求愛者"=>Data::SKL_QP_SELF
    ,"狂愛者"=>Data::SKL_MISTAKE_QP
    //邪気
    ,"邪気悪魔"=>Data::SKL_EFB
    ,"決闘者"=>Data::SKL_EFB_SELF
    ,"般若"=>Data::SKL_EFB_KILL_BAND
    //その他
    ,"一匹狼"=>Data::SKL_LONEWOLF
    ,"人虎"=>Data::SKL_LONE_TWICE
    ,"笛吹き"=>Data::SKL_PIPER
    ,"鱗魚人"=>Data::SKL_FISH
    ,"大魚人"=>Data::SKL_FISH_DOG
    ,"渋柿人"=>Data::SKL_TERU
    ,"恋未練"=>Data::SKL_YANDERE
    ,"盲信者"=>Data::SKL_MRT_WOLF
    ,"背徳者"=>Data::SKL_MRT_FRY
    ,"月下氷人"=>Data::SKL_MRT_LOVE
    ,"介在人"=>Data::SKL_MRT_EFB
    ];
  public $WTM_BM = [
     "村を照らす…"=>Data::TM_NONE
    ,"去ったのだ！"=>Data::TM_VILLAGER
    ,"しないのだ。"=>Data::TM_WOLF
    ,"無かった……"=>Data::TM_FAIRY
    ,"も尊いのだ。"=>Data::TM_LOVERS
    ,"らすために。"=>Data::TM_LWOLF
    ,"が響き渡る…"=>Data::TM_PIPER
    ,"っていく……"=>Data::TM_EFB
    ];
  public $SKL_HENTAI = [
     "常識人"=>Data::SKL_VILLAGER
    ,"戦隊英雄"=>Data::SKL_STIGMA
    ,"天然ちゃん"=>Data::SKL_FM
    ,"電波ちゃん"=>Data::SKL_FM_WIS
    ,"透視者"=>Data::SKL_SEER
    ,"おまわりさん"=>Data::SKL_GUARD
    ,"監視員"=>Data::SKL_MEDIUM
    ,"巡察師"=>Data::SKL_MEDI_READ_G
    ,"星人"=>Data::SKL_ALCHEMIST
    ,"少女"=>Data::SKL_READ_W
    ,"レイヤー"=>Data::SKL_SNATCH
    ,"ムッツリ"=>Data::SKL_LUNATIC
    ,"ドールオタ"=>Data::SKL_MUPPETER
    ,"潜伏変態"=>Data::SKL_WHISPER
    ,"完全変態"=>Data::SKL_HEADLESS
    ,"変態"=>Data::SKL_WOLF
    ,"変態紳士"=>Data::SKL_WISEWOLF
    ,"ド変態"=>Data::SKL_WOLF_CURSED
    ,"悪徳政治家"=>Data::SKL_FAIRY
    ,"妄想乙女"=>Data::SKL_QP
    ,"ドＭ"=>Data::SKL_FISH
    ];
  public $TM_HENTAI = [
     "常識"=>Data::TM_VILLAGER
    ,"変態"=>Data::TM_WOLF
    ,"異世"=>Data::TM_FAIRY
    ,"リア"=>Data::TM_LOVERS
    ,"孤高"=>Data::TM_LWOLF
    ,"踊る"=>Data::TM_PIPER
    ,"裏切"=>Data::TM_EVIL
    ,"据え"=>Data::TM_FISH
    ,"--"  =>Data::TM_NONE //2d突然死の片想い
    ,"勝利"=>Data::TM_NONE
    ];
  public $WTM_HENTAI = [
     "日の朝、住人全てが忽然と姿"=>Data::TM_NONE
    ,"の変態を更生させた……。変"=>Data::TM_VILLAGER
    ,"変態に抵抗できるほど常識人"=>Data::TM_WOLF
    ,"じ風が舞い、常識人達は凱歌"=>Data::TM_FAIRY
    ,"じ風が舞い、村中に変態達の"=>Data::TM_FAIRY
    ,"人も、変態も、異世界人でさ"=>Data::TM_LOVERS
    ,"人達は、そして変態達も自ら"=>Data::TM_LWOLF
    ,"阿呆に　見る阿呆。　同じ阿"=>Data::TM_PIPER
    ,"はたった独りだけを選んだ。"=>Data::TM_EFB //未指定
    ];
  public $SKL_CLOSED = [
     "市民"=>Data::SKL_VILLAGER
    ,"聖痕者"=>Data::SKL_STIGMA
    ,"結社員"=>Data::SKL_FM
    ,"共鳴者"=>Data::SKL_FM_WIS
    ,"占い師"=>Data::SKL_SEER
    ,"信仰占師"=>Data::SKL_SEER_TM
    ,"気占師"=>Data::SKL_SEER_AURA
    ,"賢者"=>Data::SKL_SEER_ROLE
    ,"守護者"=>Data::SKL_GUARD
    ,"霊能者"=>Data::SKL_MEDIUM
    ,"信仰霊能者"=>Data::SKL_MEDI_TM
    ,"導師"=>Data::SKL_MEDI_ROLE
    ,"降霊者"=>Data::SKL_MEDI_READ_G
    ,"追従者"=>Data::SKL_FOLLOWER
    ,"煽動者"=>Data::SKL_AGITATOR
    ,"賞金稼"=>Data::SKL_HUNTER
    ,"人犬"=>Data::SKL_DOG
    ,"狼血族"=>Data::SKL_LINEAGE
    ,"医師"=>Data::SKL_DOCTOR
    ,"呪人"=>Data::SKL_CURSED
    ,"預言者"=>Data::SKL_DYING
    ,"病人"=>Data::SKL_SICK
    ,"錬金術師"=>Data::SKL_ALCHEMIST
    ,"魔女"=>Data::SKL_WITCH
    ,"少女"=>Data::SKL_READ_W
    ,"生贄"=>Data::SKL_SG
    ,"長老"=>Data::SKL_IRON_ONCE_SICK
    ,"悪魔祓い"=>Data::SKL_COUNTER
    ,"狂人"=>Data::SKL_LUNATIC
    ,"狂信者"=>Data::SKL_FANATIC
    ,"囁き狂人"=>Data::SKL_WHISPER
    ,"半狼"=>Data::SKL_HALFWOLF
    ,"魔神官"=>Data::SKL_LUNA_MEDI
    ,"魔術師"=>Data::SKL_LUNA_SEER_ROLE
    ,"首無騎士"=>Data::SKL_HEADLESS
    ,"人狼"=>Data::SKL_WOLF
    ,"智狼"=>Data::SKL_WISEWOLF
    ,"呪狼"=>Data::SKL_WOLF_CURSED
    ,"白狼"=>Data::SKL_WHITEWOLF
    ,"仔狼"=>Data::SKL_CHILDWOLF
    ,"衰狼"=>Data::SKL_WOLF_DYING
    ,"黙狼"=>Data::SKL_WOLF_NOTALK
    ,"妖狐"=>Data::SKL_FAIRY
    ,"野狐"=>Data::SKL_FRY_POISON
    ,"九尾"=>Data::SKL_FRY_READ_ALL_P
    ,"擬狼妖狐"=>Data::SKL_FRY_MIMIC_W
    ,"風花妖狐"=>Data::SKL_FRY_DYING
    ,"悪戯妖狐"=>Data::SKL_PIXY
    ,"一匹狼"=>Data::SKL_LONEWOLF
    ];
  public $WTM_CLOSED = [
     "斐ないねえ。"=>Data::TM_NONE
    ,"は・・・ね。"=>Data::TM_VILLAGER
    ,"、お疲れ様。"=>Data::TM_WOLF
    ,"え。ふふふ。"=>Data::TM_FAIRY
    ,"に・・・ね。"=>Data::TM_FAIRY
    ,"も尊いのだ。"=>Data::TM_LOVERS //未指定
    ,"除いて、ね。"=>Data::TM_LWOLF
    ,"が響き渡る…"=>Data::TM_PIPER //未指定
    ,"っていく……"=>Data::TM_EFB //未指定
    ];
}
