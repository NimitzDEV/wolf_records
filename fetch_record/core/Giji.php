<?php

abstract class Giji extends Country
{
  use TRS_Giji,Rgl_Auto;
  protected $is_evil;
  protected $base;

  function fetch_village()
  {
    $this->fetch->load_file($this->url.$this->village->vno."#mode=info_open_player");
    $this->base = $this->fetch->find('script',-2)->innertext;

    $this->fetch_name();
    $this->fetch_date();
    $this->fetch_days();

    $this->make_cast();
    $this->fetch_nop();
    $this->fetch_rglid();
    
    if(!isset($this->policy))
    {
      $this->fetch_policy();
    }
    $this->fetch_wtmid();
  }
  protected function fetch_name()
  {
    $this->village->name = preg_replace('/.*?\),.*?"name":    "([^"]*)",.+/s',"$1",$this->base);
  }
  protected function fetch_date()
  {
    $date = preg_replace('/.+"updateddt":    new Date\(1000 \* (\d+)\),.+/s',"$1",$this->base);
    $this->village->date = date('Y-m-d',$date);
  }
  protected function fetch_days()
  {
    $this->village->days = (int)preg_replace('/.+"turn": (\d+).+/s',"$1",$this->base);
  }
  protected function fetch_nop()
  {
    $nop_all = count($this->cast);
    //見物人カウント
    preg_match_all('/giji\.potof\.roles\(999, -1\);.+/s',$this->base,$onlooker);
    $this->village->nop = $nop_all - count($onlooker[0]);
  }
  protected function fetch_rglid()
  {
    $rule = preg_replace('/.+"game_name": "([^"]*)",.+/s',"$1",$this->base);
    if($this->check_sprule($rule))
    {
      return;
    }
    $rglid = preg_replace('/.+"roletable": "([^"]*)",.+/s',"$1",$this->base);
    switch($rglid)
    {
      case "custom":
        $free = preg_replace('/.+"config":  "([^"]*)".+/s',"$1",$this->base);
        if(array_key_exists($free,$this->RGL_FREE))
        {
          $this->village->rglid = $this->RGL_FREE[$free];
        }
        else
        {
          echo $this->village->vno.' has '.$free.PHP_EOL.'　▼Should check evil team.'.PHP_EOL;
          $this->village->rglid = Data::RGL_ETC;
        }
        break;
      case "wbbs_g":
        $this->check_rgl_g($this->village->nop);
        break;
      case "wbbs_c":
        $this->check_rgl_c($this->village->nop);
        break;
      case "default":
        $this->check_rgl_leo($this->village->nop);
        break;
      case "wbbs_f":
        $this->check_rgl_f($this->village->nop);
        break;
      case "test1st":
        $this->check_rgl_tes1($this->village->nop);
        break;
      case "test2nd":
        $this->check_rgl_tes2($this->village->nop);
        break;
      case "mistery":
        $this->village->rglid = Data::RGL_ETC;
        echo $this->village->vno.' has 深い霧の夜.▼Should check evil team.'.PHP_EOL;
        break;
    }
    if(in_array($this->village->rglid,$this->EVIL))
    {
      $this->village->evil_rgl = true;
    }
  }
  protected function check_sprule($rule)
  {
    if(array_key_exists($rule,$this->RGL_SP))
    {
      $this->village->rglid = $this->RGL_SP[$rule];
      if($this->is_evil)
      {
        echo $this->village->vno.' is '.$rule.".▼Should check evil team.".PHP_EOL;
      }
      return true;
    }
    else if(preg_match("/秘話/",$this->village->name))
    {
      echo 'NOTICE: '.$this->village->vno.' may be 秘話村.'.PHP_EOL;
      $this->village->rglid = Data::RGL_SECRET;
      return true;
    }
    else
    {
      return false;
    }
  }
  protected function fetch_wtmid()
  {
    if($this->policy || $this->village->policy)
    {
      $policy = preg_replace('/.+"rating": "([^"]*)".+/s',"$1",$this->base);
      switch($policy)
      {
        case "とくになし":
        case "[言] 殺伐、暴言あり":
        case "[遖] あっぱれネタ風味":
        case "[張] うっかりハリセン":
        case "[全] 大人も子供も初心者も、みんな安心":
        case "[危] 無茶ぶり上等":
          $this->village->wtmid = $this->WTM[preg_replace('/.+"winner": giji\.event\.winner\((\d+)\),.+/s',"$1",$this->base)];
          break;
        default:
          $this->village->wtmid = Data::TM_RP;
          echo $this->village->vno.' is guessed RP.'.PHP_EOL;
          break;
      }
    }
    else
    {
      $this->village->wtmid = Data::TM_RP;
    }
  }
  protected function make_cast()
  {
    $cast = explode("gon.potofs",$this->base);
    array_shift($cast);
    array_pop($cast);
    $this->cast = $cast;
  }

  protected function fetch_users($person)
  {
    $this->fetch_persona($person);
    $this->fetch_player($person);
    $this->fetch_dtid($person);
    $this->fetch_tmid($person);

    $this->fetch_role($person);
    $this->fetch_end($person);
    $this->fetch_rltid($person);
    $this->fetch_life($person);
  }
  protected function fetch_persona($person)
  {
    $this->user->persona = preg_replace('/.+"longname": "([^"]*)",.+/s',"$1",$person);
  }
  protected function fetch_player($person)
  {
    $player = preg_replace('/.+sow_auth_id = "([^"]*)".+/s',"$1",$person);
    if(isset($this->{'d_'.get_class($this)}))
    {
      $this->user->player =$this->check_doppel($player);
    }
    else
    {
      $this->user->player = $player;
    }
  }
  protected function fetch_dtid($person)
  {
    $this->user->dtid =$this->DESTINY[preg_replace('/.+"live": "([^"]*)",.+/s',"$1",$person)];
  }
  protected function fetch_tmid($person)
  {
    $this->user->tmid =$this->TEAM[preg_replace('/.+visible: "([^"]*)",.+/s',"$1",$person)];
    if($this->is_evil)
    {
      $this->check_evil_team();
    }
  }
  protected function check_evil_team()
  {
    if($this->user->tmid  === Data::TM_EVIL && $this->village->evil_rgl !== true)
    {
      $this->user->tmid = Data::TM_WOLF;
    }
  }
  protected function fetch_role($person)
  {
    $sklid = preg_replace('/.+giji\.potof\.roles\((\d+), -?\d+\);.+/s',"$1",$person);
    $this->user->sklid =$this->SKILL[$sklid][0];

    $gift = (int)preg_replace('/.+giji\.potof\.roles\(\d+, (-?\d+)\);.+/s',"$1",$person);
    $love = preg_replace('/.+pl\.love = "([^"]*)".+/s',"$1",$person);
    //恩恵か恋邪気絆があれば追加
    if($gift >= 2 || $love !== '')
    {
      $after_role = [];
      if($gift >= 2)
      {
        $after_role[] = $this->GIFT[$gift];
      }
      if($love !== '')
      {
        $after_role[] = $this->BAND[$love];
      }
      $this->user->role = $this->SKILL[$sklid][1].'、'.implode('、',$after_role);
    }
    else
    {
      $this->user->role = $this->SKILL[$sklid][1];
    }
  }
  protected function fetch_end($person)
  {
    $end = (int)preg_replace('/.+"deathday": (-*\d+),.+/s',"$1",$person);
    switch($end)
    {
      case -2: //見物人
        $this->user->end = 1;
        break;
      case -1: //生存者
        $this->user->end = $this->village->days;
        break;
      default:
        $this->user->end = $end;
        break; 
    }
  }
  protected function fetch_rltid($person)
  {
    if($this->user->sklid === Data::SKL_ONLOOKER)
    {
      $this->user->rltid = Data::RSL_ONLOOKER;
    }
    else if($this->village->wtmid === Data::TM_RP)
    {
      $this->user->rltid = Data::RSL_JOIN;
    }
    else
    {
      $rltid = preg_replace('/.+result:  "([^"]*)".+/s',"$1",$person);
      $this->user->rltid = $this->RSL[$rltid];
    }
  }
}
