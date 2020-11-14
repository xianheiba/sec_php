<?php
$var = '<tag>phpinfo()</tag>';
preg_replace("/<tag>(.*?)<\/tag>/e", 'addslashes(\\1)', $var);
?>
