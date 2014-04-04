<?php

class Data
{
  //陣営 team
  const TM_RP        =  0; //勝敗なし
  const TM_VILLAGER  =  1; //村人陣営
  const TM_WOLF      =  2; //人狼陣営
  const TM_FAIRY     =  3; //妖魔陣営
  const TM_LOVERS    =  4; //恋人陣営
  const TM_LWOLF     =  6; //一匹狼陣営
  const TM_PIPER     =  7; //笛吹き陣営
  const TM_EFB       =  8; //邪気陣営
  const TM_EVIL      =  9; //裏切りの陣営
  const TM_FISH      = 10; //据え膳
  const TM_TERU      = 13; //照坊主
  const TM_NONE      = 11; //陣営なし
  const TM_ONLOOKER  = 12; //見物人  
  const TM_SLAVE     = 14; //奴隷陣営
  const TM_VAMPIRE   = 15; //吸血鬼陣営
    

  //編成 regulation
  const RGL_C    =  1;      //C編成
  const RGL_F    =  2;      //F編成
  const RGL_G    =  3;      //G編成
  const RGL_E    =  6;      //妖魔入り
  const RGL_S_1  = 28;      //少人数狼1
  const RGL_S_2  =  4;      //少人数狼2
  const RGL_S_3  =  5;      //少人数狼3
  const RGL_S_C2 =  7;      //少人数狼2C
  const RGL_S_C3 =  8;      //少人数狼3C
  const RGL_S_E  = 26;      //少人数妖魔入り
  const RGL_S_L0 =  9;      //少人数狂人なし
  const RGL_W2   = 10;      //狼2共有あり
  const RGL_C_ST = 25;      //聖痕入りC
  const RGL_G_ST = 17;      //聖痕入りG
  const RGL_LEO  = 11;      //決定者入り
  const RGL_TES1 = 12;      //試験壱
  const RGL_TES2 = 13;      //試験弐
  const RGL_TES3 = 31;      //試験参
  const RGL_LOVE = 15;      //恋人入り
  const RGL_LPLY = 16;      //遊び人入り
  const RGL_ROLLER=21;      //素村が霊能者
  const RGL_HERO = 22;      //占い師なし
  const RGL_EFB  = 23;      //邪気悪魔入り
  const RGL_CURSE= 33;      //呪悪

  const RGL_SECRET= 27;     //秘話村
  const RGL_MILL = 18;      //ミラーズホロウ
  const RGL_DEATH= 19;      //死んだら負け
  const RGL_LOSE = 32;      //負けたら勝ち
  const RGL_TA   = 20;      //Trouble☆Aliens
  const RGL_MIST = 14;      //深い霧の夜
  const RGL_PLOT = 29;      //陰謀に集う胡蝶
  const RGL_ETC  = 24;      //特殊


  //結果 result
  const RSL_WIN      = 1;
  const RSL_LOSE     = 2;
  const RSL_JOIN     = 3;   //参加(非ガチ村)
  const RSL_INVALID  = 4;   //無効(一部の国での突然死)
  const RSL_ONLOOKER = 5;   //見物


  //結末 destiny
  const DES_ALIVE   = 1;  //生存
  const DES_RETIRED = 2;  //突然死
  const DES_HANGED  = 3;  //処刑死
  const DES_EATEN   = 4;  //襲撃死
  const DES_CURSED  = 5;  //呪詛死
  const DES_DROOP   = 7;  //衰退死
  const DES_SUICIDE = 8;  //後追死
  const DES_FEARED  = 9;  //恐怖死 
  const DES_MARTYR  = 11; //殉教
  const DES_ONLOOKER= 10; //見物
  

  //能力 skill
  const SKL_VILLAGER =  1;  //村人
  const SKL_SEER     =  2;  //占い師
  const SKL_MEDIUM   =  3;  //霊能者
  const SKL_HUNTER   =  4;  //狩人
  const SKL_MASON    =  5;  //共有者
  const SKL_STIGMA   = 11;  //聖痕者
  const SKL_TELEPATH = 12;  //共鳴者
  const SKL_SAGE     = 15;  //賢者
  const SKL_SEERWIN  = 13;  //信仰占師
  const SKL_SEERAURA = 14;  //気占師
  const SKL_PRIEST   = 17;  //導師
  const SKL_MEDIWIN  = 16;  //信仰霊能者
  const SKL_NECRO    = 18;  //降霊者
  const SKL_LINEAGE  = 24;  //狼血族
  const SKL_CURSED   = 26;  //呪人
  const SKL_FOLLOWER = 19;  //追従者
  const SKL_AGITATOR = 20;  //扇動者
  const SKL_SG       = 32;  //生贄
  const SKL_PRINCE   = 23;  //王子様
  const SKL_BOUNTY   = 21;  //賞金稼
  const SKL_ALCHEMIST= 29;  //錬金術師
  const SKL_WITCH    = 30;  //魔女
  const SKL_GIRL     = 31;  //少女
  const SKL_WEREDOG  = 22;  //人犬
  const SKL_SICK     = 28;  //病人
  const SKL_ELDER    = 33;  //長老
  const SKL_PROPHET  = 27;  //預言者
  const SKL_DOCTOR   = 25;  //医師
  const SKL_SEERONCE = 66;  //夢占師
  const SKL_NOTARY   = 68;  //公証人
  const SKL_DARKH    = 69;  //闇狩人
  const SKL_GUARDIAN = 54;  //守護獣
  const SKL_BAPTIST  = 78;  //洗礼者
  const SKL_SNIPER   = 79;  //狙撃手
  const SKL_JUDGE    = 84;  //審判者
  const SKL_IDSEER   = 85;  //魂魄師 
  const SKL_CONTACT  = 87;  //交信者
  const SKL_NOBLE    = 90;  //貴族
  const SKL_FUGITIVE = 92;  //容疑者
  const SKL_LYCAN    = 93;  //獣化病
  const SKL_ONMYO    = 94;  //陰陽師
  const SKL_ASSASSIN = 95;  //暗殺者
  const SKL_SEERUNSKL= 96;  //見習い占い師
  const SKL_KABUKI   = 97;  //傾奇者

  const SKL_LUNATIC  =  6;  //狂人
  const SKL_LUNAWHS  =  8;  //C狂
  const SKL_FANATIC  = 36;  //狂信者
  const SKL_LUNAMIM  = 63;  //狂鳴者
  const SKL_LUNAPATH = 35;  //叫迷狂人
  const SKL_JAMMER   = 34;  //邪魔之民
  const SKL_MUPPETER = 37;  //人形使い
  const SKL_HALFWOLF = 38;  //半狼
  const SKL_LUNASAGE = 40;  //魔術師
  const SKL_LUNAPRI  = 39;  //魔神官
  const SKL_SNATCH   = 60;  //宿借之民
  const SKL_LUNASIL  = 62;  //聞き耳狂人
  const SKL_SEAL     = 71;  //封印狂人
  const SKL_STRSEER  = 72;  //辻占狂人
  const SKL_LUNASEER = 55;  //狂神官
  const SKL_BLASPHEME= 81;  //冒涜者
  const SKL_BETRAYER = 82;  //背信者
  const SKL_IDLUNASR = 86;  //呪魂者 
  const SKL_TEMPTER  = 88;  //誘惑者
  const SKL_SLEEPER  = 89;  //睡狼
  const SKL_DECOY    = 98;  //囮人形
  const SKL_SUSPECT  = 99;  //悟られ狂人

  const SKL_WOLF     =  7;  //人狼
  const SKL_HEADLESS = 41;  //首無騎士
  const SKL_WISEWOLF = 42;  //智狼
  const SKL_CURSEWOLF= 43;  //呪狼
  const SKL_WHITEWOLF= 44;  //白狼
  const SKL_CHILDWOLF= 45;  //仔狼
  const SKL_DYINGWOLF= 46;  //衰狼
  const SKL_SILENT   = 47;  //黙狼
  const SKL_POSWOLF  = 70;  //憑狼
  const SKL_MALICE   = 80;  //瘴狼
  const SKL_MEDIWOLF = 100; //賢狼
  const SKL_NECROWOLF= 101; //霊狼

  const SKL_FAIRY    =  9;  //妖魔
  const SKL_BAT      = 61;  //蝙蝠人間
  const SKL_PIXY     = 50;  //悪戯妖精
  const SKL_MIMIC    = 48;  //擬狼妖精
  const SKL_SNOW     = 49;  //風花妖精
  const SKL_JAMFAIRY = 64;  //邪魔妖精
  const SKL_SNAFAIRY = 65;  //宿借妖精
  const SKL_VAMPIRE  = 74;  //吸血鬼
  const SKL_RABBIT   = 75;  //夜兎
  const SKL_NIGHTMARE= 83;  //夢魔
  const SKL_NINETALES= 102; //九尾
  const SKL_CURSEFOX = 103; //呪狐
  const SKL_HALFFOX  = 104; //半妖
  const SKL_OLDFOX   = 105; //仙狐

  const SKL_QP       = 52;  //恋愛天使
  const SKL_PASSION  = 53;  //片想い
  const SKL_PLAYBOY  = 59;  //遊び人
  const SKL_LOVER    = 67;  //求婚者

  const SKL_EFB      = 51;  //邪気悪魔
  const SKL_LWOLF    = 56;  //一匹狼
  const SKL_PIPER    = 57;  //笛吹き
  const SKL_FISH     = 58;  //鱗魚人
  const SKL_TERU     = 76;  //照坊主
  const SKL_SLAVE    = 91;  //奴隷
  const SKL_SUCKER   = 73;  //血人
  const SKL_ONLOOKER = 10;  //見物人
  const SKL_OWNER    = 77;  //支配人
  const SKL_VAMPSEA  = 106; //吸血鬼
  const SKL_SERVANT  = 107; //眷属
}
