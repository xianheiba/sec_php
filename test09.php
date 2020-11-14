<?php
define('DEFAULT_NAME', '������');
extract($_POST);
$isError = false;
$errorHTML = '';

$fp = @fopen('log.txt', 'a+b');
if ($abone) {
	$comments = array();
	ftruncate($fp, 0);
	header('location: ' . $_SERVER['PHP_SELF']);
	setcookie('name', '', time() - 31536001);
	return;
}

$comments = @unserialize(stream_get_contents($fp)) ?: array();
if ($submit) {
	if (!$isError) {
		array_unshift($comments, array(
			'no' => count($comments) + 1,
			'title' => $title,
			'name' => $name ?: DEFAULT_NAME,
			'url' => $url,
			'text' => $text
		));
		ftruncate($fp, 0);
		fwrite($fp, serialize($comments));
		setcookie('name', $name, time() + (60 * 60 * 24));
	}
}
fclose($fp);

if (!$isError) {
	$url = '';
	$text = '';
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<style>
#main { width: 24em; margin: 0 auto; }
a { color: #428bca; text-decoration: none; }
a:hover, a:focus { color: #2a6496; text-decoration: underline }
input[type=text], textarea { width: 98%; margin: 0.2em 0; padding: 0.2em; border: #A9A9A9 solid 1px; font-size: 14px; font-family: Osaka-mono, 'MS Gothic', monospace; }
textarea { display: block; height: 3em; }
.tabs { list-style: none; padding: 0; }
.tabs li { display: inline-block; }
.tab { display: inline-block; padding: 0.5em; background-color: #eee; border: 1px solid #A9A9A9; }
.tab:target, .tab:hover, .tab:focus { background-color: #fff; text-decoration: none; }
.tab-page { display: none; }
#tab1:target #btn1, #tab2:target #btn2, #tab3:target #btn3 { background-color: #fff; }
#tab1:target #page1, #tab2:target #page2, #tab3:target #page3 { display: block; }
.form-name { padding: 0.2em 0.5em; }
.error { padding: 0.2em 0.5em; color: red; }
.no { font-weight: bold; }
.title { font-weight: bold; color: #008040; }
.text { margin: 0.5em 0 0 1em; }
</style>
<title>XSS�f����</title>
</head>
<body>
<div id="main">

<form accept-charset="UTF-8" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>#tab1">
<h1><a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>">XSS�f����</a> <input type="submit" name="abone" value="�N���A"></h1>
</form>

<h2>�悤���� <span id="name"></span> ����</h2>

<div id="tab1"><div id="tab2"><div id="tab3">

<ul class="tabs">
	<li><a id="btn1" class="tab" href="#tab1">���e�t�H�[��</a></li>
	<li><a id="btn2" class="tab" href="#tab2">�U���t�H�[��</a></li>
	<li><a id="btn3" class="tab" href="#tab3">�����ϊ��c�[��</a></li>
</ul>

<div id="page1" class="tab-page">
	<form class="tab-contents" accept-charset="UTF-8" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>#tab1">
		<div class="form-name">���e�t�H�[��</div>
		<div class="error"><?php echo $errorHTML; ?></div>
		<input type="hidden" name="name" readonly placeholder="���O">
		<input type="text" name="title" placeholder="�^�C�g��" value="<?php echo $title; ?>">
		<input type="text" name="url" placeholder="�����N" value="<?php echo $url; ?>">
		<textarea name="text" placeholder="�R�����g"><?php echo $text; ?></textarea>
		<input type="submit" name="submit" value="���M">
	</form>
</div>

<div id="page2" class="tab-page">
	<form class="tab-contents" accept-charset="UTF-8" method="POST" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>#tab2">
		<div class="form-name">�U���t�H�[��</div>
		<textarea name="title" placeholder="�^�C�g��"></textarea>
		<textarea name="name" placeholder="���O"></textarea>
		<textarea name="url" placeholder="�����N"></textarea>
		<textarea name="text" placeholder="�R�����g"></textarea>
		<input type="submit" name="submit" value="�U��">
	</form>
</div>

<div id="page3" class="tab-page">
	<form class="tab-contents" accept-charset="UTF-8" method="POST" id="convert">
		<div class="form-name">
		<input type="radio" id="code" name="method" value="code" checked>
		<label for="code">�e�L�X�g > ���l�����Q��</label>
		<input type="radio" id="base64" name="method" value="base64">
		<label for="base64">�e�L�X�g > Base64</label>
		</div>
		<textarea name="before" placeholder="�ϊ��������������\��t���Ă�������"></textarea>
		<textarea name="after" placeholder="�����ɕϊ����ʂ��\������܂�"></textarea>
		<input type="submit" value="�ϊ�">
	</form>
</div>

</div></div></div>

<hr>

<?php foreach ($comments as $comment): ?>
<div class="header">
	<span class="no">[<?php echo $comment['no']; ?>]</span>
	<span class="title"><?php echo $comment['title']; ?></span>
	<span class="name">���O�F<?php echo $comment['name']; ?></span>
	<a href="<?php echo $comment['url']; ?>">�����N</a>
</div>
<div class="text">
<?php echo $comment["text"]; ?>
</div>
<hr>
<?php endforeach; ?>

<script>
document.querySelector('#convert').addEventListener('submit', function(e){
	var text = e.target.before.value;
	if(!text) return;
	if (document.querySelector('#base64').checked) {
		e.target.after.value = btoa(text);
	}
	else {
		var len = text.length;
		var code = '';
		for(var i = 0; i < len; i++) {
			code += '&#' + text.charCodeAt(i) + ';';
		}
		e.target.after.value = code;
	}
	e.preventDefault();
});

var name = '<?php echo DEFAULT_NAME; ?>';
var pos = -1;
if ((pos = document.URL.indexOf('name=')) >= 0) {
	name = decodeURI(document.URL.substring(pos + 5, document.URL.length));
}
else if ((pos = document.cookie.indexOf('name=')) >= 0) {
	name = document.cookie.substring(pos + 5, document.cookie.length);
	name = decodeURIComponent(name);
}
else {
	name = window.prompt('�����O����͂��Ă�������', name) || name;
}
document.querySelector('#name').innerHTML = name;
document.querySelector('#page1 input[name=name]').value = name;
document.querySelector('#page2 textarea[name=name]').value = name;
</script>
</div>
</body>
</html>