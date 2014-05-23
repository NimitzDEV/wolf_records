<?php

class Sea_Old extends Giji_Old
{
  private $SKL_SEA = [
     "共有者"=>Data::SKL_MASON
    ,"狩人"=>Data::SKL_HUNTER
    ,"革命家"=>Data::SKL_AGITATOR
    ,"ハンター"=>Data::SKL_BOUNTY
    ,"鉄人"=>Data::SKL_WEREDOG
    ,"貴族"=>Data::SKL_PRINCE
    ,"狼憑き"=>Data::SKL_LINEAGE
    ,"秘薬師"=>Data::SKL_WITCH
    ,"邪念霊"=>Data::SKL_JAMMER
    ,"憑依霊"=>Data::SKL_SNATCH
    ,"怨霊"=>Data::SKL_LUNAPATH
    ,"傀儡師"=>Data::SKL_MUPPETER
    ,"牙狼"=>Data::SKL_HEADLESS
    ,"妖狐"=>Data::SKL_FAIRY
    ,"狼狐"=>Data::SKL_MIMIC
    ,"仙狐"=>Data::SKL_SNOW
    ,"惑狐"=>Data::SKL_PIXY
    ,"帝国義勇兵"=>Data::SKL_TELEPATH
    ,"王国義勇兵"=>Data::SKL_LUNAWHS
    ,"公国義勇兵"=>Data::SKL_WOLF
    ,"王国工作兵"=>Data::SKL_MIMIC
    ];
  private $DT_LINK = [
     "社会的死"=>Data::DES_HANGED
    ,"戦死"=>Data::DES_EATEN
  ];
  private   $WTM_RP = [
     "の人物が消え失せた時、其処"=>Data::TM_NONE
    ,"の魔が消滅し舞台はついに終"=>Data::TM_VILLAGER
    ,"侵食は進行しついに舞台の全"=>Data::TM_WOLF
    ,"して全ての魔は滅された。た"=>Data::TM_FAIRY
    ,"して魔は舞台の全てを覆い尽"=>Data::TM_FAIRY
    ,"な困難の果てに勝ち残ったの"=>Data::TM_LOVERS
    ,"して舞台は終焉に近づく。誰"=>Data::TM_LWOLF
    ,"の静寂が舞台を包む。立場の"=>Data::TM_PIPER
    ,"はたった独りだけを選んだ。"=>Data::TM_EFB
  ];
  protected $RP_SP = [
    "Role Play"=>'RP'
  ];
  function __construct()
  {
    $cid = 34;
    $url_vil = "http://chaos-circle.versus.jp/wolf/abyss/sow.cgi?vid=";
    $url_log = "http://chaos-circle.versus.jp/wolf/abyss/sow.cgi?cmd=oldlog";
    parent::__construct($cid,$url_vil,$url_log);
    $this->is_evil = true;
    $this->SKILL = array_merge($this->SKILL,$this->SKL_SEA);
    $this->DESTINY = array_merge($this->DESTINY,$this->DT_LINK);
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
      case 'とくになし':
      case 'ガチ推理':
      case 'ゆるガチ':
        $this->village->policy = true;
        break;
      default:
        $this->village->policy = false;
        echo $this->village->vno.' is guessed RP.'.PHP_EOL;
        break;
    }
  }
}
