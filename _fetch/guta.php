<?php

require_once('./fetch_village.php');

define('COUNTRY',11);  //国ID
define('VID',0);       //villageテーブルの終了済ID

$fetch = new fetch_Village(COUNTRY);

//裏切りの陣営分岐用 深い霧は別途分岐を書く
$TM_EVIL = array($fetch::RGL_E,$fetch::RGL_ETC);

$base_list = $fetch->read_list();

$fetch->open_list('village');
$fetch->open_list('users');

foreach($base_list as $val_vil=>$item_vil)
{
  //初期化
  $village = array(
             'vno'  =>$item_vil[0]
            ,'name' =>$item_vil[1]
            ,'date' =>""
            ,'nop'  =>$item_vil[2]
            ,'rglid'=>""
            ,'days' =>$item_vil[4]
            ,'wtmid'=>""
  );

  //情報欄取得
  $html = $fetch->fetch_url($item_vil[6]);

  $wtmid = $html->find('p.multicolumn_left',1)->plaintext;
  //ガチ村のみ勝利陣営を挿入
  if($wtmid === 'ガチ推理' OR $wtmid === '推理＆RP')
  {
    switch($item_vil[3])
    {
      case '村人の勝利':
        $village['wtmid'] = $fetch::TM_VILLAGER;
        break;
      case '人狼の勝利':
        $village['wtmid'] = $fetch::TM_WOLF;
        break;
      case '妖精の勝利':
        $village['wtmid'] = $fetch::TM_FAIRY;
        break;
      case '恋人達の勝利':
        $village['wtmid'] = $fetch::TM_LOVERS;
        break;
      case '一匹狼の勝利':
        $village['wtmid'] = $fetch::TM_LWOLF;
        break;
      case '笛吹き勝利':
        $village['wtmid'] = $fetch::TM_PIPER;
        break;
      case '邪気の勝利':
        $village['wtmid'] = $fetch::TM_EFB;
        break;
      case '勝利者なし':
        $village['wtmid'] = $fetch::TM_NONE;
        break;
      default:
        echo 'ERROR: '.$village['vno'].' has undefined winteam.'.PHP_EOL;
        break;
    }
  }
  else
  {
    $village['wtmid'] = $fetch::TM_NONE;
  }

  //編成
  switch($item_vil[5])
  {
    case '標準':
      $village['rglid'] = $fetch::RGL_LEO;
      break;
    case '深い霧の夜':
      $village['rglid'] = $fetch::RGL_MIST;
      break;
    case '人狼BBS C国':
      $village['rglid'] = $fetch::RGL_C;
      break;
    case '人狼BBS F国':
      $village['rglid'] = $fetch::RGL_F;
      break;
    case '人狼BBS G国':
      $village['rglid'] = $fetch::RGL_G;
      break;
    case '人狼審問 試験壱型':
      $village['rglid'] = $fetch::RGL_TES1;
      break;
    case '人狼審問 試験弐型':
      $village['rglid'] = $fetch::RGL_TES2;
      break;
    case '自由設定':
      $village['rglid'] = $fetch::RGL_ETC;
      if($village['wtmid'] !== 0)
      {
        echo $village['vno'].' is 自由設定 && ガチ'.PHP_EOL;
      }
      break;
    default:
      echo 'ERROR: '.$village['vno'].' has undefined regulation.'.PHP_EOL;
      break;
  }



  //初日取得
  $html->clear();
  $url = preg_replace("/cmd=vinfo/","turn=0&row=10&mode=all&move=page&pageno=1",$item_vil[6]);
  $html = $fetch->fetch_url($url);
  $village['date'] = mb_substr($html->find('p.mes_date',0)->plaintext,5,10);

  //村を書き込む
  $fetch->write_list('village',$village,$val_vil+1,$village['nop']);

  //エピローグ取得
  $html->clear();
  $url = preg_replace("/0&row=10/",$village['days']."&row=50",$url);
  $html = $fetch->fetch_url($url);
  $cast = $html->find('tbody tr.i_active');

  foreach($cast as $val_cast => $item_cast)
  {
    $users = array(
               'vid'    =>$val_vil + VID
              ,'persona'=>trim($item_cast->find("td",0)->plaintext)
              ,'player' =>trim($item_cast->find("td",1)->plaintext)
              ,'role'  =>""
              ,'dtid'=>""
              ,'end' =>""
              ,'sklid'=>""
              ,'tmid'=>""
              ,'life'=>""
              ,'rltid'=>""
    );


    //日数
    $users['end'] = (int)preg_replace("/(.+)日/","$1",$item_cast->find("td",2)->plaintext);
    if($users['end'] === 0)
    {
      $users['end'] === $village['days'];
    }

    //陣営と役職を取得
    $role = $item_cast->find("td",4)->plaintext;
    $role = mb_substr($role,0,mb_strpos($role,"\n")-1);
    $users['role'] = mb_substr($role,mb_strpos($role,'：')+1);

    if(mb_substr($users['role'],-3) === "居た ")
    {
      //見物人設定
      $users['role'] = '見物人';
      $users['end'] = 0;
      $users['life'] = 0;
      $users['rltid'] = $fetch::RSL_ONLOOKER;
    }
    else
    {
      switch(mb_substr($role,0,2))
      {
        case '村人':
          $users['tmid'] = $fetch::TM_VILLAGER;
          break;
        case '人狼':
          $$users['tmid'] = $fetch::TM_WOLF;
          break;
        case '妖精':
          $users['tmid'] = $fetch::TM_FAIRY;
          break;
        case '恋人':
          $users['tmid'] = $fetch::TM_LOVERS;
          break;
        case '一匹':
          $users['tmid'] = $fetch::TM_LWOLF;
          break;
        case '笛吹':
          $users['tmid'] = $fetch::TM_PIPER;
          break;
        case '邪気':
          $users['tmid'] = $fetch::TM_EFB;
          break;
        case '据え':
          $users['tmid'] = $fetch::TM_FISH;
          break;
        case '裏切':
          //三陣営目がいない村では、裏切り陣営を人狼陣営カウントにする
          if(in_array($village['rglid'],$TM_EVIL) OR ($village['rglid'] === $fetch::RGL_MIST AND ($village['nop'] <8 OR $village['nop'] >18)))
          {
            $users['tmid'] = $fetch::TM_EVIL;
          }
          else
          {
            $users['tmid'] = $fetch::TM_WOLF;
          }
          break;
      }
    }


      //echo "##f##".$users['persona'].$users['tmid']."##l##";

    $item_cast->clear();
    unset($item_cast);
  }

  $html->clear();
  unset($html);
}
