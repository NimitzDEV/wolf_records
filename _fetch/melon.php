<?php

require_once('../_afetch/data.php');
require_once('./txt_list.php');
require_once('../../lib/simple_html_dom.php');

define('COUNTRY',26);  //国ID
define('VID',6368);    //villageテーブルの開始ID

$fetch = new simple_html_dom();
$list  = new Txt_List(COUNTRY);
$data  = new Data();


$FREE_NAME = ['自由設定','いろいろ','ごった煮','オリジナル','選択科目','フリーダム','特殊事件','特殊業務','闇鍋'];
$RGL_FREE = [
  '（ 村人: 7人 占い師: 1人 霊能者: 1人 狩人: 1人 ） （ 人狼: 2人 狂信者: 1人 ）'=>$data::RGL_TES3
];

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

  //人数
  $village['nop'] = (int)preg_replace('/(\d+)人.+/','\1',$fetch->find('p.multicolumn_left',1)->plaintext);

  //レギュレーション挿入
  $rglid_check = $fetch->find('p.multicolumn_right',1)->plaintext;
  $rglid = preg_replace('/^ ([^ ]+) .+/','\1',$rglid_check);
  if(in_array($rglid,$FREE_NAME))
  {
    //自由設定でも特定の編成はレギュレーションを指定する
    $free = mb_substr($rglid_check,mb_strpos($rglid_check,'　')+1);
    $free = preg_replace('/ ＋（ 見物人: \d*人 ） /','',$free);
    if(array_key_exists($free,$RGL_FREE))
    {
      $village['rglid'] = $RGL_FREE[$free];
    }
    else
    {
      echo $village['vno'].'->'.$free.PHP_EOL;
      $village['rglid'] = $data::RGL_ETC;
    }
  }
  else
  {
    switch($rglid)
    {
      case "標準":
      case "いつもの":
      case "ふつー":
      case "通常業務":
        switch(true)
        {
          case ($village['nop']  >= 16):
            $village['rglid'] = $data::RGL_F;
            break;
          case ($village['nop']  === 15):
            $village['rglid'] = $data::RGL_S_3;
            break;
          case ($village['nop'] <=14 && $village['nop'] >= 8):
            $village['rglid'] = $data::RGL_S_2;
            break;
          default:
            $village['rglid'] = $data::RGL_S_1;
            break;
        }
        break;
      case "試験壱型":
      case "しけん１":
      case "壱型？":
      case "聖獣降臨":
      case "業務１課":
        switch(true)
        {
          case ($village['nop']  >= 13):
            $village['rglid'] = $data::RGL_TES1;
            break;
          case ($village['nop'] <=12 && $village['nop'] >= 8):
            $village['rglid'] = $data::RGL_S_2;
            break;
          default:
            $village['rglid'] = $data::RGL_S_1;
            break;
        }
        break;
      case "試験弐型":
      case "しけん２":
      case "屹度弐型":
      case "猛烈信鬼":
      case "業務２課":
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
      case "試験参型":
      case "しけん３":
      case "屹度参型":
      case "絶叫狂鬼":
      case "業務３課":
        switch(true)
        {
          case ($village['nop']  >= 10):
            $village['rglid'] = $data::RGL_TES3;
            break;
          case ($village['nop']  === 8 || $village['nop']  === 9):
            $village['rglid'] = $data::RGL_S_2;
            break;
          default:
            $village['rglid'] = $data::RGL_S_1;
            break;
        }
        break;
      case "Ｃ国":
      case "ヒソヒソ":
      case "囁けます":
      case "闇の囁鬼":
      case "囁く補佐":
      case "魔術師の愛弟子は暗躍する":
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
      case "ハム入り":
      case "よーまだ":
      case "妖魔有り":
      case "ハムハム":
      case "飯綱暗躍":
      case "産業間諜":
        switch(true)
        {
          case ($village['nop']  >= 16):
            $village['rglid'] = $data::RGL_E;
            break;
          case ($village['nop']  === 15):
            $village['rglid'] = $data::RGL_S_3;
            break;
          case ($village['nop'] <=14 && $village['nop'] >= 8):
            $village['rglid'] = $data::RGL_S_2;
            break;
          default:
            $village['rglid'] = $data::RGL_S_1;
            break;
        }
        break;
      case "Ｇ国":
        switch(true)
        {
          case ($village['nop']  >= 16):
            $village['rglid'] = $data::RGL_G;
            break;
          case ($village['nop']  <= 15 && $village['nop'] >= 13):
            $village['rglid'] = $data::RGL_S_3;
            break;
          case ($village['nop'] <=12 && $village['nop'] >= 8):
            $village['rglid'] = $data::RGL_S_2;
            break;
          default:
            $village['rglid'] = $data::RGL_S_1;
            break;
        }
        break;
      default:
        echo 'NOTICE: '.$village['vno'].' has unknown regulation.->'.$rglid.PHP_EOL;
    }
  }

  //言い換え
  $rp = $fetch->find('p.multicolumn_left',9)->plaintext;

  var_dump($village);

  $fetch->clear();
  //echo $village['vno']. ' is end.'.PHP_EOL;
}
unset($fetch);
