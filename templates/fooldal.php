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
<h3>�j felhaszn�l�k ></h3>

<ul>
<?
foreach($users as $i)
	print("\t<li><a href=\"/" . $i['name'] . "\">" . name2nick($i['name']) .
		"</a></li>\n");
?>
</ul>
</div>
<div class="box">
<h3>Legutols� bejegyz�sek ></h3>
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
<h1>N�pszer� a blogol�s</h1>

<h2>2005.03.27. | vmiklos</h2>
<p>T�bb helyen olvastam m�r az online napl�, illetve a webnapl� fogalmak
haszn�lat�t. A weblogok eleinte val�ban olyan website-ok voltak, amelyek
egyfajta d�tum szerint vezetett online napl�k�nt m?k�dtek. </p>
<p>A blogok alapfunkci�ja ugyan ma is ez, de a fejl?d�s hat�s�ra sz�mos �j eszk�zzel,
tartalmi �s technol�giai megold�ssal gazdagodtak, �gy a webnapl� kifejez�s
olyan t�lzott leegyszer?s�t�s lenne, mintha a pr-t egyszer?en
k�zkapcsolatoknak nevezn�nk, egy sz� szerinti ford�t�st k�vet?en.</p>
</div>

<div class="new">
<h1>Slackware</h1>
<h2>2005.03.28. | djsmiley</h2>
<p>Tavaly okt�berben mer�lt fel el�sz�r, hogy Pat ak�r v�glegesen is
elt�vol�thatja a GNOME desktop �s fejleszt�i k�rnyeztet a Slackware-b�l. Az
indok a neh�zkes karbantarthat�s�g volt.
M�r nem kell azon gondolkozni, hogy vajon mikort�l ker�l elt�vol�t�sra a
GNOME, mert a mai nappal megt�rt�nt...
</p><p>
Ahogy a DistroWatch �rja a GNOME elt�vol�t�sra ker�lt a -current-b�l �s
teljes eg�sz�ben k�z�ss�gi karbantart�s �s terjeszt�s al� ker�lt. Mint Pat
�rja a logban, nem k�v�nja az elt�vol�t�s okait ism�t felsorolni. Mint �rja
nagyon r�g�ta foglalkoztatja a gondolat, hogy meg k�ne tenni. Szerinte t�bb
j� projekt is van, amely k�pes lesz Slackware GNOME-ot produk�lni.</p>

</div>

<div class="new">
<h1>N�pszer� a spam?</h1>
<h2>2005.03.29. | vmiklos</h2>
<p>A k�ztudat szerint ink�bb gy�l�ltnek tekintett spam a val�s�gban
rendk�v�l n�pszer�nek mutatkozik az internetes levelez�k k�r�ben. Legal�bbis
erre utal, hogy egy k�zelm�ltban elv�gzett kutat�s el�zetes eredm�nyei
szerint az elektronikus postafi�kkal rendelkez� emberek k�z�l kb. minden
harmadik rendszeresen kattint a k�retlen rekl�mlevelekben elhelyezett
linkeken, egy tized�k pedig v�s�rol is az azokban hirdetett term�kekb�l. 
</p><p>
A spamsz�r� szoftverek fejleszt�s�vel foglalkoz� Mirapoint �s a piackutat�
Radicati Group a napokban tette k�zz� k�zelm�ltbeli felm�r�s�nek eredm�ny�t,
amely meglep� sz�mokkal szolg�l a spam n�pszer�s�g�t illet�en. Az �ltal�nos
v�leked�s szerint ugyanis a k�retlen rekl�mlevelek sikerar�nya - azaz a
t�nylegesen v�s�rl�st gener�l� levelek az �sszes kik�ld�tt emailekre
vonatkoztatott sz�ma - valahol n�h�ny milliomod k�r�l mozoghat, a felm�r�s
adatai azonban enn�l nagys�grendekkel "jobb" sz�mokra utalnak.</p>
</div>

</div>
<div id="footer">
<? print($c_text); ?>
</div>

</div>
</body>
</html>
