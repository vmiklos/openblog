<?
	print("Jelenleg elérhetõ felhasználók: ");
	foreach($users as $i)
		print("<a href=\"" . $_SERVER["SCRIPT_NAME"] . "/" . 
			nick2name($i) . "\">$i</a>, ");
	print(".\n");
?>
