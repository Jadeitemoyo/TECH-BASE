<!--
全ての商品
-->

<?php
require "common.php";
$pdo = connecter();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //exceptionを投げるようにする

try {
    $res = $pdo->query('select * from items');
} catch (PDOException $e) {
    print('Error:' . $e->getMessage());
}
$pdo = null;
?>

<head>
<meta charset = "UTF-8">
<title> わさび宝石 - 商品一覧</title>
<link rel="stylesheet" type="text/css" href="6-1.css">
<link rel="stylesheet" type="text/css" href="items.css">
<style type="text/css">

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
<h2> 商品一覧 </h2>

<div id="items">
<ul>
<?php
foreach ($res as $row) {
    $id = $row['id'];
    $img_tag = img_tag($id);
    $name = $row['name'];
    $detail = $row['detail'];
    $link = $id . ".php";
    ?>
<li>
    <img class="itemimg" src=<?php if (file_exists($id . ".jpg")) echo $id . ".jpg"; else echo "noimage.png" ?> alt=<?php echo $name; ?>>
	<h3><?php echo $name; ?></h3>
	<span>
		<?php echo $detail; ?>
	</span>
	<p>詳しく見る</p>
	<a href=<?php echo $link; ?>></a>
</li>
<?php
}
?>

<!--
<li>
	<img src="noimage.jpg" alt="" width=30%>
	<h3>タイトル</h3>
	<span>
		説明が入ります。<br/>
		説明が入ります。<br/>
	</span>
	<p>詳しく見る</p>
	<a href=""></a>
</li> 
-->
</ul>
</div>
</div>
</body>
