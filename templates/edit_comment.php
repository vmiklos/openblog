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
<!--
<div id="left-wrapper">
<div id="left">

<div class="box">
<h3>OpenBlog ></h3>
<ul>
<li>lap</li>
</ul>
</div>
</div>
</div>
-->
<div id="content" class="plain">
<h1>�j hozz�sz�l�s</h1>
<div class="form">
<?
	print("$errstr
	<form action=\"../comment\" method=\"post\">
	<input type=hidden name=postid value=$postid>
	<p align=\"left\">N�v: <input type=text name=name value=\"".$_POST['name']."\"></p>
	<p align=\"left\">El�rhet�s�g (e-mail, honlap): <input type=text name=contact value=\"".$_POST['contact']."\"></p>
	<p align=\"left\">A hozz�sz�l�s c�me: <input type=text name=title value=\"".$_POST['title']."\"></p>
	<p align=\"left\">A hozz�sz�l�s: </p>
	<div align=\"left\">
	<textarea rows=\"5\" cols=\"55\" name=\"content\">".$_POST['content']."</textarea><br />\n");
	if($user['want_antispam'])
	print("Olvasd el: <img src=\"/authimage\" width=\"155\" height=\"50\" alt=\"authimage\" border=\"1\" /><br />
	�rd ide: <input type=\"text\" name=\"code\" size=\"20\" /><br />");
	print("</div>
	<input type=hidden name=postid value=" . $post['id'] . ">
	<p><input type=submit value=\"K�ld�s\">
	<input type=button value=\"Vissza az eg�sz\" onClick=\"history.back()\"></p>
	</form>
	");
?>
</div>
</div>
<div id="clear"></div>
<div id="footer">
<span><? print($c_text); ?></span>
</div>
</body>
</html>
