<?

function vd($input)
{
	print("<pre>");
	var_dump($input);
	print("</pre>");
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

	$query = "SELECT id  FROM posts WHERE userid='" . $user['id'] . "'";
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
		display_post($i);
	print(strtr(get_template($user['templateid'], "footer"), $csere));
}

function display_post($postid)
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
		'$ido' => $post['letrehozas'],
		'$post' => $post['content']
	);
	
	print(strtr(get_template($user['templateid'], "post"), $csere));
}

?>
