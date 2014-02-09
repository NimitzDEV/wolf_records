<?php

class Skill_Count
{
  use Properties;

  private  $name
          ,$win
          ,$lose
          ,$sum
          ,$rate
          ,$rp
          ;

  function __construct($name)
  {
    $this->name = $name;
  }
  function insert_count($result,$count)
  {
    if($result === 'onlooker' || $result === 'invalid')
    {
      return;
    }
    $this->{$result} = $count;
  }
}
