<?php

class Team_Count
{
  use Properties;

  private  $name
          ,$win
          ,$lose
          ,$sum
          ,$rate
          ,$rp
          ,$css
          ;
  private  $skill = [];

  function __construct($name,$css)
  {
    $this->name = $name;
    $this->css = $css;
  }
  function insert_count($item)
  {
    $this->insert_team_count($item['result'],$item['sum']);
    $this->insert_skill($item['skl'],$item['sid'],$item['result'],$item['count']);
  }
  private function insert_team_count($result,$count)
  {
    $this->{$result} = (int)$count;
  }
  private function insert_skill($skill,$sid,$result,$count)
  {
    $sid = (int)$sid;
    if(!isset($this->skill[$sid]))
    {
      ${'skl'.$sid} = new Skill_Count($skill);
      $this->skill[$sid] = ${'skl'.$sid};
    }
      $this->skill[$sid]->insert_count($result,$count);
  }
  function insert_sum()
  {
    $this->sum = $this->win + $this->lose;
    if($this->win)
    {
      $this->rate = round($this->win / $this->sum,3) * 100;
    }
    else
    {
      $this->win = 0;
      $this->rate = 0;
    }
    $this->insert_skl_sum();
  }
  private function insert_skl_sum()
  {
    foreach($this->skill as $key =>$item)
    {
      $this->skill[$key]->insert_sum();
    }
  }
}
