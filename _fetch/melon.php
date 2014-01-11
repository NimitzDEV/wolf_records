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
   '（ 村人: 5人 占い師: 1人 霊能者: 1人 狩人: 1人 聖痕者: 1人 ） （ 人狼: 2人 狂人: 2人 ）'=>$data::RGL_TES1
  ,'（ 村人: 7人 占い師: 1人 霊能者: 1人 狩人: 1人 ） （ 人狼: 2人 狂信者: 1人 ）'=>$data::RGL_TES2
  ,'（ 村人: 2人 占い師: 1人 霊能者: 1人 狩人: 1人 ） （ 人狼: 2人 狂信者: 1人 ）'=>$data::RGL_TES2
  ,'（ むらびと: 7人 うらないし: 1人 れいのー: 1人 しゅご: 1人 きょーめいしゃ: 2人 ） （ じんろー: 3人 きょーしんしゃ: 1人 ）'=>$data::RGL_TES2
  ,'（ 村人: 7人 占い師: 1人 霊能者: 1人 狩人: 1人 共鳴者: 2人 ） （ 人狼: 3人 狂信者: 1人 ）'=>$data::RGL_TES2
  ,'（ むらびと: 5人 うらないし: 1人 れいのー: 1人 しゅご: 1人 ） （ じんろー: 2人 きょーしんしゃ: 1人 ）'=>$data::RGL_TES2
  ,'（ 支社社員: 7人 監査役: 1人 筆頭株主: 1人 保守派: 1人 秘匿恋愛者: 2人 ） （ 本社人事: 3人 人事補佐Ｂ: 1人 ）'=>$data::RGL_TES2
  ,'（ 村人: 5人 占い師: 1人 霊能者: 1人 狩人: 1人 ） （ 人狼: 2人 叫迷狂人: 1人 ）'=>$data::RGL_TES3
  ,'（ むらびと: 8人 うらないし: 1人 れいのー: 1人 しゅご: 1人 きょーめいしゃ: 2人 ） （ じんろー: 3人 おたけびきょーじん: 2人 ）'=>$data::RGL_TES3
  ,'（ 村人: 6人 ） （ 人狼: 1人 狂人: 1人 ）'=>$data::RGL_HERO
  ,'（ 村人: 4人 占い師: 1人 霊能者: 1人 守護者: 1人 ） （ 人狼: 1人 狂人: 1人 ）'=>$data::RGL_S_1
  ,'（ 村人: 3人 占い師: 1人 ） （ 人狼: 1人 狂人: 1人 ）'=>$data::RGL_S_1
  ,'（ むらびと: 4人 うらないし: 1人 ） （ じんろー: 1人 きょーじん: 1人 ）'=>$data::RGL_S_1
  ,'（ 村人: 4人 占い師: 1人 ） （ 人狼: 1人 狂人: 1人 ）'=>$data::RGL_S_1
  ,'（ 村人: 2人 占い師: 1人 狩人: 1人 ） （ 人狼: 1人 Ｃ国狂人: 1人 ）'=>$data::RGL_S_1
  ,'（ ただの人: 5人 エスパー: 1人 イタコ: 1人 ストーカー: 1人 ） （ おおかみ: 2人 人狼教神官: 1人 ）'=>$data::RGL_S_2
  ,'（ 村人: 5人 占い師: 1人 霊能者: 1人 狩人: 1人 ） （ 人狼: 2人 ）'=>$data::RGL_S_2
  ,'（ 村人: 3人 占い師: 1人 霊能者: 1人 狩人: 1人 ） （ 人狼: 1人 狂人: 1人 ）'=>$data::RGL_S_1
  ,'（ 村人: 6人 占い師: 1人 霊能者: 1人 ） （ 人狼: 2人 ）'=>$data::RGL_S_2
  ,'（ むらびと: 5人 うらないし: 1人 れいのー: 1人 ） （ じんろー: 2人 ）'=>$data::RGL_S_2
  ,'（ 村人: 6人 占い師: 1人 霊能者: 1人 狩人: 1人 ） （ 人狼: 2人 狂人: 1人 ）'=>$data::RGL_S_2
  ,'（ 村人: 5人 占い師: 1人 狩人: 1人 ） （ 人狼: 2人 狂人: 1人 ）'=>$data::RGL_S_2
  ,'（ 村人: 4人 占い師: 1人 霊能者: 1人 狩人: 1人 ） （ 人狼: 2人 狂人: 1人 ）'=>$data::RGL_S_2
  ,'（ むらびと: 4人 うらないし: 1人 れいのー: 1人 しゅご: 1人 ） （ じんろー: 2人 きょーじん: 1人 ）'=>$data::RGL_S_2
  ,'（ ただの人: 4人 エスパー: 1人 イタコ: 1人 ストーカー: 1人 ） （ おおかみ: 2人 人狼スキー: 1人 ）'=>$data::RGL_S_2
  ,'（ 村人: 7人 占い師: 1人 霊能者: 1人 狩人: 1人 ） （ 人狼: 2人 狂人: 1人 ）'=>$data::RGL_S_2
  ,'（ 村人: 5人 占い師: 1人 霊能者: 1人 ） （ 人狼: 2人 狂人: 1人 ）'=>$data::RGL_S_2
  ,'（ 村人: 5人 占い師: 1人 霊能者: 1人 狩人: 1人 ） （ 人狼: 2人 狂人: 1人 ）'=>$data::RGL_S_2
  ,'（ 村人: 6人 占い師: 1人 霊能者: 1人 狩人: 1人 ） （ 人狼: 2人 狂人: 1人 ）'=>$data::RGL_S_2
  ,'（ 村人: 6人 占い師: 1人 霊能者: 1人 狩人: 1人 ） （ 人狼: 3人 狂人: 1人 ）'=>$data::RGL_S_3
  ,'（ 村人: 8人 占い師: 1人 霊能者: 1人 狩人: 1人 ） （ 人狼: 3人 狂人: 1人 ）'=>$data::RGL_S_3
  ,'（ 村人: 6人 占い師: 1人 霊能者: 1人 狩人: 1人 聖痕者: 1人 ） （ 人狼: 3人 狂人: 1人 ）'=>$data::RGL_S_3
  ,'（ 村人: 6人 占い師: 1人 霊能者: 1人 狩人: 1人 聖痕者: 1人 ） （ 人狼: 3人 ）'=>$data::RGL_S_3
  ,'（ 村人: 6人 占い師: 1人 霊能者: 1人 狩人: 1人 聖痕者: 1人 ） （ 人狼: 3人 狂人: 1人 ）'=>$data::RGL_S_3
  ,'（ 村人: 7人 占い師: 1人 霊能者: 1人 狩人: 1人 ） （ 人狼: 3人 狂人: 1人 ）'=>$data::RGL_S_3
  ,'（ むらびと: 7人 うらないし: 1人 れいのー: 1人 しゅご: 1人 ） （ じんろー: 3人 きょーじん: 1人 ）'=>$data::RGL_S_3
  ,'（ ただの人: 7人 エスパー: 1人 イタコ: 1人 ストーカー: 1人 おしどり夫婦: 2人 ） （ おおかみ: 3人 人狼教神官: 1人 ）'=>$data::RGL_C
  ,'（ 村人: 11人 占い師: 1人 霊能者: 1人 狩人: 1人 共鳴者: 2人 ） （ 人狼: 3人 Ｃ国狂人: 1人 ）'=>$data::RGL_C
  ,'（ 村人: 7人 占い師: 1人 霊能者: 1人 狩人: 1人 共鳴者: 2人 ） （ 人狼: 3人 Ｃ国狂人: 1人 ）'=>$data::RGL_C
  ,'（ 村人: 7人 占い師: 1人 霊能者: 1人 狩人: 1人 共有者: 2人 ） （ 人狼: 3人 Ｃ国狂人: 1人 ）'=>$data::RGL_C
  ,'（ ただの人: 8人 エスパー: 1人 イタコ: 1人 ストーカー: 1人 おしどり夫婦: 2人 ） （ おおかみ: 3人 人狼教神官: 1人 ）'=>$data::RGL_C
  ,'（ 村人: 10人 占い師: 1人 霊能者: 1人 狩人: 1人 聖痕者: 1人 ） （ 人狼: 3人 Ｃ国狂人: 1人 ）'=>$data::RGL_C_ST
  ,'（ 村人: 8人 占い師: 1人 霊能者: 1人 狩人: 1人 聖痕者: 1人 ） （ 人狼: 3人 Ｃ国狂人: 1人 ）'=>$data::RGL_C_ST
  ,'（ 村人: 2人 占い師: 1人 霊能者: 1人 狩人: 1人 ） （ 人狼: 2人 Ｃ国狂人: 1人 ）'=>$data::RGL_S_C2
  ,'（ 村人: 6人 占い師: 1人 狩人: 1人 ） （ 人狼: 2人 Ｃ国狂人: 1人 ）'=>$data::RGL_S_C2
  ,'（ 村人: 3人 占い師: 1人 霊能者: 1人 狩人: 1人 ） （ 人狼: 2人 Ｃ国狂人: 1人 ）'=>$data::RGL_S_C2
  ,'（ 村人: 4人 占い師: 1人 霊能者: 1人 狩人: 1人 ） （ 人狼: 2人 Ｃ国狂人: 1人 ）'=>$data::RGL_S_C2
  ,'（ 村人: 5人 占い師: 1人 霊能者: 1人 狩人: 1人 ） （ 人狼: 2人 Ｃ国狂人: 1人 ）'=>$data::RGL_S_C2
  ,'（ 村人: 6人 占い師: 1人 霊能者: 1人 狩人: 1人 ） （ 人狼: 2人 Ｃ国狂人: 1人 ）'=>$data::RGL_S_C2
  ,'（ ただの人: 4人 エスパー: 1人 イタコ: 1人 ストーカー: 1人 ） （ おおかみ: 2人 人狼教神官: 1人 ）'=>$data::RGL_S_C2
  ,'（ 参加者: 6人 証人: 1人 医者: 1人 守衛: 1人 聖痕者: 1人 ） （ 犯人: 3人 囁き狂言者: 1人 ）'=>$data::RGL_S_C3
  ,'（ 村人: 8人 占い師: 1人 霊能者: 1人 狩人: 1人 ） （ 人狼: 3人 Ｃ国狂人: 1人 ）'=>$data::RGL_S_C3
  ,'（ 村人: 6人 占い師: 1人 霊能者: 1人 狩人: 1人 共有者: 2人 ） （ 人狼: 3人 狂人: 1人 ） （ ハムスター人間: 1人 ）'=>$data::RGL_E
  ,'（ 村人: 7人 占い師: 1人 霊能者: 1人 狩人: 1人 共有者: 2人 ） （ 人狼: 3人 狂人: 1人 ） （ ハムスター人間: 1人 ）'=>$data::RGL_E
  ,'（ むらびと: 6人 うらないし: 1人 れいのー: 1人 しゅご: 1人 きょーめいしゃ: 2人 ） （ じんろー: 3人 きょーじん: 1人 ） （ よーま: 1人 ）'=>$data::RGL_E
  ,'（ 村人: 8人 占い師: 1人 霊能者: 1人 狩人: 1人 ） （ 人狼: 3人 狂人: 1人 ） （ ハムスター人間: 1人 ）'=>$data::RGL_E
  ,'（ 村人: 6人 占い師: 1人 霊能者: 1人 狩人: 1人 共鳴者: 2人 ） （ 人狼: 3人 狂人: 1人 ） （ ハムスター人間: 1人 ）'=>$data::RGL_E
  ,'（ 村人: 7人 占い師: 1人 霊能者: 1人 狩人: 1人 共鳴者: 1人 ） （ 人狼: 3人 狂人: 1人 ） （ 蝙蝠人間: 1人 ）'=>$data::RGL_E
  ,'（ 村人: 7人 占い師: 1人 霊能者: 1人 狩人: 1人 聖痕者: 1人 ） （ 人狼: 3人 狂人: 1人 ） （ ハムスター人間: 1人 ）'=>$data::RGL_E
  ,'（ 村人: 7人 占い師: 1人 霊能者: 1人 守護者: 1人 聖痕者: 1人 ） （ 人狼: 3人 狂人: 1人 ） （ 妖魔: 1人 ）'=>$data::RGL_E
  ,'（ 村人: 7人 占い師: 1人 霊能者: 1人 狩人: 1人 聖痕者: 1人 ） （ 人狼: 3人 Ｃ国狂人: 1人 ） （ ハムスター人間: 1人 ）'=>$data::RGL_E
  ,'（ 村人: 8人 占い師: 1人 霊能者: 1人 狩人: 1人 共鳴者: 2人 ） （ 人狼: 3人 狂人: 1人 ） （ ハムスター人間: 1人 ）'=>$data::RGL_E
  ,'（ 村人: 7人 占い師: 1人 霊能者: 1人 狩人: 1人 共有者: 2人 ） （ 人狼: 3人 Ｃ国狂人: 1人 ） （ ハムスター人間: 1人 ）'=>$data::RGL_E
  ,'（ 村人: 7人 占い師: 1人 霊能者: 1人 守護者: 1人 聖痕者: 1人 ） （ 人狼: 3人 囁き狂人: 1人 ） （ 妖魔: 1人 ）'=>$data::RGL_E
  ,'（ 村人: 6人 占い師: 1人 霊能者: 1人 狩人: 1人 共鳴者: 2人 ） （ 人狼: 3人 Ｃ国狂人: 1人 ） （ ハムスター人間: 1人 ）'=>$data::RGL_E
  ,'（ 村人: 7人 占い師: 1人 霊能者: 1人 狩人: 1人 聖痕者: 1人 ） （ 人狼: 3人 狂信者: 1人 ） （ ハムスター人間: 1人 ）'=>$data::RGL_E
  ,'（ 村人: 6人 占い師: 1人 霊能者: 1人 守護者: 1人 結社員: 2人 ） （ 人狼: 3人 狂人: 1人 ） （ 妖魔: 1人 ）'=>$data::RGL_E
  ,'（ 村人: 8人 占い師: 1人 霊能者: 1人 守護者: 1人 ） （ 人狼: 3人 狂人: 1人 ） （ 妖魔: 1人 ）'=>$data::RGL_E
  ,'（ むらびと: 8人 うらないし: 1人 れいのー: 1人 しゅご: 1人 けっしゃ: 2人 ） （ じんろー: 3人 きょーしんしゃ: 1人 ） （ よーま: 1人 ）'=>$data::RGL_E
  ,'（ ただの人: 8人 エスパー: 1人 イタコ: 1人 ストーカー: 1人 ） （ おおかみ: 3人 人狼スキー: 1人 ） （ ハム: 1人 ）'=>$data::RGL_E
  ,'（ 村人役: 6人 占い師役: 1人 霊能者役: 1人 狩人役: 1人 共有者役: 2人 ） （ 人狼役: 3人 狂人役: 1人 ） （ 妖狐役: 1人 ）'=>$data::RGL_E
  ,'（ 村人: 6人 占い師: 1人 霊能者: 1人 守護者: 1人 結社員: 2人 ） （ 人狼: 3人 囁き狂人: 1人 ） （ 妖魔: 1人 ）'=>$data::RGL_E
  ,'（ 村人: 4人 占い師: 1人 霊能者: 1人 狩人: 1人 ） （ 人狼: 2人 狂人: 1人 ） （ ハムスター人間: 1人 ）'=>$data::RGL_S_E
  ,'（ 村人: 5人 占い師: 1人 ） （ 人狼: 1人 ） （ ハムスター人間: 1人 ）'=>$data::RGL_S_E
  ,'（ むらびと: 5人 うらないし: 1人 れいのー: 1人 しゅご: 1人 ） （ じんろー: 2人 きょーじん: 1人 ） （ よーま: 1人 ）'=>$data::RGL_S_E
  ,'（ 村人: 6人 占い師: 1人 霊能者: 1人 狩人: 1人 ） （ 人狼: 2人 狂人: 1人 ） （ ハムスター人間: 1人 ）'=>$data::RGL_S_E
  ,'（ 村人: 6人 占い師: 1人 霊能者: 1人 狩人: 1人 聖痕者: 1人 ） （ 人狼: 2人 Ｃ国狂人: 1人 ） （ ハムスター人間: 1人 ）'=>$data::RGL_S_E
  ,'（ 村人: 5人 占い師: 1人 霊能者: 1人 守護者: 1人 共鳴者: 2人 ） （ 人狼: 3人 狂人: 1人 ） （ 妖魔: 1人 ）'=>$data::RGL_S_E
  ,'（ むらびと: 6人 うらないし: 1人 れいのー: 1人 しゅご: 1人 ホクロもち: 1人 ） （ じんろー: 2人 きょーじん: 1人 ） （ よーま: 1人 ）'=>$data::RGL_S_E
  ,'（ 村人: 7人 占い師: 1人 霊能者: 1人 狩人: 1人 ） （ 人狼: 2人 狂信者: 1人 ） （ ハムスター人間: 1人 ）'=>$data::RGL_S_E
  ,'（ 村人: 5人 占い師: 1人 霊能者: 1人 守護者: 1人 聖痕者: 1人 ） （ 人狼: 2人 囁き狂人: 1人 ） （ 妖魔: 1人 ）'=>$data::RGL_S_E
  ,'（ むらびと: 3人 うらないし: 1人 れいのー: 1人 ） （ じんろー: 2人 ） （ よーま: 1人 ）'=>$data::RGL_S_E
  ,'（ むらびと: 4人 うらないし: 1人 れいのー: 1人 しゅご: 1人 ホクロもち: 1人 ） （ じんろー: 2人 きょーじん: 1人 ） （ よーま: 1人 ）'=>$data::RGL_S_E
  ,'（ 村人: 7人 占い師: 1人 霊能者: 1人 守護者: 1人 共鳴者: 2人 ） （ 人狼: 3人 狂人: 1人 ） （ 妖魔: 1人 ）'=>$data::RGL_S_E
  ,'（ 村人: 7人 占い師: 1人 霊能者: 1人 狩人: 1人 ） （ 人狼: 2人 狂人: 1人 ） （ ハムスター人間: 1人 ）'=>$data::RGL_S_E
  ,'（ 村人: 3人 占い師: 1人 霊能者: 1人 狩人: 1人 ） （ 人狼: 2人 狂人: 1人 ） （ ハムスター人間: 1人 ）'=>$data::RGL_S_E
  ,'（ 村人: 5人 占い師: 1人 霊能者: 1人 狩人: 1人 ） （ 人狼: 2人 狂人: 1人 ） （ ハムスター人間: 1人 ）'=>$data::RGL_S_E
  ,'（ 村人: 3人 占い師: 1人 ） （ 人狼: 1人 ） （ ハムスター人間: 1人 ）'=>$data::RGL_S_E
  ,'（ むらびと: 4人 うらないし: 1人 れいのー: 1人 しゅご: 1人 ） （ じんろー: 2人 きょーじん: 1人 ） （ よーま: 1人 ）'=>$data::RGL_S_E
  ,'（ 村人: 7人 占い師: 1人 霊能者: 1人 狩人: 1人 共有者: 2人 ） （ 人狼: 3人 狂人: 1人 ）'=>$data::RGL_F
  ,'（ 村人: 9人 占い師: 1人 霊能者: 1人 守護者: 1人 ） （ 人狼: 3人 狂人: 1人 ）'=>$data::RGL_G
  ,'（ 村人: 9人 占い師: 1人 霊能者: 1人 狩人: 1人 ） （ 人狼: 3人 狂人: 1人 ）'=>$data::RGL_G
  ,'（ 村人: 10人 占い師: 1人 霊能者: 1人 狩人: 1人 ） （ 人狼: 3人 狂人: 1人 ）'=>$data::RGL_G
  ,'（ むらびと: 9人 うらないし: 1人 れいのー: 1人 しゅご: 1人 ） （ じんろー: 3人 きょーじん: 1人 ）'=>$data::RGL_G
  ,'（ 村人: 8人 占い師: 1人 霊能者: 1人 狩人: 1人 聖痕者: 1人 ） （ 人狼: 3人 狂人: 1人 ）'=>$data::RGL_G_ST
  ,'（ 一般人: 8人 占い師: 1人 霊能者: 1人 狩人: 1人 聖痕者: 1人 ） （ 人狼: 3人 狂人: 1人 ）'=>$data::RGL_G_ST
  ,'（ 村人: 5人 占い師: 1人 霊能者: 1人 狩人: 1人 共有者: 2人 ） （ 人狼: 3人 狂人: 1人 ） （ 求婚者: 2人 ）'=>$data::RGL_LOVE
  ,'（ 村人: 8人 占い師: 1人 霊能者: 1人 狩人: 1人 ） （ 人狼: 3人 狂人: 1人 ） （ 求婚者: 1人 ）'=>$data::RGL_LOVE
  ,'（ 村人: 1人 占い師: 1人 霊能者: 1人 共鳴者: 2人 ） （ 人狼: 1人 狂人: 1人 ） （ 求婚者: 1人 ）'=>$data::RGL_LOVE
  ,'（ 村人: 7人 占い師: 1人 霊能者: 1人 狩人: 1人 聖痕者: 1人 ） （ 人狼: 3人 Ｃ国狂人: 1人 ） （ 求婚者: 1人 ）'=>$data::RGL_LOVE
  ,'（ 村人: 4人 占い師: 1人 霊能者: 1人 狩人: 1人 共鳴者: 2人 ） （ 人狼: 3人 Ｃ国狂人: 1人 ） （ 求婚者: 4人 ）'=>$data::RGL_LOVE
  ,'（ むらびと: 6人 うらないし: 1人 れいのー: 1人 しゅご: 1人 きょーめいしゃ: 2人 ） （ じんろー: 3人 きょーじん: 1人 ） （ きゅーこんしゃ: 1人 ）'=>$data::RGL_LOVE
  ,'（ 村人: 7人 占い師: 1人 霊能者: 1人 狩人: 1人 ） （ 人狼: 2人 ） （ 求婚者: 1人 ）'=>$data::RGL_LOVE
  ,'（ むらびと: 4人 うらないし: 1人 れいのー: 1人 しゅご: 1人 けっしゃ: 2人 ） （ じんろー: 3人 きょーじん: 1人 ） （ きゅーこんしゃ: 3人 ）'=>$data::RGL_LOVE
  ,'（ 村人: 7人 占い師: 1人 霊能者: 1人 狩人: 1人 聖痕者: 1人 ） （ 人狼: 2人 Ｃ国狂人: 2人 ） （ 求婚者: 1人 ）'=>$data::RGL_LOVE
  ,'（ 村人: 5人 占い師: 1人 霊能者: 1人 狩人: 1人 ） （ 人狼: 2人 狂人: 1人 ） （ 求婚者: 1人 ）'=>$data::RGL_LOVE
  ,'（ 村人: 6人 占い師: 1人 霊能者: 1人 狩人: 1人 ） （ 人狼: 2人 狂人: 1人 ） （ 求婚者: 2人 ）'=>$data::RGL_LOVE
  ,'（ 村人: 7人 占い師: 1人 霊能者: 1人 狩人: 1人 共有者: 2人 ） （ 人狼: 3人 狂人: 1人 ） （ 求婚者: 2人 ）'=>$data::RGL_LOVE
  ,'（ 村人: 4人 占い師: 1人 霊能者: 1人 ） （ 人狼: 2人 ） （ 求婚者: 1人 ）'=>$data::RGL_LOVE
  ,'（ むらびと: 5人 うらないし: 1人 れいのー: 1人 しゅご: 1人 ホクロもち: 1人 ） （ じんろー: 3人 きょーしんしゃ: 1人 ） （ きゅーこんしゃ: 3人 ）'=>$data::RGL_LOVE
  ,'（ 村人: 4人 占い師: 1人 霊能者: 1人 狩人: 1人 ） （ 人狼: 2人 狂人: 1人 ） （ 求婚者: 1人 ）'=>$data::RGL_LOVE
  ,'（ 村人: 5人 占い師: 1人 霊能者: 1人 狩人: 1人 ） （ 人狼: 2人 Ｃ国狂人: 1人 ） （ 求婚者: 1人 ）'=>$data::RGL_LOVE
  ,'（ 村人: 3人 占い師: 1人 霊能者: 1人 ） （ 人狼: 2人 ） （ 求婚者: 1人 ）'=>$data::RGL_LOVE
  ,'（ 村人: 4人 占い師: 1人 霊能者: 1人 ） （ 人狼: 2人 狂人: 1人 ） （ 求婚者: 1人 ）'=>$data::RGL_LOVE
  ,'（ 村人: 7人 占い師: 1人 霊能者: 1人 狩人: 1人 ） （ 人狼: 2人 Ｃ国狂人: 1人 ） （ 求婚者: 1人 ）'=>$data::RGL_LOVE
  ,'（ 村人: 6人 占い師: 1人 霊能者: 1人 狩人: 1人 共鳴者: 2人 ） （ 人狼: 3人 Ｃ国狂人: 1人 ） （ 求婚者: 2人 ）'=>$data::RGL_LOVE
  ,'（ むらびと: 6人 うらないし: 1人 れいのー: 1人 しゅご: 1人 けっしゃ: 2人 ） （ じんろー: 3人 きょーじん: 1人 ） （ きゅーこんしゃ: 2人 ）'=>$data::RGL_LOVE
  ,'（ むらびと: 4人 うらないし: 1人 れいのー: 1人 しゅご: 1人 ） （ じんろー: 2人 きょーじん: 1人 ） （ きゅーこんしゃ: 1人 ）'=>$data::RGL_LOVE
];

$RP_LIST = [
   '人狼物語'=>'SOW'
  ,'まったり'=>'MELON'
  ,'適当系'=>'FOOL'
  ,'妖怪物語'=>'SOW_Y'
  ,'人狼審問'=>'JUNA'
  ,'人狼BBS'=>'WBBS'
  ,'ビジネスオフィス'=>'OFFICE'
  ,'人狼劇場'=>'THEATER'
  ,'アリスのお茶会'=>'ALICE'
  ,'魔神村'=>'MAJIN'
  ,'騎士団領への旅路'=>'TOUR'
  ,'宵闇の琥珀'=>'KOHAKU'
  ,'国史学園'=>'KOKUSI'
  ,'旧校舎の怪談'=>'GB'
  ,'煌びやかな賭博場'=>'CASINO'
  ,'F2077再戦企画'=>'F2077'
  ,'月見村の狼(限定)'=>'MOON'
  ,'裏切りのゲーム盤（限定）'=>'BETRAY'
  ,'ネヴァジスタ(限定)'=>'NEVER'
  ,'VO8(限定)'=>'VO8'
  ,'商店街BBS(限定)'=>'MARKET'
];

$WTM_SKIP = [
   '/村の設定が変更されました。/','/遺言状が公開されました。/','/遺言メモが公開/','/おさかな、美味しかったね！/','/魚人はとても美味/','/人魚は/','/とってもきれいだね！/','/運動会は晴天です！/'
  ,'/見物しに/','/がやってきたよ/'
];

$WTM_SOW = [
   '人狼を退治したのだ！'=>$data::TM_VILLAGER
  ,'を立ち去っていった。'=>$data::TM_WOLF
  ,'いていなかった……。'=>$data::TM_FAIRY
  ,'にも無力なのだ……。'=>$data::TM_LOVERS
  ,'領域だったのだ……。'=>$data::TM_LOVERS
];
$WTM_MELON = [
   'かいけつ！やったね！'=>$data::TM_VILLAGER
  ,'かいけつならずだよ！'=>$data::TM_WOLF
  ,'つ！…したっぽいよ？'=>$data::TM_FAIRY
  ,'がひろがったみたい？'=>$data::TM_FAIRY
  ,'ゅ〜ん&#9825;'=>$data::TM_LOVERS
  ,'きどうなっちゃうの？'=>$data::TM_LOVERS
];
$WTM_FOOL = [
   'が勝ちやがりました。'=>$data::TM_VILLAGER
  ,'ようだ。おめでとう。'=>$data::TM_WOLF
  ,'けている（らしい）。'=>$data::TM_FAIRY
  ,'んだよ！（意味不明）'=>$data::TM_FAIRY
  ,'は世界を救うんだよ！'=>$data::TM_LOVERS
  ,'狼だって救うんだよ！'=>$data::TM_LOVERS
];
$WTM_SOW_Y = [
   '戻ってくると思うよ。'=>$data::TM_VILLAGER
  ,'なくなっちゃったよ。'=>$data::TM_WOLF
  ,'ちを見ているよ……。'=>$data::TM_FAIRY
];
$WTM_JUNA = [
   '人狼に勝利したのだ！'=>$data::TM_VILLAGER
  ,'めて去って行った……'=>$data::TM_WOLF
  ,'くことはなかった……'=>$data::TM_FAIRY
  ,'すすべがなかった……'=>$data::TM_FAIRY
  ,'真の愛に目覚めた……'=>$data::TM_LOVERS
];
$WTM_WBBS = [
   'る日々は去ったのだ！'=>$data::TM_VILLAGER
  ,'の村を去っていった。'=>$data::TM_WOLF
  ,'生き残っていた……。'=>$data::TM_FAIRY
  ,'に世界はあるの……。'=>$data::TM_LOVERS
];
$WTM_OFFICE = [
   'る支社に戻りました！'=>$data::TM_VILLAGER
  ,'ってしまいました…。'=>$data::TM_WOLF
  ,'てしまったようです。'=>$data::TM_FAIRY
];
$WTM_THEATER = [//先頭12文字
   '素敵狼さんを全員捕まえち'=>$data::TM_VILLAGER
  ,'もう狼さんの魅力に抵抗出'=>$data::TM_WOLF
  ,'素敵狼さんを全員捕まえた'=>$data::TM_LOVERS
];
$WTM_ALICE = [
   'いお茶会の再開です！'=>$data::TM_VILLAGER
  ,'ありませんでした…。'=>$data::TM_WOLF
  ,'んか要らないのです。'=>$data::TM_LOVERS
];
$WTM_MAJIN = [
  '年の間閉ざされる……'=>$data::TM_WOLF
];
$WTM_TOUR = [
   '。村人側の勝利です！'=>$data::TM_VILLAGER
  ,'。人狼側の勝利です！'=>$data::TM_WOLF
  ,'にも無力なのだ……。'=>$data::TM_LOVERS
];
$WTM_KOHAKU = [
   '平和な日々が訪れる。'=>$data::TM_VILLAGER
  ,'まで愛でるとしよう。'=>$data::TM_WOLF
];
$WTM_KOKUSI = [
   '人狼を退治したのだ！'=>$data::TM_VILLAGER
  ,'を立ち去っていった。'=>$data::TM_WOLF
];
$WTM_GB = [
  '悪霊を始末したのだ！'=>$data::TM_VILLAGER
];
$WTM_CASINO = [
  'る村人はいないのだ！'=>$data::TM_WOLF
  ,'にも無力なのだ……。'=>$data::TM_LOVERS
];
$WTM_F2077 = [
  'めでとうございます！'=>$data::TM_VILLAGER
];
$WTM_MOON = [
  'を取り戻したのです！'=>$data::TM_VILLAGER
];
$WTM_BETRAY = [
  'の＞を捕らえたのだ！'=>$data::TM_VILLAGER
];
$WTM_NEVER = [
  'と戻っていきました。'=>$data::TM_WOLF
];
$WTM_VO8 = [
  'ーの姿が！ハム勝利！'=>$data::TM_FAIRY
];
$WTM_MARKET = [
  '生き残っていた……。'=>$data::TM_FAIRY
];

//結末
$DESTINY = [
   "処刑"=>$data::DES_HANGED
  ,"突然死"=>$data::DES_RETIRED
  ,"襲撃"=>$data::DES_EATEN
  ,"呪殺"=>$data::DES_CURSED
  ,"後追"=>$data::DES_SUICIDE
];
//勝敗
$RSL = [
   "勝利"=>$data::RSL_WIN
  ,"敗北"=>$data::RSL_LOSE
  ,"--"=>$data::RSL_INVALID //無効(突然死)
];

//能力、陣営
$SKILL = [
   [$data::SKL_VILLAGER,$data::TM_VILLAGER]
  ,[$data::SKL_SEER,$data::TM_VILLAGER]
  ,[$data::SKL_MEDIUM,$data::TM_VILLAGER]
  ,[$data::SKL_HUNTER,$data::TM_VILLAGER]
  ,[$data::SKL_MASON,$data::TM_VILLAGER]
  ,[$data::SKL_TELEPATH,$data::TM_VILLAGER]
  ,[$data::SKL_STIGMA,$data::TM_VILLAGER]
  ,[$data::SKL_NOTARY,$data::TM_VILLAGER]
  ,[$data::SKL_DARKH,$data::TM_VILLAGER]
  ,[$data::SKL_WOLF,$data::TM_WOLF]
  ,[$data::SKL_CURSEWOLF,$data::TM_WOLF]
  ,[$data::SKL_WISEWOLF,$data::TM_WOLF]
  ,[$data::SKL_POSWOLF,$data::TM_WOLF]
  ,[$data::SKL_LUNATIC,$data::TM_WOLF]
  ,[$data::SKL_FANATIC,$data::TM_WOLF]
  ,[$data::SKL_LUNAWHS,$data::TM_WOLF]
  ,[$data::SKL_LUNAPATH,$data::TM_WOLF]
  ,[$data::SKL_SEAL,$data::TM_WOLF]
  ,[$data::SKL_LUNASEER,$data::TM_WOLF]
  ,[$data::SKL_FAIRY,$data::TM_FAIRY]
  ,[$data::SKL_BAT,$data::TM_FAIRY]
  ,[$data::SKL_PIXY,$data::TM_FAIRY]
  ,[$data::SKL_SUCKER,$data::TM_NONE]
  ,[$data::SKL_VAMPIRE,$data::TM_FAIRY]
  ,[$data::SKL_LOVER,$data::TM_LOVERS]
  ,[$data::SKL_FIANCE,$data::TM_LOVERS]
  ,[$data::SKL_FISH,$data::TM_FISH]
  ,[$data::SKL_TERU,$data::TM_TERU]
];
$RP_DEFAULT = ['WBBS','THEATER','MAJIN','TOUR','F2077','BETRAY','NEVER','VO8','MARKET'];
$SKL_SOW = [
   '村人','占い師','霊能者','狩人','共有者','共鳴者','聖痕者','公証人','闇狩人'
   ,'人狼','呪狼','智狼','憑狼','狂人','狂信者','Ｃ国狂人','叫迷狂人','封印狂人','辻占狂人'
   ,'ハムスター人間','蝙蝠人間','小悪魔','血人','吸血鬼'
   ,'求婚者','婚約者','魚人','照坊主'
];

$SKL_MELON = [
   'むらびと','うらないし','れいのー','しゅご','けっしゃ','きょーめいしゃ','ホクロもち','こーしょーにん','やみしゅご'
  ,'じんろー','じゅろー','ちろー','ひょうろー','きょうじん','きょーしんしゃ','ヒソヒソきょーじん','おたけびきょーじん','ふーいんきょーじん','つじうらきょーじん'
  ,'よーま','てんま','ようせい','ちびと','きゅーけつき'
  ,'きゅーこんしゃ','こんやくしゃ','さかなびと','てるぼーず'
];
$SKL_FOOL = [
   'ただの人','エスパー','イタコ','ストーカー','夫婦','おしどり夫婦','痣もち','公証人','闇ストーカー'
  ,'おおかみ','逆恨み狼','グルメ','憑狼','人狼スキー','人狼教信者','人狼教神官','叫迷狂人','封印狂人','辻占狂人'
  ,'ハム','コウモリ','イタズラっ子','血人','吸血鬼'
  ,'求婚者','婚約者','魚人','照坊主'
];
$SKL_SOW_Y = [
    'ただの妖怪','鏡持ち','口寄せ','銭投げ','仙人','山彦','聖獣','公証人','闇銭'
   ,'貧乏神','死神','疫病神','付喪神','狂鬼','信鬼','囁鬼','叫迷狂人','封印狂人','辻占狂人'
   ,'飯綱','鳴家','生霊','血人','吸血鬼'
   ,'求婚者','婚約者','魚人','照坊主'
];
$SKL_JUNA = [
    '村人','占い師','霊能者','守護者','結社員','共鳴者','聖痕者','公証人','闇守護'
   ,'人狼','呪狼','智狼','憑狼','狂人','狂信者','囁き狂人','叫迷狂人','封印狂人','辻占狂人'
   ,'妖魔','天魔','悪戯妖精','血人','吸血鬼'
   ,'求婚者','婚約者','魚人','照坊主'
];
$SKL_OFFICE = [
  '支社社員','監査役','筆頭株主','保守派','労組幹部','秘匿恋愛者','名誉会長','公証人','闇保守派','本社人事','本社人事課長','本社人事次長','本社人事部長','人事補佐Ａ','人事補佐Ｂ','人事補佐Ｃ','叫迷狂人','封印狂人','辻占狂人','産業スパイ梅','産業スパイ桃','産業スパイ桜','血人','吸血鬼','求婚者','婚約者','魚人','照坊主'
];
$SKL_ALICE = [
  '参加者','証人','医者','守衛','裁判官','共鳴者','聖痕者','公証人','闇守衛','犯人','弁の立つ犯人','観察力のある犯人','憑狼','狂言者','狂信者','囁き狂言者','叫迷狂人','封印狂人','辻占狂人','ちゃっかり屋','しっかり屋','うっかり屋','血人','吸血鬼','求婚者','婚約者','魚人','照坊主'
];
$SKL_KOHAKU = [
   '町人','真名探り','好事家','護符職人','刑事','警部','不在証明アリ','公証人','落ち零れ護符職人'
   ,'魔術師','呪術師','秘術師','憑狼','悪徳琥珀商人','魔術師を目撃した者','魔術師の愛弟子','念話術士','封印狂人','辻占狂人'
   ,'琥珀妖精','蝙蝠人間','悪意ある琥珀妖精','尊き琥珀妖精の血族','高貴なる琥珀妖精'
   ,'求婚者','婚約者','人魚','自殺志願者'
];
$SKL_KOKUSI = $SKL_SOW;
$SKL_KOKUSI[0] = '一般人';
$SKL_GB = [
  '学生','占い師','霊能者','退魔師','共有者','共鳴者','聖痕者','公証人','闇狩人','悪霊','呪狼','智狼','憑霊','狂人','狂信者','Ｃ国狂人','叫迷狂人','封印狂人','辻占狂人','ハムスター人間','蝙蝠人間','小悪魔','血人','吸血鬼','求婚者','婚約者','魚人','照坊主'
];
$SKL_CASINO = $SKL_SOW;
$SKL_CASINO[9] = '狼';
$SKL_CASINO[19] = '妖狐';
$SKL_MOON = [
   '村人役','占い師役','霊能者役','狩人役','共有者役','共鳴者','聖痕者','公証人','闇狩人'
   ,'人狼役','呪狼','智狼','憑狼','狂人役','狂信者','Ｃ国狂人','叫迷狂人','封印狂人','辻占狂人'
   ,'妖狐役','蝙蝠人間','小悪魔','血人','吸血鬼'
   ,'求婚者','婚約者','魚人','照坊主'
];
$SKL_BETRAY = [
   'AGNメンバー','覗き屋','情報屋','ガーディアン','共有者','魂の通話者','聖痕者','公証人','闇狩人'
   ,'満月に吼えるもの','呪狼','満月を知るもの','憑狼','狂人','狂信者','内通者','遺伝子の通話者','封じる人','辻占狂人'
   ,'ハムスター人間','蝙蝠人間','小悪魔','神経質な人','吸血鬼'
   ,'求める人','婚約者','魚人','照坊主'
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
    $free = trim(mb_substr($rglid_check,mb_strpos($rglid_check,'　')+1));
    $free = preg_replace('/ ＋.+/','',$free);
    if(array_key_exists($free,$RGL_FREE))
    {
      $village['rglid'] = $RGL_FREE[$free];
    }
    else
    {
      //echo $village['vno'].'->'.$free.PHP_EOL;
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
      case "テスト１式":
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
      case "テスト２式":
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
      case "テスト３式":
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
      case "狐入り":
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
        break;
    }
  }

  //日数取得
  $days = trim($fetch->find('p.turnnavi',0)->find('a',-4)->innertext);
  $days = mb_convert_encoding($days,"UTF-8","auto");
  $village['days'] = mb_substr($days,0,mb_strpos($days,'日')) +1;

  //言い換え
  $rp = $fetch->find('p.multicolumn_left',9)->plaintext;
  if(array_key_exists($rp,$RP_LIST))
  {
    $rp = $RP_LIST[$rp];
  }
  else
  {
    echo $village['vno'].' has undefined RP.';
    $rp = 'SOW';
  }
  //村の方針 推理アイコンがなければfalse
  $policy = mb_strstr($fetch->find('p.multicolumn_left',-2)->plaintext,'推理');

  //初日取得
  $fetch->clear();
  $url = preg_replace("/cmd=vinfo/","t=0&r=10&o=a&mv=p&n=1",$item_vil[2]);
  $fetch->load_file($url);

  //開始日(プロローグ第一声)
  $date = $fetch->find('div.mes_date',0)->plaintext;
  $date = mb_substr($date,7,10);
  //MySQL用に日付の区切りを/から-に変換
  $village['date'] = preg_replace('/(\d{4})\/(\d{2})\/(\d{2})/','\1-\2-\3',$date);

  //エピローグ取得
  $fetch->clear();
  $url = preg_replace("/0&r=10/",$village['days']."&r=40",$url);
  $fetch->load_file($url);

  //勝利陣営
  if(!$policy)
  {
    $village['wtmid'] = $data::TM_RP;
  }
  else
  {
    $wtmid = trim($fetch->find('p.info',-1)->plaintext);
    //遅刻見物人のシスメなどを除外
    $count_replace = 0;
    preg_replace($WTM_SKIP,'',$wtmid,1,$count_replace);
    if($count_replace)
    {
      $do_i = -2;
      do
      {
        $wtmid = trim($fetch->find('p.info',$do_i)->plaintext);
        $do_i--;
        preg_replace($WTM_SKIP,'',$wtmid,1,$count_replace);
      } while($count_replace);
    }
    $wtmid = preg_replace('/\r\n/','',$wtmid);
    //人狼劇場言い換えのみ、先頭12文字で取得する
    if($rp === 'THEATER')
    {
      $wtmid = mb_substr($wtmid,0,12);
    }
    else
    {
      $wtmid = mb_substr($wtmid,-10);
    }
    $village['wtmid'] = ${'WTM_'.$rp}[$wtmid];
  }

  //村を書き込む
  $cast = $fetch->find('table tr');
  array_shift($cast);
  $count_cast = count($cast);
  //見物人がいるなら見出し分を引く
  if($count_cast !== $village['nop'])
  {
    $arr_guest = [];
    foreach($cast as $val_guest => $item_guest)
    {
      $guest = $item_guest->find('th',0);
      if($guest)
      {
        $arr_guest[] = $val_guest;
      }
    }
    foreach($arr_guest as $item_guest)
    {
      unset($cast[$item_guest]);
    }
    $count_cast = count($cast);
  }
  //見物人込みの人数を参加者行数として送る
  $list->write_list('village',$village,$val_vil+1,$count_cast);

  foreach($cast as $val_cast => $item_cast)
  {
    $users = array(
       'vid'    =>$val_vil + VID
      ,'persona'=>trim($item_cast->find("td",0)->plaintext)
      ,'player' =>trim($item_cast->find("td",1)->plaintext)
      ,'role'   =>""
      ,'dtid'   =>""
      ,'end'    =>""
      ,'sklid'  =>""
      ,'tmid'   =>""
      ,'life'   =>""
      ,'rltid'  =>""
    );

    //役職の改行以降をカットする
    $role = $item_cast->find("td",4)->plaintext;
    $dtid = $item_cast->find("td",3)->plaintext;
    $users['role'] = preg_replace('/\r\n.+/','',$role);
    //--を支配人/見物人にする
    if($role === '--')
    {
      $users['dtid'] = $data::DES_ONLOOKER;
      $users['end'] = 1;
      $users['tmid'] = $data::TM_ONLOOKER;
      $users['life'] = 0;
      $users['rltid'] = $data::RSL_ONLOOKER;
      if($dtid === '--')
      {
        $users['sklid'] = $data::SKL_OWNER;
        switch($rp)
        {
          case 'GB':
            $users['role'] = '旧校舎の主';
            break;
          default:
            $users['role'] = '支配人';
            break;
        }
      }
      else
      {
        $users['sklid'] = $data::SKL_ONLOOKER;
        switch($rp)
        {
          case 'MELON':
            $users['role'] = 'やじうま';
            break;
          case 'GB':
            $users['role'] = '観客';
            break;
          case 'MOON':
            $users['role'] = 'お客様';
            break;
          default:
            $users['role'] = '見物人';
            break;
        }
      }
    }
    else
    {
      //特殊言い換え役職が存在しない言い換えセットは物語準拠
      if(array_search($rp,$RP_DEFAULT) !== false)
      {
        $rp = 'SOW';
      }
      //婚約者は元の役職扱いにする
      if(mb_strstr($users['role'],${'SKL_'.$rp}[25]))
      {
        $sklid = preg_replace('/^.+\((.+)\)/','\1',$users['role']);
        $users['tmid'] = $data::TM_LOVERS;
      }
      else
      {
        $sklid = preg_replace('/\(.+/','',$users['role']);
      }
      //能力が登録済かチェック
      $skl_key = array_search($sklid,${'SKL_'.$rp});
      if($skl_key !== false)
      {
        $users['sklid'] = $SKILL[$skl_key][0];
        if($users['tmid'] !== $data::TM_LOVERS)
        {
          $users['tmid'] = $SKILL[$skl_key][1];
        }
      }
      else if(mb_strstr($sklid,${'SKL_'.$rp}[6]))
      {
        //聖痕者
        $users['sklid'] = $SKILL[6][0];
        $users['tmid'] = $SKILL[6][1];
      }
      else
      {
        //未定義の役職
        echo 'NOTICE: '.$village['vno'].'.'.$users['persona'].' has undefined role.';
        $users['sklid'] = $SKILL[0][0];
        $users['tmid'] = $SKILL[0][1];
      }
      //結末
      if($dtid === '生存')
      {
        $users['dtid'] = $data::DES_ALIVE;
        $users['end'] = $village['days'];
        $users['life'] = 1;
      }
      else
      {
        $users['dtid'] = $DESTINY[mb_substr($dtid,mb_strpos($dtid,'d')+1)];
        $users['end'] = (int)mb_substr($dtid,0,mb_strpos($dtid,'d'));
        $users['life'] = round(($users['end']-1) / $village['days'],2);
      }
      //勝敗
      if(!$policy)
      {
        $users['rltid'] = $data::RSL_JOIN;
      }
      else
      {
        $users['rltid'] = $RSL[$item_cast->find("td",2)->plaintext];
      }
    }

    var_dump($users);
  }

  //var_dump($village);

  $fetch->clear();
  //echo $village['vno']. ' is end.'.PHP_EOL;
}
unset($fetch);
