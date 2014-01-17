<?php

require_once('simple_html_dom.php');
require_once('data.php');
mb_internal_encoding("UTF-8");

class Insert_Destiny
{
  private  $url
          ,$days;

  function __construct($url_vil,$vno,$days)
  {
    $this->url = $url_vil.$vno;
    $this->days = $days - 1; //初日=0
  }

  function make_destiny(&$cast)
  {
    $data = new Data();
    $characters = array_keys($cast);
    for($i=1; $i<=$this->days; $i++) //2d=001から始まる
    {
      $fetch = new simple_html_dom();
      $fetch->load_file($this->make_url($i));
      $announce = $fetch->find('div.announce');

      foreach($announce as $item)
      {
        $destiny = mb_substr(trim($item->plaintext),-6,6);
        $destiny = preg_replace("/\r\n/","",$destiny);

        switch($destiny)
        {
          case "突然死した。":
            $persona = preg_replace("/^ ?(.+) は、突然死した。 ?/", "$1", $item->plaintext);
            $cast[$persona] += array('destiny'=>$data::DES_RETIRED);
            break;
          case "処刑された。":
            $persona = preg_replace("/(.+\r\n){1,}\r\n(.+) は村人達の手により処刑された。 ?/", "$2", $item->plaintext);
            $cast[$persona] += array('destiny'=>$data::DES_HANGED);
            break;
          case "発見された。":
            $persona = preg_replace("/.+朝、(.+) が無残.+\r\n ?/", "$1", $item->plaintext);
            $cast[$persona] += array('destiny'=>$data::DES_EATEN);
            break;
          default:
            continue;
        }   
        $cast[$persona] += array('end'=>$i+1);
        unset($characters[array_search($persona,$characters)],$item);
      }
      $fetch->clear();
      unset($announce,$fetch);

    }
    unset($fetch);

    //destinyとendが空のペルソナを生存扱いにする
    foreach($characters as $item)
    {
      $cast[$item] += array('destiny'=>$data::DES_ALIVE);
      $cast[$item] += array('end'=>$this->days+1);
    }
  }

  function make_url($day)
  {
    if($day === $this->days)
    {
      $suffix = '_party';
    }
    else
    {
      $suffix = '_progress';
    }
    $day = str_pad($day,3,"0",STR_PAD_LEFT);

    return $this->url.'&meslog='.$day.$suffix;
  }
}

