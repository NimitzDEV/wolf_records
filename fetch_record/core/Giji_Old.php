<?php

abstract class Giji_Old extends Country
{
  use AR_Giji_Old;
  protected $is_evil; //裏切り陣営あり≒ガチ国かどうか
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

  function fetch_village()
  {
    $this->fetch_from_info();
    $this->fetch_from_pro();
    $this->fetch_from_epi();
    var_dump($this->village->get_vars());
  }

  protected function fetch_from_info()
  {
    $this->fetch->load_file($this->url.$this->village->vno."&cmd=vinfo");

    $this->fetch_name();
    $this->fetch_nop();
    $this->fetch_rglid();
    $this->fetch_days();

    $this->fetch_rp();
    if($this->is_evil)
    {
      $this->fetch_policy();
    }
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
    //ぐたは変える
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
  protected function fetch_rp()
  {
    $this->village->rp = trim($this->fetch->find('dl.mes_text_report dt',0)->plaintext);
  }
  protected function fetch_policy()
  {
    $this->village->policy = $this->fetch->find('p.multicolumn_left',1)->plaintext;
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
    $this->fetch->clear();
  }
  protected function fetch_wtmid()
  {
    //ぐたは変える
    if(!$this->is_evil)
    {
      $this->village->wtmid = Data::TM_RP;
    }
    else
    {
      switch($this->village->policy)
      {
        case "とくになし":
        case "[言] 殺伐、暴言あり":
        case "[遖] あっぱれネタ風味":
        case "[張] うっかりハリセン":
        case "[全] 大人も子供も初心者も、みんな安心":
        case "[危] 無茶ぶり上等":
          //勝利陣営
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
          $wtmid = mb_substr(preg_replace("/\r\n/","",$wtmid),2,13);
          switch($this->village->rp)
          {
            case "ParanoiA":
              $this->village->wtmid = $this->WTM_ZAP[$wtmid];
              break;
            default:
              $this->village->wtmid = $this->WTM[$wtmid];
              break;
          }
          echo $this->village->vno.'.'.$this->village->name.' is guessed GACHI.'.PHP_EOL;
          break;
        default:
          $this->village->wtmid = Data::TM_RP;
          echo $this->village->vno.'.'.$this->village->name.' is guessed RP.->'.PHP_EOL;
          break;
      }
    }
  }
  protected function make_cast()
  {
    $this->cast = $this->fetch->find('tbody tr.i_active');
  }
  protected function fetch_users($cast_item)
  {
  }
  protected function fetch_persona()
  {
  }
  protected function fetch_player()
  {
  }
  protected function fetch_role()
  {
  }
  protected function fetch_dtid()
  {
  }
  protected function fetch_end()
  {
  }
  protected function fetch_sklid()
  {
  }
  protected function fetch_tmid()
  {
  }
  protected function fetch_rltid()
  {
  }
}
