<!--
掲示板
-->

<?php
require "common.php";
$pdo = connecter();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //exceptionを投げるようにする

$account = $_SESSION['account'];
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
<title> わさび宝石 - 掲示板 </title>
<link rel="stylesheet" type="text/css" href="6-1.css">
</head>

<body background="jppattern.jpg" style="margin-top:30px;">
<a href="top.php"><img class="logo" src="logo.png" alt="わさび宝石"></a>

<div id="topmenu">
<ul>
<li><a class="active" href="top.php">トップページ</a></li>
<li><a href="all.php">商品一覧</a></li>
<li><a href="inquiry.php">お問い合わせ</a></li>
<li><a href="bbs.php">掲示板</a><li>
<li><a href="cart.php">カートを見る</a></li>
</ul>
</div>

<div class="menubox">
<div id="menu">
<ul>
<li><a href="all.php">全ての宝石</a></li>
<li><a href="red.php">赤系の宝石</a></li>
<li><a href="blue.php">青系の宝石</a></li>
<li><a href="green.php">緑系の宝石</a></li>
<li><a href="yellow.php">黄系の宝石</a></li>
<li><a href="other.php">その他の宝石</a></li>
</ul>
</div>
</div>


<div class="contents">
<font color="#0B0B3B"><h2>みんなの掲示板</h2></font>
<font color="#0B0B61">
<div style="display:inline-block; padding: 12px; margin-bottom: 30px; background:#fcfcff; border: 1px dashed #3b5998; border-radius: 5px;">
<h3>機能</h3>
<ol>
<b><li>コメント投稿機能</b>：お好きな名前でコメントを投稿してみよう。</li>
<b><li>画像アップロード機能</b>：皆に見せたい画像を投稿しよう。（JPEGのみ：100KB以下）</li>
<b><li>パスワード機能</b>：自分だけのパスワードを設定しよう。</li>
<b><li>編集/削除機能</b>：投稿番号とパスワードの組み合わせを入力するとコメントを編集・削除できる。</li>
</ol>
</div>

</br>
<div style="width: 40%; min-width:230px; display:inline-block; padding: 10px; margin-bottom: 30px; background:#fcfcff; border: 1px dashed #3b5998; border-radius: 5px;">
<form action="bbs.php" method="post" enctype="multipart/form-data">
  <label for="name">名前</label></br>
  <input type="text" name="name" value= "<?php if(!empty($name_e)) echo $name_e; else echo $account;?>"> </br>
  <label for="comment">コメント</label></br>
  <input type="text" name="comment" placeholder="コメントを入力" size="30" value= "<?php if(!empty($comment_e)) echo $comment_e; ?>"></br>
  <input type="hidden" name="max_file_size" value="100000">
  <label for="image">画像(JPEG)</label></br>
  <input type="file" name="image"></br>
  <label for="password">パスワード</label></br>
  <input type="text" name="password" placeholder="Password"></br>
  <!-- <label for="comment">編集用</label></br> -->
  <input type="hidden" name="editflag" value= <?php if(!empty($id_e)) echo $id_e; ?>></br>
  <input type="submit" value="送信">
</form>
</div>

<div style="display:inline-block; padding: 12px; margin-bottom: 30px; background:#fcfcff; border: 1px dashed #3b5998; border-radius: 5px;">
<form action="bbs.php" method="post">
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
	$name = htmlspecialchars($_POST["name"]);
	$comment = htmlspecialchars($_POST["comment"]);
	$date = date("Y/m/d H:i:s");
	$pass = htmlspecialchars($_POST["password"]);
	if(!empty($name) || !empty($comment)){
		if(empty($name)) $name = "名無しさん";
		if(empty($comment)) $comment = '(空白のコメント)';
		$stmt->execute();
	}
	
	//画像アップロード＆サムネイル作成機能
	$tname = $_FILES['image']['tmp_name'];
	$errors = null; //エラー配列の初期化
	if($_FILES['image']['error'] == 2){
		echo "ファイルサイズが大きすぎます。<br>画像はアップロードされませんでした。";
	}
	if(!empty($tname)){
		if(!is_uploaded_file($tname)){ //アップロードされたファイルでない場合
			$errors['notup'] = "不正なアップロードです。";
		}
		$type = $_FILES['image']['type'];
		if($type != "image/jpeg" && $type != "image/pjpeg"){ //ファイル形式がjpegでない場合
			$errors['type']  = "画像はJPEG形式にしてください。";
		}		
		if(count($errors) == 0){
			$path = "image/{$id}.jpg";
			//一時データからimageフォルダへ格納する
			move_uploaded_file($tname, $path);

			/*サムネイルの作成*/
			//表示するサムネイル用のパス
			$path_t = "image/{$id}_t.jpg";
			//元画像の幅と高さを取得
			list($ow, $oh) = getimagesize($path);
			$tw = 128;
			$th = $tw * ($oh / $ow); //縦横比を保ったまま縮小
			$original = imagecreatefromjpeg($path); //元画像のイメージID取得
			$thumb = imagecreatetruecolor($tw, $th); //指定した大きさの黒い画像を返す
			imagecopyresized($thumb, $original, 0, 0, 0, 0, $tw, $th, $ow, $oh); //画像の縮小
			imagejpeg($thumb, $path_t); //サムネイルとして保存
		}
		else{
			echo "Error!：<br>";
			foreach($errors as $lines){
				echo $lines . "<br>";
			}
			echo "画像はアップロードされませんでした。<br>";
		}
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
	<div style="width: 90%; max-width: 400px; padding: 10px; margin-bottom: 0; background:#fcfcff; border: 1px solid #0B3861; border-radius: 10px 0 10px 0;">
	<?php
		echo $row["id"] . " ";
		echo $row["name"] . "<br>";
	?>
		<div style="padding-left:20px;"><b>
	<?php
		echo $row["comment"] . "<br>";
		//image/id.jpgが存在すれば同時に表示する
		$fn = "image/{$row['id']}.jpg";
		$fn_t = "image/{$row['id']}_t.jpg";
		if(file_exists($fn)){
			print "<br><a href = '$fn'><img src='$fn_t' border='0'></a><br>";
		}
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