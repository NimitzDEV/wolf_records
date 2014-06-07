<?php

class Mikan extends SOW
{
  use TRS_SOW;
  function __construct()
  {
    $cid = 55;
    $url_vil = "http://mecan.nazo.cc/sow/sow.cgi?vid=";
    $url_log = "http://mecan.nazo.cc/sow/sow.cgi?cmd=oldlog";
    parent::__construct($cid,$url_vil,$url_log);
    $this->policy = false;
  }
}
