<?php

abstract class Giji_Old extends Country
{
  use AR_Giji_Old;
  protected $is_evil; //裏切り陣営ありの国かどうか

  function fetch_village()
  {
    $this->fetch_from_info();
    $this->fetch_from_pro();
    $this->fetch_from_epi();
    //var_dump(get_object_vars($this->village));
  }

  protected function fetch_from_info()
  {
    $this->fetch->load_file($this->url.$this->village->vno."&cmd=vinfo");

    $this->fetch_name();
    $this->fetch_nop();
    $this->fetch_rglid();
    $this->fetch_days();

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
    $rglid = trim($this->fetch->find('dl.mes_text_report dt',2)->plaintext);
    $rglid = mb_substr($rglid,mb_strpos($rglid,"：")+1);
    switch($rglid)
    {
      case "自由設定":
        //自由設定でも特定の編成はレギュレーションを指定する
        $free = trim($this->fetch->find('dl.mes_text_report dd',3)->plaintext);
        if(array_key_exists($free,$this->RGL_FREE))
        {
          $this->village->rglid = $this->RGL_FREE[$free];
        }
        else
        {
          echo $this->village->vno.' has '.$free.PHP_EOL;
          $this->village->rglid = Data::RGL_ETC;
        }
        break;
      case "新標準":
      case "標準":
        if($this->village->nop <= 7)
        {
          $this->village->rglid = Data::RGL_S_1;
        }
        else
        {
          $this->village->rglid = Data::RGL_LEO;
        }
        break;
      case "深い霧の夜":
        $this->village->rglid = Data::RGL_MIST;
        break;
      case "人狼審問 試験壱型":
        switch(true)
        {
          case ($this->village->nop  >= 13):
            $this->village->rglid = Data::RGL_TES1;
            break;
          case ($this->village->nop <=12 && $this->village->nop >= 8):
            $this->village->rglid = Data::RGL_S_2;
            break;
          default:
            $this->village->rglid = Data::RGL_S_1;
            break;
        }
        break;
      case "人狼審問 試験弐型":
        switch(true)
        {
          case ($this->village->nop  >= 10):
            $this->village->rglid = Data::RGL_TES2;
            break;
          case ($this->village->nop  === 8 || $this->village->nop  === 9):
            $this->village->rglid = Data::RGL_S_2;
            break;
          default:
            $this->village->rglid = Data::RGL_S_1;
            break;
        }
        break;
      case "人狼BBS C国":
        switch(true)
        {
          case ($this->village->nop  >= 16):
            $this->village->rglid = Data::RGL_C;
            break;
          case ($this->village->nop  === 15):
            $this->village->rglid = Data::RGL_S_C3;
            break;
          case ($this->village->nop <=14 && $this->village->nop >= 10):
            $this->village->rglid = Data::RGL_S_C2;
            break;
          case ($this->village->nop  === 8 || $this->village->nop === 9):
            $this->village->rglid = Data::RGL_S_2;
            break;
          default:
            $this->village->rglid = Data::RGL_S_1;
            break;
        }
        break;
      case "人狼BBS F国":
        switch(true)
        {
          case ($this->village->nop  >= 16):
            $this->village->rglid = Data::RGL_F;
            break;
          case ($this->village->nop  === 15):
            $this->village->rglid = Data::RGL_S_3;
            break;
          case ($this->village->nop <=14 && $this->village->nop >= 8):
            $this->village->rglid = Data::RGL_S_2;
            break;
          default:
            $this->village->rglid = Data::RGL_S_1;
            break;
        }
        break;
      case "人狼BBS G国":
        $this->check_g_rgl();
        break;
    }
    if($this->is_evil)
    {
      $this->check_evil_rgl();
    }
  }
  protected function check_sprule($rule)
  {
    if(array_key_exists($rule,$this->RGL_SP))
    {
      $this->village->rglid = $this->RGL_SP[$rule];
      if($this->is_evil)
      {
        echo $this->village->vno.' is '.$rule.".Should check evil team.".PHP_EOL;
      }
      return true;
    }
    else if(preg_match("/秘話/",$this->village->name))
    {
      echo 'NOTICE: '.$this->village->vno.' may be 秘話村.';
      $this->village->rglid = Data::RGL_SECRET;
      return true;
    }
    else
    {
      return false;
    }
  }
  protected function check_g_rgl()
  {
    switch(true)
    {
      case ($this->village->nop  >= 16):
        //16人編成はF編成になっている
        $this->village->rglid = Data::RGL_F;
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
  protected function check_evil_rgl()
  {
    $rglid = $this->village->rglid;
    $nop = $this->village->nop;
    if(in_array($rglid,$this->EVIL) || ($rglid === Data::RGL_MIST && ($nop <8 || $nop >18)))
    {
      $this->village->evil_rgl = true;
    }
  }
  protected function fetch_days()
  {
    $days = trim($this->fetch->find('p.turnnavi',0)->find('a',-4)->innertext);
    $days = mb_convert_encoding($days,"UTF-8","SJIS");
    $this->village->days = mb_substr($days,0,mb_strpos($days,'日')) +1;
  }

}
