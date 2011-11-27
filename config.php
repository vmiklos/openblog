<?
// mysql connect paramok
$mysql_host = 'localhost';
$mysql_user = 'openblog';
$mysql_pass = '3dFr5p';
$mysql_db   = 'openblog';

// ezt csak rss feednel hasznaljuk
$site_name  = "http://openblog.hu";

// ~vmiklos/openblog lehetne pl, szal / _nem_ kell az elejere
$site_root  = "";

// YYYYMM ez alapjan bontja a bejegyzeseket honapokra
$date_format='%Y %M';

// 2004 Marcius, igy jelenik meg a honap
// TODO: ezt a user a prefsben kene tudja allitsa
$date_format_display="%Y %B";

// HH:MM a fooldalon megjeleno postok datumformatuma
$date_format_fooldalipost = "%H:%i";

// YYYY.MM.DD. a fooldalon megjeleno hirek datumformatuma
$date_format_hir = "%Y.%m.%e.";

// ettol lesznek magyarok a datumok - mar ahol ;)
setlocale (LC_TIME, "hu_HU");

// ha nincs a postnak cime az archivumban, akkor a post elso x szava lesz
// hasznalva
$postcim_lenght = 5;

// az elso x szot tartarlmazza a $postidezet_lenght
$postidezet_lenght = 23;

// ennyi uj user jelenik meg a fooldalon
$users_limit=5;

// ennyi uj post
$posts_limit=5;

// ennyi uj hir
$news_limit=3;

// ez jelenik meg minden oldal aljan
$c_text="OpenBlog.hu engine &copy; Copyright 2005-2007 OpenBlog.hu " .
	"| Minden jog fenntartva.";

// ennel _tobb_ templatescore kell, h regelesnel megjelenjen
$sablonkuszob=5;

// speci /foo parancsok, ilyen neven nem lehet felhasznalo
$handlers = array("new", "news", "rss", "posts", "delete", "edit", "ad",
	"adclick", "upload", "prefs", "register", "archives", "list",
	"comment", "comments", "editcomment", "delcomment", "xmlposts",
	"xmlpost", "postrss", "commentrss", "authimage");

// oldalankent ennyi felhasznalo jelenik meg listazaskor
$plimit = 20;
?>
