<?php
class Rose extends Country
{
  use AR_SOW,TR_SOW,TR_SOW_RGL;
  protected $RP_PRO = [
     'この村にも'=>'SOW'
    ,'なんか人狼'=>'FOOL'
    ,'　村は数十'=>'JUNA'
    ,'昼間は人間'=>'WBBS'
    ,'呼び寄せた'=>'PHANTOM'
    ,'　それはま'=>'DREAM'
    ];
  protected $WTM_PHANTOM = [
     '、人は何を思うのか。'=>Data::TM_VILLAGER
    ,'の先にあるのは……。'=>Data::TM_WOLF
    ,'いていなかった……。'=>Data::TM_FAIRY
  ];
  protected $WTM_DREAM = [
     'が差し込んできます。'=>Data::TM_VILLAGER
    ,'りませんでした……。'=>Data::TM_WOLF
    ,'いていなかった……。'=>Data::TM_FAIRY
  ];
  protected $SKL_SP = [
     "妖狐"=>[Data::SKL_FAIRY,Data::TM_FAIRY]
    ,"天狐"=>[Data::SKL_BAT,Data::TM_FAIRY]
    ,"冥狐"=>[Data::SKL_PIXY,Data::TM_FAIRY]
    ];
  protected $DT_DREAM = [
     'のです……。'=>['.+(\(ランダム投票\)|指さしました。)(.+) は人々の意思により処断されたのです……。',Data::DES_HANGED]
    ,'突然死した。'=>['^( ?)(.+) は、突然死した。',Data::DES_RETIRED]
    ,'発見された。'=>['(.+)朝、 ?(.+) が無残.+',Data::DES_EATEN]
    ,'後を追った。'=>['^( ?)(.+) は(絆に引きずられるように) .+ の後を追った。',Data::DES_SUICIDE]
  ];
  function __construct()
  {
    $cid = 47;
    $url_vil = "http://schicksal.sakura.ne.jp/sow/sow.cgi?vid=";
    $url_log = "http://schicksal.sakura.ne.jp/sow/sow.cgi?cmd=oldlog";
    parent::__construct($cid,$url_vil,$url_log);
    $this->SKILL = array_merge($this->SKILL,$this->SKL_SP);
  }
  protected function fetch_from_pro()
  {
    $url = $this->url.$this->village->vno.'&turn=0&row=10&mode=all&move=page&pageno=1';
    $this->fetch->load_file($url);

    $this->fetch_date();
    $this->fetch_rp();
    $this->fetch->clear();
  }
  protected function fetch_rp()
  {
    $rp = mb_substr($this->fetch->find('p.info',0)->plaintext,1,5);
    if(array_key_exists($rp,$this->RP_PRO))
    {
      $this->village->rp = $this->RP_LIST[$rp];
    }
    else
    {
      echo 'NOTICE: '.$this->village->vno.' has undefined RP.'.PHP_EOL;
      $this->village->rp = 'PHANTOM';
    }
  }
  protected function fetch_policy()
  {
    $this->village->policy = false;
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
      if($this->user->end !== null)
      {
        unset($list[$key]);
      }
      if($this->user->sklid  === Data::SKL_BAPTIST && $this->user->dtid === Data::DES_MARTYR)
      {
        $martyr = true;
      }
    }
    $this->fetch_from_daily($list);
    if(isset($martyr))
    {
      $this->insert_baptist($list);
    }

    foreach($this->users as $user)
    {
      if(!$user->is_valid())
      {
        echo 'NOTICE: '.$user->persona.'could not fetched.'.PHP_EOL;
      }
    }
  }
  protected function fetch_users($person)
  {
    $this->user->persona = trim($person->find('td',0)->plaintext);
    $this->fetch_player($person);
    $this->fetch_role($person);
    $this->user->tmid = $this->TEAM[$person->find('td',3)->plaintext];

    if($this->user->tmid === Data::TM_ONLOOKER)
    {
      $this->insert_onlooker();
    }
    else
    {
      $this->user->dtid = $this->DESTINY[$person->find('td',2)->plaintext];
      $this->fetch_sklid();
      $this->fetch_rltid();
    }

    if($this->user->dtid === Data::DES_ALIVE)
    {
      $this->insert_alive();
      return;
    }
  }
  protected function fetch_role($person)
  {
    $role = $person->find('td',4)->plaintext;
    if(preg_match('/\r\n/',$role))
    {
      $this->user->role = mb_ereg_replace('(.+) \(.+\)\r\n.+','\1',$role);
    }
    else
    {
      $this->user->role = mb_ereg_replace('(.+) \(.+\)(☆勝利|)','\1',$role);
    }
    //死神陣営の勝利者は、役職の後ろに「☆勝利」がつく
    if($this->village->policy && mb_strstr($role,'☆勝利'))
    {
      $this->user->rltid = Data::RSL_WIN;
    }
  }
  protected function fetch_sklid()
  {
    if($this->village->rp === 'FOOL')
    {
      $this->user->sklid = $this->SKL_FOOL[$this->user->role];
    }
    else
    {
      $this->user->sklid = $this->SKILL[$this->user->role];
    }
  }
  protected function fetch_rltid()
  {
    if($this->user->rltid)
    {
      return;
    }

    if(!$this->village->policy)
    {
      $this->user->rltid = Data::RSL_JOIN;
    }
    else if($this->user->tmid === $this->village->wtmid || $this->user->tmid === $this->village->add_winner)
    {
      if($this->user->tmid === Data::TM_EFB)
      {
        //死神陣営で勝利判定が埋まっていない者は敗北扱い
        $this->user->rltid = Data::RSL_LOSE;
      }
      else
      {
        $this->user->rltid = Data::RSL_WIN;
      }
    }
    else
    {
      $this->user->rltid = Data::RSL_LOSE;
    }
  }
  protected function insert_alive()
  {
    $this->user->end = $this->village->days;
    $this->user->life = 1.000;
  }
  protected function fetch_from_daily($list)
  {
    $days = $this->village->days;
    $rp = $this->village->rp;
    $find = 'p.info';
    for($i=2; $i<=$days; $i++)
    {
      $announce = $this->fetch_daily_url($i,$find);
      foreach($announce as $item)
      {
        $destiny = trim($item->plaintext);
        $key= mb_substr($destiny,-6,6);
        $destiny = preg_replace("/\r\n/","",$destiny);
        if($rp === "FOOL")
        {
          if(!isset($this->DT_FOOL[$key]))
          {
            continue;
          }
          else if($key === "ったみたい。")
          {
            echo "NOTICE: day".$i."occured EATEN but cannot find who it is.".PHP_EOL;
            continue;
          }
          else
          {
            $persona = trim(mb_ereg_replace($this->DT_FOOL[$key][0],'\2',$destiny,'m'));
            $key_u = array_search($persona,$list);
            $dtid = $this->DT_FOOL[$key][1];
          }
        }
        else if(!isset($this->DT_NORMAL[$key]))
        {
          continue;
        }
        else
        {
          $persona = trim(mb_ereg_replace($this->DT_NORMAL[$key][0],'\2',$destiny,'m'));
          $key_u = array_search($persona,$list);
          $dtid = $this->DT_NORMAL[$key][1];
        }
        $this->users[$key_u]->end = $i;
        $this->users[$key_u]->life = round(($i-1) / $this->village->days,3);
      }
      $this->fetch->clear();
    }
  }
  protected function insert_baptist($list)
  {
    $days = $this->village->days;
    $find = 'p.infosp';
    for($i=4; $i<=$days; $i++)
    {
      $announce = $this->fetch_daily_url($i,$find);
      foreach($announce as $item)
      {
        $destiny = trim($item->plaintext);
        $key = mb_substr($destiny,-6,6);
        $persona = trim(mb_ereg_replace("(.+) は、.+ を命を引き換えに復活させた。",'\1',$destiny));
        $key_u = array_search($persona,$list);
        if($key_u)
        {
          $this->users[$key_u]->end = $i;
          $this->users[$key_u]->life = round(($i-1) / $this->village->days,3);
        }
      }
      $this->fetch->clear();
    }
  }
}
