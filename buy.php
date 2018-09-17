<!--
購入手続き
-->

<?php
require "common.php";
$pdo = connecter();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //exceptionを投げるようにする

$account = $_SESSION['account'];
?>

<head>
<meta charset = "UTF-8">
<title> わさび宝石 - 購入手続き</title>
<link rel="stylesheet" type="text/css" href="6-1.css">
<style type="text/css">
.form{
	display:inline-block;
	padding: 12px;
	margin-bottom: 30px;
	background:#fcfcff;
	border: 1px dashed #3b5998;
	border-radius: 5px;"
}
</style>


</head>

<body background="jppattern.jpg" style="margin-top:30px;">
<a href="top.php"><img src="logo.png" alt="わさび宝石" width = 30%></a>

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
<?php
$errors = null;
if(@$_POST['submit']){
	$name = htmlspecialchars($_POST['name']);
	$address = htmlspecialchars($_POST['address']); 
	$tel = htmlspecialchars($_POST['tel']);
	if(empty($name)) $errors['name'] = "お名前を入力してください。";
	if(empty($address)) $errors['address'] = "住所を入力してください。";
	if(empty($tel)) $errors['tel'] = "電話番号を入力してください。";
	
	if(count($errors)==0){
		/*ログインユーザのメールアドレスを取得*/
		$res = $pdo->query("select * from members where account = '$account'");
		$row = $res->fetch();
		$usermail = $row['email'];
		$adminmail = "t.sekiguchi09@gmail.com";
		/*再度購入したものの情報を取得*/
		foreach($_SESSION['cart'] as $lines){
			$stmt = $pdo->prepare("select * from items where id=?");
			$stmt->execute(array($lines));
			$res = $stmt->fetch();
			$stmt->closeCursor();
			
			$contents .= "商品名: {$res['name']}\n"
				. "単価: {$res['price']}\n"
				. "数量: 1\n\n";
			$stmt = $pdo->prepare("update items set stock = 0 where id = ?");
			$stmt->execute(array($lines));
		}

		//日本語をメールに含む準備
		mb_language('ja');
		mb_internal_encoding('UTF-8');
		
		/*購入者へメール*/
		$body = "ご注文ありがとうございました。\n"
				. "注文内容を再度ご確認下さい。\n"
				. "お名前: $name\n"
				. "ご住所: $address\n"
				. "電話番号: $tel\n\n"
				. "ご注文内容\n"
				. $contents
				. "またのご利用をお待ちしております。";
		mb_send_mail($usermail, '【わさび宝石】ご注文ありがとうございました', $body, "From: $adminmail");
		
		/*管理者へメール*/
		$body = "商品が購入されました。\n"
				. "お名前: $name\n"
				. "ご住所: $address\n"
				. "電話番号: $tel\n\n"
				. "ご注文内容\n"
				. $contents;
		mb_send_mail($adminmail, '【わさび宝石】商品の購入がありました', $body, "From: $usermail");
		$_SESSION['cart'] = null;
		header('Location: http://tt-222.99sv-coco.com/buy_complete.php');
	}
}
?>

<h2>購入手続き</h2>
  <div class="form">
  <form action="buy.php" method="post">
    <p>
      お名前<br>
      <input type="text" name="name" value="<?php echo $account ?>" readonly="readonly">
    </p>
    <p>
      ご住所<br>
      <input type="text" name="address" size="60" value="のんびり県ほげ市ぴよぴよ町1234" readonly="readonly">
    </p>
    <p>
      電話番号<br>
      <input type="text" name="tel" value="1234567890" readonly="readonly">
    </p>
    <p>
      <input type="submit" name="submit" value="購入">
    </p>
  </form>
  </div>

<hr>
  <div align="right">
  <a href="all.php">お買い物に戻る</a>
  <a href="cart.php">カートに戻る</a>
  </div>
</div>
</body>

<?php
if(count($errors) > 0){
	echo "エラー！<br>";
	foreach($errors as $value){
		echo "・". $value . "<br>";
	}
}

$pdo=null;
?>