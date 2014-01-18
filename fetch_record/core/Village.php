<?php

class Village
{
  //後でprivateに直す
  public  $cid
          ,$vno
          ,$name
          ,$date
          ,$nop
          ,$rglid
          ,$days
          ,$wtmid
          ;

  function __construct($cid,$vno)
  {
    $this->cid = $cid;
    $this->vno = $vno;
  }

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

  function is_valid()
  {
    $list = get_object_vars($this);
    foreach($list as $key=>$item)
    {
      switch($key)
      {
      case 'cid':
      case 'vno':
      case 'nop':
      case 'rglid':
      case 'days':
      case 'wmtid':
        if(empty($item) || !is_int($item))
        {
          echo $key.' is invalid.->'.$item.PHP_EOL;
          return false;
        }
        break;
      case 'name':
        if(empty($item) || !is_string($item) || !mb_check_encoding($item))
        {
          echo $key.' is invalid.->'.$item.PHP_EOL;
          return false;
        }
        break;
      case 'date':
        if(empty($item) || !preg_match('/\d{4}-\d{2}-\d{2}/',$item))
        {
          echo $key.' is invalid.->'.$item.PHP_EOL;
          return false;
        }
        break;
      }
    }
    return true;
  }
}
