<?
include("config.php");
include("functions.php");
require_once("connection.php");

$command = substr($_SERVER["PATH_INFO"], 1);
if (strpos($_SERVER["PATH_INFO"], "/") !== false)
	$command = preg_replace('|^([^/]*)/.*|', '$1', $command);

if ($command == "")
	display_users();
elseif($command == "posts")
	display_post(preg_replace('|^[^/]*/([^/]*)|', '$1', substr($_SERVER["PATH_INFO"], 1)));
elseif(in_array($command, get_users(true)))
	display_user($command);
else
	print("Ismeretlen felhasználó: $command");

?>
