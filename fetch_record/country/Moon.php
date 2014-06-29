<?php

class Moon extends SOW
{
  use TRS_SOW;
  function __construct()
  {
    $cid = 56;
    $url_vil = "http://managarmr.sakura.ne.jp/sow.cgi?vid=";
    $url_log = "http://managarmr.sakura.ne.jp/sow.cgi?cmd=oldlog";
    parent::__construct($cid,$url_vil,$url_log);
  }
}
