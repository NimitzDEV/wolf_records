# result.phpの構造
## header
### サマリ
>この辺全部戦績テーブルを取得した後の配列から算出する？  
ddで定義したい

####ID
* player名→入力されたものをエスケープして表示
* →クエリにしない

#### 総合参加数

|種別|数|
|--
|勝利|3|
|敗北|2|
|参加|5|
|ガチ生存|0.33|
|RP生存|0.63|
|(総合)|10|

	select r.name,count(*)
	  from users as u inner join result as r on r.id=u.rltid
	  where player='som' group by rltid
	union
	select case rltid when 1 then 'ガチ'
	 when 2 then 'ガチ'
	 when 3 then 'RP'
	 else 'no'
	end as rlt
	,truncate(avg(life)+0.005,2)
	  from users
	  where player='som' group by rlt
	union
	select rltid,count(*)
	  from users
	  where player='som'

* 総合参加数 playerの数
	* array_pop($array)
* ガチ参加数 player合致から勝敗があるもののみ
	* $array['勝利']+$array['敗北']
* RP参加数 player合致からresultが参加のみ
	* $array['参加']
* 勝率
    * player合致からresult=勝利をカウントして、ガチ参加数で割る
    * $array['勝利'] / ガチ参加数
* 平均生存係数
    * ガチ player+勝敗あり合致の生存係数の平均
    	* $array['ガチ生存']
    * RP player+result=参加合致の生存係数の平均
    	* $array['RP生存']

 
### メニュー
* ページ内リンク
* ツイートボタン
* 別ページリンク

## body
### 広告
>レスポンシブでサイズを変えたい  
ジャンルを絞りたい

### 戦績一覧
>見出し文字を太文字にする  
現在結果をコードで取得しているのを、"勝利"など表参照した文字列にする

* 日付、国番号(国名+村番号)、村名、編成、キャラクタ、役職、結末、結果

### 役職別

|team|skl|result|count(*)|sum|
|----
|村人|占い師|勝利|3|2|
|村人|占い師|敗北|1|1|

	SELECT t.name team,s.name skl,r.name result,count(*) count,sum.sum
      FROM users u
	INNER JOIN skill s ON u.sklid=s.id
	INNER JOIN team t ON u.tmid=t.id
	INNER JOIN result r ON u.rltid=r.id
        INNER JOIN (
          SELECT tmid,rltid,count(*) sum FROM users WHERE player='luxx' GROUP BY tmid,rltid
        ) sum ON u.tmid=sum.tmid AND u.rltid=sum.rltid
      WHERE u.player='luxx'
      GROUP BY u.rltid,s.name
      ORDER BY u.tmid,u.sklid,u.rltid



#### 陣営別
* 行った事のない陣営テーブルは表示させない
* ガチ勝利数/ガチ参加数(ガチ勝率)
	* 陣営ごとに算出する
* RP回数
	* 陣営ごとに算出する

#### 役職別
* ガチ勝利数/ガチ参加数(ガチ勝率)
	* 役職ごとに算出する
* RP回数
	* 役職ごとに算出する


## footer
>ページ共通　→　スタイルもcommonに入れる  
Githubリンクを付ける  
li形式にする  
右に寄せる


# .htmlページの構造
## header
### タイトル
* h1
* 黒背景に文字色白
* テクスチャをつけてもよい