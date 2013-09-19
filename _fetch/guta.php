<?php

require_once('./fetch_village.php');

define('COUNTRY',11);  //国ID
define('VID',0);       //villageテーブルの開始ID

$fetch = new fetch_Village(COUNTRY);

$base_list = $fetch->read_list();

foreach($base_list as $item_vil)
{
  //初期化
  $village = array(
             'vno'  =>$item_vil[0]
            ,'name' =>$item_vil[1]
            ,'date' =>""
            ,'nop'  =>$item_vil[2]
            ,'rglid'=>""
            ,'days' =>""
            ,'wtmid'=>""
  );

  //情報欄取得
  $html = $fetch->fetch_url($item_vil[4]);

  //ガチ村のみ勝利陣営を挿入
  if($html->find('p.multicolumn_left',1)->plaintext  === 'ガチ推理')
  {
    $village['wtmid'] = $fetch->insert_winteam_id($item_vil[3]);
  }
  else
  {
    $village['wtmid'] = $fetch::TM_NONE;
  }

  echo $village['wtmid'].'###';

  //var_dump($village);
}
