<?php

class Join_Count
{
  use Properties;

  private  $win
          ,$lose
          ,$rate
          ,$gachi
          ,$rp
          ,$invalid
          ,$onlooker
          ,$live_gachi
          ,$live_rp
          ,$sum
          ;

  function calculate()
  {
    $this->set_sum();
    $this->set_gachi();
    $this->set_rate();
  }
  function set_sum()
  {
    $this->sum = $this->win+$this->lose+$this->rp+$this->invalid+$this->onlooker;
  }
  function set_gachi()
  {
    $this->gachi = $this->win + $this->lose;
  }
  function set_rate()
  {
    if($this->win !== 0)
    {
      $this->rate = round($this->win / $this->gachi,3) * 100;
    } else
    {
      $this->rate = 0;
    }
  }
}
