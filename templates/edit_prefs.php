<?
// blogtitle, email
print("
<form action=\"" . $user['name'] . "\" method=\"post\">
<input type=hidden name=id value=" . $user['id'] . ">
<table><tr><td>Beceneved:<td><input type=text name=displayname value=\"" . $user['displayname'] . "\"><br>
<tr><td>Blogod c�me:<td><input type=text name=blogtitle value=\"" . $user['blogtitle'] . "\"><br>
<tr><td>Email c�med:<td><input type=text name=email value=\"" . $user['email'] . "\"><br>
<tr><td>H�ny bejegyz�s jelenjen<br>meg a f�oldalon:<td valign=bottom><input type=text name=limit value=\"" . $user['limit'] . "\"><br>
<tr><td><input type=\"submit\" value=\"Meg akarok v�ltozni!\">
</table>
</form>
<a href=\"$site_root/" . $user['name'] . "\">Vissza a f�lapra</a>
")
?>
