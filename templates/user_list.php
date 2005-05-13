<?
foreach($users as $i)
	print("\t<li><a href=\"/" . $i['name'] . "\">" . name2nick($i['name']) .
		"</a></li>\n");
if ($id != $prev)
{
	print("<a href=\"/list/$pprev\">&lt;&lt;- eleje</a> ");
	print("<a href=\"/list/$prev\">&lt;- elozo</a><br>");
}
if ($id != $next)
{
	print("<a href=\"/list/$next\">kovetkezo -&gt;</a> ");
	print("<a href=\"/list/$nnext\">vege -&gt;&gt;</a>");
}
?>
