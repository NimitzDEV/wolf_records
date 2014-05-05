<?php
class Dark extends Country
{
  use AR_SOW,TR_SOW_RGL;
  protected $RP_PRO = [
     '平和なはず'=>'DARK'
    ,'　君はある'=>'ONE'
    ,'何家族かが'=>'WHITE'
    ,'――その旅'=>'SHIP'
    ,'人があまり'=>'IMMORTAL'
    ,'この村にも'=>'SOW'
    ,'なんか人狼'=>'FOOL'
    ,'　村は数十'=>'JUNA'
    ,'昼間は人間'=>'WBBS'
    ];
  protected $WTM_DARK= [
     'だ…さし当たっては。'=>Data::TM_VILLAGER
    ,'た…さし当たっては。'=>Data::TM_WOLF
    ,'めて気付かされた…。'=>Data::TM_FAIRY
    ,'ないままであった…。'=>Data::TM_FAIRY
  ];
  protected $WTM_ONE= [
     'の悪夢と戦いながら。'=>Data::TM_VILLAGER
    ,'帰還を遂げるだろう。'=>Data::TM_WOLF
    ,'かへと去っていった。'=>Data::TM_FAIRY
  ];
  protected $WTM_WHITE= [
     'っ子に勝利したのだ！'=>Data::TM_VILLAGER
    ,'えて笑い続けた……。'=>Data::TM_WOLF
    ,'日々が始まった……。'=>Data::TM_FAIRY
    ,'にされてしまった……'=>Data::TM_FAIRY
  ];
  protected $WTM_DARK= [
     'だ…さし当たっては。'=>Data::TM_VILLAGER
    ,'た…さし当たっては。'=>Data::TM_WOLF
    ,'めて気付かされた…。'=>Data::TM_FAIRY
    ,'ないままであった…。'=>Data::TM_FAIRY
  ];
  protected $SKL_SP = [
     "町人"=>[Data::SKL_VILLAGER,Data::TM_VILLAGER]
    ,"憑かれた人"=>[Data::SKL_LINEAGE,Data::TM_VILLAGER]
    ,"悟られ狂人"=>[Data::SKL_SUSPECT,Data::TM_WOLF]
    ,"邪教徒"=>[Data::SKL_WOLF,Data::TM_WOLF]
    ,"報告者"=>[Data::SKL_MASON,Data::TM_VILLAGER]
    ,"古きもの"=>[Data::SKL_FAIRY,Data::TM_FAIRY]
    ,"入信者"=>[Data::SKL_LUNAWHS,Data::TM_WOLF]
    ,"協力者"=>[Data::SKL_STIGMA,Data::TM_VILLAGER]
    ,"感応者"=>[Data::SKL_TELEPATH,Data::TM_VILLAGER]
    ,"古きもの・古代種"=>[Data::SKL_BAT,Data::TM_FAIRY]
    ,"醜き眷族"=>[Data::SKL_CURSEWOLF,Data::TM_WOLF]
    ,"忌まわしき眷族"=>[Data::SKL_WISEWOLF,Data::TM_WOLF]
    ,"古きもの・戦闘種"=>[Data::SKL_PIXY,Data::TM_FAIRY]
    ];
  protected $SKL_ONE = [
     "村人"=>[Data::SKL_VILLAGER,Data::TM_VILLAGER]
    ,"邪教徒"=>[Data::SKL_WOLF,Data::TM_WOLF]
    ,"占い師"=>[Data::SKL_SEER,Data::TM_VILLAGER]
    ,"霊能者"=>[Data::SKL_MEDIUM,Data::TM_VILLAGER]
    ,"狂人"=>[Data::SKL_LUNATIC,Data::TM_WOLF]
    ,"守護者"=>[Data::SKL_HUNTER,Data::TM_VILLAGER]
    ,"報告者"=>[Data::SKL_MASON,Data::TM_VILLAGER]
    ,"古きもの"=>[Data::SKL_FAIRY,Data::TM_FAIRY]
    ,"入信者"=>[Data::SKL_LUNAWHS,Data::TM_WOLF]
    ,"協力者"=>[Data::SKL_STIGMA,Data::TM_VILLAGER]
    ,"狂信者"=>[Data::SKL_FANATIC,Data::TM_WOLF]
    ,"感応者"=>[Data::SKL_TELEPATH,Data::TM_VILLAGER]
    ,"古きもの・古代種"=>[Data::SKL_BAT,Data::TM_FAIRY]
    ,"醜き眷族"=>[Data::SKL_CURSEWOLF,Data::TM_WOLF]
    ,"忌まわしき眷族"=>[Data::SKL_WISEWOLF,Data::TM_WOLF]
    ,"古きもの・戦闘種"=>[Data::SKL_PIXY,Data::TM_FAIRY]
    ,"憑かれた人"=>[Data::SKL_LINEAGE,Data::TM_VILLAGER]
    ,"悟られ狂人"=>[Data::SKL_SUSPECT,Data::TM_WOLF]
    ];
  protected $SKL_WHITE = [
     "良い子"=>[Data::SKL_VILLAGER,Data::TM_VILLAGER]
    ,"悪戯っ子"=>[Data::SKL_WOLF,Data::TM_WOLF]
    ,"弱虫"=>[Data::SKL_SEER,Data::TM_VILLAGER]
    ,"学級委員"=>[Data::SKL_MEDIUM,Data::TM_VILLAGER]
    ,"ミーハー"=>[Data::SKL_LUNATIC,Data::TM_WOLF]
    ,"カミナリさん"=>[Data::SKL_HUNTER,Data::TM_VILLAGER]
    ,"担任"=>[Data::SKL_MASON,Data::TM_VILLAGER]
    ,"裏番"=>[Data::SKL_FAIRY,Data::TM_FAIRY]
    ,"面白がり"=>[Data::SKL_LUNAWHS,Data::TM_WOLF]
    ,"すご担"=>[Data::SKL_STIGMA,Data::TM_VILLAGER]
    ];
  protected $DESTINY = [
     "突死"=>Data::DES_RETIRED
    ,"処刑"=>Data::DES_HANGED
    ,"襲撃"=>Data::DES_EATEN
    ,"呪殺"=>Data::DES_CURSED
    ,"逆呪"=>Data::DES_CURSED
    ,"連死"=>Data::DES_SUICIDE
    ];
  function __construct()
  {
    $cid = 48;
    $url_vil = "http://wolf.nacht.jp/sw/?vid=";
    $url_log = "http://wolf.nacht.jp/sw/?cmd=oldlog";
    parent::__construct($cid,$url_vil,$url_log);
  }
  function fetch_village()
  {
    $this->fetch_from_info();
    $this->fetch_from_pro();
    $this->fetch_from_epi();
  }
  protected function fetch_from_info()
  {
    $this->fetch->load_file($this->url.$this->village->vno."&cmd=vinfo");

    $this->fetch_name();
    $this->fetch_nop();
    $this->fetch_rglid();
    $this->fetch_days();

    $this->village->policy = true;

    $this->fetch->clear();
  }
  protected function fetch_name()
  {
    $this->village->name = $this->fetch->find('p.multicolumn_left',0)->plaintext;
  }
  protected function fetch_nop()
  {
    $nop = $this->fetch->find('p.multicolumn_left',1)->plaintext;
    $this->village->nop = (int)preg_replace('/(\d+)人.+/','\1',$nop);
  }
  protected function fetch_rglid()
  {
    $rgl_base = trim($this->fetch->find('p.multicolumn_right',-1)->plaintext);
    $rglid = preg_replace('/\r\n.+/','',$rgl_base);
    switch($rglid)
    {
      case "自由設定":
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
    }
  }
  protected function fetch_days()
  {
    $days = trim($this->fetch->find('p.turnnavi',0)->find('a',-4)->innertext);
    $this->village->days = mb_substr($days,0,mb_strpos($days,'日')) +1;
  }
  protected function fetch_from_pro()
  {
    $url = $this->url.$this->village->vno.'&turn=0&row=10&mode=all&move=page&pageno=1';
    $this->fetch->load_file($url);

    $this->fetch_date();
    $this->fetch_rp();
    $this->fetch->clear();
  }
  protected function fetch_date()
  {
    $date = $this->fetch->find('td.time_info span',0)->plaintext;
    $date = mb_substr($date,0,10);
    $this->village->date = preg_replace('/(\d{4})\/(\d{2})\/(\d{2})/','\1-\2-\3',$date);
  }
  protected function fetch_rp()
  {
    $rp = mb_substr($this->fetch->find('p.info',0)->plaintext,1,5);
    if(array_key_exists($rp,$this->RP_PRO))
    {
      $this->village->rp = $this->RP_PRO[$rp];
    }
    else
    {
      echo 'NOTICE: '.$this->village->vno.' has undefined RP.'.PHP_EOL;
      $this->village->rp = 'BW';
    }
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
  protected function fetch_win_message()
  {
    $wtmid = trim($this->fetch->find('p.info',-1)->plaintext);
    if(preg_match("/村の更新日が延長|村の設定が変更/",$wtmid))
    {
      $do_i = -2;
      do
      {
        $wtmid = trim($this->fetch->find('p.info',$do_i)->plaintext);
        $do_i--;
      } while(preg_match("/村の更新日が延長|村の設定が変更/",$wtmid));
    }
    return mb_substr(preg_replace("/\r\n/","",$wtmid),-10);
  }

  protected function make_cast()
  {
    $cast = $this->fetch->find('table.castlist tbody tr');
    array_shift($cast);
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
      $this->users[] = $this->user;
    }
  }
  protected function fetch_users($person)
  {
    $this->user->persona = trim($person->find('td',0)->plaintext);
    $this->fetch_player($person);
    $this->fetch_role($person);
    $this->fetch_end($person);
    $this->fetch_sklid();
    $this->fetch_rltid();
  }
  protected function fetch_player($person)
  {
    $player =trim($person->find("td a",0)->plaintext);
    if(isset($this->d_BW))
    {
      $this->user->player =$this->check_doppel($player);
    }
    else
    {
      $this->user->player = $player;
    }
  }
  protected function fetch_role($person)
  {
    $role = $person->find('td',4)->plaintext;
    $this->user->role = mb_ereg_replace('\A(.+) \(.+\)(.+|)','\1',$role,'m');
  }
  protected function fetch_end($person)
  {
    $destiny = trim($person->find('td',3)->plaintext);
    if($destiny === '生存')
    {
      $this->user->dtid = Data::DES_ALIVE;
      $this->user->end = $this->village->days;
      $this->user->life = 1.000;
    }
    else
    {
      $this->user->dtid = $this->DESTINY[mb_ereg_replace('\d+d(.+)','\1',$destiny)];
      $this->user->end = (int)mb_ereg_replace('(\d+)d.+','\1',$destiny);
      $this->user->life = round(($this->user->end-1) / $this->village->days,3);
    }
  }
  protected function fetch_sklid()
  {
    if($this->village->rp === 'CLOCK')
    {
      $this->user->sklid = $this->SKL_CLOCK[$this->user->role][0];
      $this->user->tmid = $this->SKL_CLOCK[$this->user->role][1];
    }
    else
    {
      $this->user->sklid = $this->SKILL[$this->user->role][0];
      $this->user->tmid = $this->SKILL[$this->user->role][1];
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
}
