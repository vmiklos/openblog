<?
$connect = mysql_connect($mysql_host, $mysql_user, $mysql_pass) or die('Hiba: ' . mysql_error() . "<br>\n");
mysql_select_db($mysql_db) or die('Hiba az adatbázis kiválasztása közben!<br>\n');
mysql_query('SET NAMES latin2') or die('Hiba a lekérdezésben: ' . mysql_error());
?>
