<?php

trait Rgl
{
  protected $rgl_rpb = 
  [
     '/守護者/'
    ,'/結社員/'
    ,'/Ｃ国狂人/'
    ,'/ハムスター人間/'
    ,'/栗鼠妖精/'
    ,'/レオナルド/'
    ,'/恋天使/'
    ,'/キューピッド/'
  ];
  protected $rgl_rpa =
  [
     '狩人'
    ,'共有者'
    ,'囁き狂人'
    ,'妖魔'
    ,'妖魔'
    ,'決定者'
    ,'恋愛天使'
    ,'恋愛天使'
  ];
  protected $rgl_maxlen = 46;
  protected $rgl =
  [
    //G4-20
     '人狼x1,占い師x1,村人x2'=>Data::RGL_S_1
    ,'人狼x1,占い師x1,村人x3'=>Data::RGL_S_1
    ,'人狼x1,占い師x1,村人x4'=>Data::RGL_S_1
    ,'人狼x1,占い師x1,村人x5'=>Data::RGL_S_1
    ,'人狼x2,占い師x1,村人x5'=>Data::RGL_S_2
    ,'人狼x2,占い師x1,村人x5,霊能者x1'=>Data::RGL_S_2
    ,'人狼x2,占い師x1,村人x6,霊能者x1'=>Data::RGL_S_2
    ,'人狼x2,占い師x1,村人x5,狂人x1,狩人x1,霊能者x1'=>Data::RGL_S_2
    ,'人狼x2,占い師x1,村人x6,狂人x1,狩人x1,霊能者x1'=>Data::RGL_S_2
    ,'人狼x3,占い師x1,村人x6,狂人x1,狩人x1,霊能者x1'=>Data::RGL_S_3
    ,'人狼x3,占い師x1,村人x7,狂人x1,狩人x1,霊能者x1'=>Data::RGL_S_3
    ,'人狼x3,占い師x1,村人x8,狂人x1,狩人x1,霊能者x1'=>Data::RGL_S_3
    ,'人狼x3,占い師x1,村人x9,狂人x1,狩人x1,霊能者x1'=>Data::RGL_G
    ,'人狼x3,占い師x1,村人x10,狂人x1,狩人x1,霊能者x1'=>Data::RGL_G
    ,'人狼x3,占い師x1,村人x11,狂人x1,狩人x1,霊能者x1'=>Data::RGL_G
    ,'人狼x3,占い師x1,村人x12,狂人x1,狩人x1,霊能者x1'=>Data::RGL_G
    ,'人狼x3,占い師x1,村人x13,狂人x1,狩人x1,霊能者x1'=>Data::RGL_G
    //F10,13,14,16-20
    ,'人狼x2,占い師x1,村人x5,狂人x1,霊能者x1'=>Data::RGL_S_2
    ,'人狼x2,占い師x1,村人x7,狂人x1,狩人x1,霊能者x1'=>Data::RGL_S_2
    ,'人狼x2,占い師x1,村人x8,狂人x1,狩人x1,霊能者x1'=>Data::RGL_S_2
    ,'人狼x3,共有者x2,占い師x1,村人x7,狂人x1,狩人x1,霊能者x1'=>Data::RGL_F
    ,'人狼x3,共有者x2,占い師x1,村人x8,狂人x1,狩人x1,霊能者x1'=>Data::RGL_F
    ,'人狼x3,共有者x2,占い師x1,村人x9,狂人x1,狩人x1,霊能者x1'=>Data::RGL_F
    ,'人狼x3,共有者x2,占い師x1,村人x10,狂人x1,狩人x1,霊能者x1'=>Data::RGL_F
    ,'人狼x3,共有者x2,占い師x1,村人x11,狂人x1,狩人x1,霊能者x1'=>Data::RGL_F
    //C10-20
    ,'人狼x2,占い師x1,囁き狂人x1,村人x5,霊能者x1'=>Data::RGL_S_C2
    ,'人狼x2,占い師x1,囁き狂人x1,村人x5,狩人x1,霊能者x1'=>Data::RGL_S_C2
    ,'人狼x2,占い師x1,囁き狂人x1,村人x6,狩人x1,霊能者x1'=>Data::RGL_S_C2
    ,'人狼x2,占い師x1,囁き狂人x1,村人x7,狩人x1,霊能者x1'=>Data::RGL_S_C3
    ,'人狼x2,占い師x1,囁き狂人x1,村人x8,狩人x1,霊能者x1'=>Data::RGL_S_C3
    ,'人狼x3,占い師x1,囁き狂人x1,村人x8,狩人x1,霊能者x1'=>Data::RGL_S_C3
    ,'人狼x3,共有者x2,占い師x1,囁き狂人x1,村人x7,狩人x1,霊能者x1'=>Data::RGL_C
    ,'人狼x3,共有者x2,占い師x1,囁き狂人x1,村人x8,狩人x1,霊能者x1'=>Data::RGL_C
    ,'人狼x3,共有者x2,占い師x1,囁き狂人x1,村人x9,狩人x1,霊能者x1'=>Data::RGL_C
    ,'人狼x3,共有者x2,占い師x1,囁き狂人x1,村人x10,狩人x1,霊能者x1'=>Data::RGL_C
    ,'人狼x3,共有者x2,占い師x1,囁き狂人x1,村人x11,狩人x1,霊能者x1'=>Data::RGL_C
    //共有無しC16-17
    ,'人狼x3,占い師x1,囁き狂人x1,村人x9,狩人x1,霊能者x1'=>Data::RGL_C
    ,'人狼x3,占い師x1,囁き狂人x1,村人x10,狩人x1,霊能者x1'=>Data::RGL_C
    //試験壱13-20
    ,'人狼x2,占い師x1,村人x5,狂人x2,狩人x1,聖痕者x1,霊能者x1'=>Data::RGL_TES1
    ,'人狼x2,占い師x1,村人x6,狂人x2,狩人x1,聖痕者x1,霊能者x1'=>Data::RGL_TES1
    ,'人狼x3,占い師x1,村人x7,狂人x1,狩人x1,聖痕者x1,霊能者x1'=>Data::RGL_TES1
    ,'人狼x3,占い師x1,村人x7,狂人x1,狩人x1,聖痕者x2,霊能者x1'=>Data::RGL_TES1
    ,'人狼x3,占い師x1,村人x8,狂人x1,狩人x1,聖痕者x2,霊能者x1'=>Data::RGL_TES1
    ,'人狼x3,占い師x1,村人x9,狂人x1,狩人x1,聖痕者x2,霊能者x1'=>Data::RGL_TES1
    ,'人狼x3,共有者x2,占い師x1,村人x8,狂人x2,狩人x1,聖痕者x1,霊能者x1'=>Data::RGL_TES1
    ,'人狼x3,共有者x2,占い師x1,村人x9,狂人x2,狩人x1,聖痕者x1,霊能者x1'=>Data::RGL_TES1
    //試験弐10-20
    ,'人狼x2,占い師x1,村人x5,狂信者x1,霊能者x1'=>Data::RGL_TES2
    ,'人狼x2,占い師x1,村人x5,狂信者x1,狩人x1,霊能者x1'=>Data::RGL_TES2
    ,'人狼x2,占い師x1,村人x6,狂信者x1,狩人x1,霊能者x1'=>Data::RGL_TES2
    ,'人狼x2,占い師x1,村人x7,狂信者x1,狩人x1,霊能者x1'=>Data::RGL_TES2
    ,'人狼x2,占い師x1,村人x8,狂信者x1,狩人x1,霊能者x1'=>Data::RGL_TES2
    ,'人狼x3,占い師x1,村人x8,狂信者x1,狩人x1,霊能者x1'=>Data::RGL_TES2
    ,'人狼x3,共有者x2,占い師x1,村人x7,狂信者x1,狩人x1,霊能者x1'=>Data::RGL_TES2
    ,'人狼x3,共有者x2,占い師x1,村人x8,狂信者x1,狩人x1,霊能者x1'=>Data::RGL_TES2
    ,'人狼x3,共有者x2,占い師x1,村人x9,狂信者x1,狩人x1,霊能者x1'=>Data::RGL_TES2
    ,'人狼x3,共有者x2,占い師x1,村人x10,狂信者x1,狩人x1,霊能者x1'=>Data::RGL_TES2
    ,'人狼x3,共有者x2,占い師x1,村人x11,狂信者x1,狩人x1,霊能者x1'=>Data::RGL_TES2
    //決定者8-20
    ,'人狼x2,占い師x1,村人x4,決定者x1,狩人x1'=>Data::RGL_LEO
    ,'人狼x2,占い師x1,村人x5,決定者x1,狩人x1'=>Data::RGL_LEO
    ,'人狼x2,占い師x1,村人x4,決定者x1,狂人x1,狩人x1,霊能者x1'=>Data::RGL_LEO
    ,'人狼x2,占い師x1,村人x5,決定者x1,狂人x1,狩人x1,霊能者x1'=>Data::RGL_LEO
    ,'人狼x2,占い師x1,村人x6,決定者x1,狂信者x1,狩人x1,霊能者x1'=>Data::RGL_LEO
    ,'人狼x2,占い師x1,村人x5,決定者x1,狂人x2,狩人x1,聖痕者x1,霊能者x1'=>Data::RGL_LEO
    ,'人狼x2,占い師x1,村人x6,決定者x1,狂人x2,狩人x1,聖痕者x1,霊能者x1'=>Data::RGL_LEO
    ,'人狼x2,占い師x1,囁き狂人x1,村人x9,決定者x1,狩人x1,霊能者x1'=>Data::RGL_LEO
    ,'人狼x3,共有者x2,占い師x1,村人x7,決定者x1,狂人x1,狩人x1,霊能者x1'=>Data::RGL_LEO
    ,'人狼x3,共有者x2,占い師x1,村人x8,決定者x1,狂人x1,狩人x1,霊能者x1'=>Data::RGL_LEO
    ,'人狼x3,共有者x2,占い師x1,村人x9,決定者x1,狂人x1,狩人x1,霊能者x1'=>Data::RGL_LEO
    ,'人狼x3,共有者x2,占い師x1,村人x10,決定者x1,狂人x1,狩人x1,霊能者x1'=>Data::RGL_LEO
    ,'人狼x3,共有者x2,占い師x1,村人x11,決定者x1,狂人x1,狩人x1,霊能者x1'=>Data::RGL_LEO
    //聖痕入りCG
    ,'人狼x3,占い師x1,村人x8,狂人x1,狩人x1,聖痕者x1,霊能者x1'=>Data::RGL_G_ST
    ,'人狼x3,占い師x1,囁き狂人x1,村人x8,狩人x1,聖痕者x1,霊能者x1'=>Data::RGL_C_ST
    //共鳴者入りCF16-17
    ,'人狼x3,共鳴者x2,占い師x1,囁き狂人x1,村人x7,狩人x1,霊能者x1'=>Data::RGL_C
    ,'人狼x3,共鳴者x2,占い師x1,囁き狂人x1,村人x8,狩人x1,霊能者x1'=>Data::RGL_C
    ,'人狼x3,共鳴者x2,占い師x1,村人x7,狂人x1,狩人x1,霊能者x1'=>Data::RGL_F
    ,'人狼x3,共鳴者x2,占い師x1,村人x8,狂人x1,狩人x1,霊能者x1'=>Data::RGL_F
    //妖魔入り
    ,'人狼x3,占い師x1,妖魔x1,村人x8,狂人x1,狩人x1,霊能者x1'=>Data::RGL_E //G16
    ,'人狼x3,占い師x1,妖魔x1,村人x9,狂人x1,狩人x1,霊能者x1'=>Data::RGL_E //G17
    ,'人狼x3,共有者x2,占い師x1,囁き狂人x1,妖魔x1,村人x6,狩人x1,霊能者x1'=>Data::RGL_E //C16
    ,'人狼x3,共有者x2,占い師x1,囁き狂人x1,妖魔x1,村人x7,狩人x1,霊能者x1'=>Data::RGL_E //C17
    ,'人狼x3,共鳴者x2,占い師x1,囁き狂人x1,妖魔x1,村人x6,狩人x1,霊能者x1'=>Data::RGL_E //C16
    ,'人狼x3,共鳴者x2,占い師x1,囁き狂人x1,妖魔x1,村人x7,狩人x1,霊能者x1'=>Data::RGL_E //C17
    ,'人狼x3,共有者x2,占い師x1,妖魔x1,村人x6,狂人x1,狩人x1,霊能者x1'=>Data::RGL_E //F16
    ,'人狼x3,共有者x2,占い師x1,妖魔x1,村人x7,狂人x1,狩人x1,霊能者x1'=>Data::RGL_E //F17
    ,'人狼x3,共鳴者x2,占い師x1,妖魔x1,村人x6,狂人x1,狩人x1,霊能者x1'=>Data::RGL_E //F16
    ,'人狼x3,共鳴者x2,占い師x1,妖魔x1,村人x7,狂人x1,狩人x1,霊能者x1'=>Data::RGL_E //F17
    //少人数妖魔入り9-15
    ,'人狼x2,占い師x1,妖魔x1,村人x4,霊能者x1'=>Data::RGL_S_E //9
    ,'人狼x2,占い師x1,妖魔x1,村人x5,霊能者x1'=>Data::RGL_S_E //G10
    ,'人狼x2,占い師x1,妖魔x1,村人x4,狂人x1,霊能者x1'=>Data::RGL_S_E //F10
    ,'人狼x2,占い師x1,妖魔x1,村人x4,狂人x1,狩人x1,霊能者x1'=>Data::RGL_S_E //11
    ,'人狼x2,占い師x1,妖魔x1,村人x5,狂人x1,狩人x1,霊能者x1'=>Data::RGL_S_E //12
    ,'人狼x2,占い師x1,妖魔x1,村人x6,狂人x1,狩人x1,霊能者x1'=>Data::RGL_S_E //F13
    ,'人狼x2,占い師x1,妖魔x1,村人x7,狂人x1,狩人x1,霊能者x1'=>Data::RGL_S_E //F14
    ,'人狼x3,占い師x1,妖魔x1,村人x5,狂人x1,狩人x1,霊能者x1'=>Data::RGL_S_E //G13
    ,'人狼x3,占い師x1,妖魔x1,村人x6,狂人x1,狩人x1,霊能者x1'=>Data::RGL_S_E //G14
    ,'人狼x3,占い師x1,妖魔x1,村人x7,狂人x1,狩人x1,霊能者x1'=>Data::RGL_S_E //15
    //恋人入り
    ,'人狼x3,占い師x1,恋愛天使x1,村人x8,狂人x1,狩人x1,霊能者x1'=>Data::RGL_LOVE //G16
    ,'人狼x3,占い師x1,恋愛天使x1,村人x9,狂人x1,狩人x1,霊能者x1'=>Data::RGL_LOVE //G17
    ,'人狼x3,共有者x2,占い師x1,囁き狂人x1,恋愛天使x1,村人x6,狩人x1,霊能者x1'=>Data::RGL_LOVE //C16
    ,'人狼x3,共有者x2,占い師x1,囁き狂人x1,恋愛天使x1,村人x7,狩人x1,霊能者x1'=>Data::RGL_LOVE //C17
    ,'人狼x3,共鳴者x2,占い師x1,囁き狂人x1,恋愛天使x1,村人x6,狩人x1,霊能者x1'=>Data::RGL_LOVE //C16
    ,'人狼x3,共鳴者x2,占い師x1,囁き狂人x1,恋愛天使x1,村人x7,狩人x1,霊能者x1'=>Data::RGL_LOVE //C17
    ,'人狼x3,共有者x2,占い師x1,恋愛天使x1,村人x6,狂人x1,狩人x1,霊能者x1'=>Data::RGL_LOVE //F16
    ,'人狼x3,共有者x2,占い師x1,恋愛天使x1,村人x7,狂人x1,狩人x1,霊能者x1'=>Data::RGL_LOVE //F17
    ,'人狼x3,共鳴者x2,占い師x1,恋愛天使x1,村人x6,狂人x1,狩人x1,霊能者x1'=>Data::RGL_LOVE //F16
    ,'人狼x3,共鳴者x2,占い師x1,恋愛天使x1,村人x7,狂人x1,狩人x1,霊能者x1'=>Data::RGL_LOVE //F17
    ,'人狼x3,占い師x1,恋愛天使x1,村人x7,狂人x1,狩人x1,霊能者x1'=>Data::RGL_LOVE //15
    //霊ロラ
    ,'人狼x1,村人x4,狂信者x1'=>Data::RGL_HERO
    ,'人狼x1,村人x5,狂信者x1'=>Data::RGL_HERO
  ];

  protected function find_rglid($arg_rgl)
  {
    if($this->village->rp === 'FOOL')
    {
      $this->rgl_maxlen = $this->rgl_maxlen_fool;
    }
    //指定文字数以上はリストに無い
    if(mb_strlen($arg_rgl) > $this->rgl_maxlen)
    {
      $this->insert_etc($arg_rgl,'over maxlen');
      return;
    }

    $rgl = preg_replace($this->rgl_rpb,$this->rgl_rpa,$arg_rgl);
    $ary = explode(' ',$rgl);
    sort($ary);
    $rgl = implode(',',$ary);

    if($this->village->rp === 'FOOL')
    {
      $this->rgl = $this->rgl_fool;
    }
    if(array_key_exists($rgl,$this->rgl))
    {
      $this->village->rglid = $this->rgl[$rgl];
      echo $this->village->vno.' has '.$rgl.'=>'.$this->village->rglid.PHP_EOL;
    }
    else
    {
      $this->insert_etc($rgl,'N/A');
    }
  }

  protected function insert_etc($rgl,$message)
  {
    $this->village->rglid = Data::RGL_ETC;
    echo $this->village->vno.' has '.$rgl.'=>FREE =>'.$message.PHP_EOL;
    if(!empty($this->is_evil))
    {
      echo '　▼Should check evil team.'.PHP_EOL;
    }
  }
}
