<?php

class Cherry extends Country
{
  use TR_SOW,AR_SOW,TR_SOW_RGL;

  function __construct()
  {
    $cid = 30;
    $url_vil = "http://mirage.sakuratan.com/sow.cgi?vid=";
    $url_log = "http://mirage.sakuratan.com/sow.cgi?cmd=oldlog";
    parent::__construct($cid,$url_vil,$url_log);
  }
  protected function fetch_rp()
  {
    $this->village->rp = 'SOW';
  }
  protected function make_cast()
  {
    $cast = $this->fetch->find('table tr');
    array_shift($cast);
    //見物人がいるなら「見物人」見出しを削除する
    if(count($cast) !== $this->village->nop)
    {
      unset($cast[$this->village->nop]);
      $cast = array_merge($cast);
    }
    $this->cast = $cast;
  }
  protected function fetch_users($person)
  {
    $this->user->persona = trim($person->find('td',0)->plaintext);
    $this->fetch_player($person);
    $this->fetch_role($person);

    if($this->user->role === '見物人')
    {
      $this->insert_onlooker();
    }
    else
    {
      $this->fetch_sklid();
      $this->fetch_rltid();
      if($person->find('td',3)->plaintext === '生存')
      {
        $this->insert_alive();
      }
    }
  }
  protected function fetch_role($person)
  {
    $role = $person->find('td',4)->plaintext;
    $this->user->role = mb_ereg_replace('\A(.+) \(.+\)(.+|)','\1',$role,'m');
  }
}
