<?
// blogtitle, email
print("
<form action=\"" . $user['name'] . "\" method=\"post\">
<input type=hidden name=id value=" . $user['id'] . ">
<table><tr><td>Beceneved:<td><input type=text name=displayname value=\"" . $user['displayname'] . "\"><br>
<tr><td>Blogod címe:<td><input type=text name=blogtitle value=\"" . $user['blogtitle'] . "\"><br>
<tr><td>Email címed:<td><input type=text name=email value=\"" . $user['email'] . "\"><br>
<tr><td>Hány bejegyzés jelenjen<br>meg a fõoldalon:<td valign=bottom><input type=text name=limit value=\"" . $user['limit'] . "\"><br>
<tr><td><input type=\"submit\" value=\"Meg akarok változni!\">
</table>
</form>
<a href=\"$site_root/" . $user['name'] . "\">Vissza a fõlapra</a>
")
?>
