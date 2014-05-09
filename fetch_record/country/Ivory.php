<?php

class Ivory extends Giji_Old
{
  use AR_Ivory;
  private $SKL_SP = [
     "審問官"=>Data::SKL_SEERWIN
    ,"隠秘学者"=>Data::SKL_SEERAURA
    ,"登記役人"=>Data::SKL_SAGE
    ,"聴罪師"=>Data::SKL_MEDIWIN
    ,"検死官"=>Data::SKL_PRIEST
    ,"貴族"=>Data::SKL_PRINCE
    ,"目隠し妖怪"=>Data::SKL_JAMMER
    ,"取替え妖怪"=>Data::SKL_SNATCH
    ,"古狸"=>Data::SKL_LUNAPATH
    ,"傀儡師"=>Data::SKL_MUPPETER
    ,"悪魔"=>Data::SKL_EFB
    ,"天使"=>Data::SKL_QP
    ,"誓約者"=>Data::SKL_PASSION
    ,"二枚舌"=>Data::SKL_PLAYBOY
    ,"宣教師"=>Data::SKL_PIPER
    ];
  protected $RP_SP = [
     "ミラーズホロウ"=>'MILLERS'
    ,"マフィア"=>'MAFIA'
  ];
  function __construct()
  {
    $cid = 50;
    $url_vil = "http://kids.sphere.sc/tabula/lupus/sow.cgi?vid=";
    $url_log = "http://kids.sphere.sc/tabula/lupus/sow.cgi?cmd=oldlog";
    parent::__construct($cid,$url_vil,$url_log);
    $this->SKILL = array_merge($this->SKILL,$this->SKL_SP);
  }

  protected function fetch_name()
  {
    $name = $this->fetch->find('p.multicolumn_left',0)->plaintext;
    $this->village->name = mb_ereg_replace("(.+)\r\n.+","\\1",$name);
  }
  protected function fetch_rglid()
  {
    $rule= trim($this->fetch->find('dl.mes_text_report dt',1)->plaintext);
    if(array_key_exists($rule,$this->RGL_IVORY))
    {
      $this->village->rglid = $this->RGL_IVORY[$rule];
      return;
    }

    $rglid = trim($this->fetch->find('dl.mes_text_report dt',2)->plaintext);
    $rglid = mb_substr($rglid,mb_strpos($rglid,"：")+1);
    switch($rglid)
    {
      case "自由設定":
        //自由設定でも特定の編成はレギュレーションを指定する
        $free = trim($this->fetch->find('dl.mes_text_report dd',2)->plaintext);
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
      case "恋（誓い）入り":
        $this->village->rglid = Data::RGL_LOVE;
        break;
      case "教祖（宣教師）入り":
        $this->village->rglid = Data::RGL_ETC;
        break;
    }
  }
  protected function fetch_rp()
  {
    $rp = trim($this->fetch->find('dl.mes_text_report dt',0)->plaintext);
    $rp = mb_ereg_replace('文章セット：「(.+)」','\\1',$rp);
    if(array_key_exists($rp,$this->RP_SP))
    {
      $this->village->rp = $this->RP_SP[$rp]; 
    }
    else
    {
      $this->village->rp = 'NORMAL'; 
    }
  }
  protected function fetch_wtmid()
  {
    $wtmid = trim($this->fetch->find('p.info',-1)->plaintext);
    if(preg_match("/村の更新日が延長されました|が参加しました。/",$wtmid))
    {
      $do_i = -2;
      do
      {
        $wtmid = trim($this->fetch->find('p.info',$do_i)->plaintext);
        $do_i--;
      } while(preg_match("/村の更新日が延長されました|が参加しました。/",$wtmid));
    }
    $wtmid = mb_substr(preg_replace("/\r\n/","",$wtmid),2,13);
    if($this->village->rp !== 'NORMAL')
    {
      $this->village->wtmid = $this->{'WTM_'.$this->village->rp}[$wtmid];
    }
    else
    {
      $this->village->wtmid = $this->WTM[$wtmid];
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
  protected function fetch_tmid($result)
  {
    if($this->village->rp === "MAFIA")
    {
      $this->user->tmid = $this->TM_MAFIA[mb_substr($result,0,2)];
    }
    else
    {
      $this->user->tmid = $this->TEAM[mb_substr($result,0,2)];
    }
  }
}
