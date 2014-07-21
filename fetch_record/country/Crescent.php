<?php

class Crescent extends Giji_Old
{
  use TRS_Crescent;
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
    ,"市民"=>Data::TM_VILLAGER
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
    $nop = $this->fetch->find('p.multicolumn_left',7)->plaintext;
    $this->village->nop = (int)mb_substr($nop,0,mb_strpos($nop,'人'));
  }

  protected function fetch_date()
  {
    $date = $this->fetch->find('div.mes_date',0)->plaintext;
    $date = mb_substr($date,mb_strpos($date,"2"),10);
    $this->village->date = preg_replace('/(\d{4})\/(\d{2})\/(\d{2})/','\1-\2-\3',$date);
  }
  protected function fetch_policy_detail()
  {
    $policy = $this->fetch->find('p.multicolumn_left',1)->plaintext;
    if(mb_ereg_match("真剣勝負",$policy) !== false && mb_ereg_match("ネタ重視|ストーリー重視",$policy))
    {
      $this->village->policy = false;
      $this->output_comment('rp');
    }
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
    $this->user->role = mb_ereg_replace('.+：([^\r\n]+)\r\n　　.+','\\1',$role,'m');
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
