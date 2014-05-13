<?php
header("Cache-Control: no-cache");
require 'lib/ClassLoader.php';
$class_loader = new ClassLoader([__DIR__.'/lib','/home/waoon/lib']);
$id = new IDs();
if($id->is_valid_id($_GET))
{
  $db = new Get_DB($id->players);
  $db->start_fetch();
}
else
{
  echo <<<EOF
<!DOCTYPE html><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><link rel="stylesheet" href="css/index.css"><body><header><h1>エラー</h1></header><div class="container"><section><p>IDを入力して下さい。</p></section><form action="./result.php" method="GET"><input type="text" name="id_0" placeholder="IDを入力して下さい" required><br><button type="submit" class="btn-primary">検索</button></form></div></body>
EOF;
  exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta name="author" content="fortmorst">
    <meta name="description" content="ID: <?= $id->view_name;?>さんのWeb人狼戦績の一覧ページです。">
    <link rel="apple-touch-icon-precomposed" href="img/apple-touch-icon-precomposed.png"/>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="css/result.css">
    <title>
      <?= $id->view_name;?> の人狼戦績 | 人狼戦績まとめ
    </title>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-42678919-1', 'waoon.net'); ga('require', 'displayfeatures');ga('require', 'linkid', 'linkid.js');  ga('send', 'pageview'); ga(‘set’, ‘&uid’, {{USER_ID}});
</script>
<!--[if lt IE 9]>
  <script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
  <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->
  </head>
  <body>
    <header>
      <div class="container">
<?php
if($db->doppel !== null)
{
  echo '<h1 class="doppel">'.$id->view_name.'</h1><div>'.$db->make_doppel(htmlspecialchars($_SERVER["REQUEST_URI"])).'</div>';
}
else
{
  echo '<h1>'.$id->view_name.'</h1>';
}
?>
        <dl>
          <dt>総合参加数</dt>
          <dd>
           <?= $db->join->sum; ?>
           <span class="i-fire"></span><?= $db->join->gachi; ?>
           <span class="i-book"></span><?= $db->join->rp; ?>
          </dd>
<br>
          <dt>勝率</dt>
          <dd>
            <span class="i-fire"></span><?= $db->join->rate ?><span>%</span>
          </dd>
<br class="mod-sphone">
          <dt>平均生存係数</dt>
          <dd>
            <span class="i-fire"></span><?= $db->join->live_gachi; ?>
            <span class="i-book"></span><?= $db->join->live_rp; ?>
          </dd>

        </dl>
        <a href="https://twitter.com/share" class="twitter-share-button" data-lang="ja" data-count="none">ツイート</a>
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
              <th data-dynatable-column="vno">国番号</th>
              <th>村名</th>
              <th>編成</th>
              <th>キャラクタ名</th>
              <th>役職</th>
              <th data-dynatable-column="des">結末</th>
              <th>結果</th>
          </tr>
        </thead>
        <tbody>
<?= $db->record; ?>
        </tbody>
      </table>
    </section>
    <section id="role">
<?= $db->teams; ?>
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
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="lib/checkWidth.js"></script>
    <script src="lib/dynatable.js"></script>
    <script src="lib/sorts.js"></script>
    <script src="lib/balloon.min.js"></script>
    <script src="lib/leanModal.min.js"></script>
    <script src="lib/addInputArea.min.js"></script>
    <script src="../lib/slidemenu.min.js"></script>
    <script>
      $(window).load(function() {
        $('#list')
        .bind('dynatable:init', function(e, dynatable) {
          dynatable.sorts.functions["cnt"] = sortCountry;
          dynatable.sorts.functions["des"] = sortDestiny;
        })
        .dynatable({
          dataset: {
            sortTypes: { vno: 'cnt', des: 'des' }
          }
        });
          $('td a,td span[title]').balloon({
            minLifetime: 0, showDuration: 0, hideDuration: 0,
          });
      });
      $(function() 
        { 
          $('#id').addInputArea({
            maximum : 7
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
