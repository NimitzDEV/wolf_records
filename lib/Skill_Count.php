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
    $this->{$result} = $count;
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
  }
}
