<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>OpenBlog.hu</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<link href="/old/templates/fooldal/style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../old/rte/rte.js"></script>
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

<h1>Bejegyzés szerkesztése</h1>
<div class="form">
<?
	print("
	<form name=\"myform\" onsubmit=\"return submitForm();\" action=\"../edit\" method=\"post\">
	<p>A bejegyzés címe: <input type=text name=title value=\"" . $post['title'] . "\"></p>
	</div>
	<div class=\"rte\" align=\"center\">
	<script language=\"JavaScript\" type=\"text/javascript\">
	<!--
	function submitForm()
	{
		updateRTE('rte1');
		return true;
	}
	//Usage: initRTE(imagesPath, includesPath, cssFile)
	initRTE(\"../../old/rte/images/\", \"../../old/rte/\", \"../../old/rte/\");
	//-->
	</script>
	<script language=\"JavaScript\" type=\"text/javascript\">
	<!--
	//Usage: writeRichText(fieldname, html, width, height, buttons, readOnly)
	writeRichText('rte1', '" . $post['content'] . "', 615, 380, true, false);
	//-->
	</script>
	</div>
	<div class=\"form\">
	<input type=hidden name=postid value=" . $post['id'] . ">
	<p><input type=submit value=\"Küldés\">
	<input type=button value=\"Vissza az egész\" onClick=\"history.back()\"></p>
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
