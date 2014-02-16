<?php

class IDs
{
  use Properties;
  private $players
         ,$view_name;

  function is_valid_id($id)
  {
    //旧URL対策
    if (isset($id['player']) && $id['player'] !== "")
    {
      $this->view_name = $this->fix_id($id['player']);
      return true;
    }
    //複数IDを配列に入れる
    else if(isset($id['id_0']) && $id['id_0'] !=="")
    {
      for($i=0;$i<5;$i++)
      {
        if (isset($id['id_'.$i]) && $id['id_'.$i] !== "")
        {
          $views[] = $this->fix_id($id['id_'.$i]);
        }
      }
      $this->view_name = implode(', ',$views);
      return true;
    }
    //一番上のフォーム未入力
    else
    {
      return false;
    }
  }

  function fix_id($name)
  {
    $name = urldecode($name);
    if(mb_substr($name,-1,1,"utf-8") === ' ')
    {
      //末尾に半角スペースが入っている場合は変換する
      $player = htmlspecialchars($name);
      $this->players[] = preg_replace("/ /","&amp;nbsp;",$player);
      return preg_replace("/ /","&nbsp;",$player);
    }
    else
    {
      return $this->players[] = htmlspecialchars($name);
    }
  }
}
