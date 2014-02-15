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

  function __construct()
  {
    $this->gachi = 0;
    $this->rp = 0;
    $this->sum = 0;
    $this->rate = 0;
    $this->live_gachi = 0;
    $this->live_rp = 0;
  }
  function calculate()
  {
    $this->set_sum();
    $this->set_gachi();
    $this->set_rate();
  }
  private function set_sum()
  {
    $this->sum = $this->win+$this->lose+$this->rp+$this->invalid+$this->onlooker;
  }
  private function set_gachi()
  {
    $this->gachi = $this->win + $this->lose;
  }
  private function set_rate()
  {
    if($this->win != 0)
    {
      $this->rate = round($this->win / $this->gachi,3) * 100;
    } else
    {
      $this->rate = 0;
    }
  }
}
