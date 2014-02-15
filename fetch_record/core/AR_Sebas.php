<?php

trait AR_Sebas
{
  protected $RP_LIST = [
     '人狼物語'=>'SOW'
    ,'適当系'=>'FOOL'
    ,'人狼審問'=>'JUNA'
    ,'鬼ごっこ'=>'ONI'
    ,'無茶振り人狼'=>'JUNA'
    ,'ガチっているフリ'=>'RAMDOM'
    ,'企画村用'=>'SP'
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
  protected $WTM_SOW = [
     '人狼を退治したのだ！'=>Data::TM_VILLAGER
    ,'る日々は去ったのだ！'=>Data::TM_VILLAGER
    ,'を立ち去っていった。'=>Data::TM_WOLF
    ,'の村を去っていった。'=>Data::TM_WOLF
    ,'生き残っていた……。'=>Data::TM_FAIRY
  ];
  protected $WTM_ONI = [
     'した……！ぜぇはぁ。'=>Data::TM_VILLAGER
    ,'テープを切りました。'=>Data::TM_WOLF
    ,'時代が到来しました。'=>Data::TM_FAIRY
  ];
  protected $WTM_SP = [
     'でした。(村人勝利)'=>Data::TM_VILLAGER
  ];
  protected $SKILL = [
     "村人"=>[Data::SKL_VILLAGER,Data::TM_VILLAGER]
    ,"人狼"=>[Data::SKL_WOLF,Data::TM_WOLF]
    ,"鬼（人狼）"=>[Data::SKL_WOLF,Data::TM_WOLF]
    ,"占い師"=>[Data::SKL_SEER,Data::TM_VILLAGER]
    ,"霊能者"=>[Data::SKL_MEDIUM,Data::TM_VILLAGER]
    ,"狂人"=>[Data::SKL_LUNATIC,Data::TM_WOLF]
    ,"狩人"=>[Data::SKL_HUNTER,Data::TM_VILLAGER]
    ,"守護者"=>[Data::SKL_HUNTER,Data::TM_VILLAGER]
    ,"共有者"=>[Data::SKL_MASON,Data::TM_VILLAGER]
    ,"結社員"=>[Data::SKL_MASON,Data::TM_VILLAGER]
    ,"妖魔"=>[Data::SKL_FAIRY,Data::TM_FAIRY]
    ,"ハムスター人間"=>[Data::SKL_FAIRY,Data::TM_FAIRY]
    ,"狐"=>[Data::SKL_FAIRY,Data::TM_FAIRY]
    ,"囁き狂人"=>[Data::SKL_LUNAWHS,Data::TM_WOLF]
    ,"Ｃ国狂人"=>[Data::SKL_LUNAWHS,Data::TM_WOLF]
    ,"聖痕者"=>[Data::SKL_STIGMA,Data::TM_VILLAGER]
    ,"狂信者"=>[Data::SKL_FANATIC,Data::TM_WOLF]
    ,"共鳴者"=>[Data::SKL_TELEPATH,Data::TM_VILLAGER]
    ,"天魔"=>[Data::SKL_BAT,Data::TM_FAIRY]
    ,"コウモリ人間"=>[Data::SKL_BAT,Data::TM_FAIRY]
    ,"天狗"=>[Data::SKL_BAT,Data::TM_FAIRY]
    ,"呪狼"=>[Data::SKL_CURSEWOLF,Data::TM_WOLF]
    ,"呪鬼"=>[Data::SKL_CURSEWOLF,Data::TM_WOLF]
    ,"智狼"=>[Data::SKL_WISEWOLF,Data::TM_WOLF]
    ,"智鬼"=>[Data::SKL_WISEWOLF,Data::TM_WOLF]
    ,"悪戯妖精"=>[Data::SKL_PIXY,Data::TM_FAIRY]
    ,"ピクシー"=>[Data::SKL_PIXY,Data::TM_FAIRY]
    ,"悪戯っ子"=>[Data::SKL_PIXY,Data::TM_FAIRY]
    ];
  protected $SKL_FOOL = [
     "ただの人"=>[Data::SKL_VILLAGER,Data::TM_VILLAGER]
    ,"おおかみ"=>[Data::SKL_WOLF,Data::TM_WOLF]
    ,"エスパー"=>[Data::SKL_SEER,Data::TM_VILLAGER]
    ,"イタコ"=>[Data::SKL_MEDIUM,Data::TM_VILLAGER]
    ,"人狼スキー"=>[Data::SKL_LUNATIC,Data::TM_WOLF]
    ,"ストーカー"=>[Data::SKL_HUNTER,Data::TM_VILLAGER]
    ,"夫婦"=>[Data::SKL_MASON,Data::TM_VILLAGER]
    ,"ハム"=>[Data::SKL_FAIRY,Data::TM_FAIRY]
    ,"人狼教神官"=>[Data::SKL_LUNAWHS,Data::TM_WOLF]
    ,"痣もち"=>[Data::SKL_STIGMA,Data::TM_VILLAGER]
    ,"人狼教信者"=>[Data::SKL_FANATIC,Data::TM_WOLF]
    ,"おしどり夫婦"=>[Data::SKL_TELEPATH,Data::TM_VILLAGER]
    ,"コウモリ"=>[Data::SKL_BAT,Data::TM_FAIRY]
    ,"逆恨み狼"=>[Data::SKL_CURSEWOLF,Data::TM_WOLF]
    ,"グルメ"=>[Data::SKL_WISEWOLF,Data::TM_WOLF]
    ,"イタズラっ子"=>[Data::SKL_PIXY,Data::TM_FAIRY]
    ];
  protected $DESTINY = [
     '生き'=>Data::DES_ALIVE
    ,"突然"=>Data::DES_RETIRED
    ,"処刑"=>Data::DES_HANGED
    ,"襲撃"=>Data::DES_EATEN
    ,"呪殺"=>Data::DES_CURSED
    ,"後追"=>Data::DES_SUICIDE
    ];
}
