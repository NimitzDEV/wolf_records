<?php

class CheckID
{
  private $isID;
  private $playerArr;
  private $viewName;


  function __construct($argID)
  {
    //旧URL対策
    if (isset($argID['player']) && $argID['player'] !== "")
    {
      $this->viewName = $this->fixGetID($argID['player']);
      $this->isID = true;
    }
    //複数IDを配列に入れる
    else if(isset($argID['id_0']) && $argID['id_0'] !=="")
    {
      for($i=0;$i<5;$i++)
      {
        if (isset($argID['id_'.$i]) && $argID['id_'.$i] !== "")
        {
          $viewArray[] = $this->fixGetID($argID['id_'.$i]);
        }
      }
      $this->viewName = implode(', ',$viewArray);
      $this->isID = true;
    }
    //一番上のフォーム未入力
    else
    {
      $this->isID = false;
    }
  }

  function fixGetID($argName)
  {
    if(mb_substr($argName,-1,1,"utf-8") === ' ')
    {
      //末尾に半角スペースが入っている場合は変換する
      $player = htmlspecialchars($argName);
      $this->playerArr[] = preg_replace("/ /","&amp;nbsp;",$player);
      return preg_replace("/ /","&nbsp;",$player);
    }
    else
    {
      return $this->playerArr[] = htmlspecialchars($argName);
    }
  }

  function getIsID()
  {
    return $this->isID;
  }
  function getPlayerArr()
  {
    return $this->playerArr;
  }
  function getViewName()
  {
    return $this->viewName;
  }
}
