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
<h3>Men� ></h3>
<ul>
<li><a href="/">F�oldal</a></li>
<li><a href="/register">St�lus kiv�laszt�sa</a></li>
<li><a href="/list">Bloggerek list�ja</a></li>
</ul>
</div>
<h3>Seg�ts�g ></h3>
<p>A loginn�v csak �kezetmentes kis �s nagybet�ket, valamint sz�mokat tartalmazhat.</p>
<p>A beceneved lesz minde�tt megjelen�tve, ez m�r b�rmilyen karaktert tartalmazhat.</p>
<p>Email c�mnek saj�t �rdekedben olyat c�met adj meg, amit t�nylegesen olvasol is!</p>
</div>
</div>
</div>
<div id="content">
<h1>Add meg az adataidat</h1>
<div class="form">
<form action="../register" method="post">
<table>
<tr><td class="left">Loginn�v:</td><td class="right"><input type=text name=name></td></tr>
<tr><td class="left">Jelsz�:</td><td class="right"><input type=password name=passwd></td></tr>
<tr><td class="left">Jelsz� m�gegyszer:</td><td class="right"><input type=password name=passwd2></td></tr>
<tr><td class="left">Becen�v:</td><td class="right"><input type=text name=displayname></td></tr>
<tr><td class="left">A blogod neve:</td><td class="right"><input type=text name=blogtitle></td></tr>
<tr><td class="left">Ennyi bejegyz�s jelenjen meg az oldalon:</td><td class="right"><input type=text name=limit value=10></td></tr>
<tr><td class="left">Email c�med:</td><td class="right"><input type=text name=email></td></tr>
<tr><td class="left">D�tum form�tum:</td><td class="right"><input type=text name=date_format value="%Y.%m.%d. %H:%i"></td></tr>
</table>
<? print("<input type=hidden name=tid value=" . $template['id'] . ">");?>
<p>
<input type=submit value="Regisztr�lok">
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
