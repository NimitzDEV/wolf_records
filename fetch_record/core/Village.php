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
  use Properties;

  function __construct($cid,$vno)
  {
    $this->cid = $cid;
    $this->vno = $vno;
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
          $this->invalid_error($key,$item);
          return false;
        }
        break;
      case 'name':
        if(empty($item) || !is_string($item) || !mb_check_encoding($item))
        {
          $this->invalid_error($key,$item);
          return false;
        }
        break;
      case 'date':
        if(empty($item) || !preg_match('/\d{2}-\d{2}-\d{2}/',$item))
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
