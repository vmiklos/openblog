<?
// blogtitle, email
print("
<form action=\"" . $user['name'] . "\" method=\"post\">
<input type=hidden name=id value=" . $user['id'] . ">
<table><tr><td>Beceneved:<td><input type=text name=displayname value=\"" . $user['displayname'] . "\"><br>
<tr><td>Blogod c�me:<td><input type=text name=blogtitle value=\"" . $user['blogtitle'] . "\"><br>
<tr><td>Email c�med:<td><input type=text name=email value=\"" . $user['email'] . "\"><br>
<tr><td>Bejegyz�sek a f�oldalon:<td><input type=text name=limit value=\"" . $user['limit'] . "\"><br>
<tr><td>Sablonod neve:<td><input type=text name=templatetitle value=\"" . $user['templatetitle'] . "\"><br>
<tr><td valign=top>Sablon:<td><textarea cols=80 rows=20 name=\"template\">
" . $user['template'] . "</textarea><br>
<tr><td><td><input type=\"submit\" value=\"Meg akarok v�ltozni!\">
</table>
</form>
")
?>
