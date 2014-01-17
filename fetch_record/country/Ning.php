<?php

class Ning extends Country
{
  //private $fp;

  function __construct()
  {
    $cid = 9;
    $url_vil = "http://www.wolfg.x0.com/index.rb?vid=";
    $url_log = "http://www.wolfg.x0.com/index.rb?vid=";

    parent::__construct($cid,$url_vil,$url_log);
    //$this->fp = fopen(__DIR__.'/../queue/9.txt','r+');
    //if(flock($this->fp,LOCK_EX))
    //{
      //echo 'lock now.'.PHP_EOL;
    //}
  }

  //function write()
  //{
    //$string = 'okkk';
    //var_dump(fwrite($this->fp,$string));
    //fflush($this->fp);
    //flock($this->fp,LOCK_UN);
    //fclose($this->fp);
  //}
}
