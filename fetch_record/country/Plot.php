<?php

class Plot extends Giji
{
  private $doppel = 
    [
      "cosmos"=>"cosmos&lt;議事&gt;"
    ];
  function __construct()
  {
    $cid = 14;
    $url_vil = 'http://cabala.halfmoon.jp/cafe/sow.cgi?vid=';
    $url_log = 'http://cabala.halfmoon.jp/cafe/sow.cgi?cmd=oldlog';
    parent::__construct($cid,$url_vil,$url_log);
    $this->is_evil = true;
  }
}
