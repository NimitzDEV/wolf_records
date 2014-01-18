<?php

abstract class Country
{
  public   $check
          ,$cid
          ,$url
          ,$village
          ,$fetch
          ;
            
  protected function __construct($cid,$url_vil,$url_log)
  {
    $this->check = new Check_Village($cid,$url_vil,$url_log); 
    $this->cid = $cid;
    $this->url = $url_vil;
  }

  function insert_village()
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
      $this->village = new Village($this->cid,$vno);
      $this->fetch_village();
      var_dump(get_object_vars($this->village));
    }
  }
  abstract function fetch_village();
  abstract function fetch_name();
  abstract function fetch_date();
  abstract function fetch_nop();
  abstract function fetch_rglid();
  abstract function fetch_days();
  abstract function fetch_wtmid();
}
