<?php

class Real extends Country
{
  use TR_SOW,AR_SOW,TR_SOW_RGL;
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
}
