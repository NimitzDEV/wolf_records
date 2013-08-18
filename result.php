<?
require_once('lib/GetDB.php');

//旧URL対策
if (isset($_GET['player']) && $_GET['player'] !== "")
{
  $viewName = fixGetID($_GET['player'],$playerArr);
}
//複数IDを配列に入れる
else if(isset($_GET['id_0']) && $_GET['id_0'] !=="")
{
  for($i=0;$i<5;$i++)
  {
    if (isset($_GET['id_'.$i]) && $_GET['id_'.$i] !== "")
    {
      $viewArray[] = fixGetID($_GET['id_'.$i],$playerArr);
    }
  }
  $viewName = implode(', ',$viewArray);
}
//一番上のフォーム未入力
else
{
  echo '<!DOCTYPE html><meta charset="UTF-8"><link rel="stylesheet" href="css/index.css"><link rel="stylesheet" href="css/bootstrap.css"><link rel="stylesheet" href="css/bootstrap-responsive.css"><body>';
  echo '<header><h1>エラー</h1></header><div class="container"><section><p id="err">';
  echo 'IDを入力して下さい。</p></section>';
  echo '<form action="./result.php" method="GET"><fieldset><input class="search-query" type="text" name="id_0" placeholder="IDを入力して下さい" required><br><button type="submit" class="btn btn-primary">検索</button></fieldset></form></div></body>';
  exit;
}
$db = new GetDB($playerArr);
$db->connect();

if($db->FetchJoinCount())
{
  $table = $db->getTable();
  $db->fetchTeamCount();
}
$db->disConnect();

function fixGetID($argName,&$playerArr)
{
  if(mb_substr($argName,-1,1,"utf-8")==' ')
  {
    $player = htmlspecialchars($argName);
    //末尾に半角スペースが入っている場合は変換する
    $playerArr[] = preg_replace("/ /","&amp;nbsp;",$player);

    return $player;
  }
  else
  {
    return $playerArr[] = htmlspecialchars($argName);
  }
}
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">

    <meta name="author" content="fortmorst">
    <meta name="description" content="ID: <? echo $viewName;?>さんのWeb人狼戦績の一覧です。">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap-responsive.css">
    <link rel="stylesheet" href="css/result.css">
    <title>
      <? echo $viewName;?> の人狼戦績 | 人狼戦績まとめ
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
          <h1><? echo $viewName;?></h1>
          <dl>
              <dt>総合参加数</dt>
              <dd>
                <? echo $db->getJoinSum(); ?>
<!--
               <span class="ig"></span><? //echo $db->getJoinGachi(); ?>
               <span class="ir"></span><? //echo $db->getJoinRP(); ?>
-->
              </dd>
              <dt>勝率</dt>
              <dd>
                <span class="ig"></span><? echo $db->getJoinWinPercent() ?><span>%</span>
              </dd>
              <dt>平均生存係数</dt>
              <dd>
                <span class="ig"></span><? echo $db->getLiveGachi(); ?>
                <!--<span class="ir"></span><? //echo $db->getLiveRP(); ?>-->
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
            <a href="#moreID" class="add" data-toggle="modal"><span class="ip"></span></a>
            <input type="text" class="search-query" name="id_0" placeholder="ID検索" required>
            <button type="submit" class="btn btn-primary">検索</button>
          </fieldset>
        </form>
        <ul id="sub">
          <li>
            <a href="https://twitter.com/share" class="twitter-share-button" data-lang="ja" data-count="none">ツイート</a>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
          </li>
          <li>
            <span class="id"></span><a href="document.html">説明書</a>
          </li>
<!--
          <li>
            <span class="ig"></span>: 勝敗のある村
          </li>
          <li>
            <span class="ir"></span>: 勝敗度外視村
          </li>
-->
        </ul>
      </nav>
    </header>
    <div id="moreID" class="modal hide fade in">
      <div class="modal-header">
        <a class="close" href="#" data-dismiss="modal">&times;</a>
        <h4>複数ID入力</h4>
      </div>
      <div class="modal-body">
        <form action="./result.php" method="GET">
          <fieldset><ul id="id">
            <li class="id_var">
              <input class="search-query" type="text" name="id_0" placeholder="IDを入力して下さい">
            </li>
            <li class="id_var">
              <input class="search-query" type="text" name="id_1" placeholder="IDを入力して下さい">
            </li>
          </ul></fieldset>
          <a href="#" class="id_add"><span class="ip"></span>もっと入力</a><br>
          <button type="submit" class="btn btn-primary">検索</button>
        </form>
      </div>
      <div class="modal-footer">
        <a class="btn" href="#" data-dismiss="modal">閉じる</a>
      </div>
    </div>

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
        echo '<td><span class="ig"></span>'.$db->getTeamWin($team)
          .'/'.$db->getTeamGachi($team).'</td>';
        echo '<td>('.$db->getTeamWinP($team).'%)</td>';
        //echo '<td><span class="ir"></span>'.$db->getTeamRP($team).'</td>';
        echo '</tr></thead><tbody>';

        foreach($db->getSkillArray($team) as $skill)
        {
          echo '<tr><td>'.$skill.'</td>';
          echo '<td><span class="ig"></span>'.$db->getSkillWin($team,$skill)
            .'/'.$db->getSkillGachi($team,$skill).'</td>';
          echo '<td>('.$db->getSkillWinP($team,$skill).'%)</td>';
          //echo '<td><span class="ir"> </span>'.$db->getSkillRP($team,$skill).'</td></tr>';
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
    <script src="lib/bootstrap.modal.min.js"></script>
    <script src="lib/addInputArea.js"></script>
    <script>
      $(document).ready(function() 
        { 
          $("#list").tablesorter(); 
          $('#id').addInputArea({
            maximum : 5
          });
        } 
      );            
    </script>
  </body>
</html>
