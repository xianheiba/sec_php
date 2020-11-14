<html>
  <meta charset="UTF-8">
  <head>
    <title>ログイン処理</title>
  </head>
  <body>
<?php
$conn = mysql_connect('localhost', 'root', '*******');
$db = mysql_select_db('system', $conn);

$uid = $_POST['uid'];
$pass = $_POST['password'];

$result = mysql_query("SELECT email FROM users where uid='$uid' AND passwd='{$pass}'");
if (mysql_num_rows($result) == 0) {
  echo "ユーザ名または、パスワードに誤りがあります。";
  exit;
}

while ($row = mysql_fetch_assoc($result)) {
  print('email addressはこちらです '.$row['email']);
  print('<br>');
}

mysql_close($conn);
?>
  <a href="index.html">ログイン画面に戻る</a>
  </body>
</html>
