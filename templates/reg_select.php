<html><body>
V�lassz, h melyik kell neked:
<table><tr><td>
<?
foreach($templates as $i)
{
	print("<div style=\"float: left;\">
	<a href=\"/register/" . $i['id'] . "\"><img align=left src=\"/old/templates/" . $i['id'] . "/shot.png\"></a>
		" . $i['templatetitle'] . "<br>
		k�sz�tette: <a href=\"/" . $i['name'] . "\">" . nick2name($i['name']) . "</a><br>
		</div>\n");
}
?>
</td></tr></table>
</body></html>
