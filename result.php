<?
  require_once('lib/GetDB.php');
  if (!empty($_GET['player']))
  {
    $player = htmlspecialchars($_GET['player']);
    $db = new GetDB($player);
  } else {
    $db = new GetDB('-NODATA-');
}

    $db->connect();
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">


    <meta name="author" content="fortmorst">
    <meta name="description:" content="人狼戦績一覧を表示します。 ">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap-responsive.css">
    <link rel="stylesheet" href="css/result.css">
    <title>
      <?
        echo $player; //ID取得
      ?> 
      の人狼戦績 | 人狼戦績まとめ
    </title>
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-42678919-1', 'waoon.net');
      ga('send', 'pageview');

    </script>
<!--[if lt IE 9]>
  <script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
  <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->
  </head>
  <body id="record">
    <header>

      <!--サマリ-->
      <div id="summary">
        <div class="container">
          <h1>
          <?
            echo $player; //ID取得
          ?> 
          </h1>
        <p>
          総合参加数 : 
            <?
              echo '<span>'.$join = $db->getJoin().'</span>';
              $gachi = $join; //非ガチ村未実装用
              //echo ' / ';
              //if ($join != 0)
              //{
                //echo '<span class="icon-fire icon-white">　</span><span>'.$gachi=$db->getGachi().'</span> ';
                ////echo '<span class="icon-book icon-white">　</span><span>'.$rp=$db->getRP().'</span>';

              //}
            ?>
            　<span class="icon-fire icon-white">　</span>勝率 : 
            <?
              if ($gachi != 0)
              {
                $win = $db->getWin();
                $avg = round($win / $gachi,3) * 100;
                echo '<span>'.$avg.'</span>%';
                unset($avg,$win);
              } else {
                echo '0/0 (0%)';
              }
            ?>
            　平均生存係数
            <span class="icon-fire icon-white">　</span>
            <?
              if (!empty($gachi))
              {
                echo '<span>'.$db->getGachiLife().'</span>';
                unset($gachi);

              } else {
                echo '0.00';
              }
            ?>
<!--
              
                <span class="icon-book icon-white">　</span>
              <?
                if (!empty($rp))
                {
                  echo '<span>'.$db->getRPLife().'</span>';
                  unset($rp);

                } else {
                  echo '0.00';
                }
              ?>
-->
          </p>
        </div>
      </div>

      <!--メニュー-->
      <nav id="headerMenu">
        <ul>
          <li>
            <a href="#record">戦績一覧</a>
          </li>
          <li>
            <a href="#role">役職別参加数</a>
          </li>
        </ul>
        <form class="navbar-search pull-right" action="./result.php" method="GET">
          <fieldset>
            <input type="text" class="sarch-query" name="player" placeholder="ID検索">
            <button type="submit" class="btn btn-primary">検索</button>
          </fieldset>
        </form>
        <ul id="sub">
          <li>
            <a href="https://twitter.com/share" class="twitter-share-button" data-lang="ja" data-count="none">ツイート</a>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
          </li>
          <li>
            <a href="document.html"><span class="icon-file"></span>説明書</a>
          </li>
          <li>
            <a href="index.html"><span class="icon-circle-arrow-left"></span>トップへ</a>
          </li>
<!--
          <li>
            <span class="icon-fire">　</span>: 勝敗のある村
          </li>
          <li>
            <span class="icon-book">　</span>: 勝敗度外視村
          </li>
-->
        </ul>
      </nav>
    </header>

    <div class="container">
      <div id="ad">
        <script type="text/javascript">
          google_ad_client = "ca-pub-8063117190073359";
          /* record/result.php */
          google_ad_slot = "7246012795";
          google_ad_width = 728;
          google_ad_height = 90;
        </script>
        <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
      </div>
    </div>


    <section class="container">
      <table id="list" class="table table-striped table-condensed table-hover tablesorter">
        <thead>
          <tr>
              <th>日付</th>
              <th>国番号</th>
              <th>村名</th>
              <th>編成</th>
              <th>キャラクタ名</th>
              <th>役職</th>
              <th>結末</th>
              <th>結果</th>
          </tr>
        </thead>
        <tbody>
          <?
            $table = $db->getTable();
            if (!empty($table))
            {
              foreach($table as $item)
              {
                echo '<tr>';
                echo '<td>'.date("Y/m/d",strtotime($item['date'])).'</td>';
                echo '<td>'.$item['country'].$item['vno'].'</td>';
                echo '<td><a href="'.$item['url'].$item['vno'].'">'.$item['vname'].'</a></td>';
                echo '<td>'.$item['rgl'].'</td>';
                echo '<td>'.$item['persona'].'</td>';
                echo '<td>'.$item['role'].'</td>';
                echo '<td>'.$item['end'].'d'.$item['destiny'].'</td>';
                switch ($item['result'])
                {
                  case GetDB::RSL_WIN:
                    echo '<td class="w">勝利</td>';
                    break;
                  case GetDB::RSL_LOSE:
                    echo '<td class="l">敗北</td>';
                    break;
                  case GetDB::RSL_JOIN:
                    echo '<td class="j">参加</td>';
                    break;
                  case GetDB::RSL_INVALID:
                    echo '<td class="i">無効</td>';
                    break;
                  case GetDB::RSL_ONLOOKER:
                    echo '<td class="o">見物</td>';
                    break;
                }

                echo '</tr>'.PHP_EOL;
              }
              unset($table,$item);
            } else {
              echo '<tr>';
              echo '<td class="noData" colspan="8">NO DATA</td>';
              echo '</tr>';
            }
          ?>
        </tbody>
      </table>
    </section>
    <section id="role" class="container">
<? 
            if($join != 0)
            {
              $table = $db->getTeamTable();
              if(!empty($table))
              {
                  $tTmGachi = $db->getTeamGachi();
                  $tTmWin = $db->getTeamGachiWin();
                  //$tTmRP = $db->getTeamRP();
                foreach($table as $team => $item)
                {
                  //class名
                  switch ($team)
                  {
                  case '村人':
                    $tClass = 'vil';
                    break;
                  case '人狼':
                    $tClass = 'wlf';
                    break;
                  case '妖魔':
                    $tClass = 'fry';
                    break;
                  default:
                    $tClass = 'ukn';
                    break;
                  }

                  if (!empty($tTmGachi))
                  {
                    $cTmGachi = $tTmGachi[$team];
                    if(!empty($tTmWin[$team]))
                    {
                      $cTmWin = $tTmWin[$team];
                      $cTmWinP = round($cTmWin/$cTmGachi,3) * 100;
                    } else {
                      //負け記録しかない場合
                      $cTmWin = 0;
                      $cTmWinP = 0;
                    }
                  } else {
                    $cTmGachi = 0;
                    $cTmWin = 0;
                    $cTmWinP = 0;
                  }

                  //if (!empty($tTMRP))
                  //{
                    //$cTmRP = $tTMRP[$team];
                  //} else {
                    //$cTmRP = 0;
                  //}
                  

                  echo '<table class="table table-striped table-hover table-condensed"><thead>'; 
                  echo '<tr class="'.$tClass.'">';
                  echo '<td>'.$team.'陣営</td>';
                  echo '<td><span class="icon-fire icon-white"> </span> '.$cTmWin.'/'.$cTmGachi.' ('.$cTmWinP.'%)</td>';
                  //echo '<td><span class="icon-book icon-white"> </span> '.$cTmRP.'</td>';
                  echo '</tr></thead><tbody>';


                  $tSklGachi = $db->getSklGachi();
                  $tSklWin = $db->getSklWin();
                  //$tSklRP = $db->getSklRP();
                  

                  foreach($item as $skl)
                  {
                    if (!empty($tSklGachi[$skl]))
                    {
                      $cSklGachi = $tSklGachi[$skl];
                      if(!empty($tSklWin[$skl]))
                      {
                        $cSklWin = $tSklWin[$skl];
                        $cSklWinP = round($cSklWin/$cSklGachi,3) * 100;
                      } else {
                        //負け記録しかない場合
                        $cSklWin = 0;
                        $cSklWinP = 0;
                      }
                    } else {
                      $cSklGachi = 0;
                      $cSklWin = 0;
                      $cSklWinP = 0;
                    }

                    //if (!empty($tSklRP[$skl]))
                    //{
                      //$cSklRP = $tSklRP[$skl];
                    //} else {
                      //$cSklRP = 0;
                    //}
                    

                    echo '<tr><td>'.$skl.'</td>';
                    echo '<td><span class="icon-fire"> </span> '.$cSklWin.'/'.$cSklGachi.' ('.$cSklWinP.'%)</td>';
                    //echo '<td><span class="icon-book"> </span> '.$cSklRP.'</td></tr>';

                  }
                  echo '</tbody></table>';

                }

                unset($table,$item,$tTmGachi,$tTmWin,$tTmRP,$tSklGachi,$tSkilWin,$tSklRP);
              }

            }
?>
    </section>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script src="lib/tablesorter.min.js"></script>
    <script>
      $(document).ready(function() 
        { 
          $("#list").tablesorter(); 
        } 
      );            
    </script>
  </body>
</html>
<?
  $db->disConnect();
?>
