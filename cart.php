<!--
カート
-->

<head>
<meta charset = "UTF-8">
<title> わさび宝石 - カート</title>
<link rel="stylesheet" type="text/css" href="6-1.css">
<style type="text/css">
.button {
	float: right;
	display: inline-block;
	background-color: #1a381e;
	padding: 5px 20px;
	border-radius: 10px;
	color: #fff;
}

#inbutton a{
	display: block;
	color: white;
	text-align: center;
	padding: 14px 16px;
	text-decoration: none;
}

table{
  width: 60%;
  border-collapse:collapse;
  margin:0 auto;
  border: 1px solid #3b5998;
}
td,th{
  padding:10px;
}
th{
  color:#fff;
  background:#1a381e;
}
table tr:nth-child(odd){
  background:#ccdfc1;
}
td{
  border-bottom:2px solid #89a877;
</style>
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
require "common.php";
$pdo = connecter();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //exceptionを投げるようにする

//変数の準備
$items = array(); //カートに入れた商品データを入れる配列
$sum = 0;
$index = $_POST['index']; //カートに送られてきた商品のindex

//カート配列の準備 空なら新しく配列を作り、そうでなければ追加していく
if(empty($_SESSION['cart'])){
	$_SESSION['cart'] = array();
}
if(@$_POST['submit']){
	$res = $pdo->query("select * from items where num = '$index'");
	foreach($res as $row) $id = $row['id'];
	@$_SESSION['cart'][$index] = $id; //カートに入れられた商品の数量を1にする
}

//print_r($_SESSION['cart']);


foreach($_SESSION['cart'] as $lines){
	$stmt = $pdo->prepare("select * from items where id=?");
	//print_r(array($lines));
	$stmt->execute(array($lines));
	$res = $stmt->fetch();
	$stmt->closeCursor();
	$sum += $res['price'];
	$items[] = $res;
}

//print_r($res);
//print_r($items);
?>


<h2>カート</h2>
<?php if(empty($_SESSION['cart'])){
?>
	<p>カートは空です。</p>
<?php
}
else{
?>
<table>
  <tr><th>商品名</th><th>単価</th><th>数量</th><th>小計</th></tr>
  <?php foreach($items as $r) { ?>
    <tr>
      <td><?php echo $r['name'] ?></td>
      <td><?php echo $r['price'] ?></td>
      <td>1</td>
      <td><?php echo $r['price'] ?> ペッパー</td>
    </tr>
  <?php } ?>
  <tr><td colspan='2'> </td><td><strong>合計</strong></td>
  <td><b style="font-size: 20px;"><?php echo $sum ?></b> ペッパー</td></tr>
</table>
</br>
<?php
}

$pdo = null;
?>
<?php
if(!empty($_SESSION['cart'])){
?>
	<div class="button">
	<a href="buy.php" style="text-decoration: none; color: #fff;">購入する</a>
	</div>
	</br>
<?php
}
?>

</br>
<hr>
<div align="right">
<a href="all.php">お買い物に戻る</a>
<a href="cart_empty.php">カートを空にする</a>

</div>
</div>
<hr>
<div align="right"><a href="logout.php">ログアウト</a></div>
</body>
<!--参考
-->