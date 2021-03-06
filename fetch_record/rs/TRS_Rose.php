<?php

trait TRS_Rose
{
  protected $RP_LIST = [
     '薔薇の下'=>'ROSE'
    ,'人狼物語'=>'SOW'
    ,'適当系'=>'FOOL'
    ,'人狼審問'=>'JUNA'
    ,'人狼BBS'=>'WBBS'
    ,'ルーガルー'=>'LOUP'
    ,'蛇村'=>'JR'
    ,'蛇同窓会'=>'JR'
  ];
  protected $WTM_ROSE= [
     '人狼を退治したのだ！'=>Data::TM_VILLAGER
    ,'屍ばかりであった…。'=>Data::TM_WOLF
    ,'気づいていなかった。'=>Data::TM_FAIRY
    ,'在に気づくまでは…。'=>Data::TM_FAIRY
    ,'紅く染まる孤影のみ。'=>Data::TM_EFB
    ,'しっかりと繋いで…。'=>Data::TM_LOVERS
    ,'する馘は存在しない。'=>Data::TM_SLAVE
  ];
  protected $WTM_SOW= [
     '人狼を退治したのだ！'=>Data::TM_VILLAGER
    ,'を立ち去っていった。'=>Data::TM_WOLF
    ,'いていなかった……。'=>Data::TM_FAIRY
    ,'ただ独りを残して…。'=>Data::TM_EFB
    ,'存在しなかった……。'=>Data::TM_LOVERS
    ,'が、辺りに響き渡る。'=>Data::TM_SLAVE
  ];
  protected $WTM_FOOL = [
     'が勝ちやがりました。'=>Data::TM_VILLAGER
    ,'ようだ。おめでとう。'=>Data::TM_WOLF
    ,'けている（らしい）。'=>Data::TM_FAIRY
    ,'んだよ！（意味不明）'=>Data::TM_FAIRY
    ,'ン勝負、勝ったでえ！'=>Data::TM_EFB
    ,'生、リア充爆発しろ！'=>Data::TM_LOVERS
    ,'ッハアアアアア！！！'=>Data::TM_SLAVE
  ];
  protected $WTM_JUNA = [
     '人狼に勝利したのだ！'=>Data::TM_VILLAGER
    ,'めて去って行った……'=>Data::TM_WOLF
    ,'くことはなかった……'=>Data::TM_FAIRY
    ,'すすべがなかった……'=>Data::TM_FAIRY
    ,'紅く染まる孤影のみ。'=>Data::TM_EFB
    ,'しっかりと繋いで…。'=>Data::TM_LOVERS
    ,'する馘は存在しない。'=>Data::TM_SLAVE
  ];
  protected $WTM_WBBS = [
     'る日々は去ったのだ！'=>Data::TM_VILLAGER
    ,'の村を去っていった。'=>Data::TM_WOLF
    ,'生き残っていた……。'=>Data::TM_FAIRY
    ,'ただ独りを残して…。'=>Data::TM_EFB
    ,'存在しなかった……。'=>Data::TM_LOVERS
    ,'が、辺りに響き渡る。'=>Data::TM_SLAVE
  ];
  protected $WTM_LOUP= [
     '人狼を退治したのだ！'=>Data::TM_VILLAGER
    ,'屍ばかりであった…。'=>Data::TM_WOLF
    ,'気づいていなかった。'=>Data::TM_FAIRY
    ,'在に気づくまでは…。'=>Data::TM_FAIRY
    ,'紅く染まる孤影のみ。'=>Data::TM_EFB
    ,'しっかりと繋いで…。'=>Data::TM_LOVERS
    ,'する馘は存在しない。'=>Data::TM_SLAVE
  ];

  protected $SKILL = [
     "村人"=>Data::SKL_VILLAGER
    ,"人狼"=>Data::SKL_WOLF
    ,"占い師"=>Data::SKL_SEER
    ,"霊能者"=>Data::SKL_MEDIUM
    ,"狂人"=>Data::SKL_LUNATIC
    ,"狩人"=>Data::SKL_GUARD
    ,"守護者"=>Data::SKL_GUARD
    ,"共有者"=>Data::SKL_FM
    ,"結社員"=>Data::SKL_FM
    ,"妖魔"=>Data::SKL_FAIRY
    ,"ハムスター人間"=>Data::SKL_FAIRY
    ,"囁き狂人"=>Data::SKL_WHISPER
    ,"Ｃ国狂人"=>Data::SKL_WHISPER
    ,"聖痕者"=>Data::SKL_STIGMA
    ,"狂信者"=>Data::SKL_FANATIC
    ,"共鳴者"=>Data::SKL_FM_WIS
    ,"天魔"=>Data::SKL_FRY_WIS
    ,"コウモリ人間"=>Data::SKL_FRY_WIS
    ,"呪狼"=>Data::SKL_WOLF_CURSED
    ,"智狼"=>Data::SKL_WISEWOLF
    ,"悪戯妖精"=>Data::SKL_PIXY
    ,"ピクシー"=>Data::SKL_PIXY
    ,"銀狼"=>Data::SKL_WOLF_NOTALK
    ,"夜兎"=>Data::SKL_FRY_READ_W
    ,"賢者"=>Data::SKL_SEER_ROLE
    ,"霊媒師"=>Data::SKL_MEDI_ROLE
    ,"白狼"=>Data::SKL_WHITEWOLF
    ,"守護獣"=>Data::SKL_GRD_BLACK
    ,"首無騎士"=>Data::SKL_HEADLESS
    ,"狂神官"=>Data::SKL_LUNA_SEER
    ,"魔術師"=>Data::SKL_LUNA_SEER_ROLE
    ,"恋天使"=>Data::SKL_QP
    ,"キューピッド"=>Data::SKL_QP
    ,"洗礼者"=>Data::SKL_BAPTIST
    ,"殉教者"=>Data::SKL_BAPTIST
    ,"狙撃手"=>Data::SKL_GRD_ALCH
    ,"瘴狼"=>Data::SKL_WOLF_SNATCH
    ,"冒涜者"=>Data::SKL_LUNA_TEMPT
    ,"背信者"=>Data::SKL_LUNA_TEMPTED
    ,"夢魔"=>Data::SKL_FRY_SEER_BAND
    ,"死神"=>Data::SKL_EFB
    ,"審判者"=>Data::SKL_SEER_TM_FUZZ
    ,"魂魄師"=>Data::SKL_SEER_ID
    ,"呪魂者"=>Data::SKL_LUNA_SEER_ID
    ,"交信者"=>Data::SKL_CONTACT
    ,"誘惑者"=>Data::SKL_LUNA_WIS_TEMPT
    ,"睡狼"=>Data::SKL_SLEEPER
    ,"呪人"=>Data::SKL_CURSED
    ,"人犬"=>Data::SKL_DOG
    ,"貴族"=>Data::SKL_NOBLE
    ,"奴隷"=>Data::SKL_SLAVE
    ,"落胤"=>Data::SKL_PRINCE
    ,"胡蝶"=>Data::SKL_LUNA_WIS_LINEAGE
    ,"従狼"=>Data::SKL_WOLF_DEPEND
    ];
  protected $SKL_FOOL = [
     "ただの人"=>Data::SKL_VILLAGER
    ,"おおかみ"=>Data::SKL_WOLF
    ,"エスパー"=>Data::SKL_SEER
    ,"イタコ"=>Data::SKL_MEDIUM
    ,"人狼スキー"=>Data::SKL_LUNATIC
    ,"ストーカー"=>Data::SKL_GUARD
    ,"夫婦"=>Data::SKL_FM
    ,"ハム"=>Data::SKL_FAIRY
    ,"人狼教神官"=>Data::SKL_WHISPER
    ,"痣もち"=>Data::SKL_STIGMA
    ,"人狼教信者"=>Data::SKL_FANATIC
    ,"おしどり夫婦"=>Data::SKL_FM_WIS
    ,"コウモリ"=>Data::SKL_FRY_WIS
    ,"逆恨み狼"=>Data::SKL_WOLF_CURSED
    ,"グルメ"=>Data::SKL_WISEWOLF
    ,"イタズラっ子"=>Data::SKL_PIXY
    ,"ペーペー狼"=>Data::SKL_WOLF_NOTALK
    ,"盗聴屋"=>Data::SKL_FRY_READ_W
    ,"超エスパー人"=>Data::SKL_SEER_ROLE
    ,"ユタ"=>Data::SKL_MEDI_ROLE
    ,"ビッグな狼"=>Data::SKL_WHITEWOLF
    ,"コマ犬"=>Data::SKL_GRD_BLACK
    ,"くいしんぼう狼"=>Data::SKL_HEADLESS
    ,"もふもふマニア"=>Data::SKL_LUNA_SEER
    ,"もふ一級鑑定士"=>Data::SKL_LUNA_SEER_ROLE
    ,"ニヨ天使"=>Data::SKL_QP
    ,"メガザラー"=>Data::SKL_BAPTIST
    ,"もえかす"=>Data::SKL_BAPTIST
    ,"ヤンデレ"=>Data::SKL_GRD_ALCH
    ,"調教師"=>Data::SKL_WOLF_SNATCH
    ,"凄腕営業"=>Data::SKL_LUNA_TEMPT
    ,"魔法少女"=>Data::SKL_LUNA_TEMPTED
    ,"パパラッチ"=>Data::SKL_FRY_SEER_BAND
    ,"番長"=>Data::SKL_EFB
    ,"においフェチ"=>Data::SKL_SEER_TM_FUZZ
    ,"よいのぞき屋"=>Data::SKL_SEER_ID
    ,"悪いのぞき屋"=>Data::SKL_LUNA_SEER_ID
    ,"チャネラー"=>Data::SKL_CONTACT
    ,"フジコちゃん"=>Data::SKL_LUNA_WIS_TEMPT
    ,"おねぼうさん"=>Data::SKL_SLEEPER
    ,"逆恨み野郎"=>Data::SKL_CURSED
    ,"死にぞこない"=>Data::SKL_DOG
    ,"おえらいさん"=>Data::SKL_NOBLE
    ,"ゲボク根性"=>Data::SKL_SLAVE
    ,"ボンボン"=>Data::SKL_PRINCE
    ,"夢見がち"=>Data::SKL_LUNA_WIS_LINEAGE
    ,"従狼"=>Data::SKL_WOLF_DEPEND
    ];
  protected $SKL_JR = [
     "白薔薇兵士"=>Data::SKL_VILLAGER
    ,"ズニア"=>Data::SKL_VILLAGER
    ,"情報屋"=>Data::SKL_SEER
    ,"サイゾー"=>Data::SKL_SEER
    ,"看護士"=>Data::SKL_MEDIUM
    ,"熱血の騎士"=>Data::SKL_GUARD
    ,"事務員"=>Data::SKL_WHISPER
    ,"痣の保持者"=>Data::SKL_STIGMA
    ,"粘着テロリスト"=>Data::SKL_WOLF_CURSED
    ,"ニヨリスト"=>Data::SKL_PIXY
    ,"孤独を愛するテロリスト"=>Data::SKL_WOLF_NOTALK
    ,"銀の蛇受賞予定者"=>Data::SKL_WOLF_NOTALK
    ,"盗聴名人"=>Data::SKL_FRY_READ_W
    ,"情報通"=>Data::SKL_SEER_ROLE
    ,"軍医"=>Data::SKL_MEDI_ROLE
    ,"変装名人"=>Data::SKL_WHITEWOLF
    ,"博愛の騎士"=>Data::SKL_GRD_BLACK
    ,"銀のヒモ受賞予定者"=>Data::SKL_GRD_BLACK
    ];
  protected $TEAM = [
     "村人"=>Data::TM_VILLAGER
    ,"人狼"=>Data::TM_WOLF
    ,"妖魔"=>Data::TM_FAIRY
    ,"恋人"=>Data::TM_LOVERS
    ,"死神"=>Data::TM_EFB
    ,"奴隷"=>Data::TM_SLAVE
    ,"観戦"=>Data::TM_ONLOOKER
    ];
  protected $DESTINY = [
     "生存"=>Data::DES_ALIVE
    ,"突然死"=>Data::DES_RETIRED
    ,"処刑死"=>Data::DES_HANGED
    ,"襲撃死"=>Data::DES_EATEN
    ,"呪殺"=>Data::DES_CURSED
    ,"後追死"=>Data::DES_SUICIDE
    ,"殉教"=>Data::DES_MARTYR
    ];

  protected $DT_NORMAL = [
     '処刑された。'=>['.+(\(ランダム投票\)|投票した。|投票した)(.+) は村人達?の手により処刑された。',Data::DES_HANGED]
    ,'刑された……'=>['.+(\(ランダム投票\)|投票した。|投票した) ?(.+) は村人の手により処刑された……',Data::DES_HANGED]
    ,'突然死した。'=>['^( ?)(.+) は、突然死した。',Data::DES_RETIRED]
    ,'病に倒れた。'=>['^( ?)(.+) は、病に倒れた。',Data::DES_RETIRED]
    ,'発見された。'=>['(.+)朝、 ?(.+) が無残.+',Data::DES_EATEN]
    ,'後を追った。'=>['^( ?)(.+) は(絆に引きずられるように|哀しみに暮れて) .+ の後を追った。',Data::DES_SUICIDE]
  ];
  protected $DT_FOOL = [
     'ち殺された。'=>['.+投票(した（らしい）。|してみた。) ?(.+) は村人達によってたかってぶち殺された。',Data::DES_HANGED]
    ,'ぶっ倒れた。'=>['^( ?)(.+) は、ぶっ倒れた。',Data::DES_RETIRED]
    ,'ったみたい。'=>['',Data::DES_EATEN]
    ,'えを食った。'=>['^( ?)(.+) は .+ の巻き添えを食った。',Data::DES_SUICIDE]
    ,'したようだ。'=>['^()(.+) は .+ との赤い糸の切断に失敗したようだ。',Data::DES_SUICIDE]
  ];
  protected $DT_JR = [
     '搬送された。'=>['.+(\(ランダム投票\)|投票した。)(.+) は、前線を離脱し.+',Data::DES_HANGED]
    ,'おめでとう！'=>['.+(\(ランダム投票\)|投票した。)(.+) は、最優秀男優賞.+',Data::DES_HANGED]
    ,'発見された。'=>['.+朝、(泥酔した |)(.+) が(瀕死|、控え室).+',Data::DES_EATEN]
    ,'後を追った。'=>['^( ?)(.+) は(絆に引きずられるように|哀しみに暮れて) .+ の後を追った。',Data::DES_SUICIDE]
  ];
}
