<?php

class Crescent extends Giji_Old
{
  use AR_Guta;
  protected $SKL_SP = 
  [
     "テルテル"=>Data::SKL_TERU
    ,"求愛者"=>Data::SKL_COURTSHIP
  ];
  protected $TM_SP = 
  [
     "テルテル"=>Data::TM_TERU
    ,"物干し"=>Data::TM_TERU
    ,"悪霊"=>Data::TM_YANDERE
  ];
  protected $RP_SP = 
  [
     "蒼い三日月"=>'BM'
    ,"変態BBS"=>'HENTAI'
    ,"ＧＭイラットのクロサー"=>'CLOSED'
  ];
  function __construct()
  {
    $cid = 52;
    $url_vil = "http://www.moonpupa.jp/wolf/sow/sow.cgi?vid=";
    $url_log = "http://www.moonpupa.jp/wolf/sow/sow.cgi?cmd=oldlog";
    parent::__construct($cid,$url_vil,$url_log);
    $this->is_evil = true;
    $this->SKILL = array_merge($this->SKILL,$this->SKL_SP);
    $this->TEAM = array_merge($this->TEAM,$this->TM_SP);
  }

  protected function fetch_nop()
  {
    $nop = $this->fetch->find('p.multicolumn_left',6)->plaintext;
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
    //$policy = $this->fetch->find('p.multicolumn_left',1)->plaintext;
    $this->village->policy = true;
  }
  protected function fetch_wtmid()
  {
    if($this->village->policy)
    {
      $wtmid = trim($this->fetch->find('p.info',-1)->plaintext);
      if(preg_match("/村の更新日が延長されました/",$wtmid))
      {
        $do_i = -2;
        do
        {
          $wtmid = trim($this->fetch->find('p.info',$do_i)->plaintext);
          $do_i--;
        } while(preg_match("/村の更新日が延長されました/",$wtmid));
      }

      //照・据え膳勝利メッセージがあったら削除
      $wtmid = mb_ereg_replace('そして、天に召された魚料理.+|そして、お日様をたっぷり浴びた.+|明日の遠足、大丈夫かなあ。','',$wtmid,'m');
      //特定の言い換えだけ取得文字部分を変更
      if($this->village->rp === 'BM' || $this->village->rp === CLOSED)
      {
        $wtmid = mb_substr(preg_replace("/\r\n/","",$wtmid),-6);
      }
      else
      {
        $wtmid = mb_substr(preg_replace("/\r\n/","",$wtmid),2,13);
      }

      if($this->village->rp !== 'NORMAL')
      {
        $this->village->wtmid = $this->{'WTM_'.$this->village->rp}[$wtmid];
      }
      else
      {
        $this->village->wtmid = $this->WTM[$wtmid];
      }
    }
    else
    {
      $this->village->wtmid = Data::TM_RP;
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
    if($this->village->rp === "HENTAI")
    {
      $this->user->tmid = $this->TM_HENTAI[mb_substr($result,0,2)];
    }
    else
    {
      $this->user->tmid = $this->TEAM[mb_substr($result,0,2)];
    }
    $this->check_evil_team();
  }
}
