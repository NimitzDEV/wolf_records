<?php

class Mikan extends SOW
{
  use TRS_SOW;
  protected $RP_SP = [
     'RP村用'=>'RP'
    ,'はぴたん王国'=>'HAPI'
    ];
  protected $SKL_HAPI = [
     "食いしん坊"=>[Data::SKL_VILLAGER,Data::TM_VILLAGER]
    ,"お菓子族"=>[Data::SKL_WOLF,Data::TM_WOLF]
    ,"電波ティシエ"=>[Data::SKL_TELEPATH,Data::TM_VILLAGER]
    ,"コウモリ"=>[Data::SKL_PIXY,Data::TM_FAIRY]
    ,"マイフレ"=>[Data::SKL_LUNAWHS,Data::TM_WOLF]
    ,"ハムスター"=>[Data::SKL_BAT,Data::TM_FAIRY]
    ,"ひそひそパティシエ"=>[Data::SKL_BAT,Data::TM_FAIRY]
    ,"お菓子族儲"=>[Data::SKL_CURSEWOLF,Data::TM_WOLF]
    ];

  protected $DT_RP = [
     ' は消えた。'=>['.+(\(ランダム投票\)|投票した。)(.+) は消えた。',Data::DES_HANGED]
    ,'突然死した。'=>['^( ?)(.+) は、突然死した。',Data::DES_RETIRED]
    ,'  消えた。'=>['()(.+) 消えた。',Data::DES_EATEN]
    ,'後を追った。'=>['^( ?)(.+) は(絆に引きずられるように|哀しみに暮れて) .+ の後を追った。',Data::DES_SUICIDE]
  ];
  protected $DT_HAPI = [
     'しくペロリ！'=>['.+(\(ランダム投票\)|投票した。)(.+) は食いしん.+',Data::DES_HANGED]
    ,'突然死した。'=>['^( ?)(.+) は、突然死した。',Data::DES_RETIRED]
    ,'リ！された。'=>['(.+)朝、 ?(.+) が美味しく.+',Data::DES_EATEN]
    ,'後を追った。'=>['^( ?)(.+) は(絆に引きずられるように|哀しみに暮れて) .+ の後を追った。',Data::DES_SUICIDE]
  ];

  function __construct()
  {
    $cid = 55;
    $url_vil = "http://mecan.nazo.cc/sow/sow.cgi?vid=";
    $url_log = "http://mecan.nazo.cc/sow/sow.cgi?cmd=oldlog";
    parent::__construct($cid,$url_vil,$url_log);
    $this->policy = false;
    $this->RP_LIST = array_merge($this->RP_LIST,$this->RP_SP);
  }
  protected function fetch_sklid()
  {
    if(!empty($this->{'SKL_'.$this->village->rp}))
    {
      $this->user->sklid = $this->{'SKL_'.$this->village->rp}[$this->user->role][0];
      $this->user->tmid = $this->{'SKL_'.$this->village->rp}[$this->user->role][1];
    }
    else
    {
      $this->user->sklid = $this->SKILL[$this->user->role][0];
      $this->user->tmid = $this->SKILL[$this->user->role][1];
    }
    //呪狼の名前をメモ
    if($this->user->sklid === Data::SKL_CURSEWOLF)
    {
      $this->cursewolf[] = $this->user->persona;
    }
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
        $destiny = trim(preg_replace("/\r\n/",'',$item->plaintext));
        $key= mb_substr(trim($item->plaintext),-6,6);
        if(!empty($this->{'DT_'.$rp}))
        {
          //keyが六文字未満
          if(mb_ereg_match('.+ 消えた。\z',$key))
          {
            $key = '  消えた。';
          }
          if(!isset($this->{'DT_'.$rp}[$key]))
          {
            continue;
          }
          else
          {
            $persona = trim(mb_ereg_replace($this->{'DT_'.$rp}[$key][0],'\2',$destiny,'m'));
            $key_u = array_search($persona,$list);
            $dtid = $this->{'DT_'.$rp}[$key][1];
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
}
