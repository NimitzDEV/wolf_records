<?php
require_once('../../lib/simple_html_dom.php');

class Fetch_Village
{
  //テキスト分割行
  const LIMIT = 400;

  private $country
        , $fp_village
        , $fp_users
        , $count_village
        , $count_users
        , $lastline_v
        , $nop
        , $loop_village
        , $loop_users;

  function __construct($country)
  {
    $this->country = $country;
    $this->loop_village = 0;
    $this->loop_users = 0;
  }

  function read_list()
  {
    $filename = 'list_'.$this->country.'.txt';

    if (!file_exists($filename))
    {
      echo "ERROR: ".$filename." is not found.";
      exit;
    }

    $list = fopen($filename,"r");
    while($line = fgets($list))
    {
      $line = preg_replace("/\n/","",$line);
      $vil_list[] = explode(",",$line);
    }
    fclose($list);

    $this->lastline_v = count($vil_list);

    return $vil_list;
  }

  function fetch_url($url)
  {
    $html = new simple_html_dom();
    $html->load_file($url);
    return $html;
  }

  function open_list($type)
  {
    $this->{'loop_'.$type}++;
    $this->{'fp_'.$type} = fopen($this->country.$type.'_'.$this->{'loop_'.$type}.'.sql','w+');
    flock($this->{'fp_'.$type},LOCK_EX);
    switch($type)
    {
      case 'village';
        fwrite($this->fp_village,"INSERT INTO village (cid,vno,name,date,nop,rglid,days,wtmid) VALUES\n");
        break;
      case 'users';
        fwrite($this->fp_users,"INSERT INTO users (vid,persona,player,role,dtid,end,sklid,tmid,life,rltid) VALUES\n");
        break;
    }
  }

  function write_list($type,$array,$val,$nop=0)
  {
    $line = implode('","',$array);
    switch($type)
    {
      case 'village';
        $line = '("'.$this->country.'","'.$line.'")';
        $this->nop = (int)$nop;
        $this->count_village = $val;
        if($val === $this->lastline_v)
        {
          $this->close_list($line,$type);
          return;
        }
        break;
      case 'users';
        $line = '("'.$line.'")';
        $this->count_users++;
        if($val === $this->nop && $this->count_village === $this->lastline_v)
        {
          $this->close_list($line,$type);
          return;
        }
        break;
    }

    if($this->{'count_'.$type} === $this::LIMIT * $this->{'loop_'.$type})
    {
      //規定行を越えたら次のファイルに移動
      $this->close_list($line,$type);
      $this->open_list($type);
    }
    else
    {
      fwrite($this->{'fp_'.$type},$line.",\n");
    }
  }

  function close_list($line,$type)
  {
    fwrite($this->{'fp_'.$type},$line.";");
    flock($this->{'fp_'.$type},LOCK_UN);
    fflush($this->{'fp_'.$type});
    fclose($this->{'fp_'.$type});
  }
}
