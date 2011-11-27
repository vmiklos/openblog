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
<tr><td>Blogod c�me:<td class=\"right\"><input type=text name=blogtitle value=\"" . $user['blogtitle'] . "\"><br>
<tr><td>Email c�med:<td class=\"right\"><input type=text name=email value=\"" . $user['email'] . "\"><br>
<tr><td>D�tum form�tum:<td class=\"right\"><input type=text name=date_format value=\"" . $user['date_format'] . "\"><br>
<tr><td>Bejegyz�sek a f�oldalon:<td class=\"right\"><input type=text name=limit value=\"" . $user['limit'] . "\"><br>
<tr><td>Sablonod neve:<td class=\"right\"><input type=text name=templatetitle value=\"" . $user['templatetitle'] . "\"><br>
<tr><td>Szeretn�k spamsz�r�st a hozz�sz�l�sokn�l:<td class=\"right\"><input type=\"checkbox\" name=\"want_antispam\" " . ($user['want_antispam'] ? 'checked="checked"' : '') . "/><br>
<tr><td colspan=\"2\">
Sablon:<br><textarea cols=80 rows=20 name=\"template\">
" . $user['template'] . "</textarea></td></tr>
<tr><td colspan=\"2\">Arch�vumsablon:<br><textarea cols=80 rows=20 name=\"archivetemplate\">
" . $user['archivetemplate'] . "</textarea></td></tr>
</table>
<input type=\"submit\" value=\"Meg akarok v�ltozni!\">
</div>
</form>
<h1>Jelsz�m�dos�t�s</h1>
<div align=\"center\">
<form action=\"" . $user['name'] . "\" method=\"post\">
<input type=hidden name=id value=" . $user['id'] . ">
<table><tr><td>�j jelszavad:</td><td><input type=password name=newpass></td></tr>
<tr><td>�j jelszavad m�gegyszer:</td><td><input type=password name=newpass2></td></tr>
</table>
<input type=\"submit\" value=\"V�ltoztasd!\">
</form>
</div>
")
?>
<h1>K�rd�sek �s v�laszok</h1>
<ul><li><b>Mi ez a krix-krax a d�tum form�tumn�l?</b><br>
<p>Itt adhatod meg, hogy milyen form�tumban jelenjen meg a bejegyz�sek ideje. A k�vetkez� v�ltoz�kat haszn�lhatod:</p>
<!-- <p>B�vebb inform�ci�t <a href="http://dev.mysql.com/doc/mysql/en/date-and-time-functions.html">itt</a> tal�lsz. (FIXME)</p> -->
<table align=center>
<tr><td><b>N�v</b><td><b>Le�r�s</b>
<tr><td><pre>%a<td>a nap r�vid neve (Mon..Sun)
<tr><td><pre>%b<td>a h�nap neve (Jan..Dec)
<tr><td><pre>%c<td>a h�nap sz�ma (0..12)
<tr><td><pre>%D<td>a nap sorsz�mneve (0th, 1st, 2nd, 3rd, ...)
<tr><td><pre>%d<td>a nap sz�ma (00..31)
<tr><td><pre>%e<td>a nap sz�ma (0..31)
<tr><td><pre>%f<td>mikrom�sodperc (000000..999999)
<tr><td><pre>%H<td>�ra (00..23)
<tr><td><pre>%h<td>�ra (01..12)
<tr><td><pre>%i<td>percek (00..59)
<tr><td><pre>%j<td>a nap sz�ma az �v eleje �ta (001..366)
<tr><td><pre>%M<td>h�nap neve (January..December)
<tr><td><pre>%m<td>h�nap sz�ma (00..12)
<tr><td><pre>%p<td>AM vagy PM
<tr><td><pre>%r<td>12-�r�s id� (��:pp:mm �s ut�na AM vagy PM)
<tr><td><pre>%S<td>m�sodpercek (00..59)
<tr><td><pre>%T<td>24-�r�s id�(��:pp:mm)
<tr><td><pre>%U<td>a h�t sz�ma (00..53)
<tr><td><pre>%W<td>a nap neve (Monday..Sunday)
<tr><td><pre>%w<td>a nap h�ten bel�li sorsz�ma (0=vas�rnap..6=Szombat)
<tr><td><pre>%Y<td>4-sz�mjegy� �v
<tr><td><pre>%y<td>2-sz�mjegy� �v
<tr><td><pre>%%<td>sz�zal�kjel</table>
<p>A mez�ben szerepl� egy�b karakterek helyettes�t�s n�lk�l jelennek meg</p>
</li>
<li><b>Mi az a sablon? Mit kell tudni r�la?</b><br>
Regisztraci� ut�n a jobb sablonok k�z�l v�laszhatsz, �s a v�lasztott sablon egy m�solata a ti�d lesz, kevedre szerkesztheted.<br>
A sablon egy sima HTML file, csak haszn�lhatsz benne egy-k�t v�ltoz�t, illetve 1 speci�lis &lt;post&gt; taget. Az ezek k�z�tti r�sz bejegyz�senk�nt fog megism�tl�dni.<br>
Ezeket a v�ltoz�kat haszn�lhatod a sablonodban:<br>
<ul><li>$fooldal - a blogod f�oldala
<li>$usernev - a felhaszn�l�i azonos�t�d
<li>$nev - beceneved, ha nem adt�l meg ilyet, akkor a userneved.
<li>$blogcim - a blogod neve
<li>$email - a mailc�med
<li>$szamlalo - annyit mutat, ah�nyszor megl�tog�ttak a blogod
<li>$postszam - az �sszes bejegyz�sed sz�ma
<li>$newurl - erre kattintva lehet �j bejegyz�st letrehozni
<li>$prefsurl - erre kattintva m�dos�thatod a be�ll�t�saidat
<li>$archiveurl - az arch�vumodra mutat� link
<li>$copyright - copyright sz�veg</ul>

Ezeknek az �rt�kei bejegyz�senk�nt v�ltoznak, �gy csak a <post> �s </post> tagek k�z�tt haszn�lhatod �ket:<br>
<ul><li>$deleteurl - t�rl� url-je a bejegyz�sednek
<li>$editurl - a bejegyz�s szerkeszt� urlje
<li>$commenturl - a bejegyz�s hozz�sz�l� urlje
<li>$commentnum - a hozz�sz�l�sok sz�ma
<li>$posturl - permanens url-je a bejegyz�snek
<li>$ido - a kre�l�s ideje
<li>$postcim - a bejegyz�s c�me
<li>$post - maga a sz�veg, �rdemes csak egyszer haszn�lni ;-)</ul>
</li>
<li><b>Mi ez az arch�vumsablon?</b><br>
Az arch�vumsablon eg�szen hasonl� a norm�l sablonhoz. M�gis, akkor mik a k�l�nbs�gek?<br>
A &lt;post&gt; tagen k�v�l egy &lt;month&gt; taged is van, az ezek k�z�tti r�sz fog megism�tl�dni havonta.<br>
A fej- �s l�bl�cben (teh�t a h�napokon k�v�l) ugyanazok a v�ltoz�k haszn�lhat�k, mint a norm�l sablonban a bejegyz�seken k�v�l.<br>
A h�napfej- �s l�bl�cekben (teh�t a bejegyz�seken k�v�l) szint�gy, de van egy $honap v�ltoz�, aminek a hely�be az aktu�lis h�nap lesz behelyettes�tve.<br>
A &lt;post&gt; �s &lt;/post&gt; tageken bel�l szint�n a norm�l sablon v�ltoz�it lehet haszn�lni, �s itt is haszn�lhat� a $honap v�ltoz�. Ezen k�v�l van egy $postidezet valtoz�d, melybe a post els� n�h�ny szav�t kapod meg. (�rtelemsz�en a $post v�ltoz�t itt nem�rdemes haszn�lni.)
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
