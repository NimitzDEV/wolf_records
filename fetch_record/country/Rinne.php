<?php

class Rinne extends SOW
{
  use TRS_SOW;
  function __construct()
  {
    $cid = 57;
    $url_vil = "http://monooki.sakura.ne.jp/sow/sow.cgi?vid=";
    $url_log = "http://monooki.sakura.ne.jp/sow/sow.cgi?cmd=oldlog";
    parent::__construct($cid,$url_vil,$url_log);
    $this->policy = true;
  }
}
