<?php

require_once('../../lib/simple_html_dom.php');
require_once('./data.php');
require_once('./check_village.php');
require_once('./insert_db.php');

mb_internal_encoding("UTF-8");

$COUNTRYS = array(13,15,16,17);

$data  = new Data();

//特殊ルール
$RGL_SP = array(
   'ミラーズホロウ'=>$data::RGL_MILL
  ,'死んだら負け(ミラーズホロウ)'=>$data::RGL_MILL
  ,'死んだら負け'=>$data::RGL_DEATH
  ,'Trouble☆Aliens'=>$data::RGL_TA
  ,'深い霧の夜'=>$data::RGL_MIST
);
//自由設定でも特殊レギュにしない編成
$RGL_FREE = array(
   '村人x3 占い師x1 狂人x1 人狼x1'=>$data::RGL_S_1
  ,'村人x2 占い師x1 守護者x1 霊能者x1 狂人x1 人狼x2'=>$data::RGL_S_2
  ,'村人x4 占い師x1 霊能者x1 狂人x1 人狼x2'=>$data::RGL_S_2
  ,'村人x4 占い師x1 守護者x1 霊能者x1 狂人x1 人狼x2'=>$data::RGL_S_2
  ,'村人x6 占い師x1 守護者x1 霊能者x1 狂人x1 人狼x2'=>$data::RGL_S_2
  ,'村人x6 占い師x1 守護者x1 霊能者x1 狂人x1 人狼x3'=>$data::RGL_S_3
  ,'村人x7 占い師x1 守護者x1 霊能者x1 狂人x1 人狼x3'=>$data::RGL_S_3
  ,'村人x4 占い師x1 霊能者x1 囁き狂人x1 人狼x2'=>$data::RGL_S_C2
  ,'村人x5 占い師x1 霊能者x1 囁き狂人x1 人狼x2'=>$data::RGL_S_C2
  ,'村人x5 占い師x1 守護者x1 霊能者x1 囁き狂人x1 人狼x2'=>$data::RGL_S_C2
  ,'村人x6 占い師x1 守護者x1 霊能者x1 囁き狂人x1 人狼x2'=>$data::RGL_S_C2
  ,'村人x5 占い師x1 霊能者x1 人狼x2 栗鼠妖精x1'=>$data::RGL_S_E
  ,'村人x1 霊能者x5 人狼x2'=>$data::RGL_ROLLER
);

//勝利アナウンス
//Orbital☆Starでは村人→乗客なので最初の二文字を削る
$WTM_NORMAL = array(
   "の人物が消え失せた時、其処"=>$data::TM_NONE
  ,"の人狼を退治した……。人狼"=>$data::TM_VILLAGER
  ,"達は自らの過ちに気付いた。"=>$data::TM_WOLF
  ,"の人狼を退治した……。だが"=>$data::TM_FAIRY
  ,"時、人狼は勝利を確信し、そ"=>$data::TM_FAIRY
  ,"も、人狼も、妖精でさえも、"=>$data::TM_LOVERS
  ,"達は、そして人狼達も自らの"=>$data::TM_LWOLF
  ,"達は気付いてしまった。もう"=>$data::TM_PIPER
  ,"はたった独りだけを選んだ。"=>$data::TM_EFB
 );
 $WTM_ZAP = array(
   "の人物が消え失せ、守り育む"=>$data::TM_NONE
  ,"可の組織は全滅した……。「"=>$data::TM_VILLAGER
  ,"達は自らの過ちに気付いた。"=>$data::TM_WOLF
  ,"の結社員を退治した……。"=>$data::TM_FAIRY
  ,"時、「人狼」は勝利を確信し"=>$data::TM_FAIRY
  ,"も、「人狼」も、ミュータン"=>$data::TM_LOVERS
  ,"達は、そして「人狼」も自ら"=>$data::TM_LWOLF
  ,"達は気付いてしまった。もう"=>$data::TM_PIPER
  ,"はたった独りだけを選んだ。"=>$data::TM_EFB
 );

//陣営リスト
$TEAM = array(
   "村人"=>$data::TM_VILLAGER
  ,"人狼"=>$data::TM_WOLF
  ,"妖精"=>$data::TM_FAIRY
  ,"恋人"=>$data::TM_LOVERS
  ,"一匹"=>$data::TM_LWOLF
  ,"笛吹"=>$data::TM_PIPER
  ,"邪気"=>$data::TM_EFB
  ,"裏切"=>$data::TM_EVIL
  ,"据え"=>$data::TM_FISH
  ,"勝利"=>$data::TM_NONE
);
//裏切りの陣営としてカウントするレギュレーション
$TM_EVIL = array($data::RGL_E,$data::RGL_S_E,$data::RGL_EFB,$data::RGL_ETC);

//勝敗
$RSL = array(
   "勝利"=>$data::RSL_WIN
  ,"敗北"=>$data::RSL_LOSE
  ,""=>$data::RSL_INVALID //無効(突然死)
);

//役職
$SKILL = array(
   "村人"=>$data::SKL_VILLAGER
  ,"聖痕者"=>$data::SKL_STIGMA
  ,"結社員"=>$data::SKL_MASON
  ,"共鳴者"=>$data::SKL_TELEPATH
  ,"占い師"=>$data::SKL_SEER
  ,"信仰占師"=>$data::SKL_SEERWIN
  ,"気占師"=>$data::SKL_SEERAURA
  ,"賢者"=>$data::SKL_SAGE
  ,"守護者"=>$data::SKL_HUNTER
  ,"霊能者"=>$data::SKL_MEDIUM
  ,"信仰霊能者"=>$data::SKL_MEDIWIN
  ,"導師"=>$data::SKL_PRIEST
  ,"降霊者"=>$data::SKL_NECRO
  ,"追従者"=>$data::SKL_FOLLOWER
  ,"煽動者"=>$data::SKL_AGITATOR
  ,"賞金稼"=>$data::SKL_BOUNTY
  ,"人犬"=>$data::SKL_WEREDOG
  ,"王子様"=>$data::SKL_PRINCE
  ,"狼血族"=>$data::SKL_LINEAGE
  ,"医師"=>$data::SKL_DOCTOR
  ,"呪人"=>$data::SKL_CURSED
  ,"預言者"=>$data::SKL_PROPHET
  ,"病人"=>$data::SKL_SICK
  ,"錬金術師"=>$data::SKL_ALCHEMIST
  ,"魔女"=>$data::SKL_WITCH
  ,"少女"=>$data::SKL_GIRL
  ,"生贄"=>$data::SKL_SG
  ,"長老"=>$data::SKL_ELDER
  ,"邪魔之民"=>$data::SKL_JAMMER
  ,"宿借之民"=>$data::SKL_SNATCH
  ,"念波之民"=>$data::SKL_LUNAPATH
  ,"狂人"=>$data::SKL_LUNATIC
  ,"狂信者"=>$data::SKL_FANATIC
  ,"人形使い"=>$data::SKL_MUPPETER
  ,"囁き狂人"=>$data::SKL_LUNAWHS
  ,"半狼"=>$data::SKL_HALFWOLF
  ,"魔神官"=>$data::SKL_LUNAPRI
  ,"魔術師"=>$data::SKL_LUNASAGE
  ,"首無騎士"=>$data::SKL_HEADLESS
  ,"人狼"=>$data::SKL_WOLF
  ,"智狼"=>$data::SKL_WISEWOLF
  ,"呪狼"=>$data::SKL_CURSEWOLF
  ,"白狼"=>$data::SKL_WHITEWOLF
  ,"仔狼"=>$data::SKL_CHILDWOLF
  ,"衰狼"=>$data::SKL_DYINGWOLF
  ,"黙狼"=>$data::SKL_SILENT
  ,"栗鼠妖精"=>$data::SKL_FAIRY
  ,"擬狼妖精"=>$data::SKL_MIMIC
  ,"風花妖精"=>$data::SKL_SNOW
  ,"悪戯妖精"=>$data::SKL_PIXY
  ,"邪気悪魔"=>$data::SKL_EFB
  ,"恋愛天使"=>$data::SKL_QP
  ,"片想い"=>$data::SKL_PASSION
  ,"弟子"=>$data::SKL_PUPIL
  ,"盗賊"=>$data::SKL_THIEF
  ,"一匹狼"=>$data::SKL_LWOLF
  ,"笛吹き"=>$data::SKL_PIPER
  ,"鱗魚人"=>$data::SKL_FISH
  ,"遊び人"=>$data::SKL_PLAYBOY
);

//結末
$DESTINY = array(
   "生存者"=>$data::DES_ALIVE
  ,"突然死"=>$data::DES_RETIRED
  ,"処刑死"=>$data::DES_HANGED
  ,"襲撃死"=>$data::DES_EATEN
  ,"呪詛死"=>$data::DES_CURSED
  ,"衰退死"=>$data::DES_DROOP
  ,"後追死"=>$data::DES_SUICIDE
  ,"恐怖死"=>$data::DES_FEARED
);

foreach($COUNTRYS as $cid)
{
  switch($cid)
  {
    case 13:
      $country = 'morphe';
      $url_vil = 'http://morphe.sakura.ne.jp/morphe/sow.cgi?vid=';
      $url_log = 'http://morphe.sakura.ne.jp/morphe/sow.cgi?cmd=oldlog';
      break;
    case 15:
      $country = 'perjury';
      $url_vil = 'http://perjury.rulez.jp/sow.cgi?vid=';
      $url_log = 'http://perjury.rulez.jp/sow.cgi?cmd=oldlog';
      break;
    case 16:
      $country = 'xebec';
      $url_vil = 'http://xebec.x0.to/xebec/sow.cgi?vid=';
      $url_log = 'http://xebec.x0.to/xebec/sow.cgi?cmd=oldlog';
      break;
    case 17:
      $country = 'crazy';
      $url_vil = 'http://crazy-crazy.sakura.ne.jp/crazy/sow.cgi?vid=';
      $url_log = 'http://crazy-crazy.sakura.ne.jp/crazy/sow.cgi?cmd=oldlog';
      break;
  }
  $db    = new Insert_DB($cid);
  $check = new Check_Village($country,$cid,$url_vil,$url_log);
  $check->check_queue();
  $check->check_fetch_vno();
  if($check->get_village())
  {
    $fetched_v = $check->get_village();
  }
  else
  {
    echo $country.' not fetch.'.PHP_EOL;
    continue;
  }

  $fetch = new simple_html_dom();
  foreach($fetched_v as $item_vil)
  {
    //初期化
    $village = array(
               'cid'  =>$cid
              ,'vno'  =>$item_vil
              ,'name' =>""
              ,'date' =>""
              ,'nop'  =>""
              ,'rglid'=>""
              ,'days' =>""
              ,'wtmid'=>""
    );

    $url = $url_vil.$item_vil."&cmd=vinfo";
    //情報欄取得
    $fetch->load_file($url);

    //村名取得
    $village['name'] = $fetch->find('p.multicolumn_left',0)->plaintext;

    //人数取得
    $nop = $fetch->find('p.multicolumn_left',5)->plaintext;
    $village['nop'] = (int)mb_substr($nop,0,mb_strpos($nop,'人'));

    //日数取得
    $days = trim($fetch->find('p.turnnavi',0)->find('a',-4)->innertext);
    $days = mb_convert_encoding($days,"UTF-8","auto");
    $village['days'] = mb_substr($days,0,mb_strpos($days,'日')) +1;

    //レギュレーション挿入
    $rule= trim($fetch->find('dl.mes_text_report dt',1)->plaintext);
    $rglid = trim($fetch->find('dl.mes_text_report dt',2)->plaintext);
    $rglid = mb_substr($rglid,mb_strpos($rglid,"：")+1);

    if(array_key_exists($rule,$RGL_SP))
    {
      //特殊ルールがあるならレギュレーション扱いで挿入する
      $village['rglid'] = $RGL_SP[$rule];
      echo "#".$village['vno'].' is '.$rule.".Should check evil team.##";
    }
    else if(preg_match("/秘話/",$village['name']))
    {
      //秘話村を挿入
      echo 'NOTICE: '.$village['vno'].' may be 秘話村.';
      $village['rglid'] = $data::RGL_SECRET;
    }
    else if($rglid === '自由設定')
    {
      //自由設定でも特定の編成はレギュレーションを指定する
      $free = trim($fetch->find('dl.mes_text_report dd',3)->plaintext);
      if(array_key_exists($free,$RGL_FREE))
      {
        $village['rglid'] = $RGL_FREE[$free];
      }
      else
      {
        echo "#".$village['vno'].' has '.$free."#";
        $village['rglid'] = $data::RGL_ETC;
      }
    }
    else
    {
      switch($rglid)
      {
        case "標準":
          if($village['nop'] <= 7)
          {
            $village['rglid'] = $data::RGL_S_1;
          }
          else
          {
            $village['rglid'] = $data::RGL_LEO;
          }
          break;
        case "深い霧の夜":
          $village['rglid'] = $data::RGL_MIST;
          break;
        case "人狼審問 試験壱型":
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
        case "人狼審問 試験弐型":
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
        case "人狼BBS C国":
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
        case "人狼BBS F国":
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
        case "人狼BBS G国":
          switch(true)
          {
            case ($village['nop']  >= 16):
              //16人編成はF編成になっている
              $village['rglid'] = $data::RGL_F;
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
      }
    }

    //狂人は裏切りの陣営かどうか
    //編成が深い霧の夜なら人数によって可変
    if(in_array($village['rglid'],$TM_EVIL) || ($rglid === "深い霧の夜" && ($village['nop'] <8 || $village['nop'] >18)))
    {
      $is_evil = true;
    }
    else
    {
      $is_evil = false;
    }
    
    //言い換え
    $rp= trim($fetch->find('dl.mes_text_report dt',0)->plaintext);
    //morpheは村の方針を取得しておく
    if($country == 'morphe')
    {
      $policy = $fetch->find('p.multicolumn_left',1)->plaintext;
    }

    //初日取得
    $fetch->clear();
    $url = preg_replace("/cmd=vinfo/","turn=0&row=10&mode=all&move=page&pageno=1",$url);
    $fetch->load_file($url);

    //開始日(プロローグ第一声)
    $date = $fetch->find('p.mes_date',0)->plaintext;
    $date = mb_substr($date,mb_strpos($date,"2"),10);
    //MySQL用に日付の区切りを/から-に変換
    $village['date'] = preg_replace('/(\d{4})\/(\d{2})\/(\d{2})/','\1-\2-\3',$date);

    //エピローグ取得
    $fetch->clear();
    $url = preg_replace("/0&row=10/",$village['days']."&row=30",$url);
    $fetch->load_file($url);

    //morpheは村の方針でガチ/RPを分岐
    if($country == 'morphe')
    {
      switch($policy)
      {
        case "とくになし":
        case "[言] 殺伐、暴言あり":
        case "[遖] あっぱれネタ風味":
        case "[張] うっかりハリセン":
        case "[全] 大人も子供も初心者も、みんな安心":
        case "[危] 無茶ぶり上等":
          //勝利陣営
          $wtmid = trim($fetch->find('p.info',-1)->plaintext);
          if(preg_match("/村の更新日が延長されました/",$wtmid))
          {
            $do_i = -2;
            do
            {
              $wtmid = trim($fetch->find('p.info',$do_i)->plaintext);
              $do_i--;
            } while(preg_match("/村の更新日が延長されました/",$wtmid));
          }
          $wtmid = mb_substr(preg_replace("/\r\n/","",$wtmid),2,13);
          switch($rp)
          {
            case "ParanoiA":
              $village['wtmid'] = $WTM_ZAP[$wtmid];
              break;
            default:
              $village['wtmid'] = $WTM_NORMAL[$wtmid];
              break;
          }
          echo $village['vno'].'.'.$village['name'].' is guessed GACHI.->';
          break;
        default:
          $village['wtmid'] = $data::TM_RP;
          echo $village['vno'].'.'.$village['name'].' is guessed RP.->';
          break;
      }
    }
    else
    {
      $village['wtmid'] = $data::TM_RP;
    }

    $cast = $fetch->find('tbody tr.i_active');
    $list_users = array();
    //キャスト表を配列にする
    foreach($cast as $item_cast)
    {
      $users = array(
                'persona'=>trim($item_cast->find("td",0)->plaintext)
                ,'player' =>trim($item_cast->find("td",1)->plaintext)
                ,'role'   =>""
                ,'dtid'   =>""
                ,'end'    =>""
                ,'sklid'  =>""
                ,'tmid'   =>""
                ,'life'   =>""
                ,'rltid'  =>""
      );

      //結末、陣営、役職
      $result = $item_cast->find("td",3)->plaintext;
      $result = mb_substr($result,0,mb_strpos($result,"\n")-1);
      $result = explode(' ',$result);

      //陣営と役職を取得
      $users['role'] = mb_substr($result[2],mb_strpos($result[2],'：')+1);
      if(mb_substr($users['role'],-2) === "居た")
      {
        //見物人設定
        $users['role'] = '見物人';
        $users['dtid'] = $data::DES_ONLOOKER;
        $users['end'] = 1;
        $users['sklid'] = $data::SKL_ONLOOKER;
        $users['tmid'] = $data::TM_ONLOOKER;
        $users['life'] = 0;
        $users['rltid'] = $data::RSL_ONLOOKER;
      }
      else
      {
        //日数
        if($result[0] === '生存者')
        {
          $users['end'] = $village['days'];
        }
        else
        {
          $users['end'] = (int)preg_replace("/(.+)日/","$1",$item_cast->find("td",2)->plaintext);
        }

        //非ガチ村は勝敗をつけずに「参加」にする
        if($village['wtmid'] === 0)
        {
          $users['rltid'] = $data::RSL_JOIN;
        }
        else
        {
          $users['rltid'] = $RSL[$result[1]];
        }

        //役職欄に絆などついている場合
        if(mb_strpos($users['role'],"、") === false)
        {
          $sklid = $users['role'];
        }
        else
        {
          $sklid = mb_substr($users['role'],0,mb_strpos($users['role'],"、"));
        }

        //能力、結末、陣営を挿入
        $users['sklid'] = $SKILL[$sklid];
        $users['dtid'] = $DESTINY[$result[0]];
        $users['tmid'] = $TEAM[mb_substr($result[2],0,2)];

        //第三陣営がいない村では裏切りの陣営を人狼陣営扱いにする
        if($users['tmid'] === $data::TM_EVIL && $is_evil === false)
        {
          $users['tmid'] = $data::TM_WOLF;
        }

        //生存係数挿入
        if($users['dtid'] === $data::DES_ALIVE)
        {
          $users['life'] = 1.00;
        }
        else
        {
          $users['life'] = round(($users['end']-1) / $village['days'],2);
        }
      }
      $list_users[] = $users;

      $item_cast->clear();
      unset($item_cast);
    }
    $fetch->clear();
    //村を書き込む
    $db->connect();
    if($db->insert_db($village,$list_users))
    {
      echo $country.$village['vno']. ' is all inserted.'.PHP_EOL;
      $check->remove_queue($village['vno']);
    }
    $db->disconnect();
  }
  unset($fetch);
}

