<!--
トップページ
-->

<head>
<meta charset = "UTF-8">
<title> わさび宝石 - トップページ</title>
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

<?php
session_start();

if (count($_SESSION) == 0) {
	header('Location: https://example.com/top.php');
	exit;
}

echo "ようこそ " . $_SESSION['account'] . "さん！<br>";


//データベースに接続
$dsn = 'データベース名';
$user = 'ユーザ名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password); //PDOオブジェクトを作成（操作の準備)
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //exceptionを投げるようにする

$pdo = null;
?>

<p>わさび宝石では、管理人が個人的に所有しているカラーストーンを販売しています。</br>
…というコンセプトで作った練習用のサイトであり、<b>実際の販売はしておりません。</b></br>
また、それに伴い、「ペッパー」という架空の通貨単位を使用しています。<br></p>
<p>各地のミネラルショーを巡って手に入れたお気に入りのストーンを紹介しています。</br>
どうぞゆっくりとご覧になってください。</br>
</p>

</div>
<hr>
<div align="right"><a href="logout.php">ログアウト</a></div>
</body>
<!--参考
-->
