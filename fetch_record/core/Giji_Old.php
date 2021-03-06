<?php

abstract class Giji_Old extends Country
{
  use TRS_Giji_Old,Rgl;
  protected $is_evil; //裏切り陣営あり
  private   $WTM_ZAP = [
     "の人物が消え失せ、守り育む"=>Data::TM_NONE
    ,"可の組織は全滅した……。「"=>Data::TM_VILLAGER
    ,"達は自らの過ちに気付いた。"=>Data::TM_WOLF
    ,"の結社員を退治した……。"=>Data::TM_FAIRY
    ,"時、「人狼」は勝利を確信し"=>Data::TM_FAIRY
    ,"も、「人狼」も、ミュータン"=>Data::TM_LOVERS
    ,"達は、そして「人狼」も自ら"=>Data::TM_LWOLF
    ,"達は気付いてしまった。もう"=>Data::TM_PIPER
    ,"はたった独りだけを選んだ。"=>Data::TM_EFB
  ];
  protected $RP_SP = [
    "ParanoiA"=>'ZAP'
  ];

  function fetch_village()
  {
    $this->fetch_from_info();
    $this->fetch_from_pro();
    $this->fetch_from_epi();
    //var_dump($this->village->get_vars());
  }

  protected function fetch_from_info()
  {
    $this->fetch->load_file($this->url.$this->village->vno."&cmd=vinfo");

    $this->fetch_name();
    $this->fetch_nop();
    $this->fetch_days();
    $this->fetch_rp();
    if(!isset($this->policy))
    {
      $this->fetch_policy();
    }
    $this->fetch_rglid();

    $this->fetch->clear();
  }

  protected function fetch_name()
  {
    $this->village->name = $this->fetch->find('p.multicolumn_left',0)->plaintext;
  }
  protected function fetch_nop()
  {
    $nop = $this->fetch->find('p.multicolumn_left',5)->plaintext;
    $this->village->nop = (int)mb_substr($nop,0,mb_strpos($nop,'人'));
  }
  protected function fetch_rglid()
  {
    $rule= trim($this->fetch->find('dl.mes_text_report dt',1)->plaintext);
    if($this->check_sprule($rule))
    {
      return;
    }
    $rglid = trim($this->fetch->find('dl.mes_text_report dd',3)->plaintext);
    $this->find_rglid($rglid);
  }
  protected function check_sprule($rule)
  {
    if(array_key_exists($rule,$this->RGL_SP))
    {
      $this->village->rglid = $this->RGL_SP[$rule];
      if($this->is_evil)
      {
        $this->output_comment('evil',$rule);
      }
      return true;
    }
    else if(preg_match("/秘話/",$this->village->name))
    {
      $this->village->rglid = Data::RGL_SECRET;
      $this->output_comment('evil','秘話村');
      return true;
    }
    else
    {
      return false;
    }
  }
  protected function fetch_days()
  {
    $days = trim($this->fetch->find('p.turnnavi',0)->find('a',-4)->innertext);
    $this->village->days = mb_substr($days,0,mb_strpos($days,'日')) +1;
  }
  protected function fetch_rp()
  {
    $rp = trim($this->fetch->find('dl.mes_text_report dt',0)->plaintext);
    if(array_key_exists($rp,$this->RP_SP))
    {
      $this->village->rp = $this->RP_SP[$rp]; 
    }
    else
    {
      $this->village->rp = 'NORMAL'; 
    }
  }
  protected function fetch_policy()
  {
    parent::fetch_policy();
    if($this->village->policy === true)
    {
      $this->fetch_policy_detail();
    }
  }
  protected function fetch_policy_detail()
  {
    $policy = $this->fetch->find('p.multicolumn_left',1)->plaintext;
    switch($policy)
    {
      case "とくになし":
      case "[言] 殺伐、暴言あり":
      case "[遖] あっぱれネタ風味":
      case "[張] うっかりハリセン":
      case "[暢] のんびり雑談":
      case "[全] 大人も子供も初心者も、みんな安心":
        $this->village->policy = true;
        break;
      default:
        $this->village->policy = false;
        $this->output_comment('rp');
        break;
    }
  }
  protected function fetch_from_pro()
  {
    $url = $this->url.$this->village->vno.'&turn=0&row=10&mode=all&move=page&pageno=1';
    $this->fetch->load_file($url);

    $this->fetch_date();
    $this->fetch->clear();
  }
  protected function fetch_date()
  {
    $date = $this->fetch->find('p.mes_date',0)->plaintext;
    $date = mb_substr($date,mb_strpos($date,"2"),10);
    $this->village->date = preg_replace('/(\d{4})\/(\d{2})\/(\d{2})/','\1-\2-\3',$date);
  }
  protected function fetch_from_epi()
  {
    $url = $this->url.$this->village->vno.'&turn='.$this->village->days.'&row=30&mode=all&move=page&pageno=1';
    $this->fetch->load_file($url);

    $this->fetch_wtmid();
    $this->make_cast();
  }
  protected function fetch_wtmid()
  {
    if($this->policy || $this->village->policy)
    {
      $wtmid = trim($this->fetch->find('p.info',-1)->plaintext);
      if(preg_match("/村の更新日が延長されました|が参加しました。/",$wtmid))
      {
        $do_i = -2;
        do
        {
          $wtmid = trim($this->fetch->find('p.info',$do_i)->plaintext);
          $do_i--;
        } while(preg_match("/村の更新日が延長されました|が参加しました。/",$wtmid));
      }
      $wtmid = mb_substr(preg_replace("/\r\n/","",$wtmid),2,13);
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
  protected function make_cast()
  {
    $this->cast = $this->fetch->find('tbody tr.i_active');
  }
  protected function insert_users()
  {
    $this->users = [];
    foreach($this->cast as $person)
    {
      $this->user = new User();
      $this->fetch_users($person);
      if(!$this->user->is_valid())
      {
        $this->output_comment('n_user');
      }
      $this->users[] = $this->user;
    }
    if($this->is_evil === true && $this->village->evil_rgl !== true)
    {
      $this->change_evil_team();
    }
  }
  protected function change_evil_team()
  {
    foreach($this->users as $key=>$user)
    {
      if($user->tmid === Data::TM_EVIL)
      {
        $this->users[$key]->tmid = Data::TM_WOLF;
      }
    }
  }
  protected function fetch_users($person)
  {
    $this->fetch_persona($person);
    $this->fetch_player($person);

    $result = $person->find("td",3)->plaintext;
    $result = mb_substr($result,0,mb_strpos($result,"\n")-1);
    $result = explode(' ',$result);

    $this->fetch_role($result[2]);
    if(mb_substr($this->user->role,-2) === "居た")
    {
      $this->insert_onlooker();
    }
    else
    {
      $this->fetch_end($result[0],$person);
      $this->fetch_rltid($result[1]);
      $this->fetch_sklid();
      $this->fetch_dtid($result[0]);
      $this->fetch_tmid($result[2]);
      $this->fetch_life();
    }
    //var_dump($this->user->get_vars());
  }
  protected function fetch_persona($person)
  {
    $this->user->persona =trim($person->find("td",0)->plaintext);
  }
  protected function fetch_player($person)
  {
    $player =trim($person->find("td",1)->plaintext);
    if(isset($this->{'d_'.get_class($this)}))
    {
      $this->user->player =$this->check_doppel($player);
    }
    else
    {
      $this->user->player = $player;
    }
  }
  protected function fetch_role($role)
  {
    $this->user->role = mb_substr($role,mb_strpos($role,'：')+1);
  }
  protected function insert_onlooker()
  {
    $this->user->role  = '見物人';
    $this->user->dtid  = Data::DES_ONLOOKER;
    $this->user->end   = 1;
    $this->user->sklid = Data::SKL_ONLOOKER;
    $this->user->tmid  = Data::TM_ONLOOKER;
    $this->user->life  = 0.000;
    $this->user->rltid = Data::RSL_ONLOOKER;
  }
  protected function fetch_dtid($result)
  {
    $this->user->dtid = $this->DESTINY[$result];
  }
  protected function fetch_end($result,$person)
  {
    if($result === '生存者')
    {
      $this->user->end = $this->village->days;
    }
    else
    {
      $this->user->end = (int)preg_replace("/(.+)日/","$1",$person->find("td",2)->plaintext);
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
    $this->user->sklid = $this->SKILL[$sklid];
  }
  protected function fetch_tmid($result)
  {
    $this->user->tmid = $this->TEAM[mb_substr($result,0,2)];
    if($this->is_evil)
    {
      $this->check_evil_team();
    }
  }
  protected function check_evil_team()
  {
    if($this->user->tmid >= Data::TM_FAIRY && $this->user->tmid !== Data::TM_LOVERS && $this->user->tmid !== Data::TM_EVIL)
    {
      $this->village->evil_rgl = true;
    }
  }
  protected function fetch_rltid($result)
  {
    if($this->village->wtmid === Data::TM_RP)
    {
      $this->user->rltid = Data::RSL_JOIN;
    }
    else
    {
      $this->user->rltid = $this->RSL[$result];
    }
  }
}
