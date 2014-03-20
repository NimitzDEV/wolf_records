<?php

class Guta_P extends Giji_Old
{
  use AR_Guta;
  private $SKL_GUTA = [
     "共有者"=>Data::SKL_MASON
    ,"狩人"=>Data::SKL_HUNTER
    ,"コウモリ人間"=>Data::SKL_LUNAPATH
    ,"Ｃ国狂人"=>Data::SKL_LUNAWHS
    ,"首なし騎士"=>Data::SKL_HEADLESS
    ,"ハムスター人間"=>Data::SKL_FAIRY
    ,"ピクシー"=>Data::SKL_PIXY
    ,"キューピッド"=>Data::SKL_QP
  ];
  protected $RP_SP = [
    "昏き宵闇の琥珀"=>'AMBER'
  ];
  protected $doppel = 
    [
       "yuki"   =>"yuki&lt;ぐた&gt;"
    ];
  function __construct()
  {
    $cid = 25;
    $url_vil = "http://cgi16.plala.or.jp/freegate/cabalala/sow.cgi?vid=";
    $url_log = "http://cgi16.plala.or.jp/freegate/cabalala/sow.cgi?cmd=oldlog";
    parent::__construct($cid,$url_vil,$url_log);
    $this->is_evil = true;
    $this->SKILL = array_merge($this->SKILL,$this->SKL_GUTA);
  }

  protected function fetch_nop()
  {
    $nop = $this->fetch->find('p.multicolumn_left',7)->plaintext;
    $this->village->nop = (int)mb_substr($nop,0,mb_strpos($nop,'人'));
  }
  protected function check_g_rgl()
  {
    switch(true)
    {
      case ($this->village->nop  >= 16):
        $this->village->rglid = Data::RGL_G;
        break;
      case ($this->village->nop  <= 15 && $this->village->nop >= 13):
        $this->village->rglid = Data::RGL_S_3;
        break;
      case ($this->village->nop <=12 && $this->village->nop >= 8):
        $this->village->rglid = Data::RGL_S_2;
        break;
      default:
        $this->village->rglid = Data::RGL_S_1;
        break;
    }
  }
  protected function fetch_policy()
  {
    $policy = $this->fetch->find('p.multicolumn_left',1)->plaintext;
    switch($policy)
    {
      case "ガチ推理（陣営勝敗最優先）":
      case "推理＆RP（勝負しながらキャラプレイも楽しむ）":
        $this->village->policy = true;
        echo $this->village->vno.'.'.$this->village->name.' is guessed GACHI.'.PHP_EOL;
        break;
      default:
        $this->village->policy = false;
        echo $this->village->vno.'.'.$this->village->name.' is guessed RP.'.PHP_EOL;
        break;
    }
  }
  protected function fetch_sklid()
  {
    $role = $this->user->role;
    if(mb_strpos($role,"、") === false)
    {
      $sklid = $role;
    }
    else
    {
      //役職欄に絆などついている場合
      $sklid = mb_substr($role,0,mb_strpos($role,"、"));
    }
    switch($this->village->rp)
    {
      case "昏き宵闇の琥珀":
        $this->user->sklid = $this->SKL_AMBER[$sklid];
        break;
      case "ミラーズホロウ":
        $this->user->sklid = $this->SKL_MILLERS[$sklid];
        break;
      default:
        $this->user->sklid = $this->SKILL[$sklid];
        break;
    }
  }
  protected function fetch_tmid($result)
  {
    if($this->village->rp === "昏き宵闇の琥珀")
    {
      $this->user->tmid = $this->TM_AMBER[mb_substr($result,0,2)];
    }
    else
    {
      $this->user->tmid = $this->TEAM[mb_substr($result,0,2)];
    }
    $this->check_evil_team();
  }
  protected function fetch_dtid($result)
  {
    if($this->village->rp === "昏き宵闇の琥珀")
    {
      $this->user->dtid = $this->DES_AMBER[$result];
    }
    else
    {
      $this->user->dtid = $this->DESTINY[$result];
    }
  }

}
