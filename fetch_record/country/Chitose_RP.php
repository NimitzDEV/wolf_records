<?php

class Chitose_RP extends Country
{
  use TR_SOW,AR_SOW,TR_SOW_RGL,AR_Chitose;
  private $RP_Chitose = [
       'ようちえん'=>'KIDS'
      ,'メトロポリスβ'=>'yETRO'
    ];

  function __construct()
  {
    $cid = 33;
    $url_vil = "http://1000nacht.sakura.ne.jp/story/sow/sow.cgi?vid=";
    $url_log = "http://1000nacht.sakura.ne.jp/story/sow/sow.cgi?cmd=oldlog";
    $this->RP_LIST = array_merge($this->RP_LIST,$this->RP_Chitose);
    parent::__construct($cid,$url_vil,$url_log);
  }
  function fetch_policy()
  {
    $this->village->policy = false;
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
        else if($rp === 'KIDS')
        {
          if($key === "りについた。")
          {
            $key= mb_substr(trim($item->plaintext),-8,8);
          }

          if(!isset($this->DT_KIDS[$key]))
          {
            continue;
          }
          else
          {
            $persona = trim(mb_ereg_replace($this->DT_KIDS[$key][0],'\2',$destiny,'m'));
            $key_u = array_search($persona,$list);
            $dtid = $this->DT_KIDS[$key][1];
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
        //呪狼が存在する編成で、占い師が襲撃した場合別途チェック
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
