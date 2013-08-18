<?

require_once('DBAdapter.php');

class GetDB
{

/*--------------------------------------------------------------------------------

  0. 定数・変数

--------------------------------------------------------------------------------*/

  private $pdo;
  private $player;
  private $holder;

  private $joinSum;
  private $joinGachi;
  private $joinWin;
  private $joinLose;
  private $joinRP;
  
  private $liveGachi;
  private $liveRP;

  private $teamCount;
  private $teamArray;
  private $skillCount;

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
    $this->teamCount = array();
    $this->teamArray = array();
    $this->skillCount = array();

    //IDの数ぶんプレースホルダを作る
    //$this->holder = implode(',',array_fill(0,count($this->player),'?'));
    for($i=0;$i<count($this->player);$i++)
    {
      $holderArray[] = ':player'.$i;
    }
    $this->holder = implode(',',$holderArray);
    
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


  function fetchJoinCount()
  {
    //DBから情報を取得
    $stmt = $this->pdo->prepare("
      SELECT r.name, count(*) AS count FROM users AS u INNER JOIN result AS r ON r.id=u.rltid
      WHERE player IN (".$this->holder.") GROUP BY rltid
      UNION
      SELECT CASE rltid
        WHEN 1 THEN 'ガチ生存'
        WHEN 2 THEN 'ガチ生存'
        WHEN 3 THEN 'RP生存'
        ELSE 'no'
      END AS rlt,
      truncate(avg(life)+0.005,2) FROM users WHERE player IN (".$this->holder.") GROUP BY rlt
      UNION
      SELECT rltid,count(*) FROM users WHERE player IN (".$this->holder.");
      ");
      $stmt = $this->exeStmt($stmt);
    $table = $stmt->fetchALL();

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

  function exeStmt(&$stmt)
  {
    foreach($this->player as $k =>$id)
    {
      $stmt->bindValue(':player'.$k,$id,PDO::PARAM_STR);
    }
    $stmt->execute();
    return $stmt;
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


  //戦績一覧取得
  function getTable(){
    $stmt = $this->pdo->prepare("SELECT v.date AS date, c.name AS country, v.vno AS vno, 
      v.name AS vname,rgl.name AS rgl,u.persona AS persona, 
      u.role AS role,u.end AS end, d.name AS destiny,
      rlt.name AS result,c.url AS url
      FROM village v INNER JOIN country c ON v.cid=c.id
      INNER JOIN users u ON v.id=u.vid
      INNER JOIN regulation rgl ON v.rglid=rgl.id 
      INNER JOIN destiny d ON u.dtid=d.id
      INNER JOIN result rlt ON u.rltid=rlt.id
      WHERE player IN (".$this->holder.") ORDER BY v.date DESC,v.vno DESC;");

    $table = $this->exeStmt($stmt);
    $table = $table->fetchAll();
    
    return $table;
  }

  //役職別一覧取得
  function fetchTeamCount()
  {
    $stmt = $this->pdo->prepare("
      SELECT t.name team,s.name skl,r.name result,count(*) count,sum.sum
      FROM users u
	INNER JOIN skill s ON u.sklid=s.id
	INNER JOIN team t ON u.tmid=t.id
	INNER JOIN result r ON u.rltid=r.id
        INNER JOIN (
          SELECT tmid,rltid,count(*) sum FROM users WHERE player IN (".$this->holder.") GROUP BY tmid,rltid
        ) sum ON u.tmid=sum.tmid AND u.rltid=sum.rltid
      WHERE u.player IN (".$this->holder.")
      GROUP BY u.rltid,s.name
      ORDER BY u.tmid,u.sklid,u.rltid
        ");

    $stmt = $this->exeStmt($stmt);
    foreach($stmt as $item)
    {
      //陣営の勝敗合計を入れる
      if(!isset($this->teamCount[$item['team']][$item['result']]))
      {
        $this->teamCount[$item['team']][$item['result']] = $item['sum'];
      }
      //陣営別に役職を分ける
      $this->skillCount[$item['team']][$item['skl']][$item['result']] = $item['count'];
    }

    //埋まっていない結果(勝利、敗北、参加)にゼロを入れる
      $checkArray = array('勝利','敗北','参加');
      array_walk($checkArray,array($this,'insertResultZero'));
    //受け渡し用の陣営リストを作る
      $this->teamArray = array_keys($this->teamCount);

  }

  function insertResultZero($value,$key)
  {
    foreach($this->teamCount as $team=>$result)
    {
      if(!array_key_exists($value,$result))
      {
        $this->teamCount[$team][$value] = 0;
      }
      foreach($this->skillCount[$team] as $skill=>$item)
      {
        if(!array_key_exists($value,$item))
        {
          $this->skillCount[$team][$skill][$value] = 0;
        }
      }
    }
  }

  function getTeamArray()
  {
    return $this->teamArray;
  }

  function getTeamWin($team)
  {
    return $this->teamCount[$team]['勝利'];
  }

  function getTeamGachi($team)
  {
    return $this->teamCount[$team]['勝利'] + $this->teamCount[$team]['敗北'];
  }

  function getTeamWinP($team)
  {
    if($this->teamCount[$team]['勝利'] !== 0)
    {
      return round($this->teamCount[$team]['勝利'] / $this->getTeamGachi($team),3) * 100;
    }
    else
    {
      return 0;
    }
  }

  function getTeamRP($team)
  {
    return $this->teamCount[$team]['参加'];
  }

  function getSkillArray($team)
  {
    return array_keys($this->skillCount[$team]);
  }

  function getSkillWin($team,$skill)
  {
    return $this->skillCount[$team][$skill]['勝利'];
  }

  function getSkillGachi($team,$skill)
  {
    return $this->skillCount[$team][$skill]['勝利']+$this->skillCount[$team][$skill]['敗北'];
  }

  function getSkillWinP($team,$skill)
  {
    if($this->skillCount[$team][$skill]['勝利'] !== 0)
    {
      return round($this->skillCount[$team][$skill]['勝利'] / $this->getSkillGachi($team,$skill),3) * 100;
    }
    else
    {
      return 0;
    }
  }

  function getSkillRP($team,$skill)
  {
    return $this->skillCount[$team][$skill]['参加'];
  }
}
