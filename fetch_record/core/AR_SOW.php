<?php

trait AR_SOW
{
  protected $RGL_FREE = [
     '（村人: 8人 人狼: 3人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 聖痕者: 1人 ）'=>Data::RGL_G_ST
    ,'（村人: 9人 人狼: 3人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 ）'=>Data::RGL_G
    ,'（村人: 7人 人狼: 3人 占い師: 1人 霊能者: 1人 狩人: 1人 Ｃ国狂人: 1人 共鳴者: 2人 ）'=>Data::RGL_C
    ,'（村人: 6人 人狼: 3人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 ハムスター人間: 1人 共鳴者: 2人 ）'=>Data::RGL_E
    ,'（村人: 5人 人狼: 2人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 ハムスター人間: 1人 ）'=>Data::RGL_S_E
    ,'（村人: 7人 人狼: 2人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 ハムスター人間: 1人 ）'=>Data::RGL_S_E
    ,'（村人: 7人 人狼: 3人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 ）'=>Data::RGL_S_3
    ,'（村人: 5人 人狼: 2人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 ）'=>Data::RGL_S_2
    ,'（村人: 8人 人狼: 2人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 ）'=>Data::RGL_S_2
    ,'（村人: 6人 人狼: 2人 占い師: 1人 霊能者: 1人 狩人: 1人 Ｃ国狂人: 1人 ）'=>Data::RGL_S_C2
    ,'（村人: 4人 人狼: 2人 占い師: 1人 霊能者: 1人 狩人: 1人 Ｃ国狂人: 1人 ）'=>Data::RGL_S_C2
    ,'（村人: 5人 人狼: 2人 占い師: 1人 霊能者: 1人 狩人: 1人 狂信者: 1人 ）'=>Data::RGL_TES2
    //後で消す↓
    ,'（村人: 3人 人狼: 2人 占い師: 1人 ）'=>Data::RGL_S_2
    ,'（村人: 1人 人狼: 2人 霊能者: 8人 ）'=>Data::RGL_ROLLER
    ,'（村人: 5人 人狼: 3人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 聖痕者: 1人 ）'=>Data::RGL_S_3
    ,'（村人: 5人 人狼: 3人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 ）'=>Data::RGL_S_3
    ,'（村人: 5人 人狼: 3人 占い師: 1人 霊能者: 1人 ハムスター人間: 1人 聖痕者: 1人 ）'=>Data::RGL_S_E
    ,'（村人: 4人 人狼: 2人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 ）'=>Data::RGL_S_2
    ,'（村人: 5人 人狼: 1人 ）'=>Data::RGL_HERO
    ];
  protected $RP_LIST = [
     '人狼物語'=>'SOW'
    ,'適当系'=>'FOOL'
    ,'人狼審問'=>'JUNA'
    ,'人狼BBS'=>'WBBS'
  ];
  protected $WTM_SOW= [
     '人狼を退治したのだ！'=>Data::TM_VILLAGER
    ,'を立ち去っていった。'=>Data::TM_WOLF
    ,'いていなかった……。'=>Data::TM_FAIRY
  ];
  protected $WTM_FOOL = [
     'が勝ちやがりました。'=>Data::TM_VILLAGER
    ,'ようだ。おめでとう。'=>Data::TM_WOLF
    ,'けている（らしい）。'=>Data::TM_FAIRY
    ,'んだよ！（意味不明）'=>Data::TM_FAIRY
  ];
  protected $WTM_JUNA = [
     '人狼に勝利したのだ！'=>Data::TM_VILLAGER
    ,'めて去って行った……'=>Data::TM_WOLF
    ,'くことはなかった……'=>Data::TM_FAIRY
    ,'すすべがなかった……'=>Data::TM_FAIRY
  ];
  protected $WTM_WBBS = [
     'る日々は去ったのだ！'=>Data::TM_VILLAGER
    ,'の村を去っていった。'=>Data::TM_WOLF
    ,'生き残っていた……。'=>Data::TM_FAIRY
  ];
  protected $SKL_SOW = [
     '村人','占い師','霊能者','狩人','共有者','共鳴者','聖痕者'
     ,'人狼','呪狼','智狼','狂人','狂信者','Ｃ国狂人'
     ,'ハムスター人間','コウモリ人間','ピクシー'
  ];
  protected $SKL_FOOL = [
     'ただの人','エスパー','イタコ','ストーカー','夫婦','おしどり夫婦','痣もち'
    ,'おおかみ','逆恨み狼','グルメ','人狼スキー','人狼教信者','人狼教神官'
    ,'ハム','コウモリ','イタズラっ子'
  ];
  protected $SKL_JUNA = [
      '村人','占い師','霊能者','守護者','結社員','共鳴者','聖痕者'
     ,'人狼','呪狼','智狼','狂人','狂信者','囁き狂人'
     ,'妖魔','天魔','悪戯妖精'
  ];
  protected $SKILL = [
     [Data::SKL_VILLAGER,Data::TM_VILLAGER]
    ,[Data::SKL_SEER,Data::TM_VILLAGER]
    ,[Data::SKL_MEDIUM,Data::TM_VILLAGER]
    ,[Data::SKL_HUNTER,Data::TM_VILLAGER]
    ,[Data::SKL_MASON,Data::TM_VILLAGER]
    ,[Data::SKL_TELEPATH,Data::TM_VILLAGER]
    ,[Data::SKL_STIGMA,Data::TM_VILLAGER]
    ,[Data::SKL_WOLF,Data::TM_WOLF]
    ,[Data::SKL_CURSEWOLF,Data::TM_WOLF]
    ,[Data::SKL_WISEWOLF,Data::TM_WOLF]
    ,[Data::SKL_LUNATIC,Data::TM_WOLF]
    ,[Data::SKL_FANATIC,Data::TM_WOLF]
    ,[Data::SKL_LUNAWHS,Data::TM_WOLF]
    ,[Data::SKL_FAIRY,Data::TM_FAIRY]
    ,[Data::SKL_BAT,Data::TM_FAIRY]
    ,[Data::SKL_PIXY,Data::TM_FAIRY]
  ];
  protected $DT_SOW = [
     '処刑された。'=>['.+投票した。 ?(.+) は村人達の手により処刑された。',Data::DES_HANGED]
    ,'突然死した。'=>['^ ?(.+) は、突然死した。',Data::DES_RETIRED]
    ,'発見された。'=>['.+朝、 ?(.+) が無残.+',Data::DES_EATEN]
    ,'後を追った。'=>['^ ?(.+) は絆に引きずられるように .+ の後を追った。',Data::DES_SUICIDE]
  ];
  protected $DT_JUNA = [
     '刑された……'=>['.+投票した ?(.+) は村人の手により処刑された……',Data::DES_HANGED]
    ,'突然死した。'=>['^ ?(.+) は、突然死した。',Data::DES_RETIRED]
    ,'発見された。'=>['.+朝、 ?(.+) が無残.+',Data::DES_EATEN]
    ,'後を追った。'=>['^ ?(.+) は絆に引きずられるように .+ の後を追った。',Data::DES_SUICIDE]
  ];
  protected $DT_FOOL = [
     'ち殺された。'=>['.+投票した（らしい）。 ?(.+) は村人達によってたかってぶち殺された。',Data::DES_HANGED]
    ,'ぶっ倒れた。'=>['^ ?(.+) は、ぶっ倒れた。',Data::DES_RETIRED]
    ,'ったみたい。'=>['',Data::DES_EATEN]
    ,'えを食った。'=>['^ ?(.+) は .+ の巻き添えを食った。',Data::DES_SUICIDE]
  ];
}
