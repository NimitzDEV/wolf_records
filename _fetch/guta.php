<?php

require_once('./fetch_village.php');

define('COUNTRY',11);  //国ID
define('VID',0);       //villageテーブルの終了済ID

$fetch = new fetch_Village(COUNTRY);

//裏切りの陣営としてカウントするレギュレーション 深い霧は別途分岐を書く
$TM_EVIL = array($fetch::RGL_E,$fetch::RGL_ETC);
//陣営リスト
$TM_NORMAL = array(
      "村人"=>$fetch::TM_VILLAGER
    , "人狼"=>$fetch::TM_WOLF
    , "妖精"=>$fetch::TM_FAIRY
    , "恋人"=>$fetch::TM_LOVERS
    , "一匹"=>$fetch::TM_LWOLF
    , "笛吹"=>$fetch::TM_PIPER
    , "邪気"=>$fetch::TM_EFB
    , "裏切"=>$fetch::TM_EVIL
    , "据え"=>$fetch::TM_FISH
  );
$TM_AMBER = array(
      "町人"=>$fetch::TM_VILLAGER
    , "魔術"=>$fetch::TM_WOLF
    , "琥珀"=>$fetch::TM_FAIRY
    , "星に"=>$fetch::TM_LOVERS
    , "星の"=>$fetch::TM_LOVERS //エピ表記
    , "はぐ"=>$fetch::TM_LWOLF
    , "吟遊"=>$fetch::TM_PIPER
    , "賭博"=>$fetch::TM_EFB
    , "不和"=>$fetch::TM_EVIL
    , "据え"=>$fetch::TM_FISH
);

//レギュレーション
$RGL = array(
    '標準'=>$fetch::RGL_LEO
  , '深い霧の森'=>$fetch::RGL_MIST
  , '人狼BBS C国'=>$fetch::RGL_C
  , '人狼BBS F国'=>$fetch::RGL_F
  , '人狼BBS G国'=>$fetch::RGL_G
  , '人狼審問 試験壱型'=>$fetch::RGL_TES1
  , '人狼審問 試験弐型'=>$fetch::RGL_TES2
  , '自由設定'=>$fetch::RGL_ETC
);

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
            ,'rglid'=>$RGL[$item_vil[5]]
            ,'days' =>$item_vil[4]
            ,'wtmid'=>""
  );

  //情報欄取得
  $html = $fetch->fetch_url($item_vil[6]);

  $wtmid = $html->find('p.multicolumn_left',1)->plaintext;
  //ガチ村のみ勝利陣営を挿入
  if($wtmid === 'ガチ推理' OR $wtmid === '推理＆RP')
  {
    $village['wtmid'] = $TM_NORMAL[mb_substr($item_vil[3],0,2)];
  }
  else
  {
    $village['wtmid'] = $fetch::TM_NONE;
  }

  //ガチ村の自由設定は手動で修正する
  if($village['wtmid'] !== 0 && $village['rglid'] === $fetch::RGL_ETC)
  {
    echo $village['vno'].' is 自由設定 && ガチ'.PHP_EOL;
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
      $users['tmid'] = $TM_NORMAL[mb_substr($role,0,2)];
      echo $users['tmid'].'##';
    }


      //echo "##f##".$users['persona'].$users['tmid']."##l##";

    $item_cast->clear();
    unset($item_cast);
  }

  $html->clear();
  unset($html);
}
