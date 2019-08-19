<!DOCTYPE html>

<html lang="ja">

<head>
<meta charset="utf-8">
<title>簡易掲示板</title>
</head>
<body>
<?php
//設定
$pass = "nack";
//mysql接続
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//テーブル作成
$sql = "CREATE TABLE IF NOT EXISTS mission5"

." ("
. "id INT AUTO_INCREMENT PRIMARY KEY,"
. "name char(32),"
. "comment TEXT,"
. "now DATETIME,"
. "pw char(32)"
.");";

$stmt = $pdo->query($sql);

if (isset($_POST["name"],$_POST["comment"])) {

$name = ($_POST["name"]);
$comment = ($_POST["comment"]);
$pw = ($_POST["passwd"]);
$delete = ($_POST["delete"]);

if (empty($_POST['edit_no'])) {
if(empty($comment)){
echo "<br>コメントが入力されていません。<br><br>";

}else if(!empty($comment)){

if(empty($pw)){
echo "<br>パスワードがありません。<br><br>";
}else if(!empty($pw)){

if($pw != $pass) {
echo "<br>パスワードが違います。<br><br>";
}else{

//テーブルにデータを入力

$sql = $pdo -> prepare("INSERT INTO mission5 (name, comment,now,pw) VALUES (:name, :comment, :now, :pw)");

$sql -> bindParam(':name', $name, PDO::PARAM_STR);

$sql -> bindParam(':now', $now, PDO::PARAM_STR);

$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);

$sql -> bindParam(':pw', $pw, PDO::PARAM_STR);

$name = ($_POST["name"]);
$comment = ($_POST["comment"]);
$now = date('Y/m/d H:i:s');
$pw = ($_POST["passwd"]);
$sql -> execute();

}
}
}
}else{
//編集実行機能
//入力データの受け取りを変数に代入

$edit_no = $_POST['edit_no'];

$id = ($_POST["edit_no"]); //変更する投稿番号
$name = ($_POST["name"]);
$comment = ($_POST["comment"]); 
$now = date('Y/m/d H:i:s');
$sql = 'update mission5 set name=:name,comment=:comment,now=:now where id=:id';
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':name', $name, PDO::PARAM_STR);
$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
$stmt->bindParam(':now', $now, PDO::PARAM_STR);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();

} 
}

  //削除機能

if (isset($_POST["delete"])){

$del_psw = ($_POST["del_pass"]);

if(!empty($_POST["delete"])){

//passwdがないときはecho、あるときは新規投稿・編集投稿

if(empty($del_psw)){
echo "<br>パスワードがありません。<br><br>";
}else if(!empty($del_psw)){
if($del_psw != $pass) {
echo "<br>パスワードが違います。<br><br>";

}else{

$delete = ($_POST["delete"]);//削除したい番号
//削除

$id = $delete;
$sql = 'delete from mission5 where id=:id';
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
}
}
}
}

//編集機能

if (isset($_POST["edit"])){
$edi_psw = ($_POST["edi_pass"]);
if(!empty($_POST["edit"])){

if(empty($edi_psw)){
echo "<br>パスワードがありません。<br><br>";
}else if(!empty($edi_psw)){

if($edi_psw != $pass) {
echo "<br>パスワードが違います。<br><br><br>";
}else{

$edit = ($_POST["edit"]);//編集したい番号
// データの読み込み

$sql = 'SELECT * FROM mission5 ';
$stmt = $pdo -> query($sql); //!!!
$result = $stmt -> fetchAll(); //!!!
	
foreach ($result as $row) {
if($row['id'] == $edit){
$editnumber = $row['id'];
$user = $row['name'];
$text = $row['comment'];
}
}
}
}
}
}
?>
<form method="POST" action="mission_5.php">
<input type="text" name="name" placeholder="名前" value="<?php if(isset($user)) {echo $user;} ?>"><br>
<input type="text" name="comment" placeholder="コメント" value="<?php if(isset($text)) {echo $text;} ?>">
<input type="hidden" name="edit_no" value="<?php if(isset($editnumber)) {echo $editnumber;} ?>"><br>
<input type="password" name="passwd" placeholder="パスワード">
<input type="submit" value="送信">
<br>
<br>
削除：<br>
<input type="text" name="delete" placeholder="番号">
<br>
<input type="password" name="del_pass" placeholder="パスワード">
<input type="submit" value="削除">
<br>
<br>
編集：<br>
<input type="text" name="edit" placeholder="番号">
<br>
<input type="password" name="edi_pass" placeholder="パスワード">
<input type="submit" value="編集">
<br>
<br>	
<br>
</form>

<?php
//データを表示
$sql = 'SELECT * FROM mission5';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row){
//$rowの中にはテーブルのカラム名が入る
echo $row['id']."\t";
echo $row['name']."\t";
echo $row['now'].'<br>';
echo $row['comment'].'<br>';
echo "<hr>";
}
    ?>
</body>

</html>