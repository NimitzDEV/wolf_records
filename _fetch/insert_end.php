<?php

require_once('../../lib/simple_html_dom.php');
require_once('../_afetch/data.php');
mb_internal_encoding("UTF-8");

class Insert_End
{
  private  $url
          ,$days;

  function __construct($url_vil,$days)
  {
    $this->url = preg_replace('/(.+)turn=\d{1,}/','$1turn=',$url_vil);
    $this->days = $days;
  }

  function make_end(&$cast,$rp)
  {
    $data = new Data();
    $characters = array_keys($cast);
    for($i=2; $i<=$this->days; $i++) 
    {
      $fetch = new simple_html_dom();
      $fetch->load_file($this->url.$i);
      $info = $fetch->find('p.info');

      foreach($info as $item)
      {
        $destiny = mb_substr(trim($item->plaintext),-6,6);
        $destiny = preg_replace("/\r\n/","",$destiny);

        switch($rp)
        {
          case '人狼BBS':
          case '人狼物語':
            switch($destiny)
            {
              case "突然死した。":
                $persona = preg_replace("/^ ?(.+) は、突然死した。 ?/", "$1", $item->plaintext);
                break;
              case "処刑された。":
                $persona = preg_replace("/(.+\r\n){1,}\r\n(.+) は村人達の手により処刑された。 ?/", "$2", $item->plaintext);
                break;
              case "発見された。":
                $persona = preg_replace("/.+朝、(.+) が無残.+/", "$1", $item->plaintext);
                break;
              default:
                continue;
            }   
            break;
          case 'アイドルになりたいもん！':
            switch($destiny)
            {
              case '行きました。':
                $persona = preg_replace("/(.+\r\n){1,}\r\n(.+)さん は候補から.+/", "$2", $item->plaintext);
                break;
              case 'ばれました。':
                $persona = preg_replace("/(.+)さん は、控え室へ.+/","$1",$item->plaintext);
                break;
              default:
                continue;
            }
            break;
          case 'ぐた国':
            switch($destiny)
            {
              case '銃殺された。':
                $persona = preg_replace("/(.+) は、逃亡を.+/","$1",$item->plaintext);
                break;
              case '殺害された。':
                $persona = preg_replace("/(.+\r\n){1,}\r\n(.+) は人々の手.+/", "$2", $item->plaintext);
                break;
              case '見つかった。':
                $persona = preg_replace("/(.+) の遺体が、.+/","$1",$item->plaintext);
                break;
              default:
                continue;
            }
            break;
          case '適当系':
            switch($destiny)
            {
              case 'ち殺された。':
                $persona = preg_replace("/(.+\r\n){1,}\r\n(.+) は村人達.+/","$2",$item->plaintext);
                break;
              default:
                //なぜかスルーされる(´・ω・`)`)
                continue;
            }
            break;
          case 'Cellule Blanche':
            switch($destiny)
            {
              case '殺された……':
                $persona = preg_replace("/(.+\r\n){1,}\r\n(.+) は無残に.+/", "$2", $item->plaintext);
                break;
              case '…まさか！？':
                $persona = preg_replace("/(.+)の姿が.+/","$1",$item->plaintext);
                break;
              default:
                continue;
            }
            break;
          case '料理は愛情！':
            switch($destiny)
            {
              case 'り込まれた。':
                $persona = preg_replace("/(.+\r\n){1,}\r\n(.+) は鍋の中に.+/", "$2", $item->plaintext);
                break;
              case 'ちゃったよ！':
                $persona = preg_replace("/(.+) がぐつぐつ.+/","$1",$item->plaintext);
                break;
              case 'んだってさ。':
                $persona = preg_replace("/(.+) は.+と一緒に鍋に.+/","$1",$item->plaintext);
              default:
                continue;
            }
            break;
          case '蛇村＠Ｇすたいる':
            switch($destiny)
            {
              case 'グに入った。':
                $persona = preg_replace("/(.+\r\n){1,}\r\n(.+) は元ズニアたち.+/", "$2", $item->plaintext);
                break;
              case 'なくなった。':
                $persona = preg_replace("/.+朝、(.+) が白タイツ.+/", "$1", $item->plaintext);
                break;
              case '後を追った。':
                $persona = preg_replace("/(.+) は絆に.+/","$1",$item->plaintext);
              default:
                continue;
            }
            break;
          case 'ふわもこ村':
            switch($destiny)
            {
            case 'けてみたよ。':
                $persona = preg_replace("/(.+\r\n){1,}\r\n(.+)ちゃん を、うらの.+/", "$2", $item->plaintext);
                break;
            case 'のしわざだ！':
                $persona = preg_replace("/(.+)ちゃん がいなく.+/","$1",$item->plaintext);
                break;
            }
            break;
          default:
            echo 'NOTICE: unknown rp type.';
            break;
        }
        if(!empty($persona))
        {
          $persona = trim($persona);
          $cast[$persona]['end'] = $i;
          unset($characters[array_search($persona,$characters)],$item);
        }
      }
      $fetch->clear();
      unset($info,$fetch);

    }
    unset($fetch);

    //endが空のペルソナを生存扱いにする
    foreach($characters as $item)
    {
      $cast[$item]['end'] = $this->days;
    }
  }
}

