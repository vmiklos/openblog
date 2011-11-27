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
<? print_ad(); ?>
</div>
<div id="left-wrapper">
<div id="left">
<div class="box">
<h3>Menü ></h3>
<ul>
<li><a href="/">Kezdőlap</a></li>
<li><a href="/list">Bloggerek listája</a></li>
</ul>
</div>
<div class="box">
<h3>Segítség ></h3>
<p>Ha előregyártott sablont szeretnél, máris választhatsz az előregyártott csodaszép sablonok közül, 
csak el kell döntened, hogy melyik illik leginkább a stílusodhoz.</p>
<p>Ha saját sablont szeretnél alkotni, válassz egyet, és a regisztráció után a beállításoknál rögtön nekikezdhetsz!</p>
</div>
</div>			
</div>
<div id="content">
<h1>Valálaszd ki a neked tetsző sablont</h1>
<?
foreach($templates as $i)
{
	print("<div class=\"style\"><p><strong>
		" . $i['templatetitle'] . "</strong></p>
		<a href=\"/register/" . $i['id'] . "\">
		<img src=\"/old/templates/" . $i['id'] . "/shot.png\" alt=\"" . $i['templatetitle'] . "\"></a><p>
		készítette: <a href=\"/" . $i['name'] . "\">" . nick2name($i['name']) . "</a></p>
		</div>\n");
}
?>
</div>
<div id="clear"></div>
<div id="secfooter"></div>
<div id="footer">
<span><? print($c_text); ?></span>
</div>
</div>
</body>
</html>
