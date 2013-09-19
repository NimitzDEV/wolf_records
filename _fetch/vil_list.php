<?php
require_once('../../lib/simple_html_dom.php');
require_once('./country.php');


if(!isset($argv))
{
  echo 'ERROR: insert country ID into parameter.';
  exit;
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
    //過去ログリストを1ページごとにクラスを作って取得(一度に取得すると解析漏れが出る)
    //この方法でも42村の</tr>が認識されない。。
    $page_no = (int)$html->find('table tr.i_hover td',0)->plaintext;
    $page_no = floor($page_no/50);
    for($i=0;$i<=$page_no;$i++)
    {
      $url = $URL_LIST[$country].'&pageno='.$i;
      unset($html);
      $html = new simple_html_dom();
      $html->load_file($url);
      $vil_list[] = $html->find('table',0)->find('tr.i_hover');
    }
    break;
  default:
    echo 'ERROR: undefined country ID.';
    exit;
}
unset($html);

//ファイルを新たに作って書き込む
$fp = fopen('list_'.$country.'.txt','w+');
$no_before = 0;

if(flock($fp,LOCK_EX))
{
  foreach($vil_list as $item)
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
          $win = trim($pages->find('td.small',0)->find('i',0)->plaintext);
          $url_info = preg_replace("/cmd=oldlog/","vid=".$vil_no."&cmd=vinfo",$URL_LIST[$country]);
          fwrite($fp,$vil_no.','.$vil_name.','.$win.','.$url_info.PHP_EOL);
        }
        break;
    }
  }
  echo 'success.';
  fflush($fp);
  flock($fp,LOCK_UN);
}
else
{
  echo 'ERROR: cannot lock file.';
}
unset($vil_list);
fclose($fp);
