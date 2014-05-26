<?php

class Sea extends Giji_Old
{
  private $SKL_SEA = [
     "狼憑き"=>Data::SKL_LINEAGE
    ,"座敷童"=>Data::SKL_LUNAMIM
    ,"容疑者"=>Data::SKL_FUGITIVE
    ,"獣化病"=>Data::SKL_LYCAN
    ,"陰陽師"=>Data::SKL_ONMYO
    ,"暗殺者"=>Data::SKL_ASSASSIN
    ,"見習い占い師"=>Data::SKL_SEERUNSKL
    ,"傾奇者"=>Data::SKL_KABUKI
    ,"囮人形"=>Data::SKL_DECOY
    ,"悟られ狂人"=>Data::SKL_SUSPECT
    ,"賢狼"=>Data::SKL_MEDIWOLF
    ,"霊狼"=>Data::SKL_NECROWOLF
    ,"九尾"=>Data::SKL_NINETALES
    ,"呪狐"=>Data::SKL_CURSEFOX
    ,"半妖"=>Data::SKL_HALFFOX
    ,"仙狐"=>Data::SKL_OLDFOX
    ,"吸血鬼"=>Data::SKL_VAMPSEA
    ,"眷属"=>Data::SKL_SERVANT
    ];
  private $WTM_SEA = [
     "らかな光が降り注ぐ。全ての"=>Data::TM_VILLAGER
    ,"全ての希望を染めつくした。"=>Data::TM_WOLF
    ,"も、人狼も、妖孤でさえも、"=>Data::TM_LOVERS//誤字
    ,"の対立に終止符が打たれたと"=>Data::TM_VAMPIRE
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
    $cid = 45;
    $url_vil = "http://chaos-circle.xsrv.jp/abyss/sow/sow.cgi?vid=";
    $url_log = "http://chaos-circle.xsrv.jp/abyss/sow/sow.cgi?cmd=oldlog";
    parent::__construct($cid,$url_vil,$url_log);
    $this->is_evil = true;
    $this->SKILL = array_merge($this->SKILL,$this->SKL_SEA);
    $this->WTM = array_merge($this->WTM,$this->WTM_SEA);
  }

  protected function fetch_nop()
  {
    $nop = $this->fetch->find('p.multicolumn_left',7)->plaintext;
    $this->village->nop = (int)mb_substr($nop,0,mb_strpos($nop,'人'));
  }
  protected function fetch_policy()
  {
    $policy = $this->fetch->find('p.multicolumn_left',1)->plaintext;
    switch($policy)
    {
      case 'とくになし':
      case 'ガチ推理':
      case 'ゆるガチ':
      case '推理&RP':
        $this->village->policy = true;
        break;
      default:
        $this->village->policy = false;
        echo $this->village->vno.' is guessed RP.'.PHP_EOL;
        break;
    }
  }
}
