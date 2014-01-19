<?php

class Ning extends Country
{
  private $url_epi;
  private $doppel = 
    [
       "asaki"    =>"asaki&lt;G国&gt;"
      ,"motimoti" =>"motimoti&lt;G薔薇国&gt;"
    ];
  private $skill =
    [
        "村人"  =>Data::SKL_VILLAGER
       ,"占い師"=>Data::SKL_SEER
       ,"霊能者"=>Data::SKL_MEDIUM
       ,"狩人"  =>Data::SKL_HUNTER
       ,"人狼"  =>Data::SKL_WOLF
       ,"狂人"  =>Data::SKL_LUNATIC
    ]; 

  function __construct()
  {
    $cid = 9;
    $url_vil = "http://www.wolfg.x0.com/index.rb?vid=";
    $url_log = "http://www.wolfg.x0.com/index.rb?cmd=log";
    parent::__construct($cid,$url_vil,$url_log);
  }

  function fetch_village()
  {
    $this->fetch_from_pro();
    $this->fetch_from_epi();
    var_dump(get_object_vars($this->village));
  }

  function fetch_from_pro()
  {
    $this->fetch->load_file($this->url.$this->village->vno."&meslog=000_ready");

    $this->fetch_name();
    $this->fetch_date();
    $this->fetch_days();

    $this->fetch->clear();
  }
  function fetch_name()
  {
    $name = $this->fetch->find('title',0)->plaintext;
    $this->village->name = preg_replace('/人狼.+\d+ (.+)/','$1',$name);
  }
  function fetch_date()
  {
    $date = $this->fetch->find('div.ch1',0)->find('a',1)->name;
    $this->village->date = date("y-m-d",preg_replace('/mes(.+)/','$1',$date));
  }
  function fetch_days()
  {
    $url = preg_replace("/index\.rb\?vid=/","",$this->url);
    $this->url_epi = $url.$this->fetch->find('p a',-2)->href;
    $this->village->days = preg_replace("/.+=0(\d{2})_party/", "$1", $this->url_epi) + 1;
  }

  function fetch_from_epi()
  {
    $this->fetch->load_file($this->url_epi);
    if(!$this->fetch->find('div.announce'))
    {
      echo 'ERROR: '.$vno.' epilogue is broken. log didnt save.'.PHP_EOL;
      return;
    }
    $this->make_cast();

    $this->fetch_nop();
    $this->fetch_rglid();
    $this->fetch_wtmid();

    $this->fetch->clear();
  }

  function make_cast()
  {
    $cast = preg_replace("/\r\n/","",$this->fetch->find('div.announce',-1)->plaintext);
    //simple_html_domを抜けてきたタグを削除(IDに{}があるとbrやaが残る)
    $cast = preg_replace([ '/<br \/>/','/<a href=[^>]+>/','/<\/a>/' ],['','',''],$cast);
    $cast = explode('だった。',$cast);
    //最後のスペース削除
    array_pop($cast);
    $this->cast = $cast;
  }

  function fetch_nop()
  {
    $this->village->nop = count($this->cast);
  }
  function fetch_rglid()
  {
    switch($this->village->nop)
    {
      case 16:
        $this->village->rglid = Data::RGL_G;
        break;
      case 15:
      case 14:
      case 13:
        $this->village->rglid = Data::RGL_S_3;
        break;
      default:
        $this->village->rglid= Data::RGL_S_2;
        break;
    }
  }
  function fetch_wtmid()
  {
    $wtmid = mb_substr($this->fetch->find('div.announce',-2)->plaintext,0,3);
    switch($wtmid)
    {
      case '全ての': //村勝利
        $this->village->wtmid = Data::TM_VILLAGER;
        break;
      case 'もう人': //狼勝利
        $this->village->wtmid = Data::TM_WOLF;
        break;
      default:
        echo 'NOTICE: unknown wtm.->'.$wtmid.PHP_EOL;
        break;
    }
  }

  function insert_users()
  {
    $list = [];
    foreach($this->cast as $key=>$cast_item)
    {
      $this->user = new User();
      $this->fetch_users($cast_item);
      $this->users[] = $this->user;
      //生存者を除く名前リストを作る
      $list[] = $this->user->persona;
      if($this->user->dtid === Data::DES_ALIVE)
      {
        unset($list[$key]);
      }
    }
    $this->fetch_from_daily($list);
    var_dump(get_object_vars($this->user));
  }
  function fetch_users($cast_item)
  {
    $cast_item = preg_replace("/ ?(.+) （(.+)）、(生存|死亡)。(.+)$/", "$1#SP#$2#SP#$3#SP#$4", $cast_item);
    $cast_item = explode('#SP#',$cast_item);
    //末尾に半角スペースがある場合は、読み込めるように変換する
    if(mb_substr($cast_item[1],-1,1)==' ')
    {
      $cast_item[1] = preg_replace("/ /","&amp;nbsp;",$cast_item[1]);
    }
    $cast_item[1] = $this->check_doppel($cast_item[1]);

    $this->user->persona = $cast_item[0];
    $this->user->player  = $cast_item[1];
    $this->user->role    = $cast_item[3]; 

    $this->fetch_sklid();
    $this->fetch_tmid();
    $this->fetch_rltid();

    if($cast_item[2] === '生存')
    {
      $this->user->dtid = Data::DES_ALIVE;
      $this->user->end = $this->village->days;
      $this->user->life = 1.00;
    }
  }
  function fetch_persona()
  {
  }
  function fetch_player()
  {
  }
  function fetch_role()
  {
  }
  function check_doppel($player)
  {
    if(array_key_exists($player,$this->doppel))
    {
      echo 'NOTICE: '.$player.' is DOPPEL.->'.$this->doppel[$player].PHP_EOL;
      return $this->doppel[$player];
    }
    else
    {
      return $player;
    }
  }

  function fetch_from_daily($list)
  {
  }
  function fetch_dtid()
  {
  }
  function fetch_end()
  {
  }
  function fetch_sklid()
  {
    $this->user->sklid = $this->skill[$this->user->role];
  }
  function fetch_tmid()
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
  function fetch_life()
  {
  }
  function fetch_rltid()
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
