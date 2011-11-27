<?
include("config.php");
include("functions.php");
require_once("connection.php");

/* a kapott url vmi openblog.hu/foo/bar lesz
 * foot benyomjuk $handle-be, bart meg $paramba */
$handle = substr($_SERVER["PATH_INFO"], 1);
if (strpos($_SERVER["PATH_INFO"], "/") !== false)
	$handle = preg_replace('|^([^/]*)/.*|', '$1', $handle);

$param = preg_replace('|^[^/]*/([^/]*)|', '$1',
	substr($_SERVER["PATH_INFO"], 1));

if ($handle == "")
	handle_news();
elseif(in_array($handle, $handlers))
	call_user_func("handle_$handle", $param);
else
	handle_user($handle, $param);
?>
