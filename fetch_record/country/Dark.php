<?php
class Dark extends Country
{
  use AR_SOW,TR_SOW,TR_SOW_RGL;
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
  protected $SKL_ONE = [//OLD, SHIP共用
     "村人"=>[Data::SKL_VILLAGER,Data::TM_VILLAGER]
    ,"普通の人"=>[Data::SKL_VILLAGER,Data::TM_VILLAGER]
    ,"邪教徒"=>[Data::SKL_WOLF,Data::TM_WOLF]
    ,"深きもの"=>[Data::SKL_WOLF,Data::TM_WOLF]
    ,"占い師"=>[Data::SKL_SEER,Data::TM_VILLAGER]
    ,"霊能者"=>[Data::SKL_MEDIUM,Data::TM_VILLAGER]
    ,"狂人"=>[Data::SKL_LUNATIC,Data::TM_WOLF]
    ,"守護者"=>[Data::SKL_HUNTER,Data::TM_VILLAGER]
    ,"報告者"=>[Data::SKL_MASON,Data::TM_VILLAGER]
    ,"古きもの"=>[Data::SKL_FAIRY,Data::TM_FAIRY]
    ,"ショゴス"=>[Data::SKL_FAIRY,Data::TM_FAIRY]
    ,"入信者"=>[Data::SKL_LUNAWHS,Data::TM_WOLF]
    ,"協力者"=>[Data::SKL_STIGMA,Data::TM_VILLAGER]
    ,"狂信者"=>[Data::SKL_FANATIC,Data::TM_WOLF]
    ,"感応者"=>[Data::SKL_TELEPATH,Data::TM_VILLAGER]
    ,"古きもの・古代種"=>[Data::SKL_BAT,Data::TM_FAIRY]
    ,"ショゴスロード"=>[Data::SKL_BAT,Data::TM_FAIRY]
    ,"醜き眷族"=>[Data::SKL_CURSEWOLF,Data::TM_WOLF]
    ,"ダゴン"=>[Data::SKL_CURSEWOLF,Data::TM_WOLF]
    ,"忌まわしき眷族"=>[Data::SKL_WISEWOLF,Data::TM_WOLF]
    ,"年ふりた深きもの"=>[Data::SKL_WISEWOLF,Data::TM_WOLF]
    ,"古きもの・戦闘種"=>[Data::SKL_PIXY,Data::TM_FAIRY]
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
  protected $DESTINY = [
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
  }
  protected function fetch_from_info()
  {
    $this->fetch->load_file($this->url.$this->village->vno."&cmd=vinfo");

    $this->fetch_name();
    $this->fetch_nop();
    $this->fetch_rglid();
    $this->fetch_days();

    $this->fetch_policy();
    $this->fetch->clear();
  }
  protected function fetch_policy()
  {
    $this->village->policy = true;
  }
  protected function fetch_from_pro()
  {
    $url = $this->url.$this->village->vno.'&turn=0&row=10&mode=all&move=page&pageno=1';
    $this->fetch->load_file($url);

    $this->fetch_date();
    $this->fetch_rp();
    $this->fetch->clear();
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
  protected function fetch_sklid()
  {
    switch($this->village->rp)
    {
      case 'FOOL':
        $this->user->sklid = $this->SKL_FOOL[$this->user->role][0];
        $this->user->tmid = $this->SKL_FOOL[$this->user->role][1];
        break;
      case 'OLD':
      case 'SHIP':
        $this->user->sklid = $this->SKL_ONE[$this->user->role][0];
        $this->user->tmid = $this->SKL_ONE[$this->user->role][1];
        break;
      default:
        $this->user->sklid = $this->SKILL[$this->user->role][0];
        $this->user->tmid = $this->SKILL[$this->user->role][1];
        break;
    }
    //呪狼の名前をメモ
    if($this->user->sklid === Data::SKL_CURSEWOLF)
    {
      $this->cursewolf[] = $this->user->persona;
    }
  }
  protected function fetch_from_daily($list)
  {
    $days = $this->village->days;
    $rp = $this->village->rp;
    $find = 'p.info';
    for($i=2; $i<=$days; $i++)
    {
      $announce = $this->fetch_daily_url($i,$find);
      foreach($announce as $item)
      {
        $destiny = trim(preg_replace("/\r\n/",'',$item->plaintext));
        $key= mb_substr(trim($item->plaintext),-6,6);
        if($rp === 'FOOL')
        {
          if(!isset($this->DT_FOOL[$key]))
          {
            continue;
          }
          else if($key === "ったみたい。")
          {
            echo "NOTICE: day".$i."occured EATEN but cannot find who it is.".PHP_EOL;
            continue;
          }
          else
          {
            $persona = trim(mb_ereg_replace($this->DT_FOOL[$key][0],'\2',$destiny,'m'));
            $key_u = array_search($persona,$list);
            $dtid = $this->DT_FOOL[$key][1];
          }
        }
        else if($rp === 'SHIP')
        {
          if(!isset($this->DT_SHIP[$key]))
          {
            continue;
          }
          else
          {
            $persona = trim(mb_ereg_replace($this->DT_SHIP[$key][0],'\2',$destiny,'m'));
            $key_u = array_search($persona,$list);
            $dtid = $this->DT_SHIP[$key][1];
          }
        }
        else if(!isset($this->DESTINY[$key]))
        {
          continue;
        }
        else
        {
          $persona = trim(mb_ereg_replace($this->DESTINY[$key][0],'\2',$destiny,'m'));
          $key_u = array_search($persona,$list);
          $dtid = $this->DESTINY[$key][1];
        }
        //妖魔陣営の無残死は呪殺死にする
        if($this->users[$key_u]->tmid === Data::TM_FAIRY && $dtid === Data::DES_EATEN)
        {
          $this->users[$key_u]->dtid = Data::DES_CURSED;
        }
        //呪狼が存在する編成で、占い師が襲撃された場合別途チェック
        else if(!empty($this->cursewolf) && $this->users[$key_u]->sklid === Data::SKL_SEER && $dtid === Data::DES_EATEN && $this->check_cursed_seer($persona,$key_u))
        {
          $this->users[$key_u]->dtid = Data::DES_CURSED;
        }
        else
        {
          $this->users[$key_u]->dtid = $dtid;
        }
        $this->users[$key_u]->end = $i;
        $this->users[$key_u]->life = round(($i-1) / $this->village->days,3);
      }
      $this->fetch->clear();
    }
  }
}
