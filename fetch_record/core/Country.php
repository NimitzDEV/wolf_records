<?php

abstract class Country
{
  use Doppel;
  protected  $check
            ,$cid
            ,$url
            ,$policy
            ,$village
            ,$fetch
            ,$cast
            ,$user
            ,$users = []
            ;
            
  protected function __construct($cid,$url_vil,$url_log)
  {
    $this->check = new Check_Village($cid,$url_vil,$url_log); 
    $this->cid = $cid;
    $this->url = $url_vil;
  }

  function insert()
  {
    $list = $this->check->get_village();
    //$list = [59];
    if(!$list)
    {
      $this->check->remove_queue();
      return;
    }
    $this->fetch = new simple_html_dom();
    //$kick = [15,16,26,35,40,43];
    foreach($list as $vno)
    {
      //if(array_search($vno,$kick)  !== false)
      //{
        //echo '※: '.$vno.' is kicked by $kick list.'.PHP_EOL;
        //continue;
      //}
      if($this->insert_village($vno))
      {
        //continue;
        $this->insert_users();
      }
      else
      {
        echo 'ERROR: '.$vno.'could not fetched.'.PHP_EOL;
        $this->fetch->clear();
        continue;
      }
      $this->fetch->clear();
      //continue;
      $db = new Insert_DB($this->cid);
      if(!$db->connect())
      {
        return;
      }
      if($db->insert_db($this->village,$this->users))
      {
        echo '★'.$this->village->vno.'. '.$this->village->name.' is all inserted.'.PHP_EOL;
      }
      $db->disconnect();
    }
    $this->check->remove_queue();
  }

  protected function insert_village($vno)
  {
    $this->village = new Village($this->cid,$vno);
    $this->fetch_village();
    if($this->village->is_valid())
    {
      return true;
    }
    else
    {
      return false;
    }
  }
  protected function insert_users()
  {
    $this->users = [];
    foreach($this->cast as $person)
    {
      $this->user = new User();
      $this->fetch_users($person);
      if(!$this->user->is_valid())
      {
        $this->output_comment('n_user');
      }
      //エラーでも歯抜けが起きないように入れる
      $this->users[] = $this->user;
    }
  }

  protected function fetch_policy()
  {
    $rp = '/[^A-Z+]RP|[^Ａ-Ｚ+]ＲＰ|[^ァ-ヾ+]ネタ村|[^ァ-ヾ+]ランダ村|[^ァ-ヾ+]ラ神|[^ァ-ヾ+]ランダム|[^ァ-ヾ+]テスト村|薔薇村|百合村|[^ァ-ヾ+]グリード村|[^A-Z+]GR村|[^Ａ-Ｚ+]ＧＲ村|スゴロク/u';
    if(preg_match($rp,$this->village->name))
    {
      $this->village->policy = false;
      $this->output_comment('rp');
    }
    else
    {
      $this->village->policy = true;
    }
  }
  protected function check_doppel($player)
  {
    $country = 'd_'.get_class($this);
    if(array_key_exists($player,$this->{$country}))
    {
      //echo '>NOTICE: '.$player.' is DOPPEL.->'.$this->{$country}[$player].PHP_EOL;
      return $this->{$country}[$player];
    }
    else
    {
      return $player;
    }
  }
  protected function fetch_life()
  {
    if($this->user->dtid === Data::DES_ALIVE)
    {
      $this->user->life = 1.000;
    }
    else if($this->user->tmid === Data::TM_ONLOOKER)
    {
      $this->user->life = 0.000;
    }
    else
    {
      $this->user->life = round(($this->user->end-1) / $this->village->days,3);
    }
  }
  protected function output_comment($type,$detail='')
  {
    switch($type)
    {
      case 'rp':
        $str =  'is guessed RP.';
        break;
      case 'free':
        $str = 'has '.$detail;
        break;
      case 'evil':
        $str = '▼Check EVIL.->'.$detail;
        break;
      case 'undefined':
        $str = 'has undefined ->'.$detail;
        break;
      case 'n_user':
        $str = 'NOTICE:'.$this->user->persona.' could not fetched.';
        break;
    }
    echo '>'.$this->village->vno.' '.$str.PHP_EOL;
  }

  abstract protected function fetch_village();
  abstract protected function fetch_name();
  abstract protected function fetch_date();
  abstract protected function fetch_nop();
  abstract protected function fetch_rglid();
  abstract protected function fetch_days();
  abstract protected function fetch_wtmid();

  abstract protected function make_cast();
  abstract protected function fetch_users($person);
}
