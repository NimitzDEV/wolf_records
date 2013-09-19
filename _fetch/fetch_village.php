<?php
require_once('../../lib/simple_html_dom.php');
require_once('./country.php');

class Fetch_Village
{
  //陣営 team
  const TM_NONE      = 0; //陣営なし
  const TM_VILLAGER  = 1; //村人陣営
  const TM_WOLF      = 2; //人狼陣営
  const TM_FAIRY     = 3; //妖魔陣営
  const TM_LOVERS    = 4; //恋人陣営
  const TM_LWOLF     = 6; //一匹狼陣営
  const TM_PIPER     = 7; //笛吹き陣営
  const TM_EFB       = 8; //邪気陣営


  private $country;

  function __construct($country)
  {
    $this->country = $country;
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

    return $vil_list;
  }

  function fetch_url($url)
  {
    $html = new simple_html_dom();
    $html->load_file($url);
    return $html;
  }

  function insert_winteam_id($winteam)
  {
    switch($this->country)
    {
      case GUTA:
        switch($winteam)
        {
          case '村人の勝利':
            return $this::TM_VILLAGER;
          case '人狼の勝利':
            return $this::TM_WOLF;
          case '妖精の勝利':
            return $this::TM_FAIRY;
          case '恋人達の勝利':
            return $this::TM_LOVERS;
          case '一匹狼の勝利':
            return $this::TM_LWOLF;
          case '笛吹き勝利':
            return $this::TM_PIPER;
          case '邪気の勝利':
            return $this::TM_EFB;
          case '勝利者なし':
            return $this::TM_NONE;
        }
        break;
    }
  }
}
