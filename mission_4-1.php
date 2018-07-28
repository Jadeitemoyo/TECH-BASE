<!--
ミッション4-1

ミッション2で作成した、削除・編集機能を持ち、投稿ごとにパスワードロックのある掲示板を
テキストファイル保存ではなく、MySQLのテーブルに連携させよう。
作成したものは、チームメンバーに実際に使ってみてもらうこと。OKならGitHubへコードをアップロードしておこう
※GitHubアップロード時には、サーバーアカウント情報は隠すこと。方法は下記参照。
-->

<?php

$dsn='データベース名';
$user='ユーザー名';
$password='パスワード';
$pdo=new PDO($dsn,$user,$password); //PDOオブジェクトを作成（操作の準備）

/*
//一旦テーブル削除
$sql = "drop table bbs";
$res = $pdo->query($sql);


//投稿番号：id（整数)
//名前：name（32文字の文字列）
//コメント：comment（可変長文字列）
//日付：date（datetime型）
//パスワード：pass(32文字の文字列)
$sql = "CREATE TABLE bbs("
	. "id INT primary key,"
	. "name char(32),"
	. "comment TEXT,"
	. "date datetime,"
	. "pass char(32)"
	.");";
$res = $pdo->query($sql);
*/

/*
//テーブル表示　作成自体はできている
$sql = "show tables";
$res = $pdo->query($sql);
foreach($res as $row){
	echo $row[0] . "<br>";
}

//カラム表示
$sql = "show columns from bbs";
$res = $pdo->query($sql);
foreach($res as $row){
	echo $row[0] . " ";
}
*/


//プリペアドステートメントの準備
$stmt = $pdo->prepare(
	'INSERT INTO bbs (id, name, comment, date, pass)'.
	'	VALUES (:id, :name, :comment, :date, :pass)');

$stmt->bindParam(':id', $id);
$stmt->bindParam(':name', $name);
$stmt->bindParam(':comment', $comment);
$stmt->bindParam(':date', $date);
$stmt->bindParam(':pass', $pass);

/*
$id = 1;
$name = 'taro';
$comment = 'hello';
$date = date("Y/m/d H:i:s");
$pass = 'pass';
$stmt->execute();
*/

?>


<?php
/* 編集モード移行機能 */
//編集対象番号とパスワードを受け取る
//（$id_eが空でなければ）対応するレコードの真のパスワードを参照
//    パスワードが一致したら$name_eと$comment_eに対応レコードの値を入れる
//    違ったら$id_eを空にして戻す
$id_e = $_POST["edit"];
$pass_e = $_POST["passedit"];
if(!empty($id_e)){
	$sql = "select name, comment, pass from bbs where id = $id_e";
	$res = $pdo->query($sql);
	foreach($res as $row){
		$truepass = $row['pass'];
		$name_t = $row['name'];
		$comment_t = $row['comment'];
		echo $truepass;
	}
	if($pass_e == $truepass){
		$name_e = $name_t;
		$comment_e = $comment_t;
	}
	else{
		$id_e = "";
	}
}
else{
}

?>

<!--
フォーム
-->

<head>
<meta charset = "UTF-8">
<title> ミッション4-1 </title>
</head>

<body background="pattern_bbs.jpg" style="margin-top:100px; margin-left:60px;">
<font color="#0B0B3B"><h2>簡易掲示板</h2></font>
<font color="#0B0B61">
<div style="display:inline-block; padding: 12px; margin-bottom: 30px; background:#fcfcff; border: 1px dashed #3b5998; border-radius: 5px;">
<h3>機能</h3>
<ol>
<b><li>コメント投稿機能</b>：お好きな名前でコメントを投稿してみよう。</li>
<b><li>パスワード機能</b>：自分だけのパスワードを設定しよう。</li>
<b><li>編集/削除機能</b>：投稿番号とパスワードの組み合わせを入力するとコメントを編集・削除できる。</li>
</ol>
</div>

</br>
<div style="display:inline-block; padding: 12px; margin-bottom: 30px; background:#fcfcff; border: 1px dashed #3b5998; border-radius: 5px;">
<form action="mission_4-1.php" method="post">
  <label for="name">名前</label></br>
  <input type="text" name="name" value= "<?php if(!empty($name_e)) echo $name_e; else echo '名無しさん';?>"> </br>
  <label for="comment">コメント</label></br>
  <input type="text" name="comment" placeholder="コメントを入力" size="30" value= "<?php if(!empty($comment_e)) echo $comment_e; ?>"></br>
  <label for="password">パスワード</label></br>
  <input type="text" name="password" placeholder="Password"></br>
  <!-- <label for="comment">編集用</label></br> -->
  <input type="hidden" name="editflag" value= <?php if(!empty($id_e)) echo $id_e; ?>></br>
  <input type="submit" value="送信">
</form>
</div>

<div style="display:inline-block; padding: 12px; margin-bottom: 30px; background:#fcfcff; border: 1px dashed #3b5998; border-radius: 5px;">
<form action="mission_4-1.php" method="post">
  <label for="edit">編集する</label></br>
  <input type="text" name="edit" placeholder="編集対象番号">
  <input type="text" name="passedit" placeholder="Password">
  <input type="submit" value="編集"></br>
  <label for="delete">削除する</label></br>
  <input type="text" name="delete" placeholder="削除対象番号">
  <input type="text" name="passdelete" placeholder="Password">
  <input type="submit" value="削除">
</form>
</div></br>
<hr>
</font>
</body>

<?php
/*書き込み保存機能&編集機能*/
$editflag = $_POST["editflag"];
if(empty($editflag)){ //新規書き込み機能
	//idの取得
	//もしレコードが空なら1を、それ以外ならmaxのレコードの番号+1する
	$sql = "select max(id) from bbs";
	$res = $pdo->query($sql);
	foreach($res as $row)$maxid = $row[0];
	if(empty($maxid)) $id = 1;
	else $id = $maxid + 1;

	//送信を取得し、レコードに書き込む
	$name = $_POST["name"];
	$comment = $_POST["comment"];
	$date = date("Y/m/d H:i:s");
	$pass = $_POST["password"];
	if(!empty($name) || !empty($comment)){
		if(empty($name)) $name = "名無しさん";
		if(empty($comment)) $comment = '(空白のコメント)';
		$stmt->execute();
	}
}
else{ //書き込み編集機能
	//echo "編集モードです<br>";
	$editedName = $_POST["name"];
	$editedComment = $_POST["comment"];
	//echo $editedComment . "<br>";
	$sql = "update bbs SET name = '$editedName' where id = $editflag;";
	$res = $pdo->query($sql);
	$sql = "update bbs SET comment = '$editedComment' where id = $editflag;";
	$res = $pdo->query($sql);
	$id_e = "";
}


/*書き込み削除機能*/
//削除対象番号とパスワードを受け取る
$id_d = $_POST["delete"];
$pass_d = $_POST["passdelete"];
if(!empty($id_d)){
	//削除対象番号に対応するレコードの真のパスワードを取得
	$sql = "select pass from bbs where id = $id_d";
	$res = $pdo->query($sql);
	foreach($res as $row){
		$truepass=$row["pass"];
	}
	if($pass_d == $truepass){
		$sql = "update bbs SET comment = null where id = $id_d";
		$res = $pdo->query($sql);
	}
}


/*出力機能*/
$sql = "select * from bbs";
$res = $pdo->query($sql);

foreach($res as $row){
	if(is_null($row["comment"])) continue;
	else{
	?>
	<font color="#0B0B61">
	<div style="width: 400px; padding: 10px; margin-bottom: 0; background:#fcfcff; border: 1px solid #0B3861; border-radius: 10px 0 10px 0;">
	<?php	echo $row["id"] . " ";
		echo $row["name"] . "<br>";
	?>
		<div style="padding-left:20px;"><b>
	<?php
		echo $row["comment"] . "<br>";
	?>
		</div></b>
		<div align="right">
	<?php
		echo $row["date"] . "<br>";
		//echo $row["pass"] . "<br>";
	?>
		</div>
	</div></br>
	</font>
	<?php
	}
}

$pdo = null;
?>