<?php

class Crescent extends Giji_Old
{
  use AR_Crescent;
  protected $SKL_SP = 
  [
     "テルテル"=>Data::SKL_TERU
    ,"求愛者"=>Data::SKL_COURTSHIP
  ];
  protected $TM_SP = 
  [
     "テルテル"=>Data::TM_TERU
    ,"物干し"=>Data::TM_TERU
    ,"悪霊"=>Data::TM_YANDERE
  ];
  protected $RP_SP = 
  [
     "蒼い三日月"=>'BM'
    ,"変態BBS"=>'HENTAI'
    ,"ＧＭイラットのクロサー"=>'CLOSED'
  ];
  function __construct()
  {
    $cid = 52;
    $url_vil = "http://www.moonpupa.jp/wolf/sow/sow.cgi?vid=";
    $url_log = "http://www.moonpupa.jp/wolf/sow/sow.cgi?cmd=oldlog";
    parent::__construct($cid,$url_vil,$url_log);
    $this->is_evil = true;
    $this->SKILL = array_merge($this->SKILL,$this->SKL_SP);
    $this->TEAM = array_merge($this->TEAM,$this->TM_SP);
  }

  protected function fetch_nop()
  {
    $nop = $this->fetch->find('p.multicolumn_left',6)->plaintext;
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

    //人狼BBS設定のみ標準編成がF
    $rp = trim($this->fetch->find('dl.mes_text_report dt',0)->plaintext);
    if($rp === '人狼BBS')
    {
      $rglid = '人狼BBS F国';
    }

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
    if($this->is_evil)
    {
      $this->check_evil_rgl();
    }
  }

  protected function fetch_date()
  {
    $date = $this->fetch->find('div.mes_date',0)->plaintext;
    $date = mb_substr($date,mb_strpos($date,"2"),10);
    $this->village->date = preg_replace('/(\d{4})\/(\d{2})\/(\d{2})/','\1-\2-\3',$date);
  }
  protected function fetch_wtmid()
  {
    if($this->village->policy)
    {
      $wtmid = trim($this->fetch->find('div.info',-1)->plaintext);
      if(preg_match("/村の更新日が延長されました/",$wtmid))
      {
        $do_i = -2;
        do
        {
          $wtmid = trim($this->fetch->find('div.info',$do_i)->plaintext);
          $do_i--;
        } while(preg_match("/村の更新日が延長されました/",$wtmid));
      }

      //照・据え膳勝利メッセージがあったら削除
      $wtmid = mb_ereg_replace('そして、天に召された魚料理.+|そして、お日様をたっぷり浴びた.+|明日の遠足、大丈夫かなあ。','',$wtmid,'m');
      //特定の言い換えだけ取得文字部分を変更
      if($this->village->rp === 'BM' || $this->village->rp === 'CLOSED')
      {
        $wtmid = mb_substr(preg_replace("/\r\n/","",$wtmid),-6);
      }
      else
      {
        $wtmid = mb_substr(preg_replace("/\r\n/","",$wtmid),2,13);
      }

      if($this->village->rp !== 'NORMAL')
      {
        $this->village->wtmid = $this->{'WTM_'.$this->village->rp}[$wtmid];
      }
      else
      {
        $this->village->wtmid = $this->WTM[$wtmid];
      }
    }
    else
    {
      $this->village->wtmid = Data::TM_RP;
    }
  }
  protected function fetch_users($person)
  {
    $this->fetch_persona($person);
    $this->fetch_player($person);

    $role = trim($person->find('td',4)->plaintext);
    if(mb_substr($role,-2) === '居た')
    {
      $this->insert_onlooker();
    }
    else
    {
      $this->fetch_role($role);
      $this->fetch_sklid();
      $this->fetch_dtid(trim($person->find('td',2)->plaintext));
      $this->fetch_rltid(trim($person->find('td',3)->plaintext));
      $this->fetch_life();
    }
  }
  protected function fetch_role($role)
  {
    $this->user->role = mb_ereg_replace('.+陣営：([^\r\n]+)\r\n　　.+','\\1',$role,'m');
    $this->fetch_tmid(mb_substr($role,0,2));
  }
  protected function fetch_tmid($team)
  {
    if($this->village->rp === 'HENTAI')
    {
      $this->user->tmid = $this->TM_HENTAI[$team];
    }
    else
    {
      $this->user->tmid = $this->TEAM[$team];
    }

    if($this->is_evil)
    {
      $this->check_evil_team();
    }
  }
  protected function fetch_dtid($dtid)
  {
    if($dtid === '生存者')
    {
      $this->user->end = $this->village->days;
      $this->user->dtid = Data::DES_ALIVE;
    }
    else
    {
      $this->user->end = (int)mb_ereg_replace(".+\((\d+)d\)","\\1",$dtid,'m');
      $this->user->dtid = $this->DESTINY[mb_substr($dtid,0,mb_strpos($dtid,"\n")-1)];
    }
  }

  protected function fetch_sklid()
  {
    $role = $this->user->role;
    if(mb_strpos($role,"、") === false)
    {
      $sklid = $role;
    }
    else
    {
      //役職欄に絆などついている場合
      $sklid = mb_substr($role,0,mb_strpos($role,"、"));
    }
    if($this->village->rp !== 'NORMAL')
    {
      $this->user->sklid = $this->{'SKL_'.$this->village->rp}[$sklid];
    }
    else
    {
      $this->user->sklid = $this->SKILL[$sklid];
    }
  }
}
