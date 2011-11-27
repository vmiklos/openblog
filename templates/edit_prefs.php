<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>OpenBlog.hu</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<link href="/old/templates/fooldal/style.css" rel="stylesheet" type="text/css">
</head>
<body>

<div id="page-wrapper" class="plain">
<div id="page-head">
<? print_ad(); ?>
</div>
<div id="content" class="plain">
<div class="form">
<h1>Adataid</h1>
<?
// blogtitle, email
print("
<form action=\"" . $user['name'] . "\" method=\"post\">
<div align=\"center\">
<input type=hidden name=id value=" . $user['id'] . ">
<table><tr><td>Beceneved:</td><td class=\"right\"><input type=text name=displayname value=\"" . $user['displayname'] . "\"><br>
<tr><td>Blogod címe:<td class=\"right\"><input type=text name=blogtitle value=\"" . $user['blogtitle'] . "\"><br>
<tr><td>Email címed:<td class=\"right\"><input type=text name=email value=\"" . $user['email'] . "\"><br>
<tr><td>Dátum formátum:<td class=\"right\"><input type=text name=date_format value=\"" . $user['date_format'] . "\"><br>
<tr><td>Bejegyzések a főoldalon:<td class=\"right\"><input type=text name=limit value=\"" . $user['limit'] . "\"><br>
<tr><td>Sablonod neve:<td class=\"right\"><input type=text name=templatetitle value=\"" . $user['templatetitle'] . "\"><br>
<tr><td>Szeretnék spamszűrést a hozzászólásoknál:<td class=\"right\"><input type=\"checkbox\" name=\"want_antispam\" " . ($user['want_antispam'] ? 'checked="checked"' : '') . "/><br>
<tr><td colspan=\"2\">
Sablon:<br><textarea cols=80 rows=20 name=\"template\">
" . $user['template'] . "</textarea></td></tr>
<tr><td colspan=\"2\">Archívumsablon:<br><textarea cols=80 rows=20 name=\"archivetemplate\">
" . $user['archivetemplate'] . "</textarea></td></tr>
</table>
<input type=\"submit\" value=\"Meg akarok változni!\">
</div>
</form>
<h1>Jelszómódosítás</h1>
<div align=\"center\">
<form action=\"" . $user['name'] . "\" method=\"post\">
<input type=hidden name=id value=" . $user['id'] . ">
<table><tr><td>Új jelszavad:</td><td><input type=password name=newpass></td></tr>
<tr><td>Új jelszavad mégegyszer:</td><td><input type=password name=newpass2></td></tr>
</table>
<input type=\"submit\" value=\"Változtasd!\">
</form>
</div>
")
?>
<h1>Kérdések és válaszok</h1>
<ul><li><b>Mi ez a krix-krax a dátum formátumnál?</b><br>
<p>Itt adhatod meg, hogy milyen formátumban jelenjen meg a bejegyzések ideje. A következő változókat használhatod:</p>
<!-- <p>Bővebb információt <a href="http://dev.mysql.com/doc/mysql/en/date-and-time-functions.html">itt</a> találsz. (FIXME)</p> -->
<table align=center>
<tr><td><b>Név</b><td><b>Leírás</b>
<tr><td><pre>%a<td>a nap rövid neve (Mon..Sun)
<tr><td><pre>%b<td>a hónap neve (Jan..Dec)
<tr><td><pre>%c<td>a hónap száma (0..12)
<tr><td><pre>%D<td>a nap sorszámneve (0th, 1st, 2nd, 3rd, ...)
<tr><td><pre>%d<td>a nap száma (00..31)
<tr><td><pre>%e<td>a nap száma (0..31)
<tr><td><pre>%f<td>mikromásodperc (000000..999999)
<tr><td><pre>%H<td>óra (00..23)
<tr><td><pre>%h<td>óra (01..12)
<tr><td><pre>%i<td>percek (00..59)
<tr><td><pre>%j<td>a nap száma az év eleje óta (001..366)
<tr><td><pre>%M<td>hónap neve (January..December)
<tr><td><pre>%m<td>hónap száma (00..12)
<tr><td><pre>%p<td>AM vagy PM
<tr><td><pre>%r<td>12-órás idő (óó:pp:mm és utána AM vagy PM)
<tr><td><pre>%S<td>másodpercek (00..59)
<tr><td><pre>%T<td>24-órás idő(óó:pp:mm)
<tr><td><pre>%U<td>a hét száma (00..53)
<tr><td><pre>%W<td>a nap neve (Monday..Sunday)
<tr><td><pre>%w<td>a nap héten belüli sorszáma (0=vasárnap..6=Szombat)
<tr><td><pre>%Y<td>4-számjegyű év
<tr><td><pre>%y<td>2-számjegyű év
<tr><td><pre>%%<td>százalékjel</table>
<p>A mezőben szereplő egyéb karakterek helyettesítés nélkül jelennek meg</p>
</li>
<li><b>Mi az a sablon? Mit kell tudni róla?</b><br>
Regisztració után a jobb sablonok közül válaszhatsz, és a választott sablon egy másolata a tiéd lesz, kevedre szerkesztheted.<br>
A sablon egy sima HTML file, csak használhatsz benne egy-két változót, illetve 1 speciális &lt;post&gt; taget. Az ezek közötti rész bejegyzésenként fog megismétlődni.<br>
Ezeket a változókat használhatod a sablonodban:<br>
<ul><li>$fooldal - a blogod főoldala
<li>$usernev - a felhasználói azonosítód
<li>$nev - beceneved, ha nem adtál meg ilyet, akkor a userneved.
<li>$blogcim - a blogod neve
<li>$email - a mailcímed
<li>$szamlalo - annyit mutat, ahányszor meglátogáttak a blogod
<li>$postszam - az összes bejegyzésed száma
<li>$newurl - erre kattintva lehet új bejegyzést letrehozni
<li>$prefsurl - erre kattintva módosíthatod a beállításaidat
<li>$archiveurl - az archívumodra mutató link
<li>$copyright - copyright szöveg</ul>

Ezeknek az értékei bejegyzésenként változnak, így csak a <post> és </post> tagek között használhatod őket:<br>
<ul><li>$deleteurl - törlő url-je a bejegyzésednek
<li>$editurl - a bejegyzés szerkesztő urlje
<li>$commenturl - a bejegyzés hozzászóló urlje
<li>$commentnum - a hozzászólások száma
<li>$posturl - permanens url-je a bejegyzésnek
<li>$ido - a kreálás ideje
<li>$postcim - a bejegyzés címe
<li>$post - maga a szöveg, érdemes csak egyszer használni ;-)</ul>
</li>
<li><b>Mi ez az archívumsablon?</b><br>
Az archívumsablon egészen hasonló a normál sablonhoz. Mégis, akkor mik a különbségek?<br>
A &lt;post&gt; tagen kívül egy &lt;month&gt; taged is van, az ezek közötti rész fog megismétlődni havonta.<br>
A fej- és láblécben (tehát a hónapokon kívül) ugyanazok a változók használhatók, mint a normál sablonban a bejegyzéseken kívül.<br>
A hónapfej- és láblécekben (tehát a bejegyzéseken kívül) szintúgy, de van egy $honap változó, aminek a helyébe az aktuális hónap lesz behelyettesítve.<br>
A &lt;post&gt; és &lt;/post&gt; tageken belül szintén a normál sablon változóit lehet használni, és itt is használható a $honap változó. Ezen kívül van egy $postidezet valtozód, melybe a post első néhány szavát kapod meg. (Értelemszűen a $post változót itt nemérdemes használni.)
</li>
</ul>
</div>
</div>
<div id="clear"></div>
<div id="footer">
<span><? print($c_text); ?></span>
</div>
</body>
</html>
