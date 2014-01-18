<?php

abstract class Country
{
  public $check;
            
  protected function __construct($cid,$url_vil,$url_log)
  {
    $this->check = new Check_Village($cid,$url_vil,$url_log); 
  }

  function fetch_village()
  {
    $list = $this->check->get_village();
    if(!$list)
    {
      echo get_class($this).' has not new villages.'.PHP_EOL;
      return;
    }
    var_dump($list);
  }
}
