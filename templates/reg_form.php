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
<div class="box">
<h3>Menü ></h3>
<ul>
<li><a href="/">Főoldal</a></li>
<li><a href="/register">Stílus kiválasztása</a></li>
<li><a href="/list">Bloggerek listája</a></li>
</ul>
</div>
<h3>Segítség ></h3>
<p>A loginnév csak ékezetmentes kis és nagybetűket, valamint számokat tartalmazhat.</p>
<p>A beceneved lesz mindeütt megjelenítve, ez már bármilyen karaktert tartalmazhat.</p>
<p>Email címnek saját érdekedben olyat címet adj meg, amit ténylegesen olvasol is!</p>
</div>
</div>
</div>
<div id="content">
<h1>Add meg az adataidat</h1>
<div class="form">
<form action="../register" method="post">
<table>
<tr><td class="left">Loginnév:</td><td class="right"><input type=text name=name></td></tr>
<tr><td class="left">Jelszó:</td><td class="right"><input type=password name=passwd></td></tr>
<tr><td class="left">Jelszó mégegyszer:</td><td class="right"><input type=password name=passwd2></td></tr>
<tr><td class="left">Becenév:</td><td class="right"><input type=text name=displayname></td></tr>
<tr><td class="left">A blogod neve:</td><td class="right"><input type=text name=blogtitle></td></tr>
<tr><td class="left">Ennyi bejegyzés jelenjen meg az oldalon:</td><td class="right"><input type=text name=limit value=10></td></tr>
<tr><td class="left">Email címed:</td><td class="right"><input type=text name=email></td></tr>
<tr><td class="left">Dátum formátum:</td><td class="right"><input type=text name=date_format value="%Y.%m.%d. %H:%i"></td></tr>
</table>
<? print("<input type=hidden name=tid value=" . $template['id'] . ">");?>
<p>
<input type=submit value="Regisztrálok">
</p>
</form>
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
