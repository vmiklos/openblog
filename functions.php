<?

function vd($input)
{
	print("<pre>");
	var_dump($input);
	print("</pre>");
}

if (!function_exists('file_get_contents')) 
{ 
	function file_get_contents($filename, $use_include_path = 0) 
	{ 
		$file = @fopen($filename, 'rb', $use_include_path); 
		$data = "";
		if ($file) 
		{ 
			while (!feof($file)) $data .= fread($file, 1024); 
			fclose($file); 
		} 
		return $data; 
	} 
}

if (!function_exists('file_put_contents'))
{
	define('FILE_APPEND', 1);
	function file_put_contents($filename, $content, $flags = 0)
	{
		if (!($file = fopen($filename, ($flags & FILE_APPEND) ? 'a' : 'w'))) return false;
		$n = fwrite($file, $content);
		fclose($file);
		return $n ? $n : false;
	}
}

// ha true, akkor nicket nem ad
function get_users($strict=false)
{
	$query = 'SELECT name, displayname FROM users';
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	while ($i = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		if (!is_null($i['displayname']) and $strict==false)
			$users[]=$i['displayname'];
		else
			$users[]=$i['name'];
	}
	mysql_free_result($result);
	return ($users);
}

function display_users()
{
	$users=get_users();
	include("templates/display_users.php");
}

function name2nick($input)
{
	$query = "SELECT displayname FROM users WHERE name='$input'";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$user = mysql_fetch_array($result, MYSQL_ASSOC);
	mysql_free_result($result);
	if (is_null($user['displayname']))
		return $input;
	else
		return $user['displayname'];
}

function nick2name($input)
{
	$query = "SELECT name FROM users WHERE displayname='$input'";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$user = mysql_fetch_array($result, MYSQL_ASSOC);
	mysql_free_result($result);
	if (is_null($user['name']))
		return $input;
	else
		return $user['name'];
}

// header, post v footer lehet. default: post

function get_template($id, $type)
{
	$query = "SELECT content  FROM templates WHERE id='$id'";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$template = mysql_fetch_array($result, MYSQL_ASSOC);
	mysql_free_result($result);
	
	$less = explode("<post>", $template['content']);
	if ($type == "header")
		return $less[0];
	else
	{
		$less = explode("</post>", $less[1]);
		if ($type == "footer")
				return $less[1];
		else
			return $less[0]; // post
	}
}

function display_user($name)
{
	$query = "SELECT id, displayname, templateid, blogtitle FROM users WHERE name='$name'";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$user = mysql_fetch_array($result, MYSQL_ASSOC);
	mysql_free_result($result);
	$name=name2nick($name);

	$query = "SELECT id  FROM posts WHERE userid='" . $user['id'] . "' ORDER BY letrehozas DESC";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	while ($i = mysql_fetch_array($result, MYSQL_ASSOC))
		$posts[] = $i['id'];
	mysql_free_result($result);

	// a $cuccok lecserelese
	$csere = array(
		'$nev' => $name,
		'$blogcim' => $user['blogtitle'],
		'$email' => $user['email']
	);
	
	print(strtr(get_template($user['templateid'], "header"), $csere));
	foreach($posts as $i)
		display_post($i, true);
	print(strtr(get_template($user['templateid'], "footer"), $csere));
}

function delete_post($postid)
{
	is_numeric($postid) or die("Nem szám: $postid");
	$query = "SELECT userid FROM posts WHERE id=$postid";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$post = mysql_fetch_array($result, MYSQL_ASSOC);
	mysql_free_result($result);
	$query = "SELECT name, passwd FROM users where id=" . $post['userid'];
	$result = mysql_query($query) or die("Nincs is ilyen bejegyzés!");
	$user = mysql_fetch_array($result, MYSQL_ASSOC);
	mysql_free_result($result);
	if( !isset($_SERVER['PHP_AUTH_USER']) )
	{
		header("WWW-Authenticate: Basic realm=\"Bejegyzés törlése\"");
		header('HTTP/1.0 401 Unauthorized');
		die("A törléshez jelszó megadása szükséges!");
	}
	else
	{
		if ($user['name']==$_SERVER['PHP_AUTH_USER'] and 
			md5($_SERVER['PHP_AUTH_PW'])==$user['passwd'])
		{
			$query = "DELETE FROM posts WHERE id=" . $postid;
			$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
			print("Töröltem!");
		}
		else
			die("Nem megfelelő felhasználónév vagy jelszó!");
	}
}

// ha pure true, akkor nincs header meg footer

function display_post($postid, $pure=false)
{
	global $site_root;
	is_numeric($postid) or die("Nem szám: $postid");
	$query = "SELECT userid, content, letrehozas FROM posts WHERE id=$postid";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$post = mysql_fetch_array($result, MYSQL_ASSOC);
	mysql_free_result($result);
	$query = "SELECT name, displayname, templateid FROM users WHERE id=" . $post['userid'];
	// $result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$result = mysql_query($query) or die("Nincs ilyen post!");
	$user = mysql_fetch_array($result, MYSQL_ASSOC);
	mysql_free_result($result);
	if (!is_null($user['displayname']))
		$post['user']=$user['displayname'];
	else
		$post['user']=$user['name'];
	
	// a $cuccok lecserelese, itt vannak post-specifikus cuccok is
	$csere = array(
		'$nev' => $name,
		'$blogcim' => $user['blogtitle'],
		'$email' => $user['email'],
		'$posturl' => $_SERVER["SCRIPT_NAME"] . "/posts/$postid",
		'$deleteurl' => $_SERVER["SCRIPT_NAME"] . "/delete/$postid",
		'$ido' => $post['letrehozas'],
		'$post' => $post['content']
	);
	
	if (!$pure)
		print(strtr(get_template($user['templateid'], "header"), $csere));
	print(strtr(get_template($user['templateid'], "post"), $csere));
	if (!$pure)
		print(strtr(get_template($user['templateid'], "footer"), $csere));
}

function handle_upload($input)
{
	if (!is_numeric($input))
		display_upload_form();
	else
	{
		$query = "SELECT name, type, data FROM uploads WHERE id=" . $input;
		$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
		$upload = mysql_fetch_array($result, MYSQL_ASSOC) or die('Nincs ilyen feltölés!');
		mysql_free_result($result);
		header("Content-Type: " . $upload['type']);
		header("Content-Length: " . strlen($upload['data']));
		header("Content-Disposition: attachment; filename=\"" . $upload['name']. "\"");
		print($upload['data']);
	}
}

function display_upload_form()
{
	global $upload_dir;
	if (!count($_FILES))
		include("templates/upload_form.php");
	else
	{
		$query="INSERT INTO uploads (name, type, ownerid, data) VALUES('" . $_FILES['feltoltes']['name'] . "', '" . $_FILES['feltoltes']['type'] . "', '1', '" . addslashes(file_get_contents($_FILES['feltoltes']['tmp_name'])) . "');";
		$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
		if(!@unlink($_FILES['feltoltes']['tmp_name']))
		{
			$query="delete from uploads where id=" . mysql_insert_id();
			$result = mysql_query($query) or die('Hiba a lekérdezésben: ' .mysql_error());
			die("Túl nagy a feltölteni kívánt file!");
		}
		include("templates/upload_success.php");
	}
}
?>
