<?
$connect = mysql_connect($mysql_host, $mysql_user, $mysql_pass) or die('Hiba: ' . mysql_error() . "<br>\n");
mysql_select_db($mysql_db) or die('Hiba az adatb�zis kiv�laszt�sa k�zben!<br>\n');
?>
