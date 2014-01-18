<?php

class Ning extends Country
{
  private  $url_epi
          ,$cast
          ;
  private $doppel = 
    [
       "asaki"    =>"asaki&lt;G国&gt;"
      ,"motimoti" =>"motimoti&lt;G薔薇国&gt;"
    ];

  function __construct()
  {
    $cid = 9;
    $url_vil = "http://www.wolfg.x0.com/index.rb?vid=";
    $url_log = "http://www.wolfg.x0.com/index.rb?cmd=log";
    parent::__construct($cid,$url_vil,$url_log);
  }

  function fetch_village()
  {
    $this->fetch_from_pro();
    $this->fetch_from_epi();
  }

  function fetch_from_pro()
  {
    $this->fetch->load_file($this->url.$this->village->vno."&meslog=000_ready");

    $this->fetch_name();
    $this->fetch_date();
    $this->fetch_days();

    $this->fetch->clear();
  }
  function fetch_name()
  {
    $name = $this->fetch->find('title',0)->plaintext;
    $this->village->name = preg_replace('/人狼.+\d+ (.+)/','$1',$name);
  }
  function fetch_date()
  {
    $date = $this->fetch->find('div.ch1',0)->find('a',1)->name;
    $this->village->date = date("y-m-d",preg_replace('/mes(.+)/','$1',$date));
  }
  function fetch_days()
  {
    $url = preg_replace("/index\.rb\?vid=/","",$this->url);
    $this->url_epi = $url.$this->fetch->find('p a',-2)->href;
    $this->village->days = preg_replace("/.+=0(\d{2})_party/", "$1", $this->url_epi) + 1;
  }

  function fetch_from_epi()
  {
    $this->fetch->load_file($this->url_epi);
    if(!$this->fetch->find('div.announce'))
    {
      echo 'ERROR: '.$vno.' epilogue is broken. log didnt save.'.PHP_EOL;
      return;
    }
    $this->make_cast();

    $this->fetch_nop();
    $this->fetch_rglid();

    $this->fetch->clear();
  }

  function make_cast()
  {
    $cast = preg_replace("/\r\n/","",$this->fetch->find('div.announce',-1)->plaintext);
    //simple_html_domを抜けてきたタグを削除(IDに{}があるとbrやaが残る)
    $cast = preg_replace([ '/<br \/>/','/<a href=[^>]+>/','/<\/a>/' ],['','',''],$cast);
    $cast = explode('だった。',$cast);
    //最後のスペース削除
    $this->cast = array_pop($cast);
  }

  function fetch_nop()
  {
    $this->village->nop = count($this->cast);
  }
  function fetch_rglid()
  {
  }
  function fetch_wtmid()
  {
  }
}
