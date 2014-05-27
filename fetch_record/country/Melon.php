<?php

class Melon extends SOW
{
  use TRS_Melon,Rgl_Auto;
  function __construct()
  {
    $cid = 26;
    $url_vil = 'http://melon-cirrus.sakura.ne.jp/sow/sow.cgi?m=a&v=';
    $url_log = 'http://melon-cirrus.sakura.ne.jp/sow/sow.cgi?cmd=oldlog';
    parent::__construct($cid,$url_vil,$url_log);
  }
  protected function fetch_rglid()
  {
    $rglid_check = $this->fetch->find('p.multicolumn_right',1)->plaintext;
    $rglid = preg_replace('/^ ([^ ]+) .+/','\1',$rglid_check);
    switch($rglid)
    {
      case "自由設定":
      case "いろいろ":
      case "ごった煮":
      case "オリジナル":
      case "選択科目":
      case "フリーダム":
      case "特殊事件":
      case "特殊業務":
      case "闇鍋":
        $free = trim(mb_substr($rglid_check,mb_strpos($rglid_check,'　')+1));
        $free = preg_replace('/ ＋.+/','',$free);
        if(array_key_exists($free,$this->RGL_FREE))
        {
          $this->village->rglid = $this->RGL_FREE[$free];
        }
        else
        {
          echo $this->village->vno.'->'.$free.PHP_EOL;
          $this->village->rglid = Data::RGL_ETC;
        }
        break;
      case "Ｇ国":
        $this->check_rgl_g($this->village->nop);
        break;
      case "Ｃ国":
      case "ヒソヒソ":
      case "囁けます":
      case "闇の囁鬼":
      case "囁く補佐":
      case "魔術師の愛弟子は暗躍する":
        $this->check_rgl_c($this->village->nop);
        break;
      case "ハム入り":
      case "よーまだ":
      case "妖魔有り":
      case "ハムハム":
      case "飯綱暗躍":
      case "産業間諜":
      case "狐入り":
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
      case "標準":
      case "いつもの":
      case "ふつー":
      case "通常業務":
        $this->check_rgl_f($this->village->nop);
        break;
      case "試験壱型":
      case "しけん１":
      case "壱型？":
      case "聖獣降臨":
      case "業務１課":
      case "テスト１式":
        $this->check_rgl_tes1($this->village->nop);
        break;
      case "試験弐型":
      case "しけん２":
      case "屹度弐型":
      case "猛烈信鬼":
      case "業務２課":
      case "テスト２式":
        $this->check_rgl_tes2($this->village->nop);
        break;
      case "試験参型":
      case "しけん３":
      case "屹度参型":
      case "絶叫狂鬼":
      case "業務３課":
      case "テスト３式":
        switch(true)
        {
          case ($this->village->nop  >= 10):
            $this->village->rglid = Data::RGL_TES3;
            break;
          case ($this->village->nop  === 8 || $this->village->nop  === 9):
            $this->village->rglid = Data::RGL_S_2;
            break;
          default:
            $this->village->rglid = Data::RGL_S_1;
            break;
        }
        break;
      default:
        echo 'NOTICE: '.$this->village->vno.' has unknown regulation.->'.$rglid.PHP_EOL;
        break;
    }
  }
  protected function fetch_rp()
  {
    $rp = $this->fetch->find('p.multicolumn_left',9)->plaintext;
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
  protected function fetch_policy()
  {
    $policy= mb_strstr($this->fetch->find('p.multicolumn_left',-2)->plaintext,'推理');
    if($policy !== false)
    {
      $this->village->policy = true;
    }
    else
    {
      $this->village->policy = false;
      echo $this->village->vno.' is guessed RP.'.PHP_EOL;
    }
  }
  protected function fetch_from_pro()
  {
    $url = $this->url.$this->village->vno.'&t=0&r=10&o=a&mv=p&n=1';
    $this->fetch->load_file($url);

    $this->fetch_date();
    $this->fetch->clear();
  }
  protected function fetch_from_epi()
  {
    $url = $this->url.$this->village->vno.'&t='.$this->village->days.'&row=40&o=a&mv=p&n=1';
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
      //遅刻見物人のシスメなどを除外
      $count_replace = 0;
      preg_replace($this->WTM_SKIP,'',$wtmid,1,$count_replace);
      if($count_replace)
      {
        $do_i = -2;
        do
        {
          $wtmid = trim($this->fetch->find('p.info',$do_i)->plaintext);
          $do_i--;
          preg_replace($this->WTM_SKIP,'',$wtmid,1,$count_replace);
        } while($count_replace);
      }
      $wtmid = preg_replace('/\r\n/','',$wtmid);
      //人狼劇場言い換えのみ、先頭12文字で取得する
      if($this->village->rp === 'THEATER')
      {
        $wtmid = mb_substr($wtmid,0,12);
      }
      else
      {
        $wtmid = mb_substr($wtmid,-10);
      }
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
    $cast = $this->fetch->find('table tr');
    array_shift($cast);
    $count_cast = count($cast);
    //見物人がいるなら見出し分を引く
    if($count_cast !== $this->village->nop)
    {
      $arr_guest = [];
      foreach($cast as $val_guest => $item_guest)
      {
        $guest = $item_guest->find('th',0);
        if($guest)
        {
          $arr_guest[] = $val_guest;
        }
      }
      foreach($arr_guest as $item_guest)
      {
        unset($cast[$item_guest]);
      }
      $count_cast = count($cast);
      //配列の連番を振り直す
      $cast = array_merge($cast);
    }
    $this->cast = $cast;
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
        echo 'NOTICE: '.$this->user->persona.'could not fetched.'.PHP_EOL;
      }
      //エラーでも歯抜けが起きないように入れる
      $this->users[] = $this->user;
    }
  }
  protected function fetch_users($person)
  {
    $this->fetch_persona($person);
    $this->fetch_player($person);
    $this->fetch_role($person);
  }
  protected function fetch_persona($person)
  {
    $this->user->persona = trim($person->find("td",0)->plaintext);
  }
  protected function fetch_player($person)
  {
    $player = trim($person->find("td",1)->plaintext);
    $this->user->player =$this->check_doppel($player);
  }

  protected function fetch_role($person)
  {
    $role = $person->find("td",4)->plaintext;
    $dtid = $person->find("td",3)->plaintext;
    //役職の改行以降をカットする
    $this->user->role = preg_replace('/\r\n.+/s','',$role);
    if($role === '--')
    {
      $this->insert_onlooker_m($dtid);
    }
    else
    {
      $rp = $this->village->rp;
      if(array_search($rp,$this->RP_DEFAULT) !== false)
      {
        $rp = 'SOW';
      }
      //婚約者は元の役職扱いにする
      if(mb_strstr($this->user->role,$this->{'SKL_'.$rp}[25]))
      {
        $sklid = preg_replace('/^.+\((.+)\)/','\1',$this->user->role);
        $this->user->tmid = Data::TM_LOVERS;
      }
      else
      {
        $sklid = preg_replace('/\(.+/','',$this->user->role);
      }
      //能力が登録済かチェック
      $skl_key = array_search($sklid,$this->{'SKL_'.$rp});
      if($skl_key !== false)
      {
        $this->user->sklid = $this->SKILL[$skl_key][0];
        if($this->user->tmid !== Data::TM_LOVERS)
        {
          $this->user->tmid = $this->SKILL[$skl_key][1];
          if($this->user->sklid === Data::SKL_LOVER && preg_match('/(失恋|片思い|孤独)★/',$role))
          {
            $this->user->tmid = Data::TM_VILLAGER;
          }
        }
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
      $this->fetch_dtid($dtid);
      $this->fetch_rltid_m($person);
    }
  }
  protected function insert_onlooker_m($dtid)
  {
    $this->user->dtid = Data::DES_ONLOOKER;
    $this->user->end = 1;
    $this->user->tmid = Data::TM_ONLOOKER;
    $this->user->life = 0.000;
    $this->user->rltid = Data::RSL_ONLOOKER;
    if($dtid === '--')
    {
      $this->user->sklid = Data::SKL_OWNER;
      switch($this->village->rp)
      {
        case 'GB':
          $this->user->role = '旧校舎の主';
          break;
        default:
          $this->user->role = '支配人';
          break;
      }
    }
    else
    {
      $this->user->sklid = Data::SKL_ONLOOKER;
      switch($this->village->rp)
      {
        case 'MELON':
          $this->user->role = 'やじうま';
          break;
        case 'GB':
          $this->user->role = '観客';
          break;
        case 'MOON':
          $this->user->role = 'お客様';
          break;
        default:
          $this->user->role = '見物人';
          break;
      }
    }
  }
  protected function fetch_dtid($dtid)
  {
    if($dtid === '生存')
    {
      $this->user->dtid = Data::DES_ALIVE;
      $this->user->end = $this->village->days;
      $this->user->life = 1.000;
    }
    else
    {
      $this->user->dtid = $this->DESTINY[mb_substr($dtid,mb_strpos($dtid,'d')+1)];
      $this->user->end = (int)mb_substr($dtid,0,mb_strpos($dtid,'d'));
      $this->user->life = round(($this->user->end-1) / $this->village->days,3);
    }
  }
  protected function fetch_rltid_m($person)
  {
    if(!$this->village->policy)
    {
      $this->user->rltid = Data::RSL_JOIN;
    }
    else if($this->user->player !== "master" && $this->user->dtid === Data::DES_EATEN && $this->user->end === 2)
    {
      //喋るダミー(IDがmasterではない)は参加扱いにする
      $this->user->rltid = Data::RSL_JOIN;
    }
    else
    {
      $this->user->rltid = $this->RSL[$person->find("td",2)->plaintext];
    }
  }
}
