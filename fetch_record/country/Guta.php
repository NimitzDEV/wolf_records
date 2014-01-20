<?php

class Guta extends Giji_Old
{
  use AR_Guta;
  function __construct()
  {
    $cid = 11;
    $url_vil = "http://www3.marimo.or.jp/~fgmaster/cabala/sow.cgi?vid=";
    $url_log = "http://www3.marimo.or.jp/~fgmaster/cabala/sow.cgi?cmd=oldlog";
    parent::__construct($cid,$url_vil,$url_log);
  }

}
