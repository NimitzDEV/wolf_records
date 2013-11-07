<?php

require_once('../_afetch/data.php');
require_once('./txt_list.php');
require_once('../../lib/simple_html_dom.php');
require_once('./insert_end.php');

define("COUNTRY",12);
define('VID',1000);    //villageテーブルの開始ID

$fetch = new simple_html_dom();
$list  = new Txt_List(COUNTRY);
$data  = new Data();

//ガチ村
$GACHI = array(61,60,59,57,55,53,52,43,41,39,37,35,33,30,29,27,26,24,23,22,16,14,13,6,4);
$FREE  = array(
             59=>$data::RGL_HERO
            ,39=>$data::RGL_S_E
            ,34=>$data::RGL_S_2
            ,6=>$data::RGL_TES2
            );

$SKILL = array(
   "村人"=>$data::SKL_VILLAGER
  ,"人狼"=>$data::SKL_WOLF
  ,"占い師"=>$data::SKL_SEER
  ,"霊能者"=>$data::SKL_MEDIUM
  ,"狂人"=>$data::SKL_LUNATIC
  ,"守護者"=>$data::SKL_HUNTER
  ,"結社員"=>$data::SKL_MASON
  ,"妖魔"=>$data::SKL_FAIRY
  ,"囁き狂人"=>$data::SKL_LUNAWHS
  ,"聖痕者"=>$data::SKL_STIGMA
  ,"狂信者"=>$data::SKL_FANATIC
  ,"共鳴者"=>$data::SKL_TELEPATH
  ,"天魔"=>$data::SKL_BAT
  ,"呪狼"=>$data::SKL_CURSEWOLF
  ,"智狼"=>$data::SKL_WISEWOLF
  ,"悪戯妖精"=>$data::SKL_PIXY
  ,"念波之民"=>$data::SKL_LUNAPATH
  ,"聞き耳狂人"=>$data::SKL_LUNASIL
  ,"狂鳴者"=>$data::SKL_LUNAMIM
  ,"黙狼"=>$data::SKL_SILENT
);
$DESTINY = array(
   "生存者"=>$data::DES_ALIVE
  ,"突然死"=>$data::DES_RETIRED
  ,"処刑死"=>$data::DES_HANGED
  ,"襲撃死"=>$data::DES_EATEN
  ,"呪詛死"=>$data::DES_CURSED
  ,"後追死"=>$data::DES_SUICIDE
);

$base_list = $list->read_list();
$list->open_list('village');
$list->open_list('users');

foreach($base_list as $val_vil=>$item_vil)
{
  //初期化
  $village = array(
             'vno'  =>$item_vil[0]
            ,'name' =>$item_vil[1]
            ,'date' =>""
            ,'nop'  =>""
            ,'rglid'=>""
            ,'days' =>""
            ,'wtmid'=>""
  );

  //情報欄取得
  $fetch->load_file($item_vil[2]);

  //人数取得
  $nop = $fetch->find('p.multicolumn_left',1)->plaintext;
  $village['nop'] = (int)mb_substr($nop,0,mb_strpos($nop,'人'));

  $rglid = trim($fetch->find('p.multicolumn_right',1)->plaintext);
  $rglid = mb_substr($rglid,0,2);
  $rp    = trim($fetch->find('p.multicolumn_left',7)->plaintext);

  //適当系は襲撃死者を取得できない
  if($rp  === '適当系')
  {
    echo 'NOTICE: '.$village['vno'].' is 適当系.';
  }

  if($rglid === '自由' || $rglid === 'ごっ' || $rglid === '聞き')
  {
    if(array_key_exists($village['vno'],$FREE))
    {
      $village['rglid'] = $FREE[$village['vno']];
    }
    else
    {
      $village['rglid'] = $data::RGL_ETC;
    }
  }
  else
  {
    switch($rglid)
    {
      case "標準":
      case "ふつ":
        switch(true)
        {
          case($village['nop']  >= 16):
            $village['rglid'] = $data::RGL_F;
            break;
          case ($village['nop'] === 15):
            $village['rglid'] = $data::RGL_S_3;
            break;
          case ($village['nop'] <= 14 && $village['nop'] >= 8):
            $village['rglid'] = $data::RGL_S_2;
            break;
          default:
            $village['rglid'] = $data::RGL_S_1;
            break;
        }
        break;
      case "Ｃ国":
      case "囁け":
        switch(true)
        {
          case ($village['nop']  >= 16):
            $village['rglid'] = $data::RGL_C;
            break;
          case ($village['nop']  === 15):
            $village['rglid'] = $data::RGL_S_C3;
            break;
          case ($village['nop'] <=14 && $village['nop'] >= 10):
            $village['rglid'] = $data::RGL_S_C2;
            break;
          case ($village['nop']  === 8 || $village['nop'] === 9):
            $village['rglid'] = $data::RGL_S_2;
            break;
          default:
            $village['rglid'] = $data::RGL_S_1;
            break;
        }
        break;
      case "試験":
        switch(true)
        {
          case ($village['nop']  >= 10):
            $village['rglid'] = $data::RGL_TES2;
            break;
          case ($village['nop']  === 8 || $village['nop']  === 9):
            $village['rglid'] = $data::RGL_S_2;
            break;
          default:
            $village['rglid'] = $data::RGL_S_1;
            break;
        }
        break;
      default:
        echo 'NOTICE: undefined regulation.';
        break;
    }
  }

  //日数取得
  $days = trim($fetch->find('p.turnnavi',0)->find('a',-4)->innertext);
  $village['days'] = mb_substr($days,0,1) +1;

  //初日取得
  $fetch->clear();
  $url = preg_replace("/cmd=vinfo/","turn=0&row=10&mode=all&move=page&pageno=1",$item_vil[2]);
  $fetch->load_file($url);

  //開始日(プロローグ第一声)
  $date = mb_substr($fetch->find('div.mes_date',0)->plaintext,5,10);
  //MySQL用に日付の区切りを/から-に変換
  $village['date'] = preg_replace('/(\d{4})\/(\d{2})\/(\d{2})/','\1-\2-\3',$date);

  //エピローグ取得
  $url = preg_replace("/cmd=vinfo/","mode=all&move=page&pageno=1&row=20&turn=".$village['days'],$item_vil[2]);
  $fetch->clear();
  $fetch->load_file($url);

  //勝利陣営取得
  if(in_array($village['vno'],$GACHI))
  {
    $wtmid = $fetch->find('p.info',-1)->innertext;
    $wtmid = mb_convert_encoding($wtmid,"UTF-8","auto");//なぜか文字化けする
    $wtmid = mb_substr(trim($wtmid),0,5);
    switch($wtmid)
    {
      case '全ての人狼':
      case '全ての死霊':
      case '最後の人狼':
      case '暗雲が去り':
        $village['wtmid'] = $data::TM_VILLAGER;
        break;
      case 'もう人狼に':
      case '闇が村を覆':
      case 'おめでとう':
      case '人狼はたっ':
        $village['wtmid'] = $data::TM_WOLF;
        break;
    }
  }
  else
  {
    $village['wtmid'] = $data::TM_RP;
  }

  //村を書き込む
  $cast = $fetch->find('table.vindex tbody tr');
  array_shift($cast);
  $list->write_list('village',$village,$val_vil+1,count($cast));

  $cast_value = array();
  $cast_keys = array();
  foreach($cast as $val_cast => $item_cast)
  {
    $users = array(
       'vid'    =>$val_vil + VID
      ,'persona'=>trim($item_cast->find('td',1)->plaintext)
      ,'player' =>$item_cast->find('td',2)->plaintext
      ,'role'   =>""
      ,'dtid'   =>""
      ,'end'    =>""
      ,'sklid'  =>""
      ,'tmid'   =>""
      ,'life'   =>""
      ,'rltid'  =>""
    );
    $role = $item_cast->find('td',4)->plaintext;
    if(mb_substr($role,0,1) === '(')
    {
      $users['role'] = preg_replace('/\((.+)\)\r\n.+/',"$1",$role);
      $users['sklid'] = $SKILL[$users['role']];
    }
    else
    {
      $users['role'] = preg_replace('/(.+)\(.+\)\r\n\(.+/s',"$1",$role);
      $users['sklid'] = $SKILL[preg_replace('/.+\((.+)\)\r\n\(.+/s',"$1",$role)];
    }

    $dtid = $item_cast->find('td',3)->plaintext;
    $users['dtid'] = $DESTINY[$dtid];

    switch($users['sklid'])
    {
      case $data::SKL_VILLAGER:
      case $data::SKL_SEER:
      case $data::SKL_MEDIUM:
      case $data::SKL_HUNTER:
      case $data::SKL_MASON:
      case $data::SKL_STIGMA:
      case $data::SKL_TELEPATH:
        $users['tmid'] = $data::TM_VILLAGER;
        break;
      case $data::SKL_WOLF:
      case $data::SKL_LUNATIC:
      case $data::SKL_LUNAWHS:
      case $data::SKL_FANATIC:
      case $data::SKL_CURSEWOLF:
      case $data::SKL_WISEWOLF:
      case $data::SKL_LUNASIL:
      case $data::SKL_LUNAMIM:
      case $data::SKL_SILENT:
        $users['tmid'] = $data::TM_WOLF;
        break;
      case $data::SKL_FAIRY:
      case $data::SKL_BAT:
      case $data::SKL_PIXY:
        $users['tmid'] = $data::TM_FAIRY;
        break;
    }

    if($village['wtmid'] === 0)
    {
      $users['rltid'] = $data::RSL_JOIN;
    }
    else if($village['wtmid'] === $users['tmid'])
    {
      $users['rltid'] = $data::RSL_WIN;
    }
    else
    {
      $users['rltid'] = $data::RSL_LOSE;
    }

    //ペルソナ名をキーにする
    $cast_value[] = $users;
    $cast_keys[] = $users['persona'];
  }
  $item_cast->clear();
  unset($item_cast);
  $cast = array_combine($cast_keys,$cast_value);

  //end
  $end = new Insert_End($url,$village['days']);
  $end->make_end($cast,$rp);

  $count_cast = 0;

  foreach($cast as $item_cast)
  {
    $count_cast++;
    //生存係数挿入
    if($item_cast['dtid'] === $data::DES_ALIVE)
    {
      $item_cast['life'] = 1.00;
    }
    else
    {
      $item_cast['life'] = round(($item_cast['end']-1) / $village['days'],2);
    }
    $list->write_list('users',$item_cast,$count_cast);
  }


  $fetch->clear();
  echo $village['vno']. ' is end.'.PHP_EOL;
}
unset($fetch);
