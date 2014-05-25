<?php

abstract class Country
{
  use TR_Doppel;
  protected  $check
            ,$cid
            ,$url
            ,$policy
            ,$village
            ,$fetch
            ,$cast
            ,$user
            ,$users = []
            ;
            
  protected function __construct($cid,$url_vil,$url_log)
  {
    $this->check = new Check_Village($cid,$url_vil,$url_log); 
    $this->cid = $cid;
    $this->url = $url_vil;
  }

  function insert()
  {
    $list = $this->check->get_village();
    //$list = [11];
    if(!$list)
    {
      $this->check->remove_queue();
      echo get_class($this).' has not new villages.'.PHP_EOL;
      return;
    }
    $this->fetch = new simple_html_dom();
    //取得しない村番号
    //$kick = [1];
    foreach($list as $vno)
    {
      //if(array_search($vno,$kick)  !== false)
      //{
        //echo '※: '.$vno.' is kicked by $kick list.'.PHP_EOL;
        //continue;
      //}
      if($this->insert_village($vno))
      {
        //continue;
        $this->insert_users();
      }
      else
      {
        echo 'ERROR: '.$vno.'could not fetched.'.PHP_EOL;
        $this->fetch->clear();
        continue;
      }
      $this->fetch->clear();
      //continue;
      $db = new Insert_DB($this->cid);
      if(!$db->connect())
      {
        return;
      }
      if($db->insert_db($this->village,$this->users))
      {
        echo '★'.$this->village->vno.'. '.$this->village->name.' is all inserted.'.PHP_EOL;
      }
      $db->disconnect();
    }
    $this->check->remove_queue();
  }

  protected function insert_village($vno)
  {
    $this->village = new Village($this->cid,$vno);
    $this->fetch_village();
    if($this->village->is_valid())
    {
      return true;
    }
    else
    {
      return false;
    }
  }
  protected function insert_users()
  {
    $this->users = [];
    foreach($this->cast as $person)
    {
      $this->user = new User();
      $this->fetch_users($person);
      if(!$this->user->is_valid())
      {
        echo 'NOTICE: '.$this->user->persona.'could not fetched.'.PHP_EOL;
      }
      //エラーでも歯抜けが起きないように入れる
      $this->users[] = $this->user;
    }
  }

  protected function fetch_policy()
  {
    $rp = '/[^A-Z+]RP村|[^Ａ-Ｚ+]ＲＰ村|RP】|[^ァ-ヾ+]ネタ村|[^ァ-ヾ+]ランダ村|[^ァ-ヾ+]ラ神|[^ァ-ヾ+]ランダム/u';
    if(preg_match($rp,$this->village->name))
    {
      $this->village->policy = false;
      echo $this->village->vno.' is guessed RP.'.PHP_EOL;
    }
    else
    {
      $this->village->policy = true;
    }
  }
  protected function check_doppel($player)
  {
    if(array_key_exists($player,$this->{'d_'.get_class($this)}))
    {
      echo 'NOTICE: '.$player.' is DOPPEL.->'.$this->{'d_'.get_class($this)}[$player].PHP_EOL;
      return $this->{'d_'.get_class($this)}[$player];
    }
    else
    {
      return $player;
    }
  }
  protected function fetch_life()
  {
    if($this->user->dtid === Data::DES_ALIVE)
    {
      $this->user->life = 1.000;
    }
    else if($this->user->tmid === Data::TM_ONLOOKER)
    {
      $this->user->life = 0.000;
    }
    else
    {
      $this->user->life = round(($this->user->end-1) / $this->village->days,3);
    }
  }

  abstract protected function fetch_village();
  abstract protected function fetch_name();
  abstract protected function fetch_date();
  abstract protected function fetch_nop();
  abstract protected function fetch_rglid();
  abstract protected function fetch_days();
  abstract protected function fetch_wtmid();

  abstract protected function make_cast();
  abstract protected function fetch_users($person);
}
