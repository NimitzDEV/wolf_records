<?php
class Phantom extends SOW
{
  use TRS_SOW;
  private  $is_ruined
          ,$rgl_name;
  protected $RP_PRO = [
     'この村にも'=>'SOW'
    ,'なんか人狼'=>'FOOL'
    ,'　村は数十'=>'JUNA'
    ,'昼間は人間'=>'WBBS'
    ,'呼び寄せた'=>'PHANTOM'
    ,'　それはま'=>'DREAM'
    ];
  protected $WTM_RUINED = [
     'SOW'    =>'もう人影はない……。'
    ,'FOOL'   =>'て誰もいなくなった。'
    ,'JUNA'   =>'が忽然と姿を消した。'
    ,'WBBS'   =>'が忽然と姿を消した。'
    ,'PHANTOM'=>'らぬ静けさのみ……。'
    ,'DREAM'  =>'、静かな朝です……。'
    ];
  protected $SKL_SP = [
     "妖狐"=>[Data::SKL_FAIRY,Data::TM_FAIRY]
    ,"天狐"=>[Data::SKL_BAT,Data::TM_FAIRY]
    ,"冥狐"=>[Data::SKL_PIXY,Data::TM_FAIRY]
    ,"幻魔"=>[Data::SKL_PIXY,Data::TM_FAIRY]
    ,"--"=>[Data::SKL_NULL,Data::TM_NONE]
    ];
  protected $DT_DREAM = [
     'のです……。'=>['.+(\(ランダム投票\)|指差しました。)(.+) は人々の意思により処断されたのです……。',Data::DES_HANGED]
    ,'突然死した。'=>['^( ?)(.+) は、突然死した。',Data::DES_RETIRED]
    ,'されました。'=>['(.+)朝、 ?(.+) が無残.+',Data::DES_EATEN]
    ,'後を追った。'=>['^( ?)(.+) は(絆に引きずられるように) .+ の後を追った。',Data::DES_SUICIDE]
  ];
  function __construct()
  {
    $cid = 47;
    $url_vil = "http://schicksal.sakura.ne.jp/sow/sow.cgi?vid=";
    $url_log = "http://schicksal.sakura.ne.jp/sow/sow.cgi?cmd=oldlog";
    parent::__construct($cid,$url_vil,$url_log);
    $this->SKILL = array_merge($this->SKILL,$this->SKL_SP);
    $this->policy = false;
  }
  protected function fetch_from_info()
  {
    $this->rgl_name = '';
    $this->is_ruined = false;
    $this->fetch->load_file($this->url.$this->village->vno."&cmd=vinfo");

    $this->fetch_name();
    $this->fetch_nop();
    $this->fetch_days();
    $this->fetch_rgl_name();

    $this->fetch->clear();
  }
  protected function fetch_rgl_name()
  {
    $this->rgl_name = trim($this->fetch->find('p.multicolumn_right',1)->plaintext);
  }
  protected function fetch_rglid()
  {
    $patterns = ['/.+\r\n （(.+)）/','/([^ ]+): (\d+)人 /'];
    $replaces = ['\1','\1x\2 '];
    $rglid = trim(preg_replace($patterns,$replaces,$this->rgl_name));
    $this->find_rglid($rglid);
  }
  protected function fetch_from_pro()
  {
    $url = $this->url.$this->village->vno.'&turn=0&row=10&mode=all&move=page&pageno=1';
    $this->fetch->load_file($url);

    $this->fetch_date();
    $this->fetch_rp();
    $this->fetch_rglid();
    $this->fetch->clear();
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
      $this->village->rp = 'PHANTOM';
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
    $wtmid = $this->fetch_win_message();
    if($wtmid === $this->WTM_RUINED[$this->village->rp])
    {
      $this->is_ruined = true;
    }
    $this->village->wtmid = Data::TM_RP;
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
      if($this->is_ruined)
      {
        //廃村村はリストを作らない
        continue;
      }
      //生存者を除く名前リストを作る
      $list[] = $this->user->persona;
      if($this->user->end !== null)
      {
        unset($list[$key]);
      }
    }
    if($this->is_ruined === false)
    {
      $this->fetch_from_daily($list);
    }

    foreach($this->users as $user)
    {
      if(!$user->is_valid())
      {
        echo 'NOTICE: '.$user->persona.'could not fetched.'.PHP_EOL;
      }
    }
  }
  protected function fetch_role($person)
  {
    $role = $person->find('td',3)->plaintext;
    if(mb_ereg_match("\A.+を希望\z",$role))
    {
      $this->user->role = '--';
    }
    else
    {
      $this->user->role = mb_ereg_replace('\A(.+) \(.+\)(.+|)','\1',$role,'m');
    }
  }
  protected function fetch_from_daily($list)
  {
    $days = $this->village->days;
    $rp = $this->village->rp;
    if($rp !== 'FOOL' && $rp !== 'DREAM')
    {
      $rp = 'NORMAL';
    }
    $find = 'p.info';
    for($i=2; $i<=$days; $i++)
    {
      $announce = $this->fetch_daily_url($i,$find);
      foreach($announce as $item)
      {
        $destiny = trim(preg_replace("/\r\n/",'',$item->plaintext));
        $key= mb_substr(trim($item->plaintext),-6,6);
        if($rp === 'FOOL')
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
        else if(!isset($this->{'DT_'.$rp}[$key]))
        {
          continue;
        }
        else
        {
          $persona = trim(mb_ereg_replace($this->{'DT_'.$rp}[$key][0],'\2',$destiny,'m'));
          $key_u = array_search($persona,$list);
          $dtid = $this->{'DT_'.$rp}[$key][1];
        }
        //妖魔陣営の無残死は呪殺死にする
        if($this->users[$key_u]->tmid === Data::TM_FAIRY && $dtid === Data::DES_EATEN)
        {
          $this->users[$key_u]->dtid = Data::DES_CURSED;
        }
        //呪狼が存在する編成で、占い師が襲撃された場合別途チェック
        else if(!empty($this->cursewolf) && $this->users[$key_u]->sklid === Data::SKL_SEER && $dtid === Data::DES_EATEN && $this->check_cursed_seer($persona,$key_u))
        {
          $this->users[$key_u]->dtid = Data::DES_CURSED;
        }
        else
        {
          $this->users[$key_u]->dtid = $dtid;
        }
        $this->users[$key_u]->end = $i;
        $this->users[$key_u]->life = round(($i-1) / $this->village->days,3);
      }
      $this->fetch->clear();
    }
  }
  protected function check_cursed_seer($persona,$key_u)
  {
    if($this->village->rp === 'DREAM')
    {
      $dialog = 'みました。';
      $pattern = '　 ?(.+) は、(.+) を詠みました。';
    }
    else
    {
      $dialog = 'を占った。';
      $pattern = ' ?(.+) は、(.+) を占った。';
    }

    $announce = $this->fetch->find('p.infosp');
    foreach($announce as $item)
    {
      $info = trim($item->plaintext);
      $key= mb_substr($info,-5,5);
      if($key === $dialog)
      {
        $seer = trim(mb_ereg_replace($pattern,'\1',$info,'m'));
        $wolf = trim(mb_ereg_replace($pattern,'\2',$info,'m'));
        if($seer === $persona && in_array($wolf,$this->cursewolf))
        {
          return true;
        }
        else
        {
          continue;
        }
      }
    }
    return false;
  }
}
