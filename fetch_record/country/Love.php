<?php

class Love extends Giji_Old
{
  function __construct()
  {
    $cid = 54;
    $url_vil = 'http://werewolf.lovesick.jp/cabala/sow.cgi?vid=';
    $url_log = 'http://werewolf.lovesick.jp/cabala/sow.cgi?cmd=oldlog';
    parent::__construct($cid,$url_vil,$url_log);
    $this->policy = false;
    $this->is_evil = true;
  }
}
