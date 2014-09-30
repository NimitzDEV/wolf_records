<?php

trait TRS_Ivory
{
  public $RGL_IVORY = [
     'M.hollow'      =>Data::RGL_MILL
    ,'Dead or Alive' =>Data::RGL_DEATH
    ,'Trouble Aliens'=>Data::RGL_TA
    ,'Mystery'       =>Data::RGL_MIST
    ];
  public $TM_MAFIA = [
     "市民"=>Data::TM_VILLAGER
    ,"マフ"=>Data::TM_WOLF
    ,"旅団"=>Data::TM_FAIRY
    ,"恋人"=>Data::TM_LOVERS
    ,"殺人"=>Data::TM_LWOLF
    ,"笛吹"=>Data::TM_PIPER
    ,"邪気"=>Data::TM_EFB
    ,"据え"=>Data::TM_FISH
    ];
  public $SKL_MAFIA = [//マフィア言い換え
     "市民"=>Data::SKL_VILLAGER
    ,"カリスマ"=>Data::SKL_STIGMA
    ,"結社員"=>Data::SKL_FM
    ,"双子"=>Data::SKL_FM_WIS
    ,"刑事"=>Data::SKL_SEER
    ,"警部"=>Data::SKL_SEER_TM
    ,"情報通"=>Data::SKL_SEER_AURA
    ,"警視"=>Data::SKL_SEER_ROLE
    ,"ボディガード"=>Data::SKL_GUARD
    ,"検死官"=>Data::SKL_MEDIUM
    ,"鑑識"=>Data::SKL_MEDI_TM
    ,"解剖医"=>Data::SKL_MEDI_ROLE
    ,"降霊術師"=>Data::SKL_MEDI_READ_G
    ,"追従者"=>Data::SKL_FOLLOWER
    ,"煽動者"=>Data::SKL_AGITATOR
    ,"賞金稼"=>Data::SKL_HUNTER
    ,"退役軍人"=>Data::SKL_DOG
    ,"スタア"=>Data::SKL_PRINCE
    ,"みなし子"=>Data::SKL_LINEAGE
    ,"医師"=>Data::SKL_DOCTOR
    ,"呪人"=>Data::SKL_CURSED
    ,"預言者"=>Data::SKL_DYING
    ,"病人"=>Data::SKL_SICK
    ,"錬金術師"=>Data::SKL_ALCHEMIST
    ,"魔女"=>Data::SKL_WITCH
    ,"少女"=>Data::SKL_READ_W
    ,"生贄"=>Data::SKL_SG
    ,"長老"=>Data::SKL_IRON_ONCE_SICK
    ,"協力者"=>Data::SKL_JAMMER
    ,"替え玉"=>Data::SKL_SNATCH
    ,"仲介者"=>Data::SKL_LUNA_WIS
    ,"密通者"=>Data::SKL_LUNATIC
    ,"鉄砲玉"=>Data::SKL_FANATIC
    ,"人形使い"=>Data::SKL_MUPPETER
    ,"内通者"=>Data::SKL_WHISPER
    ,"不良"=>Data::SKL_HALFWOLF
    ,"葬儀屋"=>Data::SKL_LUNA_MEDI
    ,"便利屋"=>Data::SKL_LUNA_SEER_ROLE
    ,"ドン"=>Data::SKL_HEADLESS
    ,"マフィア"=>Data::SKL_WOLF
    ,"ワイズガイ"=>Data::SKL_WISEWOLF
    ,"スイーパー"=>Data::SKL_WOLF_CURSED
    ,"マストロ"=>Data::SKL_WHITEWOLF
    ,"半グレ"=>Data::SKL_CHILDWOLF
    ,"老詐欺師"=>Data::SKL_WOLF_DYING
    ,"脱獄者"=>Data::SKL_WOLF_NOTALK
    ,"旅団員"=>Data::SKL_FAIRY
    ,"工作員"=>Data::SKL_FRY_MIMIC_W
    ,"死の商人"=>Data::SKL_FRY_DYING
    ,"美人局"=>Data::SKL_PIXY
    ,"悪魔"=>Data::SKL_EFB
    ,"天使"=>Data::SKL_QP
    ,"片想い"=>Data::SKL_PASSION
    ,"殺人鬼"=>Data::SKL_LONEWOLF
    ,"笛吹き"=>Data::SKL_PIPER
    ,"鱗魚人"=>Data::SKL_FISH
    ,"遊び人"=>Data::SKL_BITCH
    ];
  public $WTM_MAFIA = [
     "の人物が消え失せた時、其処"=>Data::TM_NONE //不明
    ,"の人狼を退治した……。人狼"=>Data::TM_VILLAGER //不明
    ,"は自らの過ちに気付いた。マ"=>Data::TM_WOLF
    ,"の人狼を退治した……。だが"=>Data::TM_FAIRY //不明
    ,"時、人狼は勝利を確信し、そ"=>Data::TM_FAIRY //不明
    ,"も、人狼も、妖精でさえも、"=>Data::TM_LOVERS //不明
    ,"達は、そして人狼達も自らの"=>Data::TM_LWOLF //不明
    ,"達は気付いてしまった。もう"=>Data::TM_PIPER //不明
    ,"はたった独りだけを選んだ。"=>Data::TM_EFB //不明
    ];
}
