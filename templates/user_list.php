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
<li><a href="/register">Szeretnék saját blogot</a></li>
</ul>
</div>
<div class="box">
<h3>Legutolsó bejegyzések ></h3>
<ul>
<?
foreach($posts as $i)
	print("\t<li><a href=\"/posts/" . $i['id'] . "\">" . name2nick($i['name']) .
		"</a> (" . $i['letrehozas'] . ")</li>\n");
?>
</ul>
</div>
</div>
</div>
<div id="content">
<h1>Bloggerek listája</h1>
<ul>
<?
foreach($users as $i)
	print("\t<li><a href=\"/" . $i['name'] . "\">" . name2nick($i['name']) .
		"</a></li>\n");
?>
</ul>
<div align="center">
<div id="listingbar">
<span class="prev">
<?
if ($id != $prev and is_null($sterm))
{
	print("<a href=\"/list/$pprev\">&lt;&lt;- eleje</a> ");
	print("<a href=\"/list/$prev\">&lt;- előző</a><br>");
}
?>
</span>
<span class="next">
<?
if ($id != $next and is_null($sterm))
{
	print("<a href=\"/list/$next\">következő -&gt;</a> ");
	print("<a href=\"/list/$nnext\">vége -&gt;&gt;</a>");
}
?>
</span>
</div>
</div>
</div>
<div id="clear"></div>
<div id="secfooter"></div>
<div id="footer">
<span><? print($c_text); ?></span>
</div>
</div>
</body>
</html>
