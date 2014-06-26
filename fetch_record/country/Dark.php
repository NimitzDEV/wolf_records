<?php
class Dark extends SOW
{
  use TRS_SOW;
  private $rgl_name;
  protected $RP_PRO = [
     '平和なはず'=>'DARK'
    ,'　君はある'=>'OLD'
    ,'何家族かが'=>'WHITE'
    ,'――その旅'=>'SHIP'
    ,'人があまり'=>'IMMORTAL'
    ,'この村にも'=>'SOW'
    ,'なんか人狼'=>'FOOL'
    ,'　村は数十'=>'JUNA'
    ,'昼間は人間'=>'WBBS'
    ];
  protected $WTM_DARK= [
     'だ…さし当たっては。'=>Data::TM_VILLAGER
    ,'た…さし当たっては。'=>Data::TM_WOLF
    ,'めて気付かされた…。'=>Data::TM_FAIRY
    ,'ないままであった…。'=>Data::TM_FAIRY
  ];
  protected $WTM_OLD= [
     'の悪夢と戦いながら。'=>Data::TM_VILLAGER
    ,'帰還を遂げるだろう。'=>Data::TM_WOLF
    ,'かへと去っていった。'=>Data::TM_FAIRY
  ];
  protected $WTM_WHITE= [
     'っ子に勝利したのだ！'=>Data::TM_VILLAGER
    ,'えて笑い続けた……。'=>Data::TM_WOLF
    ,'日々が始まった……。'=>Data::TM_FAIRY
    ,'にされてしまった……'=>Data::TM_FAIRY
  ];
  protected $WTM_SHIP= [
     '帰還を果たしたのだ！'=>Data::TM_VILLAGER
    ,'在が姿を現すだろう。'=>Data::TM_WOLF
    ,'な漁船を呑み込んだ。'=>Data::TM_FAIRY
  ];
  protected $WTM_IMMORTAL= [
     '黒がいたってことだ。'=>Data::TM_VILLAGER
    ,'なったわけではない。'=>Data::TM_WOLF
    ,'めて気付かされた…。'=>Data::TM_FAIRY
    ,'と退散して行った…。'=>Data::TM_FAIRY
  ];
  protected $SKL_SP = [
     "町民"=>[Data::SKL_VILLAGER,Data::TM_VILLAGER]
    ,"憑かれた人"=>[Data::SKL_LINEAGE,Data::TM_VILLAGER]
    ,"悟られ狂人"=>[Data::SKL_SUSPECT,Data::TM_WOLF]
    ];
  protected $SKL_OLD = [
     "村人"=>[Data::SKL_VILLAGER,Data::TM_VILLAGER]
    ,"邪教徒"=>[Data::SKL_WOLF,Data::TM_WOLF]
    ,"占い師"=>[Data::SKL_SEER,Data::TM_VILLAGER]
    ,"霊能者"=>[Data::SKL_MEDIUM,Data::TM_VILLAGER]
    ,"狂人"=>[Data::SKL_LUNATIC,Data::TM_WOLF]
    ,"守護者"=>[Data::SKL_HUNTER,Data::TM_VILLAGER]
    ,"報告者"=>[Data::SKL_MASON,Data::TM_VILLAGER]
    ,"古きもの"=>[Data::SKL_FAIRY,Data::TM_FAIRY]
    ,"入信者"=>[Data::SKL_LUNAWHS,Data::TM_WOLF]
    ,"協力者"=>[Data::SKL_STIGMA,Data::TM_VILLAGER]
    ,"狂信者"=>[Data::SKL_FANATIC,Data::TM_WOLF]
    ,"感応者"=>[Data::SKL_TELEPATH,Data::TM_VILLAGER]
    ,"古きもの・古代種"=>[Data::SKL_BAT,Data::TM_FAIRY]
    ,"醜き眷族"=>[Data::SKL_CURSEWOLF,Data::TM_WOLF]
    ,"忌まわしき眷族"=>[Data::SKL_WISEWOLF,Data::TM_WOLF]
    ,"古きもの・戦闘種"=>[Data::SKL_PIXY,Data::TM_FAIRY]
    ,"憑かれた人"=>[Data::SKL_LINEAGE,Data::TM_VILLAGER]
    ,"悟られ狂人"=>[Data::SKL_SUSPECT,Data::TM_WOLF]
  ];
  protected $SKL_SHIP = [
     "普通の人"=>[Data::SKL_VILLAGER,Data::TM_VILLAGER]
    ,"深きもの"=>[Data::SKL_WOLF,Data::TM_WOLF]
    ,"占い師"=>[Data::SKL_SEER,Data::TM_VILLAGER]
    ,"霊能者"=>[Data::SKL_MEDIUM,Data::TM_VILLAGER]
    ,"狂人"=>[Data::SKL_LUNATIC,Data::TM_WOLF]
    ,"守護者"=>[Data::SKL_HUNTER,Data::TM_VILLAGER]
    ,"報告者"=>[Data::SKL_MASON,Data::TM_VILLAGER]
    ,"ショゴス"=>[Data::SKL_FAIRY,Data::TM_FAIRY]
    ,"入信者"=>[Data::SKL_LUNAWHS,Data::TM_WOLF]
    ,"協力者"=>[Data::SKL_STIGMA,Data::TM_VILLAGER]
    ,"狂信者"=>[Data::SKL_FANATIC,Data::TM_WOLF]
    ,"感応者"=>[Data::SKL_TELEPATH,Data::TM_VILLAGER]
    ,"ショゴスロード"=>[Data::SKL_BAT,Data::TM_FAIRY]
    ,"ダゴン"=>[Data::SKL_CURSEWOLF,Data::TM_WOLF]
    ,"年ふりた深きもの"=>[Data::SKL_WISEWOLF,Data::TM_WOLF]
    ,"幼生ショゴス"=>[Data::SKL_PIXY,Data::TM_FAIRY]
    ,"憑かれた人"=>[Data::SKL_LINEAGE,Data::TM_VILLAGER]
    ,"悟られ狂人"=>[Data::SKL_SUSPECT,Data::TM_WOLF]
    ];
  protected $SKL_WHITE = [
     "良い子"=>[Data::SKL_VILLAGER,Data::TM_VILLAGER]
    ,"悪戯っ子"=>[Data::SKL_WOLF,Data::TM_WOLF]
    ,"弱虫"=>[Data::SKL_SEER,Data::TM_VILLAGER]
    ,"学級委員"=>[Data::SKL_MEDIUM,Data::TM_VILLAGER]
    ,"ミーハー"=>[Data::SKL_LUNATIC,Data::TM_WOLF]
    ,"カミナリさん"=>[Data::SKL_HUNTER,Data::TM_VILLAGER]
    ,"担任"=>[Data::SKL_MASON,Data::TM_VILLAGER]
    ,"裏番"=>[Data::SKL_FAIRY,Data::TM_FAIRY]
    ,"面白がり"=>[Data::SKL_LUNAWHS,Data::TM_WOLF]
    ,"すご担"=>[Data::SKL_STIGMA,Data::TM_VILLAGER]
    ];
  protected $DT_SP = [
     '処刑された。'=>['.+(\(ランダム投票\)|投票した。)(.+) は村人達の手により処刑された。',Data::DES_HANGED]
    ,'刑された……'=>['.+(\(ランダム投票\)|投票した) ?(.+) は(村人|町の住人|村の住人)の手により処刑された……',Data::DES_HANGED]
    ,'突然死した。'=>['^( ?)(.+) は、突然死した。',Data::DES_RETIRED]
    ,'発見された。'=>['(.+)朝、 ?(.+) が無残.+',Data::DES_EATEN]
    ,'後を追った。'=>['^( ?)(.+) は(絆に引きずられるように|哀しみに暮れて) .+ の後を追った。',Data::DES_SUICIDE]
  ];
  protected $DT_SHIP = [
     '刑された……'=>['.+(\(ランダム投票\)|投票した) ?(.+) は人々の手により処刑された……',Data::DES_HANGED]
    ,'突然死した。'=>['^( ?)(.+) は、突然死した。',Data::DES_RETIRED]
    ,'発見された。'=>['(.+)朝、 ?(.+) が名状.+',Data::DES_EATEN]
    ,'後を追った。'=>['^( ?)(.+) は(絆に引きずられるように|哀しみに暮れて) .+ の後を追った。',Data::DES_SUICIDE]
  ];
  function __construct()
  {
    $cid = 51;
    $url_vil = "http://o8o8.o0o0.jp/wolf/sow.cgi?vid=";
    $url_log = "http://o8o8.o0o0.jp/wolf/sow.cgi?cmd=oldlog";
    parent::__construct($cid,$url_vil,$url_log);
    $this->SKILL = array_merge($this->SKILL,$this->SKL_SP);
    $this->DT_NORMAL = array_merge($this->DT_NORMAL,$this->DT_SP);
  }
  protected function fetch_from_info()
  {
    $this->rgl_name = '';
    $this->fetch->load_file($this->url.$this->village->vno."&cmd=vinfo");

    $this->fetch_name();
    $this->fetch_nop();
    $this->fetch_days();
    $this->fetch_rgl_name();
    $this->fetch_policy();

    $this->fetch->clear();
  }
  protected function fetch_from_pro()
  {
    $url = $this->url.$this->village->vno.'&turn=0&row=10&mode=all&move=page&pageno=1';
    $this->fetch->load_file($url);

    $this->fetch_date();
    $this->fetch_rp();
    $this->fetch_rglid();

    $this->fetch->clear();
  }
  protected function fetch_rgl_name()
  {
    $this->rgl_name = trim($this->fetch->find('p.multicolumn_right',1)->plaintext);
  }
  protected function fetch_rglid()
  {
    $patterns = ['/.+\r\n （(.+)）/','/([^ ]+): (\d+)人 /'];
    $replaces = ['\1','\1x\2 '];
    $rglid = trim(preg_replace($patterns,$replaces,$this->rgl_name));
    $rglid = mb_ereg_replace('町民','村人',$rglid);
    $this->find_rglid($rglid);
  }
  protected function fetch_rp()
  {
    $rp = mb_substr($this->fetch->find('p.info',0)->plaintext,1,5);
    if(array_key_exists($rp,$this->RP_PRO))
    {
      $this->village->rp = $this->RP_PRO[$rp];
    }
    else
    {
      echo 'NOTICE: '.$this->village->vno.' has undefined RP.'.PHP_EOL;
      $this->village->rp = 'DARK';
    }
  }

  protected function fetch_users($person)
  {
    $this->fetch_persona($person);
    $this->fetch_player($person);
    $this->fetch_role($person);
    $this->fetch_sklid();
    $this->fetch_rltid();

    if($person->find('td',2)->plaintext === '生存')
    {
      $this->insert_alive();
    }
  }
  protected function fetch_persona($person)
  {
    $persona = trim($person->find('td',0)->plaintext);
    if(preg_match('/\(勝利\)$/',$persona))
    {
      $this->user->persona = mb_ereg_replace('\(勝利\)','',$persona);
    }
    else
    {
      $this->user->persona = $persona;
    }
  }
}
