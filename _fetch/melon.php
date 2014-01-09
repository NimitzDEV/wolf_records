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
  $url = preg_replace("/0&r=10/",$village['days']."&row=30",$url);
  $fetch->load_file($url);

  //勝利陣営
  $wtmid = trim($fetch->find('p.info',2)->plaintext);
  $test = mb_substr($wtmid,0,10);

  //var_dump($village);
  //var_dump($wtmid);
  echo $rp.'->'.$test.'->'.$wtmid.PHP_EOL;

  $fetch->clear();
  //echo $village['vno']. ' is end.'.PHP_EOL;
}
unset($fetch);
