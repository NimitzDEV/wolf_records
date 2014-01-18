<?php

class Check_Village
{
  private  $cid
          ,$url_log
          ,$url_vil
          ,$queue
          ,$queue_del = []
          ,$village = []
          ,$html
          ,$fp
          ;

  function __construct($cid,$url_vil,$url_log)
  {
    $this->cid     = $cid;
    $this->url_vil = $url_vil;
    $this->url_log = $url_log;

    $this->html = new simple_html_dom();
  }

  function get_village()
  {
    $this->check_queue();
    $this->check_new_fetch();
    $this->close_queue();
    return $this->village;
  }

  function open_queue()
  {
    $fname = __DIR__.'/../queue/'.$this->cid.'.txt';
    if(is_writable($fname))
    {
      $this->fp = fopen($fname,'a+');
      flock($this->fp,LOCK_EX);
      $line = fgets($this->fp);
      if($line !== null)
      {
        return $line;
      }
      else
      {
        return null;
      }
    }
    else
    {
      if(!file_exists($fname))
      {
        fopen($fname,'w');
        echo 'NOTICE: '.$this->cid.'.txt is not exist. Now make it.'.PHP_EOL;
        return false;
      }
      else
      {
        return false;
      }
    }
  }

  function close_queue()
  {
    fflush($this->fp);
    flock($this->fp,LOCK_UN);
    fclose($this->fp);
  }

  function check_queue()
  {
    $line = $this->open_queue();
    if($line)
    {
      $this->queue = $line;
      $queue_array = explode(',',$this->queue);
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
          echo $vno.' is ruined.'.PHP_EOL;
        }
        else
        {
          echo $vno.' in queue is still proceeding.'.PHP_EOL;
        }
      }
    }
    else
    {
      return false;
    }
  }

  function check_queue_del($vno)
  {
    if(in_array($vno,$this->queue_del))
    {
      return true;
    }
    else
    {
      return false;
    }
  }

  function remove_queue($vno)
  {
    $this->open_queue();
    $queue = preg_replace('/'.$vno.',/',"",$this->queue);

    ftruncate($this->fp,0);
    fseek($this->fp, 0);
    fwrite($this->fp,$queue);
    $this->close_queue();
  }

  function check_end($vno)
  {
    $this->html->load_file($this->url_vil.$vno);
    if($this->cid === Cnt::NING)
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
    if($this->cid === Cnt::NING)
    {
      return true;
    }
    $this->html->load_file($this->url_vil.$vno);
    switch($this->cid)
    {
      case Cnt::PLOT:
      case Cnt::CIEL:
        $last_day = mb_convert_encoding($this->html->find('script',-2)->innertext,"UTF-8","SJIS");
        $last_day = preg_replace('/.+"turn": (\d+).+/s',"$1",$last_day);
        $this->html->clear();
        if($last_day == '1')
        {
          return false;
        }
        else
        {
          return true;
        }
        break;
      default:
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
        break;
    }
  }

  function check_new_fetch()
  {
    $list_vno = $this->check_endlist();
    $db_vno = $this->check_db();

    if($list_vno > $db_vno)
    {
      $fetch_n  = $list_vno - $db_vno;
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
          echo $vno.' is ruined.'.PHP_EOL;
        }
        else
        {
          //終了していない村は一旦村番号をメモ
          if(!preg_match('/'.$vno.'/',$this->queue))
          {
            fwrite($this->fp,$vno.',');
            $this->queue .= $vno.',';
            echo $vno.' is proceeding. Inserted queue.'.PHP_EOL;
          }
        }
      }
    }
  }

  function check_endlist()
  {
    $this->html->load_file($this->url_log);
    switch($this->cid)
    {
      case Cnt::NING:
        $list_vno = $this->html->find('a',1)->plaintext;
        $list_vno =(int) preg_replace('/G(\d+) .+/','$1',$list_vno);
        break;
      case Cnt::GUTA:
      case Cnt::GUTAP:
      case Cnt::MORPHE:
      case Cnt::PERJURY:
      case Cnt::XEBEC:
      case Cnt::CRAZY:
        $list_vno = (int)$this->html->find('tr.i_hover td',0)->plaintext;
        break;
      case Cnt::PLOT:
      case Cnt::CIEL:
        $list_vno = $this->html->find('tr',1)->find('td',0)->innertext;
        $list_vno = (int)preg_replace("/^(\d+) <a.+/","$1",$list_vno);
        break;
      case Cnt::MELON:
        $list_vno = (int)preg_replace('/^(\d+) .+/','\1',$this->html->find('tbody td a',0)->plaintext);
        break;
    }
    $this->html->clear();
    return $list_vno;
  }

  function check_db()
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