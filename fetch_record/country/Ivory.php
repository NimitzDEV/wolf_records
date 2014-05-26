<?php

class Ivory extends Giji_Old
{
  use AR_Ivory;
  private $SKL_SP = [
     "審問官"=>Data::SKL_SEERWIN
    ,"隠秘学者"=>Data::SKL_SEERAURA
    ,"登記役人"=>Data::SKL_SAGE
    ,"聴罪師"=>Data::SKL_MEDIWIN
    ,"検死官"=>Data::SKL_PRIEST
    ,"貴族"=>Data::SKL_PRINCE
    ,"目隠し妖怪"=>Data::SKL_JAMMER
    ,"取替え妖怪"=>Data::SKL_SNATCH
    ,"古狸"=>Data::SKL_LUNAPATH
    ,"傀儡師"=>Data::SKL_MUPPETER
    ,"悪魔"=>Data::SKL_EFB
    ,"天使"=>Data::SKL_QP
    ,"誓約者"=>Data::SKL_PASSION
    ,"二枚舌"=>Data::SKL_PLAYBOY
    ,"宣教師"=>Data::SKL_PIPER
    ];
  protected $RP_SP = [
     "ミラーズホロウ"=>'MILLERS'
    ,"マフィア"=>'MAFIA'
  ];
  function __construct()
  {
    $cid = 50;
    $url_vil = "http://kids.sphere.sc/tabula/lupus/sow.cgi?vid=";
    $url_log = "http://kids.sphere.sc/tabula/lupus/sow.cgi?cmd=oldlog";
    parent::__construct($cid,$url_vil,$url_log);

    $this->SKILL = array_merge($this->SKILL,$this->SKL_SP);
    $this->is_evil = false;
    $this->policy = true;
  }

  protected function fetch_name()
  {
    $name = $this->fetch->find('p.multicolumn_left',0)->plaintext;
    $this->village->name = mb_ereg_replace("(.+)\r\n.+","\\1",$name);
  }
  protected function fetch_rglid()
  {
    $rule= trim($this->fetch->find('dl.mes_text_report dt',1)->plaintext);
    if(array_key_exists($rule,$this->RGL_IVORY))
    {
      $this->village->rglid = $this->RGL_IVORY[$rule];
      return;
    }
    $rglid = trim($this->fetch->find('dl.mes_text_report dd',2)->plaintext);
    $this->find_rglid($rglid);
  }
  protected function fetch_rp()
  {
    $rp = trim($this->fetch->find('dl.mes_text_report dt',0)->plaintext);
    $rp = mb_ereg_replace('文章セット：「(.+)」','\\1',$rp);
    if(array_key_exists($rp,$this->RP_SP))
    {
      $this->village->rp = $this->RP_SP[$rp]; 
    }
    else
    {
      $this->village->rp = 'NORMAL'; 
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
    if($this->village->rp === "MAFIA")
    {
      $this->user->tmid = $this->TM_MAFIA[mb_substr($result,0,2)];
    }
    else
    {
      $this->user->tmid = $this->TEAM[mb_substr($result,0,2)];
    }
  }
}
