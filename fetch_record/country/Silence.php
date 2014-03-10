<?php

class Silence extends Country
{
  use TR_SOW,AR_SOW,TR_SOW_RGL;
  protected $WTM_PO = [
     '人狼を退治したのだ！'=>Data::TM_VILLAGER
    ,'CMを去っていった。'=>Data::TM_WOLF
    ,'いていなかった……。'=>Data::TM_FAIRY
    ,'の村を去っていった。'=>Data::TM_LOVERS
  ];
  protected $SKL_PO = [
     "たのしいなかま"=>[Data::SKL_VILLAGER,Data::TM_VILLAGER]
    ,"ぽぽぽぽーん"=>[Data::SKL_WOLF,Data::TM_WOLF]
    ,"おやすみなサイ"=>[Data::SKL_SEER,Data::TM_VILLAGER]
    ,"ただいまマンボウ"=>[Data::SKL_MEDIUM,Data::TM_VILLAGER]
    ,"あいさつ坊や"=>[Data::SKL_LUNATIC,Data::TM_WOLF]
    ,"スタッフ"=>[Data::SKL_HUNTER,Data::TM_VILLAGER]
    ,"AC"=>[Data::SKL_MASON,Data::TM_VILLAGER]
    ,"いただきマウス"=>[Data::SKL_FAIRY,Data::TM_FAIRY]
    ,"あいさつガール"=>[Data::SKL_LUNAWHS,Data::TM_WOLF]
    ,"ごちそうさマウス"=>[Data::SKL_PIXY,Data::TM_FAIRY]
    ,"ありがとウサギ"=>[Data::SKL_QP,Data::TM_LOVERS]
  ];
  protected $DT_PO = [
     '撲殺された。'=>['.+(\(ランダム投票\)|あいさつした。)(.+) はたのしいなかま達に撲殺された。',Data::DES_HANGED]
    ,'突然死した。'=>['^( ?)(.+) は、突然死した。',Data::DES_RETIRED]
    ,'ち亡くすね。'=>['(.+)朝、 ?(.+) がぽぽぽ.+',Data::DES_EATEN]
    ,'後を追った。'=>['^( ?)(.+) は(絆に引きずられるように|感謝の気持ちを込めて) .+ の後を追った。',Data::DES_SUICIDE]
  ];
  function __construct()
  {
    $cid = 35;
    $url_vil = "http://silence.hotcom-web.com/cgi-bin/sow/sow.cgi?vid=";
    $url_log = "http://silence.hotcom-web.com/cgi-bin/sow/sow.cgi?cmd=oldlog";
    parent::__construct($cid,$url_vil,$url_log);
  }
  function fetch_policy()
  {
    $this->village->policy = true;
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
  protected function fetch_rglid()
  {
    $rgl_base = trim($this->fetch->find('table.list tr',5)->find('td',1)->plaintext);
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
      case "Ｇ国":
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
  }
  protected function fetch_days()
  {
    $days = trim($this->fetch->find('p',0)->find('a',-4)->innertext);
    $this->village->days = mb_substr($days,0,mb_strpos($days,'日')) +1;
  }
  protected function fetch_rp()
  {
    $this->village->rp = 'SOW';
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
  protected function fetch_wtmid()
  {
    $wtmid = $this->fetch_win_message();
    if(array_key_exists($wtmid,$this->WTM_SOW))
    {
      $this->village->wtmid = $this->WTM_SOW[$wtmid];
    }
    else if(array_key_exists($wtmid,$this->WTM_WBBS))
    {
      $this->village->wtmid = $this->WTM_WBBS[$wtmid];
      $this->village->rp = 'WBBS';
    }
    else
    {
      $this->village->wtmid = $this->WTM_PO[$wtmid];
      $this->village->rp = 'PO';
    }
  }
  protected function make_cast()
  {
    $cast = $this->fetch->find('table tr');
    array_shift($cast);
    $this->cast = $cast;
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
        $this->users[$key_u]->life = round(($i-1) / $this->village->days,2);
      }
      $this->fetch->clear();
    }
  }
}