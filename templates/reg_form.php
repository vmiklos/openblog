<html>
<body>
<form action="../register" method="post">
Loginn�v: <input type=text name=name><br>
Jelsz�: <input type=password name=passwd><br>
Jelsz� m�gegyszer: <input type=password name=passwd2><br>
Becen�v: <input type=text name=displayname><br>
A blogod neve: <input type=text name=blogtitle><br>
Ennyi bejegyz�s jelenjen meg az oldalon: <input type=text name=limit value=10><br>
Email c�med: <input type=text name=email><br>
D�rum form�tum: <input type=text name=date_format value="%Y.%m.%d. %H:%i"><br>
<? print("<input type=hidden name=tid value=" . $template['id'] . ">");?>
<input type=submit value="Regisztr�lok">
<input type=button value="M�gse akarok blogot" onClick="history.back()">
</form>
</body>
</html>
