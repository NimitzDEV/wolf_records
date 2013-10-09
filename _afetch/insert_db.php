<?php

require_once('../lib/DBAdapter.php');


class Insert_DB
{
  private $cid
         ,$pdo;

  function __construct($cid)
  {
    $this->cid = $cid;
  }

  function connect()
  {
    try{
      $this->pdo = new DBAdapter();
      return true;
    } catch (pdoexception $e){
      var_dump($e->getMessage());
      exit;
    }
  }

  function disconnect()
  {
    $this->pdo = null;
  }

  function insert_db($village,$cast)
  {
    $vid = $this->insert_village(array_values($village));
    
    if($vid)
    {
      if(!$this->insert_user($vid,$cast))
      {
        return false;
      }
    }
    else
    {
      return false;
    }
    return true;
  }

  function insert_village($village)
  {
    $vno = $village[1];

    //村が登録済でないかチェック
    $stmt = $this->pdo->prepare("SELECT vno FROM village where cid=:cid AND vno=:vno");
    $stmt->bindValue(':cid',$this->cid,PDO::PARAM_INT);
    $stmt->bindValue(':vno',$vno,PDO::PARAM_INT);
    $stmt->execute();
    if(!$stmt->fetch(PDO::FETCH_NUM))
    {
      $stmt = $this->pdo->prepare("
        INSERT INTO village(cid,vno,name,date,nop,rglid,days,wtmid) VALUES (?,?,?,?,?,?,?,?)
      ");

      if($stmt->execute($village))
      {
        $stmt = $this->pdo->prepare("SELECT id FROM village WHERE cid=:cid AND vno=:vno");
        $stmt->bindValue(':cid',$this->cid,PDO::PARAM_INT);
        $stmt->bindValue(':vno',$vno,PDO::PARAM_INT);
        $stmt->bindColumn(1,$vid);
        $stmt->execute();
        $stmt->fetch(PDO::FETCH_BOUND);

        return $vid;
      }
      else
      {
        echo 'ERROR: No. '.$vno.' not inserted.=EOL='.PHP_EOL;
        return false;
      }
    }
    else
    {
      echo 'ERROR: vno.'.$vno.' is already inserted.';
      return false;
    }
  }

  function insert_user($vid,$cast)
  {
    foreach($cast as $item)
    {
      $stmt = $this->pdo->prepare("
        INSERT INTO users (vid,persona,player,role,dtid,end,sklid,tmid,life,rltid) values (?,?,?,?,?,?,?,?,?,?)
      ");

      $item = array_values($item);
      array_unshift($item,$vid);
      if(!$stmt->execute($item))
      {
        echo '>>ERROR:'.$item[1].'/'.$item[2].' in vid:'.$vid.' was NOT inserted'.PHP_EOL;
        return false;
      }
    }
    return true;
  }
}
