<?php

class Ning extends Country
{
  private $doppel = [
     "asaki"    =>"asaki&lt;G国&gt;"
    ,"motimoti" =>"motimoti&lt;G薔薇国&gt;"
    ];

  function __construct()
  {
    $cid = 9;
    $url_vil = "http://www.wolfg.x0.com/index.rb?vid=";
    $url_log = "http://www.wolfg.x0.com/index.rb?cmd=log";
    parent::__construct($cid,$url_vil,$url_log);
  }

  function fetch_name()
  {
    $this->fetch->load_file($this->url.$this->village->vno."&meslog=000_ready");
    $name = $this->fetch->find('title',0)->plaintext;
    $this->village->name = preg_replace('/人狼.+\d+ (.+)/','$1',$name);
      var_dump(get_object_vars($this->village));
  }

  function fetch_date()
  {
  }
  function fetch_nop()
  {
  }
  function fetch_rglid($nop)
  {
  }
  function fetch_days()
  {
  }
  function fetch_wtmid()
  {
  }
}
