<?
	print("Jelenleg el�rhet� felhaszn�l�k: ");
	foreach($users as $i)
		print("<a href=\"" . $_SERVER["SCRIPT_NAME"] . "/" . 
			nick2name($i) . "\">$i</a>, ");
	print(".\n");
?>
