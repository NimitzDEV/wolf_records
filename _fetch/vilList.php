<?
require_once('simple_html_dom.php');

//取得する国のアルファベットを入力;
$c = 'old2';


/*--------------------------------------------------------------------------------

  0. 定数・変数

  --------------------------------------------------------------------------------*/

define('URL_OLD','http://ninjinix.x0.com/wolf');
define('URL_LOG','/index.rb?cmd=log');
define('URL_F','http://ninjin002.x0.com/wolff');
define('URL_G','http://www.wolfg.x0.com');

switch($c)
{
  case 'a':
  case 'b':
  case 'c':
  case 'd':
  case 'e':
  case '0':
    $url_list = URL_OLD.$c.URL_LOG;
    $url_vil = URL_OLD.$c.'/';
    break;
  case 'old2':
    $url_list = URL_OLD.'/';
    $url_vil = URL_OLD.'/';
    break;
  case 'f';
    $url_list = URL_F.URL_LOG;
    $url_vil = URL_F.'/';
    break;
  case 'g';
    $url_list = URL_G.URL_LOG;
    $url_vil  = URL_G.'/';
    break;
}


// 村リスト一覧取得テスト
$html = new simple_html_dom();
$html->load_file($url_list);

//村IDと村名を取得
if ($c === 'old2')
{
  $rawVilList = $html->find('table.list',0)->find('a');
} else {
  $rawVilList = $html->find('div.main a');
  // 最後のリンク(トップに戻るを削除
  array_pop($rawVilList);
}


foreach($rawVilList as $item)
{
  //村名取得 village[0]=村番号、village[1]=村名
  $village = explode(" ",$item->plaintext);
  if($c === 'g')
  {
    $village[0] = mb_substr($village[0],1);
  }
  //村プロローグURL取得
  if($c === 'old2')
  {
    $proURL = $url_vil.'index.rb?vid='.$village[0].'&meslog='.$village[0].'_ready_0';
  } else {
    $proURL = $url_vil.$item->href; 
  }
  echo $village[0].','.$village[1].','.$proURL.PHP_EOL;

}

$html->clear();
unset($html);
