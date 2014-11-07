<?php

abstract class Sea extends Giji_Old
{
  use TRS_Sea;
  protected $RP_SP = [
    "RolePlay"=>'RP'
  ];
  abstract function set_village_data();
  function __construct()
  {
    $data = $this->set_village_data();
    parent::__construct($data['cid'],$data['url_vil'],$data['url_log']);
    $this->is_evil = true;
    $this->SKILL = array_merge($this->SKILL,$this->SKL_SP);
    $this->TEAM = array_merge($this->TEAM,$this->TM_SP);
    $this->WTM = array_merge($this->WTM,$this->WTM_SP);
  }

  protected function fetch_nop()
  {
    $nop = $this->fetch->find('p.multicolumn_left',7)->plaintext;
    if(!mb_ereg_match('\d+人.+',$nop))
    {
      $nop = $this->fetch->find('p.multicolumn_left',8)->plaintext;
    }
    $this->village->nop = (int)mb_substr($nop,0,mb_strpos($nop,'人'));
  }
  protected function fetch_policy_detail()
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
        $this->output_comment('rp');
        break;
    }
  }
}
