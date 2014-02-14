<?php
class Sebas extends Country
{
  use AR_Sebas,TR_SOW_RGL;
  private $GACHI = [140,138,137,136,132,131,130,129];

  function __construct()
  {
    $cid = 31;
    $url_vil = "http://sebas.chips.jp/sow/sow.cgi?vid=";
    $url_log = "http://sebas.chips.jp/sow/sow.cgi?cmd=oldlog";
    parent::__construct($cid,$url_vil,$url_log);
  }

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
    $this->fetch_policy();

    $this->fetch->clear();
  }
  protected function fetch_name()
  {
    $this->village->name = $this->fetch->find('p.multicolumn_left',0)->plaintext;
  }
  protected function fetch_nop()
  {
    $nop = $this->fetch->find('p.multicolumn_left',2)->plaintext;
    $this->village->nop = (int)preg_replace('/参戦者(\d+)人.+/','\1',$nop);
  }
  protected function fetch_rglid()
  {
    $rgl_base = trim($this->fetch->find('p.multicolumn_right',1)->plaintext);
    $rglid = preg_replace('/\r\n.+/','',$rgl_base);
    switch($rglid)
    {
      case "自由設定":
      case "ごった煮":
        //自由設定でも特定の編成はレギュレーションを指定する
        $free = mb_substr($rgl_base,mb_strpos($rgl_base,'（'));
        if(array_key_exists($free,$this->RGL_FREE))
        {
          $this->village->rglid = $this->RGL_FREE[$free];
        }
        else
        {
          //echo $this->village->vno.' has '.$free.PHP_EOL;
          $this->village->rglid = Data::RGL_ETC;
        }
        break;
      case "標準":
      case "ふつー":
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
      case "ハム入り":
      case "妖魔有り":
        switch(true)
        {
          case ($this->village->nop  >= 16):
            $this->village->rglid = Data::RGL_E;
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
      case "試験壱型":
      case "壱型？":
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
      case "試験弐型": 
      case "多分弐型":
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
      case "Ｃ国":
      case "囁けます":
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
      case "Ｇ国":
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
        break;
      case "呪悪":
      case "とけとけ":
        switch(true)
        {
          case ($this->village->nop  >= 9):
            $this->village->rglid = Data::RGL_CURSE;
            break;
          default:
            $this->village->rglid = Data::RGL_ETC;
            break;
        }
        break;
      case "ノーガード":
        switch(true)
        {
          case ($this->village->nop  >= 11):
            $this->village->rglid = Data::RGL_ETC;
            break;
          case ($this->village->nop <=10 && $this->village->nop >= 8):
            $this->village->rglid = Data::RGL_S_2;
            break;
          default:
            $this->village->rglid = Data::RGL_S_1;
            break;
        }
        break;
    }
  }
  protected function fetch_days()
  {
    $days = trim($this->fetch->find('p.turnnavi',1)->find('a',-1)->href);
    $this->village->days = preg_replace('/.+turn=(\d+)&amp.+/','\1',$days) -1;
  }
  protected function fetch_rp()
  {
    $rp = trim($this->fetch->find('p.multicolumn_left',8)->plaintext);
    if($rp === '人狼BBS')
    {
      $this->village->rp = 'SOW';
    }
    else if(array_key_exists($rp,$this->RP_LIST))
    {
      $this->village->rp = $this->RP_LIST[$rp];
    }
    else
    {
      echo 'NOTICE: '.$this->village->vno.' has undefined RP.'.PHP_EOL;
      $this->village->rp = 'SOW';
    }
  }
  protected function fetch_policy()
  {
    $policy = $this->fetch->find('p.multicolumn_left',1)->plaintext;
    if($policy === "推理あり村")
    {
      $this->village->policy = true;
      //echo $this->village->vno.'.'.$this->village->name.' is guessed GACHI.'.PHP_EOL;
    }
    else
    {
      $this->village->policy = false;
      if(in_array($this->village->vno,$this->GACHI))
      {
        $this->village->policy = true;
      }
      //echo $this->village->vno.'.'.$this->village->name.' is guessed RP.'.PHP_EOL;
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
    $date = $this->fetch->find('div.mes_date',0)->plaintext;
    $date = mb_substr($date,mb_strpos($date,"2"),10);
    $this->village->date = preg_replace('/(\d{4})\/ ?(\d{1,2})\/(\d{2})/','\1-\2-\3',$date);
  }
  protected function fetch_from_epi()
  {
    $url = $this->url.$this->village->vno.'&turn='.$this->village->days.'&row=40&mode=all&move=page&pageno=1';
    $this->fetch->load_file($url);

    $this->fetch_wtmid();
    $this->make_cast();
  }
  protected function fetch_wtmid()
  {
    if(!$this->village->policy)
    {
      $this->village->wtmid = Data::TM_RP;
    }
    else
    {
      $wtmid = $this->fetch_win_message();
      if($this->village->rp === 'RAMDOM')
      {
        $this->village->wtmid = $this->WTM_FOOL[$wtmid];
      }
      else if(array_key_exists($wtmid,$this->{'WTM_'.$this->village->rp}))
      {
        $this->village->wtmid = $this->{'WTM_'.$this->village->rp}[$wtmid];
      }
      else
      {
        echo 'NOTICE: '.$this->village->vno.' has undefined winners message.->'.$wtmid.PHP_EOL;
        $this->village->wtmid = Data::TM_RP;
      }
    }
  }
  protected function fetch_win_message()
  {
    $wtmid = trim($this->fetch->find('div.info',-1)->plaintext);
    if(preg_match("/0に設定されました。|村の設定が変更/",$wtmid))
    {
      $do_i = -2;
      do
      {
        $wtmid = trim($this->fetch->find('p.info',$do_i)->plaintext);
        $do_i--;
      } while(preg_match("/0に設定されました。|村の設定が変更/",$wtmid));
    }
    return mb_substr(preg_replace("/\r\n/","",$wtmid),-10);
  }
  protected function make_cast()
  {
    $cast = $this->fetch->find('tbody tr');
    array_shift($cast);
    $this->cast = $cast;
  }

  protected function fetch_users($person)
  {
    $this->user->persona = trim($person->find('td',0)->plaintext);
    $this->user->player  = $person->find('td a',0)->plaintext;
    $this->fetch_role($person);

    if($this->user->role === '参観者' || $this->user->role === '観てるだけ')
    {
      $this->insert_onlooker();
    }
    else
    {
      $this->fetch_sklid();
      $this->fetch_destiny($person);
      $this->fetch_rltid();
      $this->fetch_life();
    }
    //var_dump($this->user->get_vars());
  }
  protected function insert_onlooker()
  {
    $this->user->sklid  = Data::SKL_ONLOOKER;
    $this->user->tmid = Data::TM_ONLOOKER;
    $this->user->dtid  = Data::DES_ONLOOKER;
    $this->user->end   = 1;
    $this->user->life  = 0.00;
    $this->user->rltid = Data::RSL_ONLOOKER;
  }
  protected function fetch_role($person)
  {
    $role = $person->find('td',3)->plaintext;
    if(preg_match('/\r\n/',$role))
    {
      $this->user->role = mb_ereg_replace('([^\r\n]+)\r\n.+','\1',$role,"m");
    }
    else
    {
      $this->user->role = trim(mb_ereg_replace('(.+)','\1',$role));
    }
  }
  protected function fetch_sklid()
  {
    if($this->village->rp === 'FOOL')
    {
      $this->user->sklid = $this->SKL_FOOL[$this->user->role][0];
      $this->user->tmid = $this->SKL_FOOL[$this->user->role][1];
    }
    else
    {
      $this->user->sklid = $this->SKILL[$this->user->role][0];
      $this->user->tmid = $this->SKILL[$this->user->role][1];
    }
  }
  protected function fetch_destiny($person)
  {
    $destiny = $person->find('td',2)->plaintext;
    $pattern = '/(\d+)日(目に|間を)(.{6}).+/';
    preg_match_all($pattern,$destiny,$matches);
    $this->user->dtid = $this->DESTINY[$matches[3][0]];
    $this->user->end = (int)$matches[1][0];
  }
  protected function fetch_rltid()
  {
    if(!$this->village->policy)
    {
      $this->user->rltid = Data::RSL_JOIN;
      return;
    }
    if($this->user->tmid === $this->village->wtmid)
    {
      $this->user->rltid = Data::RSL_WIN;
    }
    else
    {
      $this->user->rltid = Data::RSL_LOSE;
    }
  }
  protected function fetch_life()
  {
    if($this->user->dtid === Data::DES_ALIVE)
    {
      $this->user->life = 1.00;
    }
    else
    {
      $this->user->life = round(($this->user->end-1) / $this->village->days,2);
    }
  }
}
