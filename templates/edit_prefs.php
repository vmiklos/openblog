<?
// blogtitle, email
print("
<form action=\"" . $user['name'] . "\" method=\"post\">
<input type=hidden name=id value=" . $user['id'] . ">
<table><tr><td>Beceneved:<td><input type=text name=displayname value=\"" . $user['displayname'] . "\"><br>
<tr><td>Blogod címe:<td><input type=text name=blogtitle value=\"" . $user['blogtitle'] . "\"><br>
<tr><td>Email címed:<td><input type=text name=email value=\"" . $user['email'] . "\"><br>
<tr><td>Bejegyzések a fõoldalon:<td><input type=text name=limit value=\"" . $user['limit'] . "\"><br>
<tr><td>Sablon:<td>
<select name=templateid>\n");
foreach($templates as $i)
{
	if ($i[0]==$user['templateid'])
		print("<option selected value=" . $i[0] . ">" . $i[1] . "\n");
	else
		print("<option value=" . $i[0] . ">" . $i[1] . "\n");
}
print("</select>
<tr><td><input type=\"submit\" value=\"Meg akarok változni!\">
</table>
</form>
")
?>
