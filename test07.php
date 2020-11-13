// 1. 文字エンコーディングを指定して DB に接続する
$dbh =
 new PDO('mysql:host=127.0.0.1;dbname=test;charset=utf8', 'username', 'password');
// 2-1. 静的プレースホルダを用いるようにエミュレーションを無効化
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$sql = "SELECT * FROM users WHERE username LIKE '%x'; SELECT password FROM users -- %'";
// 2-2. プリペアドステートメントを準備
$sth = $dbh->prepare($sql);
// 3. 型を指定してパラメータにバインドする
$sth->bindParam(1, $email, PDO::PARAM_STR);
$sth->bindParam(2, $password, PDO::PARAM_STR);