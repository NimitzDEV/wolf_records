<?php

trait TR_SOW_RGL
{
  protected $RGL_FREE = [
     '（村人: 8人 人狼: 3人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 聖痕者: 1人 ）'=>Data::RGL_G_ST
    ,'（村人: 9人 人狼: 3人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 ）'=>Data::RGL_G
    ,'（村人: 7人 人狼: 3人 占い師: 1人 霊能者: 1人 狩人: 1人 囁き狂人: 1人 共鳴者: 2人 ）'=>Data::RGL_C
    ,'（村人: 7人 人狼: 3人 占い師: 1人 霊能者: 1人 狩人: 1人 Ｃ国狂人: 1人 共鳴者: 2人 ）'=>Data::RGL_C
    ,'（村人: 6人 人狼: 3人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 Ｃ国狂人: 1人 共鳴者: 2人 ）'=>Data::RGL_C
    ,'（村人: 7人 人狼: 3人 占い師: 1人 霊能者: 1人 狩人: 1人 共有者: 2人 Ｃ国狂人: 1人 ）'=>Data::RGL_C
    ,'（村人: 8人 人狼: 3人 占い師: 1人 霊能者: 1人 狩人: 1人 Ｃ国狂人: 1人 聖痕者: 1人 ）'=>Data::RGL_C_ST
    ,'（村人: 7人 人狼: 3人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 共有者: 2人 ）'=>Data::RGL_F
    ,'（村人: 8人 人狼: 3人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 共有者: 2人 ）'=>Data::RGL_F
    ,'（村人: 8人 人狼: 3人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 ハムスター人間: 1人 ）'=>Data::RGL_E
    ,'（村人: 7人 人狼: 3人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 共有者: 2人 ハムスター人間: 1人 ）'=>Data::RGL_E
    ,'（村人: 6人 人狼: 3人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 共有者: 2人 ハムスター人間: 1人 ）'=>Data::RGL_E
    ,'（村人: 6人 人狼: 3人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 ハムスター人間: 1人 共鳴者: 2人 ）'=>Data::RGL_E
    ,'（村人: 6人 人狼: 3人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 共有者: 2人 妖魔: 1人 ）'=>Data::RGL_E
    ,'（村人: 8人 人狼: 3人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 妖魔: 1人 ）'=>Data::RGL_E
    ,'（村人: 7人 人狼: 3人 占い師: 1人 霊能者: 1人 狩人: 1人 共有者: 2人 ハムスター人間: 1人 Ｃ国狂人: 1人 ）'=>Data::RGL_E
    ,'（村人: 6人 人狼: 3人 占い師: 1人 霊能者: 1人 狩人: 1人 共有者: 2人 ハムスター人間: 1人 Ｃ国狂人: 1人 ）'=>Data::RGL_E
    ,'（村人: 6人 人狼: 3人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 妖魔: 1人 共鳴者: 2人 ）'=>Data::RGL_E
    ,'（村人: 5人 人狼: 2人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 ハムスター人間: 1人 ）'=>Data::RGL_S_E
    ,'（村人: 6人 人狼: 2人 占い師: 1人 霊能者: 1人 狂人: 1人 守護者: 1人 妖魔: 1人 ）'=>Data::RGL_S_E
    ,'（村人: 7人 人狼: 2人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 ハムスター人間: 1人 ）'=>Data::RGL_S_E
    ,'（村人: 7人 人狼: 3人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 妖魔: 1人 ）'=>Data::RGL_S_E
    ,'（村人: 3人 人狼: 2人 占い師: 1人 霊能者: 1人 狩人: 1人 ハムスター人間: 1人 ）'=>Data::RGL_S_E
    ,'（村人: 4人 人狼: 2人 占い師: 1人 霊能者: 1人 守護者: 1人 妖魔: 1人 囁き狂人: 1人 ）'=>Data::RGL_S_E
    ,'（村人: 3人 人狼: 2人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 ハムスター人間: 1人 ）'=>Data::RGL_S_E
    ,'（村人: 6人 人狼: 2人 占い師: 1人 霊能者: 1人 ハムスター人間: 1人 ）'=>Data::RGL_S_E
    ,'（村人: 2人 人狼: 2人 占い師: 1人 狂人: 1人 狩人: 1人 ハムスター人間: 1人 ）'=>Data::RGL_S_E
    ,'（村人: 8人 人狼: 3人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 ）'=>Data::RGL_S_3
    ,'（村人: 7人 人狼: 3人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 ）'=>Data::RGL_S_3
    ,'（村人: 6人 人狼: 3人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 ）'=>Data::RGL_S_3
    ,'（村人: 8人 人狼: 2人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 ）'=>Data::RGL_S_2
    ,'（村人: 7人 人狼: 2人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 ）'=>Data::RGL_S_2
    ,'（村人: 6人 人狼: 2人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 ）'=>Data::RGL_S_2
    ,'（村人: 5人 人狼: 2人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 ）'=>Data::RGL_S_2
    ,'（村人: 2人 人狼: 2人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 ）'=>Data::RGL_S_2
    ,'（村人: 4人 人狼: 2人 占い師: 1人 霊能者: 1人 ）'=>Data::RGL_S_2
    ,'（村人: 3人 人狼: 2人 占い師: 1人 霊能者: 1人 狂人: 1人 ）'=>Data::RGL_S_2
    ,'（村人: 4人 人狼: 2人 占い師: 1人 狂人: 1人 狩人: 1人 ）'=>Data::RGL_S_2
    ,'（村人: 5人 人狼: 2人 占い師: 1人 霊能者: 1人 狂人: 1人 守護者: 1人 ）'=>Data::RGL_S_2
    ,'（村人: 7人 人狼: 2人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 Ｃ国狂人: 1人 ）'=>Data::RGL_S_C2
    ,'（村人: 6人 人狼: 2人 占い師: 1人 霊能者: 1人 狩人: 1人 囁き狂人: 1人 ）'=>Data::RGL_S_C2
    ,'（村人: 6人 人狼: 2人 占い師: 1人 霊能者: 1人 狩人: 1人 Ｃ国狂人: 1人 ）'=>Data::RGL_S_C2
    ,'（村人: 5人 人狼: 2人 占い師: 1人 霊能者: 1人 狩人: 1人 Ｃ国狂人: 1人 ）'=>Data::RGL_S_C2
    ,'（村人: 5人 人狼: 2人 占い師: 1人 霊能者: 1人 狩人: 1人 囁き狂人: 1人 ）'=>Data::RGL_S_C2
    ,'（村人: 5人 人狼: 2人 占い師: 1人 霊能者: 1人 守護者: 1人 囁き狂人: 1人 ）'=>Data::RGL_S_C2
    ,'（村人: 5人 人狼: 2人 占い師: 1人 霊能者: 1人 Ｃ国狂人: 1人 ）'=>Data::RGL_S_C2
    ,'（村人: 4人 人狼: 2人 占い師: 1人 霊能者: 1人 狩人: 1人 Ｃ国狂人: 1人 ）'=>Data::RGL_S_C2
    ,'（村人: 5人 人狼: 2人 占い師: 1人 霊能者: 1人 狩人: 1人 狂信者: 1人 ）'=>Data::RGL_TES2
    ,'（村人: 6人 人狼: 2人 占い師: 1人 霊能者: 1人 狩人: 1人 狂信者: 1人 ）'=>Data::RGL_TES2
    ,'（村人: 8人 人狼: 3人 占い師: 1人 霊能者: 1人 狩人: 1人 狂信者: 1人 ）'=>Data::RGL_TES2
    ,'（村人: 7人 人狼: 3人 占い師: 1人 霊能者: 1人 狩人: 1人 共有者: 2人 狂信者: 1人 ）'=>Data::RGL_TES2
    ,'（村人: 5人 人狼: 2人 占い師: 1人 霊能者: 1人 守護者: 1人 狂信者: 1人 ）'=>Data::RGL_TES2
    ,'（村人: 10人 人狼: 3人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 共有者: 2人 狂信者: 1人 ）'=>Data::RGL_TES2
    ,'（村人: 6人 人狼: 3人 占い師: 1人 霊能者: 1人 狩人: 1人 囁き狂人: 1人 共鳴者: 2人 恋天使: 1人 ）'=>Data::RGL_LOVE
    ,'（村人: 8人 人狼: 3人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 恋天使: 1人 ）'=>Data::RGL_LOVE
    ,'（村人: 6人 人狼: 3人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 2人 Ｃ国狂人: 1人 キューピッド: 1人 ）'=>Data::RGL_LOVE
    ,'（村人: 6人 人狼: 3人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 共有者: 2人 キューピッド: 1人 ）'=>Data::RGL_LOVE
    ,'（村人: 6人 人狼: 3人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 今週のトイレ当番: 2人 キューピッド: 1人 ）'=>Data::RGL_LOVE
    ,'（村人: 7人 人狼: 2人 占い師: 1人 霊能者: 1人 キューピッド: 1人 ）'=>Data::RGL_LOVE
    ,'（村人: 6人 人狼: 3人 占い師: 1人 霊能者: 1人 狂人: 1人 狩人: 1人 今週のトイレ当番: 1人 キューピッド: 1人 ）'=>Data::RGL_LOVE
    ,'（村人: 4人 人狼: 1人 狂人: 1人 ）'=>Data::RGL_HERO
    //del
  ];
}
