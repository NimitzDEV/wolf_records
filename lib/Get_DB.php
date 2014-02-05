<?php

class Get_DB
{
  use Properties;
  private $pdo
         ,$players
         ,$holder
         ,$join
         ,$doppel
         ,$record
         ;

  private $teamCount;
  private $teamArray;
  private $skillCount;

  private $cell_format;
  const FORMAT_BOTH = 3;
  const FORMAT_GACHI = 1;
  const FORMAT_RP  = 2;

  function __construct($players)
  {
    $this->players = $players;
    $this->make_holder();

    //以下未整理
    //$this->teamCount = array();
    //$this->teamArray = array();
    //$this->skillCount = array();
  }
  private function make_holder()
  {
    //IDの数だけプレースホルダを作る
    $count = count($this->players);
    for($i=0;$i<$count;$i++)
    {
      $holderArray[] = ':player'.$i;
    }
    $this->holder = implode(',',$holderArray);
  }

  function start_fetch()
  {
    $this->connect();
    if($this->fetch_count())
    {
      $this->fetch_doppel();
      $table = $this->get_record();
      $this->make_table($table);
    //$db->fetchTeamCount();
    }
    $this->disconnect();
  }
  private function connect()
  {
    try{
      $this->pdo = new DBAdapter();
    } catch (pdoexception $e){
      var_dump($e->getMessage());
      exit;
    }
  }
  private function disconnect()
  {
    $this->pdo = null;
  }

  function fetch_count()
  {
    $stmt = $this->pdo->prepare("
      SELECT r.property, count(*) count FROM users u
      JOIN result r ON u.rltid=r.id
      WHERE player IN (".$this->holder.") GROUP BY rltid
      UNION
      SELECT CASE rltid
        WHEN 1 THEN 'live_gachi'
        WHEN 2 THEN 'live_gachi'
        WHEN 3 THEN 'live_rp'
        ELSE 'x'
      END rlt,
      truncate(avg(life)+0.005,2) FROM users WHERE player IN (".$this->holder.") GROUP BY rlt
    ");
    //$stmt = $this->exe_stmt($stmt,'Join_Count');
    $stmt = $this->exe_stmt($stmt);
    $table = $stmt->fetchAll();
    if(!empty($table))
    {
      $this->join = new Join_Count();
      array_pop($table);
      foreach($table as $row)
      {
        $this->join->{$row['property']} = $row['count'];
      }
      $this->join->calculate();
      return true;
    }
    else
    {
      return false;
    }
  }
  private function exe_stmt(&$stmt)
  {
    //$stmt->setFetchMode(PDO::FETCH_CLASS,$class);
    foreach($this->players as $k =>$id)
    {
      $stmt->bindValue(':player'.$k,$id,PDO::PARAM_STR);
    }
    $stmt->execute();
    return $stmt;
  }

  private function fetch_doppel()
  {
    $stmt = $this->pdo->prepare("
      SELECT base,doppel FROM doppel WHERE base IN (".$this->holder.");
    ");
    $stmt = $this->exe_stmt($stmt);
    $table = $stmt->fetchALL();
    if(!empty($table))
    {
      $this->doppel = $table;
    }
  }
  function make_doppel($url_base)
  {
    $url_base = preg_replace('/.+result.php\?/',"","$url_base");
    $string = 'もしかして: ';

    foreach($this->doppel as $table)
    {
      $doppel[$table['base']] = $table['doppel'];
      $url = preg_replace('/'.$table['base'].'/',$table['doppel'],$url_base);
      $url_list[] = '<a href="result.php?'.$url.'">'.htmlentities($table['doppel']).'</a>';
    }
    $string .= implode(" | ",$url_list);

    if(count($doppel) > 1)
    {
      foreach($doppel as $before=>$after)
      {
        $url =  preg_replace('/'.$before.'/',$after,$url_base);
      }
      $string .= ' | <a href="result.php?'.$url.'">全部変えて試す</a>';
    }
    return $string;
  }

  function get_record()
  {
    $stmt = $this->pdo->prepare("
      SELECT v.date date, c.name country, v.vno vno,
      v.name vname,rgl.name rgl,u.persona persona,
      u.role role,u.end end, d.name destiny,
      rlt.name result,rlt.property resclass,c.url url,v.wtmid wtmid
      FROM village v JOIN country c ON v.cid=c.id
      JOIN users u ON v.id=u.vid
      JOIN regulation rgl ON v.rglid=rgl.id 
      JOIN destiny d ON u.dtid=d.id
      JOIN result rlt ON u.rltid=rlt.id
      WHERE player IN (".$this->holder.") ORDER BY v.date DESC,v.vno DESC;
    ");
    $table = $this->exe_stmt($stmt);
    $table = $table->fetchAll();
    
    return $table;
  }
  private function make_table($table)
  {
    $string = '<tr>';
    if(!empty($table))
    {
      foreach($table as $row)
      {
        $vname = mb_strimwidth($row['vname'],0,34,"..","UTF-8");
        $url = preg_replace('/%n/',$row['vno'],$row['url']);
        $date = date("Y/m/d",strtotime($row['date']));
        if($row['wtmid'] != 0)
        {
          $icon = 'i-fire';
        }
        else
        {
          $icon = 'i-book';
        }
        $string.= <<<EOF
          <td>$date</td>
          <td>{$row['country']}{$row['vno']}</td>
          <td><span class="$icon"></span><a href="$url" title="{$row['vname']}">$vname</a></td>
          <td>{$row['rgl']}</td>
          <td>{$row['persona']}</td>
          <td>{$row['role']}</td>
          <td>{$row['end']}d{$row['destiny']}</td>
          <td><span class="{$row['resclass']}">{$row['result']}</span></td></tr>
EOF;
      }
    }
    else
    {
      for($i=1;$i<=8;$i++)
      {
        $string.= '<td>NO DATA</td>';
      }
      $string.= '</tr>';
    }
    $this->record = $string;
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
      GROUP BY u.rltid,t.name,s.name
      ORDER BY u.tmid,u.sklid,u.rltid
    ");

    $stmt = $this->exe_stmt($stmt);
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

  function get_team_tr($team)
  {
    $gachi = $this->getTeamGachi($team);
    $rp = $this->getTeamRP($team);

    if($gachi !== 0 && $rp !== 0)
    {
      //両方ある
      $this->cell_format = $this::FORMAT_BOTH;
      return <<<EOF
        <td><span class="i-fire"></span>{$this->getTeamWin($team)}/{$this->getTeamGachi($team)}</td>
        <td>{$this->getTeamWinP($team)}%</td>
        <td><span class="i-book"></span>{$this->getTeamRP($team)}</td>
EOF;
    }
    else if ($rp === 0)
    {
      //ガチのみ
      $this->cell_format = $this::FORMAT_GACHI;
      return <<<EOF
        <td><span class="i-fire"></span>{$this->getTeamWin($team)}/{$this->getTeamGachi($team)}</td>
        <td>{$this->getTeamWinP($team)}%</td>
EOF;
    }
    else
    {
      //RPのみ
      $this->cell_format = $this::FORMAT_RP;
      return <<<EOF
        <td><span class="i-book"></span>{$this->getTeamRP($team)}</td>
EOF;
    }
  }

  function get_skill_tr($team,$skill)
  {
    $gachi = $this->getSkillGachi($team,$skill);
    $rp = $this->getSkillRP($team,$skill);

    switch(true)
    {
      case($gachi !== 0 && $rp !== 0):
        //両方ある
        return <<<EOF
           <td><span class="i-fire"></span>{$this->getSkillWin($team,$skill)}/{$this->getSkillGachi($team,$skill)}</td>
           <td>{$this->getSkillWinP($team,$skill)}%</td>
           <td><span class="i-book"></span>{$this->getSkillRP($team,$skill)}</td>
EOF;
        break;
      case($rp === 0):
        if($this->cell_format === $this::FORMAT_BOTH)
        {
          //この役職はガチのみだが他の役職にはRPがある
          return <<<EOF
          <td><span class="i-fire"></span>{$this->getSkillWin($team,$skill)}/{$this->getSkillGachi($team,$skill)}</td>
          <td>{$this->getSkillWinP($team,$skill)}%</td>
          <td></td>
EOF;
        }
        else
        {
          return <<<EOF
          <td><span class="i-fire"></span>{$this->getSkillWin($team,$skill)}/{$this->getSkillGachi($team,$skill)}</td>
          <td>{$this->getSkillWinP($team,$skill)}%</td>
EOF;
        }
        break;
      case($gachi === 0):
        if($this->cell_format === $this::FORMAT_BOTH)
        {
          return <<<EOF
          <td></td>
          <td></td>
          <td><span class="i-book"></span>{$this->getSkillRP($team,$skill)}</td>
EOF;
        }
        else
        {
          return <<<EOF
          <td><span class="i-book"></span>{$this->getSkillRP($team,$skill)}</td>
EOF;
        }
        break;
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
