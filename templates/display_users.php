<?
	print("Jelenleg el�rhet� felhaszn�l�k: ");
	foreach($users as $i)
		print("<a href=\"" . $site_root . "/" . 
			nick2name($i) . "\">$i</a>, ");
	print(".\n");
?>
