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
<h3>Men� ></h3>
<ul>
<li><a href="/">Kezd�lap</a></li>
<li><a href="/list">Bloggerek list�ja</a></li>
</ul>
</div>
<div class="box">
<h3>Seg�ts�g ></h3>
<p>Ha el�regy�rtott sablont szeretn�l, m�ris v�laszthatsz az el�regy�rtott csodasz�p sablonok k�z�l, 
csak el kell d�ntened, hogy melyik illik legink�bb a st�lusodhoz.</p>
<p>Ha saj�t sablont szeretn�l alkotni, v�lassz egyet, �s a regisztr�ci� ut�n a be�ll�t�sokn�l r�gt�n nekikezdhetsz!</p>
</div>
</div>			
</div>
<div id="content">
<h1>Val�laszd ki a neked tetsz� sablont</h1>
<?
foreach($templates as $i)
{
	print("<div class=\"style\"><p><strong>
		" . $i['templatetitle'] . "</strong></p>
		<a href=\"/register/" . $i['id'] . "\">
		<img src=\"/old/templates/" . $i['id'] . "/shot.png\" alt=\"" . $i['templatetitle'] . "\"></a><p>
		k�sz�tette: <a href=\"/" . $i['name'] . "\">" . nick2name($i['name']) . "</a></p>
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
