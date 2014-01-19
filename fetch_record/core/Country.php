<?php

abstract class Country
{
  public   $check
          ,$cid
          ,$url
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
    if(!$list)
    {
      echo get_class($this).' has not new villages.'.PHP_EOL;
      return;
    }
    $this->fetch = new simple_html_dom();
    foreach($list as $vno)
    {
      if($this->insert_village($vno))
      {
        $this->insert_users();
      }
      else
      {
        echo 'ERROR: '.$vno.'could not fetched.'.PHP_EOL;
        continue;
      }
    }
  }

  function insert_village($vno)
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
  function insert_users()
  {
    //人参国は書き換える
    foreach($this->$cast as $cast_item)
    {
      $this->user = new User();
      $this->fetch_users($cast_item);
      if(!$this->user->is_valid())
      {
        echo 'NOTICE: '.$this->user->persona.'could not fetched.'.PHP_EOL;
      }
      //エラーでも歯抜けが起きないように入れる
      $this->users[] = $this->user;
    }
  }

  abstract function fetch_village();
  abstract function fetch_name();
  abstract function fetch_date();
  abstract function fetch_nop();
  abstract function fetch_rglid();
  abstract function fetch_days();
  abstract function fetch_wtmid();

  abstract function fetch_users($cas_item);
  abstract function fetch_persona();
  abstract function fetch_player();
  abstract function fetch_role();
  abstract function fetch_dtid();
  abstract function fetch_end();
  abstract function fetch_sklid();
  abstract function fetch_tmid();
  abstract function fetch_life();
  abstract function fetch_rltid();
}
