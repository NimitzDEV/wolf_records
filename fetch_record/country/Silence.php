<?php

class Silence extends SOW
{
  use TRS_SOW;
  protected $RP_PRO = [
     'この村にも'=>'SOW'
    ,'昼間は人間'=>'WBBS'
    ,'あいさつす'=>'PO'
    ];
  protected $WTM_PO = [
     'ーんはぽぽぽぽーん！'=>Data::TM_VILLAGER
    ,'CMを去っていった。'=>Data::TM_WOLF
    ,'がおはよウナギ……。'=>Data::TM_FAIRY
    ,'の村を去っていった。'=>Data::TM_LOVERS
  ];
  protected $SKL_PO = [
     "たのしいなかま"=>[Data::SKL_VILLAGER,Data::TM_VILLAGER]
    ,"ぽぽぽぽーん"=>[Data::SKL_WOLF,Data::TM_WOLF]
    ,"おやすみなサイ"=>[Data::SKL_SEER,Data::TM_VILLAGER]
    ,"ただいマンボウ"=>[Data::SKL_MEDIUM,Data::TM_VILLAGER]
    ,"あいさつ坊や"=>[Data::SKL_LUNATIC,Data::TM_WOLF]
    ,"スタッフ"=>[Data::SKL_HUNTER,Data::TM_VILLAGER]
    ,"AC"=>[Data::SKL_MASON,Data::TM_VILLAGER]
    ,"いただきマウス"=>[Data::SKL_FAIRY,Data::TM_FAIRY]
    ,"あいさつガール"=>[Data::SKL_LUNAWHS,Data::TM_WOLF]
    ,"ごちそうさマウス"=>[Data::SKL_PIXY,Data::TM_FAIRY]
    ,"ありがとウサギ"=>[Data::SKL_QP,Data::TM_LOVERS]
  ];
  protected $DT_PO = [
     '挨殺された。'=>['.+(\(ランダムあいさつ\)|あいさつした。)(.+) はたのしいなかま達に挨殺された。',Data::DES_HANGED]
    ,'突然死した。'=>['\A( ?)(.+) は、突然死した。',Data::DES_RETIRED]
    ,'ち亡くすね。'=>['(.+)朝、(.+) の首がぽぽぽ.+',Data::DES_EATEN]
    ,'後を追った。'=>['\A( ?)(.+) は(民間の広告ネットワークに引きずられるように|感謝の気持ちを込めて) .+ の後を追った。',Data::DES_SUICIDE]
  ];
  function __construct()
  {
    $cid = 35;
    $url_vil = "http://silence.hotcom-web.com/cgi-bin/sow/sow.cgi?vid=";
    $url_log = "http://silence.hotcom-web.com/cgi-bin/sow/sow.cgi?cmd=oldlog";
    parent::__construct($cid,$url_vil,$url_log);
    $this->policy = true;
  }
  protected function fetch_from_info()
  {
    $this->rgl_name = '';
    $this->fetch->load_file($this->url.$this->village->vno."&cmd=vinfo");

    $this->fetch_name();
    $this->fetch_nop();
    $this->fetch_days();
    $this->fetch_rgl_name();

    $this->fetch->clear();
  }
  protected function fetch_name()
  {
    $this->village->name = $this->fetch->find('table.list tr td',1)->plaintext;
  }
  protected function fetch_nop()
  {
    $nop = $this->fetch->find('table.list tr',2)->find('td',1)->plaintext;
    $this->village->nop = (int)preg_replace('/(\d+)人.+/','\1',$nop);
  }
  protected function fetch_rgl_name()
  {
    $this->rgl_name = trim($this->fetch->find('table.list tr',5)->find('td',1)->plaintext);
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
  protected function fetch_rglid()
  {
    $patterns = ['/.+\r\n （(.+)）/','/([^ ]+): (\d+)人 /'];
    $replaces = ['\1','\1x\2 '];
    $rglid = trim(preg_replace($patterns,$replaces,$this->rgl_name));
    $rglid = mb_ereg_replace('今週のトイレ当番','共有者',$rglid);
    $this->find_rglid($rglid);
  }
  protected function fetch_days()
  {
    $days = trim($this->fetch->find('p',0)->find('a',-4)->innertext);
    $this->village->days = mb_substr($days,0,mb_strpos($days,'日')) +1;
  }
  protected function fetch_rp()
  {
    $rp = mb_substr($this->fetch->find('div.announce',0)->plaintext,0,5);
    if(array_key_exists($rp,$this->RP_PRO))
    {
      $this->village->rp = $this->RP_PRO[$rp];
    }
    else
    {
      echo 'NOTICE: '.$this->village->vno.' has undefined RP.'.PHP_EOL;
      $this->village->rp = 'DARK';
    }
  }
  protected function fetch_win_message()
  {
    $wtmid = trim($this->fetch->find('div.announce',-1)->plaintext);
    if(preg_match("/村の更新日が延長|村の設定が変更/",$wtmid))
    {
      $do_i = -2;
      do
      {
        $wtmid = trim($this->fetch->find('div.announce',$do_i)->plaintext);
        $do_i--;
      } while(preg_match("/村の更新日が延長|村の設定が変更/",$wtmid));
    }
    return mb_substr(preg_replace("/\r\n/","",$wtmid),-10);
  }
  protected function make_cast()
  {
    $cast = $this->fetch->find('table tr');
    array_shift($cast);
    $this->cast = $cast;
  }
  protected function fetch_users($person)
  {
    $this->user->persona = trim($person->find('td',0)->plaintext);
    $this->fetch_player($person);
    $this->fetch_role($person);
    $this->fetch_rltid();

    if($person->find('td',2)->plaintext === '生存')
    {
      $this->insert_alive();
    }
  }
  protected function fetch_role($person)
  {
    $role = $person->find('td',3)->plaintext;
    $this->user->role = mb_ereg_replace('\A(.+) \(.+\)(.+|)','\1',$role,'m');

    if($this->village->rp === 'PO')
    {
      $this->user->sklid = $this->SKL_PO[$this->user->role][0];
      $this->user->tmid = $this->SKL_PO[$this->user->role][1];
    }
    else
    {
      $this->user->sklid = $this->SKILL[$this->user->role][0];
      $this->user->tmid = $this->SKILL[$this->user->role][1];
    }

    if(preg_match('/恋人/',$role))
    {
      $this->user->tmid = Data::TM_LOVERS;
    }
  }
  protected function fetch_from_daily($list)
  {
    $days = $this->village->days;
    $find = 'div.announce';
    for($i=2; $i<=$days; $i++)
    {
      $announce = $this->fetch_daily_url($i,$find);
      foreach($announce as $item)
      {
        $destiny = trim(preg_replace("/\r\n/",'',$item->plaintext));
        $key= mb_substr(trim($item->plaintext),-6,6);
        if($this->village->rp === 'PO')
        {
          if(!isset($this->DT_PO[$key]))
          {
            continue;
          }
          else
          {
            $persona = trim(mb_ereg_replace($this->DT_PO[$key][0],'\2',$destiny,'m'));
            $key_u = array_search($persona,$list);
            $dtid = $this->DT_PO[$key][1];
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
        $this->users[$key_u]->life = round(($i-1) / $this->village->days,3);
      }
      $this->fetch->clear();
    }
  }
}
