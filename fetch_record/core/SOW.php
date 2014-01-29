<?php

abstract class SOW extends Country
{
  use AR_SOW,TR_SOW_RGL;

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
    $nop = $this->fetch->find('p.multicolumn_left',1)->plaintext;
    $this->village->nop = (int)mb_substr($nop,0,mb_strpos($nop,'人'));
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
          echo $this->village->vno.' has '.$free.PHP_EOL;
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
    $rp = trim($this->fetch->find('p.multicolumn_left',7)->plaintext);
    if(array_key_exists($rp,$this->RP_LIST))
    {
      $this->village->rp = $this->RP_LIST[$rp];
    }
    else
    {
      echo $this->village->vno.' has undefined RP.'.PHP_EOL;
      $this->village->rp = 'SOW';
    }
  }
  abstract protected function fetch_policy();

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
    if(!$this->village->policy)
    {
      $this->village->wtmid = Data::TM_RP;
    }
    else
    {
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
      $wtmid = mb_substr(preg_replace("/\r\n/","",$wtmid),-10);
      if(array_key_exists($wtmid,$this->{'WTM_'.$this->village->rp}))
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
  protected function make_cast()
  {
    $cast = $this->fetch->find('tbody tr');
    array_shift($cast);
    $this->cast = $cast;
  }

  protected function insert_users()
  {
    $list = [];
    $this->users = [];
    foreach($this->cast as $key=>$person)
    {
      $this->user = new User();
      $this->fetch_users($person);
      $this->users[] = $this->user;
      //生存者を除く名前リストを作る
      $list[] = $this->user->persona;
      if($this->user->dtid === Data::DES_ALIVE)
      {
        unset($list[$key]);
      }
    }
    $this->fetch_from_daily($list);
    $this->fetch_life();

    foreach($this->users as $user)
    {
      //var_dump($user->get_vars());
      if(!$user->is_valid())
      {
        echo 'NOTICE: '.$user->persona.'could not fetched.'.PHP_EOL;
      }
    }
    //exit;
  }
  protected function fetch_users($person)
  {
    $this->user->persona = trim($person->find('td',0)->plaintext);
    $this->user->player  = $person->find('td a',0)->plaintext;
    $this->fetch_role($person);
    $this->fetch_sklid();
    $this->fetch_rltid();

    if($person->find('td',2)->plaintext === '生存')
    {
      $this->user->dtid = Data::DES_ALIVE;
      $this->user->end = $this->village->days;
      $this->user->life = 1.00;
    }
  }
  protected function fetch_role($person)
  {
    $role = $person->find('td',3)->plaintext;
    if(preg_match('/\r\n/',$role))
    {
      $this->user->role = mb_ereg_replace('(.+) \(.+\)\r\n.+','\1',$role);
    }
    else
    {
      $this->user->role = mb_ereg_replace('(.+) \(.+\)','\1',$role);
    }

  }
  protected function fetch_sklid()
  {
    $sklid = $this->user->role;
    $rp = $this->village->rp;
    if($rp === 'WBBS')
    {
      $rp = 'SOW';
      $this->village->rp = 'SOW';
    }
      $skl_key = array_search($sklid,$this->{'SKL_'.$rp});
      if($skl_key !== false)
      {
        $this->user->sklid = $this->SKILL[$skl_key][0];
        $this->user->tmid = $this->SKILL[$skl_key][1];
      }
      else if(mb_strstr($sklid,$this->{'SKL_'.$rp}[6]))
      {
        //聖痕者
        $this->user->sklid = $this->SKILL[6][0];
        $this->user->tmid = $this->SKILL[6][1];
      }
      else
      {
        //未定義の役職
        echo 'NOTICE: '.$this->village->vno.'.'.$this->user->persona.' has undefined role.->'.$sklid.PHP_EOL;
        $this->user->sklid = $this->SKILL[0][0];
        $this->user->tmid = $this->SKILL[0][1];
      }
  }
  protected function fetch_rltid()
  {
    if($this->user->tmid === $this->village->wtmid)
    {
      $this->user->rltid = Data::RSL_WIN;
    }
    else
    {
      $this->user->rltid = Data::RSL_LOSE;
    }
  }
  protected function fetch_from_daily($list)
  {
    $days = $this->village->days;
    $rp = $this->village->rp;
    $row = 30;
    for($i=2; $i<=$days; $i++)
    {
      $url = $this->url.$this->village->vno.'&turn='.$i.'mode=all&move=page&pageno=1&row='.$row;
      $this->fetch->load_file($url);
      $announce = $this->fetch->find('p.info');
      //処刑以降が取れてなさそうな場合はログ件数を増やす
      if(count($announce) <= 1)
      {
        do
        {
          $row += 10;
          $url = $this->url.$this->village->vno.'&turn='.$i.'mode=all&move=page&pageno=1&row='.$row;
          $this->fetch->load_file($url);
          $announce = $this->fetch->find('p.info');
        } while (count($announce) <= 1);
      }
      foreach($announce as $item)
      {
        $destiny = trim(preg_replace("/\r\n/",'',$item->plaintext));
        $key= mb_substr(trim($item->plaintext),-6,6);
        if(isset($this->{'DT_'.$rp}[$key]))
        {
            if($rp === "FOOL" && $key === "ったみたい。")
            {
              echo "NOTICE: day".$i."occured EATEN but cannot find who it is.".PHP_EOL;
              continue;
            }
            else
            {
              $persona = mb_ereg_replace($this->{'DT_'.$rp}[$key][0],'\1',$destiny,'m');
              $key_u = array_search($persona,$list);
              $dtid = $this->{'DT_'.$rp}[$key][1];
            }
          //妖魔陣営の無残死は呪殺死にする
          if($this->users[$key_u]->tmid === Data::TM_FAIRY && $dtid === Data::DES_EATEN)
          {
            $this->users[$key_u]->dtid = Data::DES_CURSED;
          }
          else
          {
            $this->users[$key_u]->dtid = $dtid;
          }
          $this->users[$key_u]->end = $i;
        }
      }
      $this->fetch->clear();
    }
  }
  protected function fetch_life()
  {
    foreach($this->users as $key=>$user)
    {
      if(!$this->users[$key]->life)
      {
        $life = round(($this->users[$key]->end-1) / $this->village->days,2);
        if($life < 0)
        {
          $this->users[$key]->life = null;
          echo "NOTICE: ".$this->users[$key]->persona." life is minus. fix it to null.".PHP_EOL;
        }
        else
        {
          $this->users[$key]->life = $life;
        }
      }
    }
  }
}
