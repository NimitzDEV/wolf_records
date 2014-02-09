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
          ;
  private  $skill = [];

  function __construct($name)
  {
    $this->name = $name;
  }
  function insert_count($item)
  {
    $this->insert_team_count($item['result'],$item['sum']);
    $this->insert_skill($item['skl'],$item['sid'],$item['result'],$item['count']);
  }
  private function insert_team_count($result,$count)
  {
    if($result === 'onlooker' || $result === 'invalid')
    {
      return;
    }
    $this->{$result} = $count;
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
}
