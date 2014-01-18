<?php

class Ning extends Country
{
  function __construct()
  {
    $cid = 9;
    $url_vil = "http://www.wolfg.x0.com/index.rb?vid=";
    $url_log = "http://www.wolfg.x0.com/index.rb?cmd=log";

    parent::__construct($cid,$url_vil,$url_log);
  }
}
