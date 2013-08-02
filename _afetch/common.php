<?
/*--------------------------------------------------------------------------------

  0. 定数・変数

--------------------------------------------------------------------------------*/

//結末 destiny
define('DES_ALIVE',1);    //生存
define('DES_RETIRED',2);  //突然死
define('DES_HANGED',3);   //処刑死
define('DES_EATEN',4);    //襲撃死
define('DES_CURSED',5);   //呪殺死

//陣営 team
define('TM_VILLAGER',1);  //村人陣営
define('TM_WOLF',2);      //人狼陣営
define('TM_FAIRY',3);     //妖魔陣営
define('TM_LOVERS',4);    //恋人陣営

//能力 skill
define('SKL_VILLAGER',1); //村人
define('SKL_SEER',2);     //占い師
define('SKL_MEDIUM',3);   //霊能者
define('SKL_HUNTER',4);   //狩人
define('SKL_MASON',5);    //共有者
define('SKL_LUNATIC',6);  //狂人
define('SKL_WOLF',7);     //人狼
define('SKL_LUNAWHS',8);  //C狂人
define('SKL_FAIRY',9);    //妖魔

//編成 regulation
define('RGL_C',1);        //C編成
define('RGL_F',2);        //F編成
define('RGL_G',3);        //G編成
define('RGL_S_2',4);      //少人数狼2
define('RGL_S_3',5);      //少人数狼3
define('RGL_E',6);        //妖魔入り
define('RGL_S_C2',7);      //少人数C狂狼2
define('RGL_S_C3',8);      //少人数C狂狼3
define('RGL_S_NC',9);      //少人数C狂狼3

//結果 result
define('RSL_WIN',1);      //勝利
define('RSL_LOSE',2);     //敗北
define('RSL_JOIN',3);     //参加(非ガチ村)
define('RSL_INVALID',4);  //無効(新議事の突然死など)
define('RSL_ONLOOKER',5); //見物


/*--------------------------------------------------------------------------------

  1. 関数

--------------------------------------------------------------------------------*/
