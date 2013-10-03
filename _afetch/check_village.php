<?php

require_once('../lib/DBAdapter.php');
require_once('../../lib/simple_html_dom.php');

class Check_Village
{
  private $country;
  private $cid;
  private $url_log;
  private $url_vil;
  private $queue_org;
  private $queue_crr;
  private $village;
  private $html;

  function __construct($country)
  {
    $this->country = $country;
    switch($this->country)
    {
      case 'ninjin_g':
        $this->url_log = "http://www.wolfg.x0.com/index.rb?cmd=log";
        $this->url_vil = "http://www.wolfg.x0.com/index.rb?vid=";
        $this->cid     = 9;
        break;
      case 'guta':
        $this->url_log = "http://www3.marimo.or.jp/~fgmaster/cabala/sow.cgi?cmd=oldlog";
        $this->url_vil = "http://www3.marimo.or.jp/~fgmaster/cabala/sow.cgi?vid=";
        $this->cid     = 11;
        break;
    }
    $this->html = new simple_html_dom();
  }

  function get_village()
  {
    return $this->village;
  }

  function check_queue()
  {
    $fp = fopen('q_'.$this->country.'.txt','r');
    if(flock($fp,LOCK_EX))
    {
      $this->queue_org = fgets($fp);
      $this->queue_crr = $this->queue_org;

      if($this->queue_crr!== null)
      {
        $queue_array = explode(',',$this->queue_crr);
        array_pop($queue_array);
        foreach($queue_array as $vno)
        {
          if($this->check_end($vno))
          {
            //存在した村番号を削除
            $this->queue_crr = preg_replace('/'.$vno.',/',"",$this->queue_crr);
            $this->village[] = $vno;
          }
          else
          {
            echo 'NOTICE: No.'.$vno.' in queue is still proceeding.'.PHP_EOL;
          }
        }
      }
    }
    else
    {
      echo 'ERROR: Cannot lock queue.'.PHP_EOL;
      fclose($fp);
      exit;
    }
    fflush($fp);
    flock($fp,LOCK_UN);
    fclose($fp);
  }

  function check_end($vno)
  {
    $url = $this->url_vil.$vno;
    $this->html->load_file($url);
    switch($this->country)
    {
      case 'ninjin_g':
        $last_page = trim($this->html->find('span.time',0)->plaintext);
        break;
      case 'guta':
        $last_page = $this->html->find('p.caution',0);
        if($last_page)
        {
          $last_page = trim(mb_substr($last_page->plaintext,0,3));
        }
        break;
    }
    $this->html->clear();
    if($last_page === "終了")
    {
      return true;
    }
    else
    {
      return false;
    }
  }

  function check_fetch_vno()
  {
    $list_vno = $this->check_list_vno();
    $db_vno = $this->check_db_vno();

    if($list_vno > $db_vno)
    {
      $fetch_n  = $list_vno - $db_vno;
      for ($i=1;$i<=$fetch_n;$i++)
      {
        $vno = 0;
        $vno = $db_vno + $i;

        if(check_end($vno))
        {
          $this->village[] = $vno;
        }
        else
        {
          //終了していない村は一旦村番号をメモ
          $fp = fopen('q_'.$country.'.txt','a+');
          if(flock($fp,LOCK_SH))
          {
            if(fwrite($fp,$vno.','))
            {
              echo 'NOTICE: '.$vno.' is proceeding. Inserted queue.'.PHP_EOL;
            }
            else
            {
              echo 'ERROR:'.$vno.' Cannot write queue.'.PHP_EOL;
              fflush($fp);
              flock($fp,LOCK_UN);
              fclose($fp);
              exit;
            }
          }
          else
          {
            echo 'ERROR:'.$vno.' Cannot lock queue.'.PHP_EOL;
            fclose($fp);
            exit;
          }
          fflush($fp);
          flock($fp,LOCK_UN);
          fclose($fp);
        }
      }
    }
  }

  function check_list_vno()
  {
    $this->html->load_file($this->url_log);
    switch($this->country)
    {
      case 'ninjin_g':
        $list_vno = $this->html->find('a',1)->plaintext;
        $list_vno =(int) preg_replace('/G(\d+) .+/','$1',$list_vno);
        break;
      case 'guta':
        $list_vno = (int)$this->html->find('tr.i_hover td',0)->plaintext;
        break;
    }
    $this->html->clear();
    return $list_vno;
  }

  function check_db_vno()
  {
    try{
      $pdo = new DBAdapter();
    } catch (pdoexception $e){
      var_dump($e->getMessage());
      exit;
    }

    //DBから一番最後に取得した村番号を取得
    $sql = $pdo->prepare("SELECT MAX(vno) FROM village where cid=".$this->cid);
    $sql->execute();
    $db_vno= $sql->fetch(PDO::FETCH_NUM);
    $pdo = null;

    return (int)$db_vno[0];
  }

}
