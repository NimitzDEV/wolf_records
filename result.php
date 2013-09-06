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
  echo '<!DOCTYPE html><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><link rel="stylesheet" href="../css/base.css"><link rel="stylesheet" href="css/index.css"><body>';
  echo '<header><h1>エラー</h1></header><div class="container"><section><p>';
  echo 'IDを入力して下さい。</p></section>';
  echo '<form action="./result.php" method="GET"><input type="text" name="id_0" placeholder="IDを入力して下さい" required><br><button type="submit" class="btn-primary">検索</button></form></div></body>';
  exit;
}
$db = new GetDB($playerArr);
$db->connect();

if($db->FetchJoinCount())
{
  $boolDoppel = $db->fetchDoppelID();
  $table = $db->getTable();
  $db->fetchTeamCount();
}
$db->disConnect();

function fixGetID($argName,&$playerArr)
{
  if(mb_substr($argName,-1,1,"utf-8") === ' ')
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
    <meta name="description" content="ID: <?= $viewName;?>さんのWeb人狼戦績の一覧です。">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="css/result.css">
    <title>
      <?= $viewName;?> の人狼戦績 | 人狼戦績まとめ
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
  <body>
    <header>
      <div class="container">
<?
if($db->getBoolDoppel())
{
  echo '<h1 class="doppel">'.$viewName.'</h1><div>';
  echo $db->getDoppel(htmlspecialchars($_SERVER["REQUEST_URI"]));
  echo '</div>';
}
else
{
  echo '<h1>'.$viewName.'</h1>';
}
?>
        <dl>
          <dt>総合参加数</dt>
          <dd>
            <?= $db->getJoinSum(); ?>
<!--
           <span class="i-fire"></span><? //echo $db->getJoinGachi(); ?>
           <span class="i-book"></span><? //echo $db->getJoinRP(); ?>
-->
          </dd>
          <dt>勝率</dt>
          <dd>
            <span class="i-fire"></span><?= $db->getJoinWinPercent() ?><span>%</span>
          </dd>
          <dt>平均生存係数</dt>
          <dd>
            <span class="i-fire"></span><?= $db->getLiveGachi(); ?>
            <!--<span class="i-book"></span><? //echo $db->getLiveRP(); ?>-->
          </dd>

        </dl>
 <a href="https://twitter.com/share" class="twitter-share-button" data-lang="ja" data-count="none">ツイート</a>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
      </div>

      <nav>
        <ul>
          <li><a href="#role">役職別参加数</a></li>
<!--          <li><a href="document.html">説明書</a></li>-->
        </ul>
        <form action="./result.php" method="GET">
          <a rel="leanModal" href="#more-ID"><span class="i-plus"></span></a>
          <input type="text" name="id_0" placeholder="ID検索" required>
          <button type="submit" class="btn-primary">検索</button>
        </form>
      </nav>
    </header>
    <div id="more-ID">
      <div class="modal-header">
        <h4>複数ID入力</h4>
      </div>
      <div class="modal-body">
        <form action="./result.php" method="GET">
          <ul id="id">
            <li class="id_var">
              <input type="text" name="id_0" placeholder="IDを入力して下さい">
            </li>
            <li class="id_var">
              <input type="text" name="id_1" placeholder="IDを入力して下さい">
            </li>
          </ul>
          <a href="#" class="id_add"><span class="i-plus"></span>もっと入力</a><br>
          <button type="submit" class="btn-primary">検索</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="modal_close">閉じる</button>
      </div>
    </div>
    <div id="ad"> [広告]
      <script async src="http://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
      <!-- record/result.php_responsive -->
      <ins class="adsbygoogle recordresultphp-responsive"
           style="display:inline-block"
           data-ad-client="ca-pub-8063117190073359"
           data-ad-slot="3543412796"></ins>
      <script>
      (adsbygoogle = window.adsbygoogle || []).push({});
      </script>
    </div>

  <div class="container">
    <section>
      <table id="list" class="tablesorter">
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
    <section id="role">
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

        echo '<table><thead>'; 
        echo '<tr class="'.$tClass.'"><td>'.$team.'陣営</td>';
        echo '<td><span class="i-fire"></span>'.$db->getTeamWin($team)
          .'/'.$db->getTeamGachi($team).'</td>';
        echo '<td>('.$db->getTeamWinP($team).'%)</td>';
        //echo '<td><span class="i-book"></span>'.$db->getTeamRP($team).'</td>';
        echo '</tr></thead><tbody>';

        foreach($db->getSkillArray($team) as $skill)
        {
          echo '<tr><td>'.$skill.'</td>';
          echo '<td><span class="i-fire"></span>'.$db->getSkillWin($team,$skill)
            .'/'.$db->getSkillGachi($team,$skill).'</td>';
          echo '<td>('.$db->getSkillWinP($team,$skill).'%)</td>';
          //echo '<td><span class="i-book"></span>'.$db->getSkillRP($team,$skill).'</td></tr>';
        }
        echo '</tbody></table>';
      }
?>
    </section>
      <footer>
<p><a href="#"><span class="i-up"></span></a></p>
       <ul>
           <li><a href="document.html"><span class="id"></span>説明書</a></li>
           <li><a href="index.html">トップページへ</a></li>
           <li><a href="https://twitter.com/fortmorst">作った人: fortmorst</a></li>
           <li><a href="http://waoon.net">waoon.net</a></li>
       </ul>
      </footer>
    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="lib/tablesorter.min.js"></script>
    <script src="lib/leanModal.min.js"></script>
    <script src="lib/addInputArea.min.js"></script>
    <script>
      $(document).ready(function() 
        { 
          $("#list").tablesorter(); 
          $('#id').addInputArea({
            maximum : 5
          });
          $( 'a[rel*=leanModal]').leanModal({
            overlay : 0.5,               // 背面の透明度 
            closeButton: ".modal_close"  // 閉じるボタンのCSS classを指定
          });
        } 
      );            
    </script>
  </body>
</html>
