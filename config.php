<?
$mysql_host = 'localhost'; // mysql connect paramok
$mysql_user = 'openblog';
$mysql_pass = 'ballaballa';
$mysql_db   = 'openblog';

$site_root  = ""; // ~vmiklos/openblog lehetne pl
$date_format='%Y %M'; // YYYYMM ez alapjan bontom a bejegyzeseket honapokra
$date_format_display="%Y %B"; // 2004 Marcius, igy jelenik meg a honap
			      // TODO: ezt a user a prefsben kene tudja allitsa

setlocale (LC_TIME, "hu_HU");
$postcim_lenght = 5;
$postidezet_lenght = 23;
$users_limit=5;
$posts_limit=5;
$news_limit=3;
$c_text="<small>&copy Copyright 2005 OpenBlog.hu | Minden jog fenntartva.</small>";
?>
