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
    if($this->village)
    {
      sort($this->village);
    }
    return $this->village;
  }

  function remove_queue()
  {
    if(empty($this->queue_del))
    {
      return;
    }
    $queue = $this->open_queue();
    foreach($this->queue_del as $vno)
    {
      $queue = preg_replace('/'.$this->cid.'_'.$vno.',/',"",$queue); 
    }
    ftruncate($this->fp,0);
    fseek($this->fp, 0);
    fwrite($this->fp,$queue);
    $this->close_queue();
  }

  private function open_queue()
  {
    $fname = __DIR__.'/queue.txt';
    if(is_writable($fname))
    {
      $this->fp = fopen($fname,'a+');
      flock($this->fp,LOCK_EX);
      $line = fgets($this->fp);
      return $line;
    }
    else
    {
      return false;
    }
  }
  private function close_queue()
  {
    if($this->fp)
    {
      fflush($this->fp);
      flock($this->fp,LOCK_UN);
      fclose($this->fp);
    }
  }

  private function check_queue()
  {
    $line = $this->open_queue();
    if($line && mb_strstr($line,$this->cid.'_'))
    {
      $this->queue = $line;
      $queue_array = explode(',',$line);
      array_pop($queue_array);
      foreach($queue_array as $item)
      {
        if(!mb_strstr($item,$this->cid.'_'))
        {
          continue;
        }
        $vno = preg_replace('/'.$this->cid.'_/','',$item);
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

  private function check_end($vno)
  {
    $this->html->load_file($this->url_vil.$vno);

    if($this->cid === Cnt::Ning)
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
  private function check_not_ruined($vno)
  {
    if($this->cid === Cnt::Ning)
    {
      return true;
    }
    if($this->cid === Cnt::Phantom)
    {
      echo $vno.' is ruined BUT inserted fetch list.'.PHP_EOL;
      return true;
    }
    $this->html->load_file($this->url_vil.$vno);
    switch($this->cid)
    {
      case Cnt::Plot:
      case Cnt::Ciel:
      case Cnt::Perjury:
        $last_day = $this->html->find('script',-2)->innertext;
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
      case Cnt::Sebas:
        $last_day = $this->html->find('p.turnnavi',0)->find('a',3)->plaintext;
        $this->html->clear();
        if($last_day  === "エピ")
        {
          return false;
        }
        else
        {
          return true;
        }
        break;
      case Cnt::Silence:
        $last_day = $this->html->find('p',0)->find('a',3)->plaintext;
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

  private function check_new_fetch()
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
          $this->village[] = (int)$vno;
        }
        else if($is_end)
        {
          echo $vno.' is ruined.'.PHP_EOL;
        }
        else
        {
          //終了していない村は一旦村番号をメモ
          if(!mb_strstr($this->queue,$this->cid.'_'.$vno))
          {
            fwrite($this->fp,$this->cid.'_'.$vno.',');
            echo $vno.' is proceeding. Inserted queue.'.PHP_EOL;
          }
        }
      }
    }
  }

  private function check_endlist()
  {
    $this->html->load_file($this->url_log);
    switch($this->cid)
    {
      case Cnt::Ning:
        $list_vno = $this->html->find('a',1)->plaintext;
        $list_vno =(int) preg_replace('/G(\d+) .+/','$1',$list_vno);
        break;
      case Cnt::Morphe:
      case Cnt::Xebec:
      case Cnt::Crazy:
      case Cnt::Guta:
      case Cnt::Sea:
      case Cnt::Sea_Old:
      case Cnt::Ivory:
      case Cnt::Crescent:
        $list_vno = (int)$this->html->find('tr.i_hover td',0)->plaintext;
        break;
      case Cnt::Plot:
      case Cnt::Ciel:
      case Cnt::Perjury:
        $list_vno = $this->html->find('tr',1)->find('td',0)->innertext;
        $list_vno = (int)preg_replace("/^(\d+) <a.+/","$1",$list_vno);
        break;
      case Cnt::Melon:
      case Cnt::Real:
      case Cnt::Rose:
      case Cnt::Cherry:
      case Cnt::Chitose:
      case Cnt::Chitose_RP:
      case Cnt::Phantom:
      case Cnt::Dark:
      case Cnt::BW:
        $list_vno = (int)preg_replace('/^(\d+) .+/','\1',$this->html->find('tbody td a',0)->plaintext);
        break;
      case Cnt::Sebas:
        $list_vno = (int)preg_replace('/^(\d+) .+/','\1',$this->html->find('tbody td a',1)->plaintext);
        break;
      case Cnt::Silence:
        $list_vno = (int)preg_replace('/^(\d+) .+/','\1',$this->html->find('td a',0)->plaintext);
        break;
    }
    $this->html->clear();
    return $list_vno;
  }
  private function check_db()
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
