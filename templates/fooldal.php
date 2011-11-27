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
<li><a href="/list">Bloggerek listája</a></li>
</ul>
</div>
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
<h3>Legnépszerűbb blogok ></h3>

<ul>
<?
foreach($toplist as $i)
	print("\t<li><a href=\"/" . $i['name'] . "\">" . $i['displayname'] .
		"</a> (" . $i['hits'] . ")</li>\n");
?>
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
<?
foreach($news as $i)
{
	print("<div class=\"new\">
	<h1>" . $i['cim'] . "</h1>
	<h2>" . $i['letrehozas'] . " | " . $i['author'] . "</h2>
	" . $i['content'] . "
	</div>");
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
