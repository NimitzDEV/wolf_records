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
         ,$teams
         ;

  function __construct($players)
  {
    $this->players = $players;
    $this->make_holder();
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
      $this->make_table($this->get_record());
      $this->make_teams($this->fetch_team_table());
    }
    else
    {
      $this->make_nodata();
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
  private function make_nodata()
  {
    $string = '<tr>';
    for($i=1;$i<=8;$i++)
    {
      $string.= '<td>NO DATA</td>';
    }
    $string.= '</tr>';
    $this->record = $string;
  }

  private function fetch_count()
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
      truncate(avg(life)+0.0005,3) FROM users WHERE player IN (".$this->holder.") GROUP BY rlt
    ");
    $stmt = $this->exe_stmt($stmt);
    $table = $stmt->fetchAll();
    if(!empty($table))
    {
      $this->join = new Join_Count();
      foreach($table as $row)
      {
        if($row['property'] === 'x')
        {
          continue;
        }
        $this->join->{$row['property']} = $row['count'];
      }
      $this->join->calculate();
      return true;
    }
    else
    {
      $this->join = new Join_Count();
      return false;
    }
  }
  private function exe_stmt(&$stmt)
  {
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
        $url_base =  preg_replace('/'.$before.'/',$after,$url_base);
      }
      $string .= ' | <a href="result.php?'.$url_base.'">全部変えて試す</a>';
    }
    return $string;
  }

  private function get_record()
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
    foreach($table as $row)
    {
      $vname = mb_strimwidth($row['vname'],0,34,"..","UTF-8");
      if($vname === $row['vname'])
      {
        $tip_v = '>';
      }
      else
      {
        $tip_v = ' title="'.$row['vname'].'">';
      }

      $persona = mb_strimwidth($row['persona'],0,30,"..","UTF-8");
      if($persona === $row['persona'])
      {
        $tip_p = $row['persona'];
      }
      else
      {
        $tip_p = '<span title="'.$row['persona'].'">'.$persona.'</span>';
      }

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
        <td><span class="$icon"></span><a href="$url"$tip_v$vname</a></td>
        <td>{$row['rgl']}</td>
        <td>$tip_p</td>
        <td>{$row['role']}</td>
        <td>{$row['end']}d{$row['destiny']}</td>
        <td><span class="{$row['resclass']}">{$row['result']}</span></td></tr>
EOF;
    }
    $this->record = $string;
  }

  private function fetch_team_table()
  {
    $stmt = $this->pdo->prepare("
      SELECT t.name team,t.css css,t.id tid,s.name skl,s.id sid,r.property result,count(*) count,sum.sum
      FROM users u
	JOIN skill s ON u.sklid=s.id
	JOIN team t ON u.tmid=t.id
	JOIN result r ON u.rltid=r.id
        JOIN (
          SELECT tmid,rltid,count(*) sum FROM users WHERE player IN (".$this->holder.") GROUP BY tmid,rltid
        ) sum ON u.tmid=sum.tmid AND u.rltid=sum.rltid
      WHERE u.player IN (".$this->holder.") and r.property not in('onlooker','invalid')
      GROUP BY u.rltid,t.name,s.name
      ORDER BY u.tmid,u.sklid,u.rltid
    ");

    $stmt = $this->exe_stmt($stmt);
    $stmt = $stmt->fetchAll();
    $team_list = [];
    foreach($stmt as $item)
    {
      $tid = (int)$item['tid'];
      if(!isset($team_list[$tid]))
      {
        ${'team'.$tid} = new Team_Count($item['team'],$item['css']);
        $team_list[$tid] = ${'team'.$tid};
      }
      $team_list[$tid]->insert_count($item);
    }
    //合計参加数と勝率を入れる
    foreach($team_list as $key=>$item)
    {
      $team_list[$key]->insert_sum();
    }
    return $team_list;
  }
  private function make_teams($list)
  {
    $table = '';
    foreach($list as $team)
    {
      $string = '<div class="role"><table><thead><tr class="'.$team->css.'"><td>'.$team->name.'</td>';
      if($team->sum !== 0 && $team->rp !== null)
      {
        //両方ある
        $both = true;
        $string .= <<<EOF
          <td><span class="i-fire"></span>{$team->win}/{$team->sum}</td>
          <td>{$team->rate}%</td>
          <td><span class="i-book"></span>{$team->rp}</td>
EOF;
      }
      else if ($team->rp === null)
      {
        //ガチのみ
        $both = false;
        $string .= <<<EOF
          <td><span class="i-fire"></span>{$team->win}/{$team->sum}</td>
          <td>{$team->rate}%</td>
EOF;
      }
      else
      {
        //RPのみ
        $both = false;
        $string .= <<<EOF
          <td><span class="i-book"></span>{$team->rp}</td>
EOF;
      }
      $string .= "</tr></thead><tbody>";
      foreach($team->skill as $skill)
      {
        $string .= <<<EOF
        <tr><td>{$skill->name}</td>{$this->get_skill_tr($skill,$both)}</tr>
EOF;
      }
      $string .= '</tbody></table></div>';
      $table .= $string;
    }
    $this->teams = $table;
  }
  function get_skill_tr($skill,$both)
  {
    //echo $skill->name.':'.$skill->sum.'-'.$skill->rp.PHP_EOL;
      if($skill->sum !== 0 && $skill->rp !== null)
      {
        return <<<EOF
           <td><span class="i-fire"></span>{$skill->win}/{$skill->sum}</td>
           <td>{$skill->rate}%</td><td><span class="i-book"></span>{$skill->rp}</td>
EOF;
      }
      else if ($skill->rp === null)
      {
        $string = '<td><span class="i-fire"></span>'.$skill->win.'/'.$skill->sum.'</td><td>'.$skill->rate.'%</td>';
        if($both)
        {
          //この役職はガチのみだが他の役職にはRPがある
          $string .= '<td></td>';
        }
        return $string;
      }
      else
      {
        $string = '';
        if($both)
        {
          //この役職はRPのみだが他の役職にはガチがある
          $string = '<td></td><td></td>';
        }
        $string .= '<td><span class="i-book"></span>'.$skill->rp.'</td>';
        return $string;
      }
  }
}
