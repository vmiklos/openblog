<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>OpenBlog.hu</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<link href="/old/templates/fooldal/style.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="page-wrapper">
<div id="page-head">
</div>
<div id="left-wrapper">
<div id="left">
<div class="box">
<h3>Új felhasználók ></h3>

<ul>
<?
foreach($users as $i)
	print("\t<li><a href=\"/" . $i['name'] . "\">" . name2nick($i['name']) .
		"</a></li>\n");
?>
</ul>
</div>
<div class="box">
<h3>Legutolsó bejegyzések ></h3>
<ul>
<?
foreach($posts as $i)
	print("\t<li><a href=\"/" . $i['name'] . "\">" . name2nick($i['name']) .
		"</a> (" . $i['letrehozas'] . ")</li>\n");
?>
</ul>
</div>
</div>
</div>
<div id="content">
<div class="new">
<h1>Népszerű a blogolás</h1>

<h2>2005.03.27. | vmiklos</h2>
<p>Több helyen olvastam már az online napló, illetve a webnapló fogalmak
használatát. A weblogok eleinte valóban olyan website-ok voltak, amelyek
egyfajta dátum szerint vezetett online naplóként m?ködtek. </p>
<p>A blogok alapfunkciója ugyan ma is ez, de a fejl?dés hatására számos új eszközzel,
tartalmi és technológiai megoldással gazdagodtak, így a webnapló kifejezés
olyan túlzott leegyszer?sítés lenne, mintha a pr-t egyszer?en
közkapcsolatoknak neveznénk, egy szó szerinti fordítást követ?en.</p>
</div>

<div class="new">
<h1>Slackware</h1>
<h2>2005.03.28. | djsmiley</h2>
<p>Tavaly októberben merült fel először, hogy Pat akár véglegesen is
eltávolíthatja a GNOME desktop és fejlesztői környeztet a Slackware-ből. Az
indok a nehézkes karbantarthatóság volt.
Már nem kell azon gondolkozni, hogy vajon mikortól kerül eltávolításra a
GNOME, mert a mai nappal megtörtént...
</p><p>
Ahogy a DistroWatch írja a GNOME eltávolításra került a -current-ből és
teljes egészében közösségi karbantartás és terjesztés alá került. Mint Pat
írja a logban, nem kívánja az eltávolítás okait ismét felsorolni. Mint írja
nagyon régóta foglalkoztatja a gondolat, hogy meg kéne tenni. Szerinte több
jó projekt is van, amely képes lesz Slackware GNOME-ot produkálni.</p>

</div>

<div class="new">
<h1>Népszerű a spam?</h1>
<h2>2005.03.29. | vmiklos</h2>
<p>A köztudat szerint inkább gyűlöltnek tekintett spam a valóságban
rendkívül népszerűnek mutatkozik az internetes levelezők körében. Legalábbis
erre utal, hogy egy közelmúltban elvégzett kutatás előzetes eredményei
szerint az elektronikus postafiókkal rendelkező emberek közül kb. minden
harmadik rendszeresen kattint a kéretlen reklámlevelekben elhelyezett
linkeken, egy tizedük pedig vásárol is az azokban hirdetett termékekből. 
</p><p>
A spamszűrő szoftverek fejlesztésével foglalkozó Mirapoint és a piackutató
Radicati Group a napokban tette közzé közelmúltbeli felmérésének eredményét,
amely meglepő számokkal szolgál a spam népszerűségét illetően. Az általános
vélekedés szerint ugyanis a kéretlen reklámlevelek sikeraránya - azaz a
ténylegesen vásárlást generáló levelek az összes kiküldött emailekre
vonatkoztatott száma - valahol néhány milliomod körül mozoghat, a felmérés
adatai azonban ennél nagyságrendekkel "jobb" számokra utalnak.</p>
</div>

</div>
<div id="footer">
<? print($c_text); ?>
</div>

</div>
</body>
</html>
