<?php

trait Properties
{
  function __set($name,$value)
  {
    if(array_key_exists($name,get_object_vars($this)))
    {
      $this->{$name} = $value;
    }
    else
    {
      $trace = debug_backtrace();
      trigger_error(
        'Undefined property via __get(): '.$name.' in '.$trace[0]['file'].
        ' on line '.$trace[0]['line'],
        E_USER_NOTICE);
    }
  }
  function __get($name)
  {
    if(array_key_exists($name,get_object_vars($this)))
    {
      return $this->{$name};
    }
    else
    {
      $trace = debug_backtrace();
      trigger_error(
        'Undefined property via __get(): '.$name.' in '.$trace[0]['file'].
        ' on line '.$trace[0]['line'],
        E_USER_NOTICE);
      return null;
    }
  }
}
