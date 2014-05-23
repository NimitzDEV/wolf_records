<?php

class Guta extends Giji_Old
{
  use AR_Guta;
  protected $RP_SP = [
     "ミラーズホロウ"=>'MILLERS'
    ,"昏き宵闇の琥珀"=>'AMBER'
  ];
  function __construct()
  {
    $cid = 11;
    $url_vil = "http://www3.marimo.or.jp/~fgmaster/cabala/sow.cgi?vid=";
    $url_log = "http://www3.marimo.or.jp/~fgmaster/cabala/sow.cgi?cmd=oldlog";
    parent::__construct($cid,$url_vil,$url_log);
    $this->is_evil = true;
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
        break;
      default:
        $this->village->policy = false;
        echo $this->village->vno.' is guessed RP.'.PHP_EOL;
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
    if($this->village->rp !== 'NORMAL')
    {
      $this->user->sklid = $this->{'SKL_'.$this->village->rp}[$sklid];
    }
    else
    {
      $this->user->sklid = $this->SKILL[$sklid];
    }
  }
  protected function fetch_tmid($result)
  {
    if($this->village->rp === "AMBER")
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
    if($this->village->rp === "AMBER")
    {
      $this->user->dtid = $this->DES_AMBER[$result];
    }
    else
    {
      $this->user->dtid = $this->DESTINY[$result];
    }
  }

}
