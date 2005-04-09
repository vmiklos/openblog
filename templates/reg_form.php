<html>
<body>
<form action="../register" method="post">
Loginnév: <input type=text name=name><br>
Jelszó: <input type=password name=passwd><br>
Jelszó mégegyszer: <input type=password name=passwd2><br>
Becenév: <input type=text name=displayname><br>
A blogod neve: <input type=text name=blogtitle><br>
Ennyi bejegyzés jelenjen meg az oldalon: <input type=text name=limit value=10><br>
Email címed: <input type=text name=email><br>
Dárum formátum: <input type=text name=date_format value="%Y.%m.%d. %H:%i"><br>
<? print("<input type=hidden name=tid value=" . $template['id'] . ">");?>
<input type=submit value="Regisztrálok">
<input type=button value="Mégse akarok blogot" onClick="history.back()">
</form>
</body>
</html>
