<?php
require_once('../../lib/simple_html_dom.php');

define('N_OLD1','1');
define('N_MAIN','2');
define('N_A','3');
define('N_B','4');
define('N_C','5');
define('N_D','6');
define('N_E','7');
define('N_F','8');
define('N_G','9');
define('N_OLD2','10');
define('GUTA','11');
define('GUTA_OLD','12');
define('GIJI_M','13');
define('GIJI_CABALA',14);
define('GIJI_P',15);
define('GIJI_X',16);
define('GIJI_C',17);

$URL_LIST = array(
  ""
  ,"http://ninjinix.x0.com/wolf_old/"
  ,"http://ninjinix.x0.com/wolf0/index.rb?cmd=log"
  ,"http://ninjinix.x0.com/wolfa/index.rb?cmd=log"
  ,"http://ninjinix.x0.com/wolfb/index.rb?cmd=log"
  ,"http://ninjinix.x0.com/wolfc/index.rb?cmd=log"
  ,"http://ninjinix.x0.com/wolfd/index.rb?cmd=log"
  ,"http://ninjinix.x0.com/wolfe/index.rb?cmd=log"
  ,"http://ninjin002.x0.com/wolff/index.rb?cmd=log"
  ,"http://wolfg.x0.com/index.rb?cmd=log"
  ,"http://ninjinix.x0.com/wolf/"
  ,"http://www3.marimo.or.jp/~fgmaster/cabala/sow.cgi?cmd=oldlog"
  ,"http://www3.marimo.or.jp/~fgmaster/sow/sow.cgi?pageno=0&cmd=oldlog&rowall=on"
  ,"http://morphe.sakura.ne.jp/morphe/sow.cgi?cmd=oldlog"
  ,"http://cabala.halfmoon.jp/cafe/sow.cgi?cmd=oldlog"
  ,"http://perjury.rulez.jp/sow.cgi?cmd=oldlog"
  ,"http://xebec.x0.to/xebec/sow.cgi?cmd=oldlog"
  ,"http://crazy-crazy.sakura.ne.jp/crazy/sow.cgi?cmd=oldlog"
);

if(!isset($argv))
{
  echo 'ERROR: insert country ID into parameter.';
  exit(1);
}


$html = new simple_html_dom();
$country = $argv[1];
$url = $URL_LIST[$country];
$html->load_file($url);

switch($country)
{
  case N_OLD1:
  case N_OLD2:
    $vil_list = $html->find('table.list',0)->find('a');
    break;
  case N_MAIN:
  case N_A:
  case N_B:
  case N_C:
  case N_D:
  case N_E:
  case N_F:
  case N_G:
    $vil_list = $html->find('div.main a');
    array_pop($vil_list);
    break;
  case GUTA:
  case GIJI_M:
  case GIJI_P:
  case GIJI_X:
  case GIJI_C:
  case GIJI_CABALA:
    //過去ログリストを1ページごとにクラスを作って取得(一度に取得すると解析漏れが出る)
    //この方法でもぐた42村の</tr>が認識されない。。
    if($country === GUTA)
    {
      $split = 50;
    }
    else
    {
      $split = 30;
    }
    $page_no = (int)$html->find('table tr td',0)->plaintext;
    $page_no = floor($page_no/$split);
    for($i=0;$i<=$page_no;$i++)
    {
      $url = $URL_LIST[$country].'&pageno='.$i;
      unset($html);
      $html = new simple_html_dom();
      $html->load_file($url);
      $vil_list[] = $html->find('table',0)->find('tr');
    }
    break;
  case GUTA_OLD:
    $vil_list = $html->find('tbody td[colspan=6] a');
    break;
  default:
    echo 'ERROR: undefined country ID.';
    exit;
}
$html->clear;
unset($html);

//ファイルを新たに作って書き込む
$fp = fopen('list_'.$country.'.txt','w+');
$no_before = 0;

if(flock($fp,LOCK_EX))
{
  foreach($vil_list as $value=>$item)
  {
    switch($country)
    {
      case N_OLD1:
      case N_OLD2:
      case N_MAIN:
      case N_A:
      case N_B:
      case N_C:
      case N_D:
      case N_E:
      case N_F:
      case N_G:
        //村名取得 village[0]=村番号、village[1]=村名
        $village = explode(" ",$item->plaintext);
        //重複している村番号は飛ばす
        if($no_before  === $village[0])
        {
          continue;
        }
        $no_before = $village[0];
        switch($country)
        {
          case N_OLD1:
            //日付がURLになっているため、プロローグではなく終了ページのURLを挿入
            $url_pro = $url.'index.rb?vid='.$village[0];
            break;
          case N_OLD2:
            $url_pro = $url.'index.rb?vid='.$village[0].'&meslog='.$village[0].'_ready_0';
            break;
          case N_G:
            $village[0] = mb_substr($village[0],1);
          default:
            $url_pro = preg_replace("/index\.rb\?cmd=log/",$item->href,$url);
            break;
        }
        fwrite($fp,$village[0].','.$village[1].','.$url_pro.PHP_EOL);
        $no_before = $village[0];
        break;
      case GUTA:
        foreach($item as $pages)
        {
          $vil_no = (int)$pages->find('td',0)->plaintext;
          $vil_name = $pages->find('td',1)->find('a',0)->plaintext;
          $nop = $pages->find('td.small',0)->plaintext;
          $nop = (int)mb_substr($nop,0,mb_strpos($nop,'人'));
          $win = trim($pages->find('td.small',0)->find('i',0)->plaintext);
          $days = $pages->find('td.small',1)->plaintext +1;
          $rgl = $pages->find('td.small',2)->find('a',1)->plaintext;
          $url_info = preg_replace("/cmd=oldlog/","vid=".$vil_no."&cmd=vinfo",$URL_LIST[$country]);
          fwrite($fp,$vil_no.','.$vil_name.','.$nop.','.$win.','.$days.','.$rgl.','.$url_info.PHP_EOL);
          $pages->clear();
          unset($pages);
        }
        break;
      case GUTA_OLD:
        if($value %2  === 0)
        {
          $title = $item->plaintext;
          $vil_no = mb_substr($title,0,mb_strpos($title,' '));
          $vil_name = trim(mb_substr($title,mb_strpos($title,' ')));
          $url_info = preg_replace("/pageno=0&cmd=oldlog&rowall=on/","vid=".$vil_no."&cmd=vinfo",$URL_LIST[$country]);
          fwrite($fp,$vil_no.','.$vil_name.','.$url_info.PHP_EOL);
        }
        break;
      case GIJI_M:
      case GIJI_P:
      case GIJI_X: //最後にデータのない村を取ってくる？
      case GIJI_C:
      case GIJI_CABALA:
        foreach($item as $count=>$pages)
        {
          if($count  === 0)
          {
            continue;
          }
          $vil_no = (int)$pages->find('td',0)->plaintext;
          $vil_name = $pages->find('td a',0)->plaintext;
          if($country == GIJI_CABALA)
          {
            $url_info = preg_replace("/cmd=oldlog/","vid=".$vil_no."#mode=info_open_player",$URL_LIST[$country]);
            //$url_info = preg_replace("/cmd=oldlog/","vid=".$vil_no."&rowall=on&turn=0#potofs_order=stat_type&hide_potofs=&row=10&order=asc&page=1&mode=talk_all_open_player",$URL_LIST[$country]);
          }
          else
          {
            $url_info = preg_replace("/cmd=oldlog/","vid=".$vil_no."&cmd=vinfo",$URL_LIST[$country]);
          }
          fwrite($fp,$vil_no.','.$vil_name.','.$url_info.PHP_EOL);
          $pages->clear();
          unset($pages);
        }
        break;
    }
  }
  unset($item);
  fflush($fp);
  flock($fp,LOCK_UN);
  unset($vil_list);
  fclose($fp);
  echo 'success.';
  exit(0);  //(´・ω・`)`)
}
else
{
  echo 'ERROR: cannot lock file.';
  unset($vil_list);
  fclose($fp);
  exit(1);
}
