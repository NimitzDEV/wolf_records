<?php

require_once('../lib/DBAdapter.php');
require_once('../../lib/simple_html_dom.php');
mb_internal_encoding("UTF-8");

class Check_Village
{
  private $country
          ,$cid
          ,$url_log
          ,$url_vil
          ,$queue_org
          ,$queue_del
          ,$list_vno
          ,$village
          ,$village_d
          ,$html;

  function __construct($country,$cid,$url_vil,$url_log)
  {
    $this->country = $country;
    $this->cid     = $cid;
    $this->url_vil = $url_vil;
    $this->url_log = $url_log;
    $this->queue_del = array();

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

      if($this->queue_org !== null)
      {
        $queue_array = explode(',',$this->queue_org);
        array_pop($queue_array);
        foreach($queue_array as $vno)
        {
          $is_end = $this->check_end($vno);
          if($is_end && $this->check_not_ruined($vno))
          {
            $this->village[] = (int)$vno;
            $this->queue_del[] = (int)$vno;
          }
          else if($is_end)
          {
            $this->queue_del[] = (int)$vno;
            echo $country.$vno.' is ruined.'.PHP_EOL;
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

  function remove_queue($vno)
  {
    if(in_array($vno,$this->queue_del))
    {
      $fp = fopen('q_'.$this->country.'.txt','r+');
      if(flock($fp,LOCK_EX))
      {
        $queue = fgets($fp);
        $queue = preg_replace('/'.$vno.',/',"",$queue);

        //変更内容を書き込む
        ftruncate($fp,0);
        fseek($fp, 0);
        fwrite($fp,$queue);
      }
      else
      {
        echo 'ERROR: Cannot lock queue.'.$vno.' was not deleted from queue.'.PHP_EOL;
        fclose($fp);
      }
      fflush($fp);
      flock($fp,LOCK_UN);
      fclose($fp);
    }
  }

  function check_end($vno)
  {
    $url = $this->url_vil.$vno;
    $this->html->load_file($url);
    if($this->country  === 'ninjin_g')
    {
      $last_page = trim($this->html->find('span.time',0)->plaintext);
    }
    else
    {
      $last_page = mb_substr($this->html->find('title',0)->plaintext,0,2);
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

  function check_not_ruined($vno)
  {
    if($this->country === 'ninjin_g')
    {
      return true;
    }
    $url = $this->url_vil.$vno;
    $this->html->load_file($url);
    if($this->country === 'plot' || $this->country  === 'ciel')
    {
      $base = mb_convert_encoding($this->html->find('script',-2)->innertext,"UTF-8","SJIS");
      $last_day = preg_replace('/.+"turn": (\d+).+/s',"$1",$base);
      $this->html->clear();
      if($last_day == '1')
      {
        return false;
      }
      else
      {
        return true;
      }
    }
    else
    {
      $last_day = $this->html->find('p.turnnavi',0)->find('a',2)->plaintext;
      $this->html->clear();
      if($last_day  === "エピローグ")
      {
        return false;
      }
      else
      {
        return true;
      }
    }
  }

  function check_fetch_vno()
  {
    $this->list_vno = $this->check_list_vno();
    $db_vno = $this->check_db_vno();

    if($this->list_vno > $db_vno)
    {
      $fetch_n  = $this->list_vno - $db_vno;
      for ($i=1;$i<=$fetch_n;$i++)
      {
        $vno = 0;
        $vno = $db_vno + $i;
        $is_end = $this->check_end($vno);

        if($is_end && $this->check_not_ruined($vno))
        {
          $this->village[] = $vno;
        }
        else if($is_end)
        {
          echo $country.$vno.' is ruined.'.PHP_EOL;
        }
        else
        {
          //終了していない村は一旦村番号をメモ
          $fp = fopen('q_'.$this->country.'.txt','a+');
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
      case 'perjury':
      case 'xebec':
      case 'crazy':
      case 'morphe':
        $list_vno = (int)$this->html->find('tr.i_hover td',0)->plaintext;
        break;
      case 'plot':
      case 'ciel':
        $list_vno = $this->html->find('tr',1)->find('td',0)->innertext;
        $list_vno = (int)preg_replace("/^(\d+) <a.+/","$1",$list_vno);
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
