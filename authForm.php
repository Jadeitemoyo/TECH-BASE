<head>
<meta charset="utf-8">
</head>
<body>
<form action = "authForm.php" method = "post" enctype="multipart/form-data">
管理キーを入力してください：</br>
<input type="text" name='key'></br>
<hr>
id: <input type="text" name='id' placeholder='id'></br>
name: <input type="text" name='name' placeholder='name'></br>
price: <input type="text" name='price' placeholder='price'></br>
detail: <textarea name='detail' placeholder='detail'></textarea></br>
moreDetail: <textarea name='moreDetail' placeholder='moreDetail'></textarea></br>
color: <input type="text" name='color' placeholder='color'></br>
<input type="hidden" name="max_file_size" value="10000000">
<label for="image">画像(JPEG)</label></br>
<input type="file" name="image"></br>
<input type="submit" value="Go">
</form>
</br>

<?php
require "common.php";
$pdo = connecter();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //exceptionを投げるようにする
if (!empty($_POST)) {
    $key = $_POST['key'];

    if ($key == 'password') {
        $stmt = $pdo->prepare("insert into items(id, name, price, img, detail, moreDetail, color) values(:id, :name, :price, :img, :detail, :moreDetail, :color)");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':img', $img);
        $stmt->bindParam(':detail', $detail);
        $stmt->bindParam(':moreDetail', $moreDetail);
        $stmt->bindParam(':color', $color);

        $id = $_POST['id'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $img = $id . ".jpg";
        $detail = nl2br($_POST['detail']);
        $moreDetail = nl2br($_POST['moreDetail']);
        $color = $_POST['color'];
        $stmt->execute();

        $link = $id . ".php"; //商品詳細ページの有無確認用

        $tname = $_FILES['image']['tmp_name'];
        //var_dump($tname);
        //var_dump($_FILES['image']['error']);
        $errors = null; //エラー配列の初期化
        if ($_FILES['image']['error'] == 2) {
            echo "ファイルサイズが大きすぎます。<br>画像はアップロードされませんでした。";
        }
        if (!empty($tname)) {
            //echo "uploading<br>";

            if (!is_uploaded_file($tname)) { //アップロードされたファイルでない場合
                $errors['notup'] = "不正なアップロードです。";
            }
            $type = $_FILES['image']['type'];
            if ($type != "image/jpeg" && $type != "image/pjpeg") { //ファイル形式がjpegでない場合
                $errors['type'] = "画像はJPEG形式にしてください。";
            }
            if (count($errors) == 0) {
                $path = "image/{$id}.jpg";
                //一時データからimageフォルダへ格納する
                move_uploaded_file($tname, $path);
    
                /*サムネイルの作成*/
                //表示するサムネイル用のパス
                $path_t = "{$id}.jpg";
                //元画像の幅と高さを取得
                list($ow, $oh) = getimagesize($path);
                $tw = 200;
                $th = $tw * ($oh / $ow); //縦横比を保ったまま縮小
                $original = imagecreatefromjpeg($path); //元画像のイメージID取得
                $thumb = imagecreatetruecolor($tw, $th); //指定した大きさの黒い画像を返す
                imagecopyresized($thumb, $original, 0, 0, 0, 0, $tw, $th, $ow, $oh); //画像の縮小
                imagejpeg($thumb, $path_t); //サムネイルとして保存
            } else {
                echo "Error!：<br>";
                foreach ($errors as $lines) {
                    echo $lines . "<br>";
                }
                echo "画像はアップロードされませんでした。<br>";
            }

        }
    } else {
        echo "パスワードが違います";
    }
}
$res = $pdo->query("select * from items");
$rows = $res->fetchall();
foreach ($rows as $value) {
    print_r($value);
}
if (!file_exists($link)) {
    $contents = file_get_contents('template.php');
    $fp = fopen($link, 'a+');
    fwrite($fp, $contents);
    fclose($fp);
}
$pdo = null;
?>
