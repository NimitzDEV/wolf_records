<?php

class Sea_Old extends Giji_Old
{
  private $SKL_SP = [
     "共有者"=>Data::SKL_MASON
    ,"狩人"=>Data::SKL_HUNTER
    ,"狼憑き"=>Data::SKL_LINEAGE
    ,"ハムスター人間"=>Data::SKL_FAIRY
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
    ,"中身占い師"=>Data::SKL_IDSEER
    ,"巫者"=>Data::SKL_WILD
    ,"狼少年"=>Data::SKL_LIAR
    ,"感応狂人"=>Data::SKL_LUNASIL
    ,"瘴気狂人"=>Data::SKL_MIASMA
    ,"コウモリ人間"=>Data::SKL_BAT
    ,"サトリ"=>Data::SKL_SATORI
    ,"コレクター"=>Data::SKL_COLLECTOR
    ,"スナッチャー"=>Data::SKL_SSNATCHER
    ,"ギャンブラー"=>Data::SKL_GAMBLER
    ];
  private $WTM_SP = [
     "らかな光が降り注ぐ。全ての"=>Data::TM_VILLAGER
    ,"全ての希望を染めつくした。"=>Data::TM_WOLF
    ,"も、人狼も、妖孤でさえも、"=>Data::TM_LOVERS//誤字
    ,"の対立に終止符が打たれたと"=>Data::TM_VAMPIRE
  ];
  function __construct()
  {
    $cid = 34;
    $url_vil = "http://chaos-circle.versus.jp/wolf/abyss/sow.cgi?vid=";
    $url_log = "http://chaos-circle.versus.jp/wolf/abyss/sow.cgi?cmd=oldlog";
    parent::__construct($cid,$url_vil,$url_log);
    $this->is_evil = true;
    $this->SKILL = array_merge($this->SKILL,$this->SKL_SP);
    $this->WTM = array_merge($this->WTM,$this->WTM_SP);
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
