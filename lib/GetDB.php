<?

require_once('DBAdapter.php');

class GetDB
{

/*--------------------------------------------------------------------------------

  0. 定数・変数

--------------------------------------------------------------------------------*/

  //結果 rltid
  const RSL_WIN = 1; //勝利
  const RSL_LOSE = 2; //敗北
  const RSL_JOIN = 3; //参加(勝敗が付かない村)
  const RSL_INVALID = 4; //無効(議事国の突然死など)
  const RSL_ONLOOKER = 5; //見物

  //変数
  private $pdo;
  private $player;

  function GetDB($argID)
  {
    //エスケープ
    $this->player = $argID;
  }


/*--------------------------------------------------------------------------------

  1. データベース接続

--------------------------------------------------------------------------------*/

  function connect()
  {
    try{
      $this->pdo = new DBAdapter();
    } catch (pdoexception $e){
      var_dump($e->getMessage());
      exit;
    }
  }

  function disConnect()
  {
    $this->pdo = null;
    //echo '<br>---pdo DISconnected.---<br>';
  }


/*--------------------------------------------------------------------------------

  2. データ取得

--------------------------------------------------------------------------------*/


  //プレイヤーID取得
  function getPlayer(){
    return $this->player;
  }

  function fetchCountPlayer(&$sql){
    $sql->bindParam(':player',$this->player,PDO::PARAM_STR);
    $sql->execute();
    $count = $sql->fetch(PDO::FETCH_NUM);


    if(empty($count))
    {
      $count = array("-"); //平均値算出などでデータがない場合
    }

    return $count[0];
  }
  function fetchTable(&$sql){
    $sql->bindParam(':player',$this->player,PDO::PARAM_STR);
    $sql->execute();
    $table = $sql->fetchAll();
    

    return $table;

  }

  function fetchRoleTable($data,$element){
    
    if(!empty($data))
    {
      foreach ($data as $item)
      {
        $table[$item[$element]] = $item['c'];
      }
      return $table;

    } else {
      return false;
    }

  }

  //総合参加数
  function getJoin(){
    $sql = $this->pdo->prepare("SELECT count(*) FROM users WHERE player=:player");
    return $this->fetchCountPlayer($sql);
  }

  //prepare部分変数にする
  //ガチ参加数
  function getGachi(){
    $sql = $this->pdo->prepare("SELECT count(*) FROM users WHERE player=:player AND (rltid=1 OR rltid=2)"); //結果が勝利もしくは敗北のみ集計

    return $this->fetchCountPlayer($sql);
  }

  //RP参加数
  function getRP(){
    $sql = $this->pdo->prepare("SELECT count(*) FROM users WHERE player=:player AND rltid=3"); //結果が参加のみ集計

    return $this->fetchCountPlayer($sql);
  }

  //ガチ勝利数
  function getWin(){
    $sql = $this->pdo->prepare("SELECT count(*) FROM users WHERE player=:player AND rltid=1"); 
    return $this->fetchCountPlayer($sql);
  }

  //ガチ生存係数
  function getGachiLife(){
    $sql = $this->pdo->prepare("SELECT round(avg(life),2) FROM (SELECT * FROM users WHERE rltid=1 OR rltid= 2) as rlt  GROUP BY player HAVING player=:player");//結果が勝利もしくは敗北のみ集計

    return $this->fetchCountPlayer($sql);
  }

  //RP生存係数
  function getRPLife(){
    $sql = $this->pdo->prepare("SELECT round(avg(life),2) FROM (SELECT * FROM users WHERE rltid=3) as rlt GROUP BY player HAVING player=:player");//結果が勝利もしくは敗北のみ集計

    return $this->fetchCountPlayer($sql);
  }

  //戦績一覧取得
  function getTable(){
    $sql = $this->pdo->prepare("SELECT v.date AS date, c.name AS country, v.vno AS vno, 
      v.name AS vname,rgl.name AS rgl,u.persona AS persona, 
      u.role AS role,u.end AS end, d.name AS destiny,
      u.rltid AS result,c.url AS url
      FROM village v INNER JOIN country c ON v.cid=c.id
      INNER JOIN users u ON v.id=u.vid
      INNER JOIN regulation rgl ON v.rglid=rgl.id 
      INNER JOIN destiny d ON u.dtid=d.id
      WHERE player=:player ORDER BY v.date DESC,v.vno DESC;");

    return $this->fetchTable($sql);

  }

  function getTeamTable(){
    //存在する陣営しか出ない
    $sql = $this->pdo->prepare("SELECT u.tmid,t.name AS team,u.sklid,s.name AS skl FROM users AS u INNER JOIN skill AS s ON u.sklid=s.id INNER JOIN team AS t ON u.tmid=t.id WHERE player = :player GROUP BY s.name ORDER BY u.tmid,u.sklid");

    $table =  $this->fetchTable($sql);

    //陣営別にテーブルを作る
    //恋の場合、恋人teamテーブルに人狼などのroleセルが入る
    //ガチRP込みでどれだけ役職があるかを記録して、セルだけ作る
    foreach ($table as $item){
      ${$item['tmid']}[] = $item['skl'];
      $tmTable[$item['team']] = ${$item['tmid']};
    }

    return $tmTable;
  }


  function getTeamGachi(){
    $sql = $this->pdo->prepare("SELECT tmid,t.name AS team,count(*) AS c FROM (SELECT * FROM users WHERE rltid=1 or rltid=2) AS rlt INNER JOIN team AS t ON rlt.tmid=t.id WHERE player = :player GROUP BY tmid ORDER BY tmid");

    return $this->fetchRoleTable($this->fetchTable($sql),'team');
  }

  function getTeamGachiWin(){
    $sql = $this->pdo->prepare("SELECT tmid,t.name AS team,count(*) AS c FROM (SELECT * FROM users WHERE rltid=1) AS rlt INNER JOIN team AS t ON rlt.tmid=t.id WHERE player = :player GROUP BY tmid ORDER BY tmid");

    return $this->fetchRoleTable($this->fetchTable($sql),'team');
  }

  
  function getTeamRP(){
    $sql = $this->pdo->prepare("SELECT tmid,t.name AS team,count(*) AS c FROM (SELECT * FROM users WHERE rltid=3) AS rlt INNER JOIN team AS t ON rlt.tmid=t.id WHERE player = :player GROUP BY tmid ORDER BY tmid");

    return $this->fetchRoleTable($this->fetchTable($sql),'team');
  }

  function getSklGachi(){
    $sql = $this->pdo->prepare("SELECT u.sklid,s.name AS skl,count(*) AS c FROM users AS u INNER JOIN skill AS s ON u.sklid=s.id WHERE player = :player AND (u.rltid=1 OR u.rltid=2) GROUP BY s.name ORDER BY u.sklid");

    return $this->fetchRoleTable($this->fetchTable($sql),'skl');
  }
  
  function getSklWin(){
    $sql = $this->pdo->prepare("SELECT u.sklid,s.name AS skl,count(*) AS c FROM users AS u INNER JOIN skill AS s ON u.sklid=s.id WHERE player = :player AND u.rltid=1 GROUP BY s.name ORDER BY u.sklid");

    return $this->fetchRoleTable($this->fetchTable($sql),'skl');
  }

  function getSklRP(){
    $sql = $this->pdo->prepare("SELECT u.sklid,s.name AS skl,count(*) AS c FROM users AS u INNER JOIN skill AS s ON u.sklid=s.id WHERE player = :player AND u.rltid=3 GROUP BY s.name ORDER BY u.sklid");

    return $this->fetchRoleTable($this->fetchTable($sql),'skl');
  }
}
