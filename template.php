<head>
<meta charset = "UTF-8">
</head>

<?php
require "common.php";
$pdo = connecter();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //exceptionを投げるようにする

$fname = basename(__FILE__, ".php");
$res = $pdo->query("select * from items where id = '$fname'");
foreach ($res as $row) {
    $num = $row['num'];
    $id = $row['id'];
    $name = $row['name'];
    $img = $row['img'];
    $price = $row['price'];
    $stock = $row['stock'];
    $detail = $row['moreDetail'];
}
?>

<head>
<meta charset = "UTF-8">
<title> わさび宝石 </title>
<style type="text/css">
.contents{
	width:600px;
	margin:30px auto;
	padding:12px;
	background-color:#ffffff;
	border: 1px solid #3b5998;
}

.menubox{
	float:left;
	width:180px;
	margin:30px auto;
	padding:5px;
	background-color:#ffffff;
	border: 1px solid #3b5998;
}

.item{
	position: relative;
	border: 2px solid #ccc;
	padding: 20px 20px 250px 20px;
	margin-bottom: 20px;
	overflow: scroll;
}

#topmenu ul {
	list-style-type: none;
	margin: 0;
	padding: 0;
	overflow: hidden;
	background-color: #1a381e;
}
#topmenu li {
	float: left;
	border-right: 1px solid #bbbbbb;
}
#topmenu li:last-child {
	border-right: none;
}
#topmenu li a {
	display: block;
	color: white;
	text-align: center;
	padding: 14px 16px;
	text-decoration: none;
}
#topmenu li a:hover:not(.active) {
	background-color: #a9bce2;
}
.active {
	background-color: #da3c41;
}

#menu {
	 float: left;
	 width: 150px;
}

#menu ul{ 
	margin: 0; 
	padding :0; 
	list-style: none; 
}
#menu li{ 
	padding :0; 
	margin:0; 
}
#menu li a{
	display: block;
	background: url("before.jpg") no-repeat left center;
	padding: 2px 0px 3px 20px;
	color:#333;
	width: 150px;
	margin: 1px 0px;
	text-decoration:none;
	border-bottom: 1px dotted #666666;
	font-size: 16px;
}
#menu li a:hover{
	background: url("after.jpg") no-repeat left center;
}


#items img{
	float: left;
	margin-right: 15px;
}
#items input{
	float: right;
	display: inline-block;
	background-color: #1a381e;
	padding: 5px 20px;
	border-radius: 10px;
	color: #fff;
}

.soldout{
	float: right;
	display: inline-block;
	background-color: #fff;
	padding: 5px 20px;
	border-radius: 10px;
	color: #da3c41;
	border: 2px solid #da3c41;
}

#items a{
	text-decoration:none;
}
#items a:hover{
	opacity:0.6;
	filter: alpha(opacity=60);
	-ms-filter: "alpha( opacity=60 )";
	background: #fff;
}

.absolute{
	position: absolute;
	top: 230;
	left: 20;
	margin: auto;
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
<h2> <?php if (!empty($name)) echo $name;
    else echo "商品名無定義"; ?></h2>

<div class="item">
<div id="items">
	<img src="<?php if (file_exists($img)) echo $img;
            else echo 'noimage.png'; ?>"
	alt="<?php if (!empty($name)) echo $name;
        else echo '商品名無定義'; ?>" width=30%>
	<h3><?php if (!empty($name)) echo $name;
    else echo "商品名無定義"; ?></h3>
	<span>
		商品番号：<?php echo $id; ?> <br/>
		価格：<span style="font-size:20px"> <?php if ($stock == 1) echo $price;
                                    else echo '0'; ?> </span>ペッパー（税込）<br/>
	</span>
<?php
if ($stock == 1) {
    echo "<form action='cart.php' method='post'>";
    echo "<input type='hidden' name = 'index' value=$num>";
    echo "<input type='submit' name = 'submit' value='カートに入れる'>";
    echo "</form>";
} else {
    echo "<span class='soldout'>売り切れ</span>";
}
?>
	</br>
	<hr>
	<span class="absolute">
    <?php if (!empty($detail)) echo $detail;
    else echo "準備中です。</br>"; ?>
	</span>
</div>
</div>
</body>