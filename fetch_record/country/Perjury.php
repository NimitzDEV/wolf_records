<?php

class Perjury extends Giji
{
  private $doppel = 
    [
      "cosmos"=>"cosmos&lt;議事&gt;"
    ];
  function __construct()
  {
    $cid = 15;
    $url_vil = "http://perjury.rulez.jp/sow.cgi?vid=";
    $url_log = "http://perjury.rulez.jp/sow.cgi?cmd=oldlog";
    parent::__construct($cid,$url_vil,$url_log);
    $this->is_evil = false;
  }
}
