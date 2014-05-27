<?php

trait TRS_Chitose
{
  private $RP_Chitose = [
     'ようちえん'=>'KIDS'
    ,'メトロポリスβ'=>'METRO'
  ];
  protected $WTM_KIDS= [
     '。めでたしめでたし。'=>Data::TM_VILLAGER
    ,'て去って行きました。'=>Data::TM_WOLF
    ,'が残っていたのです。'=>Data::TM_FAIRY
  ];
  protected $WTM_METRO = [
     '人狼に勝利したのだ！'=>Data::TM_VILLAGER
    ,'めて去って行った……'=>Data::TM_WOLF
    ,'くことはなかった……'=>Data::TM_FAIRY
    ,'すすべがなかった……'=>Data::TM_FAIRY
  ];
  protected $DT_KIDS = [//処刑と突然死は区別するために7文字取得
     'り眠りについた。'=>['.+(\(ランダム投票\)|投票した。)(.+) は子ども達の手により眠りについた。',Data::DES_HANGED]
    ,'然眠りについた。'=>['^( ?)(.+) は、突然眠りについた。',Data::DES_RETIRED]
    ,'発見された。'=>['(.+)朝、 ?(.+) が、むざん.+',Data::DES_EATEN]
    ,'後を追った。'=>['^( ?)(.+) は(絆に引きずられるように|哀しみに暮れて) .+ の後を追った。',Data::DES_SUICIDE]
  ];
}
