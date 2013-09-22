<?php
require_once('../../lib/simple_html_dom.php');
require_once('./country.php');

class Fetch_Village
{
  //陣営 team
  const TM_NONE      = 0; //陣営なし
  const TM_VILLAGER  = 1; //村人陣営
  const TM_WOLF      = 2; //人狼陣営
  const TM_FAIRY     = 3; //妖魔陣営
  const TM_LOVERS    = 4; //恋人陣営
  const TM_LWOLF     = 6; //一匹狼陣営
  const TM_PIPER     = 7; //笛吹き陣営
  const TM_EFB       = 8; //邪気陣営
  const TM_EVIL      = 9; //裏切りの陣営
  const TM_FISH      = 10;//据え膳

  //編成 regulation
  const RGL_C    =  1;      //C編成
  const RGL_F    =  2;      //F編成
  const RGL_G    =  3;      //G編成
  const RGL_S_2  =  4;      //少人数狼2
  const RGL_S_3  =  5;      //少人数狼3
  const RGL_E    =  6;      //妖魔入り
  const RGL_S_C2 =  7;      //少人数狼2
  const RGL_S_C3 =  8;      //少人数狼3
  const RGL_S_L0 =  9;      //少人数狂人なし
  const RGL_W2   = 10;      //狼2共有あり
  const RGL_LEO  = 11;      //決定者入り
  const RGL_TES1 = 12;      //試験壱
  const RGL_TES2 = 13;      //試験弐
  const RGL_MIST = 14;      //深い霧の夜
  const RGL_LOVE = 15;      //恋人入り
  const RGL_LPLY = 16;      //遊び人入り
  const RGL_G_ST = 17;      //聖痕入りG
  const RGL_ETC  = 100;     //特殊

  //結果 result
  const RSL_WIN  = 1;
  const RSL_LOSE = 2;
  const RSL_JOIN = 3;       //参加(非ガチ村)
  const RSL_INVALID = 4;     //無効(新議事での突然死)
  const RSL_ONLOOKER = 5;   //見物

  //テキスト分割行
  const LIMIT = 400;

  private $country
        , $fp_village
        , $fp_users
        , $count_v
        , $lastline_v
        , $nop
        , $loop_village
        , $loop_users;

  function __construct($country)
  {
    $this->country = $country;
    $this->loop_village = 0;
    $this->loop_users = 0;
  }

  function read_list()
  {
    $filename = 'list_'.$this->country.'.txt';

    if (!file_exists($filename))
    {
      echo "ERROR: ".$filename." is not found.";
      exit;
    }

    $list = fopen($filename,"r");
    while($line = fgets($list))
    {
      $line = preg_replace("/\n/","",$line);
      $vil_list[] = explode(",",$line);
    }
    fclose($list);

    $this->lastline_v = count($vil_list);

    return $vil_list;
  }

  function fetch_url($url)
  {
    $html = new simple_html_dom();
    $html->load_file($url);
    return $html;
  }

  function open_list($type)
  {
    $this->{'loop_'.$type}++;
    $this->{'fp_'.$type} = fopen($this->country.$type.'_'.$this->{'loop_'.$type}.'.sql','w+');
    flock($this->{'fp_'.$type},LOCK_EX);
    switch($type)
    {
      case 'village';
        fwrite($this->fp_village,"INSERT INTO village (cid,vno,name,date,nop,rglid,days,wtmid) VALUES\n");
        break;
      case 'users';
        fwrite($this->fp_users,"INSERT INTO users (vid,persona,player,role,dtid,end,sklid,tmid,life,rltid) VALUES\n");
    }
  }

  function write_list($type,$array,$val,$nop=0)
  {
    $line = implode("','",$array);
    switch($type)
    {
      case 'village';
        $line = "('".$this->country."','".$line."')";
        $this->nop = $nop;
        if($val === $this->lastline_v)
        {
          $this->close_list($line,$type);
          return;
        }
        $this->count_v = $val;
        break;
      case 'users';
        $line = "('".$line."')";
        if($val === $this->nop && $this->count_v === $this->lastline_v)
        {
          $this->close_list($line,$type);
          return;
        }
        break;
    }

    if($val === $this::LIMIT * $this->{'loop_'.$type})
    {
      //規定行を越えたら次のファイルに移動
      $this->close_list($line,$type);
      $this->open_list($type);
    }
    else
    {
      $line = $line.",\n";
      fwrite($this->{'fp_'.$type},$line);
    }
  }

  function close_list($line,$type)
  {
    fwrite($this->{'fp_'.$type},$line.";");
    flock($this->{'fp_'.$type},LOCK_UN);
    fflush($this->{'fp_'.$type});
    fclose($this->{'fp_'.$type});
  }
}
