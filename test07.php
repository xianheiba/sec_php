// 1. �����G���R�[�f�B���O���w�肵�� DB �ɐڑ�����
$dbh =
 new PDO('mysql:host=127.0.0.1;dbname=test;charset=utf8', 'username', 'password');
// 2-1. �ÓI�v���[�X�z���_��p����悤�ɃG�~�����[�V�����𖳌���
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$sql = "SELECT * FROM users WHERE username LIKE '%x'; SELECT password FROM users -- %'";
// 2-2. �v���y�A�h�X�e�[�g�����g������
$sth = $dbh->prepare($sql);
// 3. �^���w�肵�ăp�����[�^�Ƀo�C���h����
$sth->bindParam(1, $email, PDO::PARAM_STR);
$sth->bindParam(2, $password, PDO::PARAM_STR);