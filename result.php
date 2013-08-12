<?
  require_once('lib/GetDB.php');
  if (isset($_GET['player']) && $_GET['player'] !== "")
  {
    $player = htmlspecialchars($_GET['player']);

    if(mb_substr($player,-1,1,"utf-8")==' ')
    {
      $db = new GetDB(preg_replace("/ /","&amp;nbsp;",$player));
    }
    else
    {
      $db = new GetDB($player);
    }
    
    $db->connect();

    if($db->FetchJoinCount())
    {
      $table = $db->getTable();
      $db->fetchTeamCount();
    }
    $db->disConnect();
  }
  else
  {
    echo '<!DOCTYPE html><meta charset="UTF-8"><link rel="stylesheet" href="css/index.css"><link rel="stylesheet" href="css/bootstrap.css"><link rel="stylesheet" href="css/bootstrap-responsive.css"><body>';
    echo '<header><h1>エラー</h1></header><div class="container"><section><p id="err">';
    echo 'IDを入力して下さい。</p></section>';
    echo '<form action="./result.php" method="GET"><fieldset><input class="search-query" type="text" name="player" placeholder="IDを入力して下さい" required><br><button type="submit" class="btn btn-primary">検索</button></fieldset></form></div></body>';
    exit;
  }
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">

    <meta name="author" content="fortmorst">
    <meta name="description" content="ID: <? echo $player;?>さんのWeb人狼戦績の一覧です。">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap-responsive.css">
    <link rel="stylesheet" href="css/result.css">
    <title>
      <? echo $player;?> の人狼戦績 | 人狼戦績まとめ
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

      <div id="summary">
        <div class="container">
          <h1><? echo $player;?></h1>
          <dl>
              <dt>総合参加数</dt>
              <dd>
                <? echo $db->getJoinSum(); ?>
<!--
               <span class="icon-fire icon-white"></span><? //echo $db->getJoinGachi(); ?>
               <span class="icon-book icon-white"></span><? //echo $db->getJoinRP(); ?>
-->
              </dd>
              <dt>勝率</dt>
              <dd>
                <span class="icon-fire icon-white"></span>
                <? echo $db->getJoinWinPercent() ?><span>%</span>
              </dd>
              <dt>平均生存係数</dt>
              <dd>
                <span class="icon-fire icon-white"></span>
                <? echo $db->getLiveGachi(); ?>
                <!--<span class="icon-book icon-white"></span><? //echo $db->getLiveRP(); ?>-->
              </dd>
          </dl>
        </div>
      </div>

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
            <input type="text" class="search-query" name="player" placeholder="ID検索" required>
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
<!--
          <li>
            <span class="icon-fire"></span>: 勝敗のある村
          </li>
          <li>
            <span class="icon-book"></span>: 勝敗度外視村
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
                  case '勝利':
                    echo '<td class="w">';
                    break;
                  case '敗北':
                    echo '<td class="l">';
                    break;
                  case '参加':
                    echo '<td class="j">';
                    break;
                  case '無効':
                    echo '<td class="i">';
                    break;
                  case '見物':
                    echo '<td class="o">';
                    break;
                }
                echo $item['result'].'</td></tr>';
              }
              unset($table,$item);
            }
           else
            {
              echo '<tr><td class="noData" colspan="8">NO DATA</td></tr>';
            }
          ?>
        </tbody>
      </table>
    </section>
    <section id="role" class="container">
<? 
      foreach($db->getTeamArray() as $team)
      {
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

        echo '<table class="table table-striped table-hover table-condensed"><thead>'; 
        echo '<tr class="'.$tClass.'"><td>'.$team.'陣営</td>';
        echo '<td><span class="icon-fire icon-white"></span>'.$db->getTeamWin($team)
          .'/'.$db->getTeamGachi($team).'</td>';
        echo '<td>('.$db->getTeamWinP($team).'%)</td>';
        //echo '<td><span class="icon-book icon-white"></span>'.$db->getTeamRP($team).'</td>';
        echo '</tr></thead><tbody>';

        foreach($db->getSkillArray($team) as $skill)
        {
          echo '<tr><td>'.$skill.'</td>';
          echo '<td><span class="icon-fire"></span>'.$db->getSkillWin($team,$skill)
            .'/'.$db->getSkillGachi($team,$skill).'</td>';
          echo '<td>('.$db->getSkillWinP($team,$skill).'%)</td>';
          //echo '<td><span class="icon-book"> </span>'.$db->getSkillRP($team,$skill).'</td></tr>';
        }
        echo '</tbody></table>';
      }
?>
    </section>
    <div class="container">
      <footer>
       <ul>
           <li>作った人: <a href="https://twitter.com/fortmorst">fortmorst</a></li>
           <li><a href="index.html">トップページへ</a></li>
           <li><a href="http://waoon.net">waoon.net</a></li>
       </ul>
      </footer>
    </div>
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
