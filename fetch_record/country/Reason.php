<?php

class Reason extends Country
{
  private $url_epi;
  private $skill =
    [
        "村人"  =>Data::SKL_VILLAGER
       ,"占い師"=>Data::SKL_SEER
       ,"霊能者"=>Data::SKL_MEDIUM
       ,"狩人"  =>Data::SKL_HUNTER
       ,"共有者"  =>Data::SKL_MASON
       ,"人狼"  =>Data::SKL_WOLF
       ,"狂人"  =>Data::SKL_LUNATIC
    ]; 

  function __construct()
  {
    $cid = 59;
    $url_vil = "http://sui.sib.jp/pc/view_kako/";
    $url_log = "http://sui.sib.jp/pc/index_kako/";
    parent::__construct($cid,$url_vil,$url_log);
  }

  protected function fetch_village()
  {
    $this->fetch_from_pro();
    $this->fetch_from_epi();
    var_dump($this->village->get_vars());
  }

  protected function fetch_from_pro()
  {
    $this->fetch->load_file($this->url.$this->village->vno);

    $this->fetch_name();
    $this->fetch_date();
    $this->fetch_days();

    $this->fetch->clear();
  }
  protected function fetch_name()
  {
    $this->village->name = trim($this->fetch->find('span#village_name',0)->plaintext);
  }
  protected function fetch_date()
  {
    $date = $this->fetch->find('span.character_name',0)->id;
    $this->village->date = date("y-m-d",mb_ereg_replace('mes(.+)c1','\\1',$date));
  }
  protected function fetch_days()
  {
    $this->url_epi = $this->fetch->find('div#NaviDay a',-1)->href;
    $this->village->days = (int)mb_ereg_replace(".+view_kako/\d+/(\d+)/.+", "\\1", $this->url_epi);
  }

  protected function fetch_from_epi()
  {
    $this->fetch->load_file($this->url_epi);
    $this->make_cast();

    $this->fetch_nop();
    $this->fetch_rglid();
    $this->fetch_wtmid();

    $this->fetch->clear();
  }

  protected function make_cast()
  {
    $cast = $this->fetch->find('div.systemmessage p',-1)->plaintext;
    //simple_html_domを抜けてきたタグを削除(IDに{}があるとbrやaが残る)
    //$cast = preg_replace([ '/<br \/>/','/<a href=[^>]+>/','/<\/a>/' ],['','',''],$cast);
    $cast = explode("だった。\r",$cast);
    //最後のスペース削除
    array_pop($cast);
    $this->cast = $cast;
  }

  protected function fetch_nop()
  {
    $this->village->nop = count($this->cast);
  }
  protected function fetch_rglid()
  {
    switch($this->village->nop)
    {
      case 16:
        $this->village->rglid = Data::RGL_F;
        break;
      case 15:
        $this->village->rglid = Data::RGL_S_3;
        break;
      case 14:
      case 13:
      default:
        $this->village->rglid= Data::RGL_S_2;
        break;
    }
  }
  protected function fetch_wtmid()
  {
    $wtmid = trim($this->fetch->find('div.systemmessage p',-2)->plaintext);
    switch(mb_substr($wtmid,0,3))
    {
      case '全ての': //村勝利
        $this->village->wtmid = Data::TM_VILLAGER;
        break;
      case 'もう人': //狼勝利
        $this->village->wtmid = Data::TM_WOLF;
        break;
      default:
        $this->output_comment('undefined',$wtmid);
        break;
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
      if($this->user->dtid === Data::DES_ALIVE)
      {
        unset($list[$key]);
      }
    }
    $this->fetch_from_daily($list);
    $this->fetch_life();

    foreach($this->users as $user)
    {
      if(!$user->is_valid())
      {
        $this->output_comment('n_user');
      }
    }
  }
  protected function fetch_users($person)
  {
    $person = preg_replace("/ ?(.+) （(.+)）、(生存|死亡)。(.+)$/", "$1#SP#$2#SP#$3#SP#$4", $person);
    $person = explode('#SP#',$person);
    //末尾に半角スペースがある場合は、読み込めるように変換する
    if(mb_substr($person[1],-1,1)==' ')
    {
      $person[1] = preg_replace("/ /","&amp;nbsp;",$person[1]);
    }
    $person[1] = $this->check_doppel($person[1]);

    $this->user->persona = $person[0];
    $this->user->player  = $person[1];
    $this->user->role    = $person[3]; 

    $this->fetch_sklid();
    $this->fetch_tmid();
    $this->fetch_rltid();

    if($person[2] === '生存')
    {
      $this->user->dtid = Data::DES_ALIVE;
      $this->user->end = $this->village->days;
      $this->user->life = 1.000;
    }
  }

  protected function fetch_from_daily($list)
  {
    $days = $this->village->days -1; //初日=0
    for($i=1; $i<=$days; $i++)
    {
      $this->fetch->load_file($this->make_daily_url($i));
      $announce = $this->fetch->find('div.announce');
      foreach($announce as $item)
      {
        $destiny = mb_substr(trim($item->plaintext),-6,6);
        $destiny = preg_replace("/\r\n/","",$destiny);

        switch($destiny)
        {
          case "突然死した。":
            $persona = preg_replace("/^ ?(.+) は、突然死した。 ?/", "$1", $item->plaintext);
            $key = array_search($persona,$list);
            $this->users[$key]->dtid = Data::DES_RETIRED;
            break;
          case "処刑された。":
            $persona = preg_replace("/(.+\r\n){1,}\r\n(.+) は村人達の手により処刑された。 ?/", "$2", $item->plaintext);
            $key = array_search($persona,$list);
            $this->users[$key]->dtid = Data::DES_HANGED;
            break;
          case "発見された。":
            $persona = preg_replace("/.+朝、(.+) が無残.+\r\n ?/", "$1", $item->plaintext);
            $key = array_search($persona,$list);
            $this->users[$key]->dtid = Data::DES_EATEN;
            break;
          default:
            continue;
        }   
        $this->users[$key]->end = $i+1;
      }
      $this->fetch->clear();
    }
  }
  protected function make_daily_url($day)
  {
    if($day === $this->village->days-1)
    {
      $suffix = '_party';
    }
    else
    {
      $suffix = '_progress';
    }
    $day = str_pad($day,3,"0",STR_PAD_LEFT);

    return $this->url.$this->village->vno.'&meslog='.$day.$suffix;
  }
  protected function fetch_sklid()
  {
    $this->user->sklid = $this->skill[$this->user->role];
  }
  protected function fetch_tmid()
  {
    if($this->user->role === "人狼" || $this->user->role === "狂人")
    {
      $this->user->tmid = Data::TM_WOLF;
    }
    else
    {
      $this->user->tmid = Data::TM_VILLAGER;
    }
  }
  protected function fetch_life()
  {
    foreach($this->users as $key=>$user)
    {
      if(!$this->users[$key]->life)
      {
        $this->users[$key]->life = round(($this->users[$key]->end-1) / $this->village->days,3);
      }
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
