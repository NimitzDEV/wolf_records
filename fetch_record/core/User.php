<?php

class User
{
  //後でprivateに直す
  public   $persona
          ,$player
          ,$role
          ,$dtid
          ,$end
          ,$sklid
          ,$tmid
          ,$life
          ,$rltid
          ;
  use Properties;

  function __construct()
  {
  }

  function is_valid()
  {
    $list = get_object_vars($this);
    foreach($list as $key=>$item)
    {
      switch($key)
      {
      case 'dtid':
      case 'end':
      case 'sklid':
      case 'tmid':
      case 'rltid':
        if(empty($item) || !is_int($item))
        {
          $this->invalid_error($key,$item);
          return false;
        }
        break;
      case 'persona':
      case 'player':
      case 'role':
        if(empty($item) || !is_string($item) || !mb_check_encoding($item))
        {
          $this->invalid_error($key,$item);
          return false;
        }
        break;
      case 'life':
        if(empty($item) || !is_float($item))
        {
          $this->invalid_error($key,$item);
          return false;
        }
        break;
      }
    }
    return true;
  }
  function invalid_error($key,$item)
  {
    echo $key.' is invalid.->'.$item.PHP_EOL;
  }
}
