<?
require_once('lib/GetDB.php');
require_once('lib/CheckID.php');

$cID = new CheckID($_GET);

if($cID->getIsID())
{
  $db = new GetDB($cID->getPlayerArr());
  $db->connect();

  if($db->FetchJoinCount())
  {
    $boolDoppel = $db->fetchDoppelID();
    $table = $db->getTable();
    $db->fetchTeamCount();
  }
  $db->disConnect();
}
else
{
  echo '<!DOCTYPE html><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><link rel="stylesheet" href="css/index.css"><body>';
  echo '<header><h1>エラー</h1></header><div class="container"><section><p>';
  echo 'IDを入力して下さい。</p></section>';
  echo '<form action="./result.php" method="GET"><input type="text" name="id_0" placeholder="IDを入力して下さい" required><br><button type="submit" class="btn-primary">検索</button></form></div></body>';
  exit;
}

?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta name="author" content="fortmorst">
    <meta name="description" content="ID: <?= $cID->getViewName();?>さんのWeb人狼戦績の一覧ページです。">
    <link rel="apple-touch-icon-precomposed" href="img/apple-touch-icon-precomposed.png"/>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="css/result.css">
    <title>
      <?= $cID->getViewName();?> の人狼戦績 | 人狼戦績まとめ
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
  echo '<h1 class="doppel">'.$cID->getViewName().'</h1><div>';
  echo $db->getDoppel(htmlspecialchars($_SERVER["REQUEST_URI"]));
  echo '</div>';
}
else
{
  echo '<h1>'.$cID->getViewName().'</h1>';
}
?>
        <dl>
          <dt>総合参加数</dt>
          <dd>
           <?= $db->getJoinSum(); ?>
           <span class="i-fire"></span><? echo $db->getJoinGachi(); ?>
           <span class="i-book"></span><? echo $db->getJoinRP(); ?>
          </dd>
<br>
          <dt>勝率</dt>
          <dd>
            <span class="i-fire"></span><?= $db->getJoinWinPercent() ?><span>%</span>
          </dd>
<br class="mod-sphone">
          <dt>平均生存係数</dt>
          <dd>
            <span class="i-fire"></span><?= $db->getLiveGachi(); ?>
            <span class="i-book"></span><? echo $db->getLiveRP(); ?>
          </dd>

        </dl>
 <a href="https://twitter.com/share" class="twitter-share-button" data-lang="ja" data-count="none">ツイート</a>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
      </div>
<nav>
  <div class="navbar-header">
    <button type="button" data-toggle="collapse" data-target=".navbar-collapse">
      <span></span>
      <span></span>
      <span></span>
    </button>
    <span class="tips">
          <span class="i-fire"></span>勝敗あり
          <span class="i-book"></span>勝負度外視
      </span>
  </div>
  <div class="collapse navbar-collapse">
    <form action="./result.php" method="GET">
      <div class="form-group">
        <input type="text" name="id_0" placeholder="ID検索">
      </div>
      <div class="form-group">
        <a rel="leanModal" href="#more-ID"><div class="i-plus"><span>もっと増やす</span></div></a>
        <button type="submit" class="btn-primary">検索</button>
      </div>
    </form>
    <ul>
      <li><a href="#role">役職別参加数</a></li>
    </ul>
    <ul>
      <li><a href="document.html">説明書</a></li>
    </ul>
  </div>
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
      <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
      <!-- record/result.php_responsive -->
      <ins class="adsbygoogle"
           style="display:block"
           data-ad-client="ca-pub-8063117190073359"
           data-ad-slot="3543412796"
           data-ad-format="horizontal"></ins>
      <script>
      (adsbygoogle = window.adsbygoogle || []).push({});
      </script>
    </div>

  <div class="container">
    <section id="scroll">
      <div class="toggle_scroll">
        <a href="#">表を縮小可能にする</a>
      </div>
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
                $vname = mb_strimwidth($item['vname'],0,38,"..","UTF-8");
                switch ($item['result'])
                {
                  case '勝利':
                    $lClass = 'w';
                    break;
                  case '敗北':
                    $lClass = 'l';
                    break;
                  case '参加':
                    $lClass = 'j';
                    break;
                  case '無効':
                    $lClass = 'i';
                    break;
                  case '見物':
                    $lClass = 'o';
                    break;
                }
                if($item['wtmid'])
                {
                  $icon = 'i-fire';
                }
                else
                {
                  $icon = 'i-book';
                }
                $url = preg_replace('/%n/',$item['vno'],$item['url']);
                echo '<tr><td>'.date("Y/m/d",strtotime($item['date'])).'</td>';
                echo '<td>'.$item['country'].$item['vno'].'</td>';
                echo '<td class="vname '.$icon.'"><a href="'.$url.'" title="'.$item['vname'].'">'.$vname.'</a></td>';
                echo '<td>'.$item['rgl'].'</td>';
                echo '<td>'.$item['persona'].'</td>';
                echo '<td>'.$item['role'].'</td>';
                echo '<td>'.$item['end'].'d'.$item['destiny'].'</td>';
                echo '<td><span class="'.$lClass.'">'.$item['result'].'</span></td></tr>';
              }
              unset($table,$item,$lClass);
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
$TEAM_ARRAY = array(
   "村人"=>'village'
  ,"人狼"=>"wolf"
  ,"妖魔"=>"fairy"
  ,"恋人"=>"lovers"
  ,"一匹狼"=>"lwolf"
  ,"笛吹き"=>"piper"
  ,"邪気"=>"efb"
  ,"裏切り"=>"evil"
  ,"据え膳"=>"fish"
);


      foreach($db->getTeamArray() as $count=>$team)
      {
        if($team  === "見物人")
        {
          continue;
        }
        $tClass = $TEAM_ARRAY[$team];
        $team_rp  = $db->getTeamRP($team);

        echo '<div class="role"><table>';
        echo '<thead><tr class="'.$tClass.'"><td>'.$team.'陣営</td>';
        echo $db->get_team_tr($team);
        echo '</tr></thead><tbody>';

        foreach($db->getSkillArray($team) as $skill)
        {
          echo '<tr><td>'.$skill.'</td>';
          echo $db->get_skill_tr($team,$skill);
          echo '</tr>';
        }
        echo '</tbody></table></div>';
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
    <script src="lib/checkWidth.js"></script>
    <script src="lib/dynatable.js"></script>
    <script src="lib/leanModal.min.js"></script>
    <script src="lib/addInputArea.min.js"></script>
    <script src="../lib/slidemenu.min.js"></script>
    <script>
      $(function() 
        { 
          $("#list").dynatable();
          $('#id').addInputArea({
            maximum : 5
          });
          $( 'a[rel*=leanModal]').leanModal({
            overlay : 0.5,               // 背面の透明度 
            closeButton: ".modal_close"  // 閉じるボタンのCSS classを指定
          });
        } 
      );            
      $(window).on("load resize",function(){
          $().checkWidth();
      });
    </script>
  </body>
</html>
