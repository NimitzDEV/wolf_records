<?php

class Real extends SOW
{
  function __construct()
  {
    $cid = 27;
    $url_vil = "http://real.gunjobiyori.com/sow.cgi?vid=";
    $url_log = "http://real.gunjobiyori.com/sow.cgi?cmd=oldlog";
    parent::__construct($cid,$url_vil,$url_log);
  }
  function fetch_policy()
  {
    $this->village->policy = true;
  }
}
