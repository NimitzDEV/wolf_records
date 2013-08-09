<?

require_once('DBAdapter.php');

class GetDB
{

/*--------------------------------------------------------------------------------

  0. 定数・変数

--------------------------------------------------------------------------------*/

  //結果 rltid
  //const RSL_WIN = 1; //勝利
  //const RSL_LOSE = 2; //敗北
  //const RSL_JOIN = 3; //参加(勝敗が付かない村)
  //const RSL_INVALID = 4; //無効(議事国の突然死など)
  //const RSL_ONLOOKER = 5; //見物

  private $pdo;
  private $player;

  private $joinSum;
  private $joinGachi;
  private $joinWin;
  private $joinLose;
  private $joinRP;
  
  private $liveGachi;
  private $liveRP;

  function GetDB($argID)
  {
    $this->player = $argID;
    $this->joinSum = 0;
    $this->joinGachi = 0;
    $this->joinWin = 0;
    $this->joinLose = 0;
    $this->joinRP = 0;
    $this->liveGachi = 0.00;
    $this->liveRP = 0.00;
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
  }


/*--------------------------------------------------------------------------------

  2. データ取得

--------------------------------------------------------------------------------*/


  //プレイヤーID取得
  //function getPlayer(){
    //return $this->player;
  //}

  function fetchJoinCount()
  {
    //DBから情報を取得
    $sql = $this->pdo->prepare("
      SELECT r.name, count(*) AS count FROM users AS u INNER JOIN result AS r ON r.id=u.rltid
      WHERE player=:player GROUP BY rltid
      UNION
      SELECT CASE rltid
        WHEN 1 THEN 'ガチ生存'
        WHEN 2 THEN 'ガチ生存'
        WHEN 3 THEN 'RP生存'
        ELSE 'no'
      END AS rlt,
      truncate(avg(life)+0.005,2) FROM users WHERE player=:player GROUP BY rlt
      UNION
      SELECT rltid,count(*) FROM users WHERE player=:player
      ");
    $sql->bindParam(':player',$this->player,PDO::PARAM_STR);
    $sql->execute();
    $table = $sql->fetchALL();


    if(isset($table[0]['name']))
    {
      foreach($table as $item)
      {
        switch($item['name'])
        {
          case '勝利':
            $this->joinWin = $item['count'];
            break;
          case '敗北':
            $this->joinLose = $item['count'];
            break;
          case '参加':
            $this->joinRP = $item['count'];
            break;
          case 'ガチ生存':
            $this->liveGachi = $item['count'];
            break;
          case 'RP生存':
            $this->liveRP = $item['count'];
            break;
          case 'no':
            break;
          default:  //総合参加数
            $this->joinSum = $item['count'];
            break;
        }
      }
      return true;
    }
    else
    {
      return false;
    }
  }

  function getJoinWin()
  {
    return $this->joinWin;
  }

  function getJoinGachi()
  {
    return $this->joinWin + $this->joinLose; 
  }

  function getJoinWinPercent()
  {
    if($this->joinWin  !== 0)
    {
      return round($this->joinWin / $this->getJoinGachi(),3) * 100;
    } else
    {
      return 0;
    }
  }

  function getJoinRP()
  {
    return $this->joinRP;
  }

  function getJoinSum()
  {
    return $this->joinSum;
  }

  function getLiveGachi()
  {
    return $this->liveGachi;
  }

  function getLiveRP()
  {
    return $this->liveRP;
  }



  //function fetchCountPlayer(&$sql){
    //$sql->bindParam(':player',$this->player,PDO::PARAM_STR);
    //$sql->execute();
    //$count = $sql->fetch(PDO::FETCH_NUM);


    //if(empty($count))
    //{
      //$count = array("-"); //平均値算出などでデータがない場合
    //}

    //return $count[0];
  //}
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
  //function getJoin(){
    //$sql = $this->pdo->prepare("SELECT count(*) FROM users WHERE player=:player");
    //return $this->fetchCountPlayer($sql);
  //}

  //prepare部分変数にする
  //ガチ参加数
  //function getGachi(){
    //$sql = $this->pdo->prepare("SELECT count(*) FROM users WHERE player=:player AND (rltid=1 OR rltid=2)"); 結果が勝利もしくは敗北のみ集計

    //return $this->fetchCountPlayer($sql);
  //}

  //RP参加数
  //function getRP(){
    //$sql = $this->pdo->prepare("SELECT count(*) FROM users WHERE player=:player AND rltid=3"); 結果が参加のみ集計

    //return $this->fetchCountPlayer($sql);
  //}

  //ガチ勝利数
  //function getWin(){
    //$sql = $this->pdo->prepare("SELECT count(*) FROM users WHERE player=:player AND rltid=1"); 
    //return $this->fetchCountPlayer($sql);
  //}

  //ガチ生存係数
  //function getGachiLife(){
    //$sql = $this->pdo->prepare("SELECT round(avg(life),2) FROM (SELECT * FROM users WHERE rltid=1 OR rltid= 2) as rlt  GROUP BY player HAVING player=:player");結果が勝利もしくは敗北のみ集計

    //return $this->fetchCountPlayer($sql);
  //}

  //RP生存係数
  //function getRPLife(){
    //$sql = $this->pdo->prepare("SELECT round(avg(life),2) FROM (SELECT * FROM users WHERE rltid=3) as rlt GROUP BY player HAVING player=:player");結果が勝利もしくは敗北のみ集計

    //return $this->fetchCountPlayer($sql);
  //}

  //戦績一覧取得
  function getTable(){
    $sql = $this->pdo->prepare("SELECT v.date AS date, c.name AS country, v.vno AS vno, 
      v.name AS vname,rgl.name AS rgl,u.persona AS persona, 
      u.role AS role,u.end AS end, d.name AS destiny,
      rlt.name AS result,c.url AS url
      FROM village v INNER JOIN country c ON v.cid=c.id
      INNER JOIN users u ON v.id=u.vid
      INNER JOIN regulation rgl ON v.rglid=rgl.id 
      INNER JOIN destiny d ON u.dtid=d.id
      INNER JOIN result rlt ON u.rltid=rlt.id
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
