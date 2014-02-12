<?php

class Cherry extends Country
{
  use TR_SOW,AR_SOW,TR_SOW_RGL;
  private $rp_array = [242,240,234,232,231,227,224,219,216,213,209,205,201,200,199,193,188,186,181,175,174,170,166,161,156,155,151,145,144,140,137,130,127,121,115,109,103,100,93,85,79,72,68,63,58,51,50,37,29,22,19,17,16,15,14,9,7,6,5,4,2];

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
  protected function fetch_policy()
  {
    //自動取得時はタイトルを表示させてtrue
    //if(preg_match('/RP村|ＲＰ村/',$this->village->name))
    if(in_array($this->village->vno,$this->rp_array))
    {
      $this->village->policy = false;
    }
    else
    {
      $this->village->policy = true;
    }
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
    $this->user->player  = $person->find('td a',0)->plaintext;
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
    if(preg_match('/\r\n/',$role))
    {
      $this->user->role = mb_ereg_replace('(.+) \(.+\)\r\n.+','\1',$role);
    }
    else
    {
      $this->user->role = mb_ereg_replace('(.+) \(.+\)','\1',$role);
    }
  }
}
