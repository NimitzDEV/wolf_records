<?php

class Moon extends SOW
{
  use TRS_SOW;
  protected $RP_SP = [
     '月狼'=>'MOON'
    ,'人狼署'=>'POLICE'
  ];
  protected $WTM_POLICE= [
     '平和は守られたのだ！'=>Data::TM_VILLAGER
    ,'の遠吠えが響くのみ。'=>Data::TM_WOLF
    //,'が残っていたのです。'=>Data::TM_FAIRY
  ];
  protected $WTM_MOON= [
     //'。めでたしめでたし。'=>Data::TM_VILLAGER
     '達の楽園なのだ――！'=>Data::TM_WOLF
    //,'が残っていたのです。'=>Data::TM_FAIRY
  ];
  protected $DT_MOON = [
     '儚く散った。'=>['.+(\(ランダム投票\)|置いた。)(.+) の命が儚く散った。',Data::DES_HANGED]
    ,'突然死した。'=>['^( ?)(.+) は、突然死した。',Data::DES_RETIRED]
    ,'ていた……。'=>['(.+)朝、(.+) の姿が消.+',Data::DES_EATEN]
    ,'後を追った。'=>['^( ?)(.+) は(絆に引きずられるように|哀しみに暮れて) .+ の後を追った。',Data::DES_SUICIDE]
  ];
  function __construct()
  {
    $cid = 56;
    $url_vil = "http://managarmr.sakura.ne.jp/sow.cgi?vid=";
    $url_log = "http://managarmr.sakura.ne.jp/sow.cgi?cmd=oldlog";
    parent::__construct($cid,$url_vil,$url_log);
    $this->RP_LIST = array_merge($this->RP_LIST,$this->RP_SP);
  }
  protected function fetch_policy()
  {
    $policy= mb_strstr($this->fetch->find('p.multicolumn_left',11)->plaintext,'推理');
    if($policy !== false)
    {
      $this->village->policy = true;
    }
    else
    {
      $this->village->policy = false;
      $this->output_comment('rp');
    }
  }
  protected function fetch_role($person)
  {
    $role = $person->find('td',4)->plaintext;
    $this->user->role = mb_ereg_replace('\A(.+) \(.+を希望\)(.+|)','\1',$role,'m');
  }
}
