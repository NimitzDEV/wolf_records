<?php

abstract class Country
{
  protected  $check
            ;
            
  protected function __construct($cid,$url_vil,$url_log)
  {
    $this->check = new Check_Village($cid,$url_vil,$url_log); 
  }
}
