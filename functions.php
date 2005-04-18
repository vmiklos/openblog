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

function display_fooldal()
{
	global $site_root, $users_limit, $posts_limit, $news_limit,
		$date_format_fooldalipost, $date_format_hir, $c_text;
	
	$query = "SELECT id, name, displayname FROM users";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	while ($i = mysql_fetch_array($result, MYSQL_ASSOC))
		$userdb[$i['id']]=$i;
	mysql_free_result($result);
	$query = "SELECT id, name, displayname, blogtitle, email, registration_date FROM users ORDER BY registration_date DESC LIMIT $users_limit";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	while ($i = mysql_fetch_array($result, MYSQL_ASSOC))
		$users[]=$i;
	mysql_free_result($result);
	$query = "SELECT id, userid, title, date_format(letrehozas, '$date_format_fooldalipost') FROM posts ORDER BY letrehozas DESC LIMIT $posts_limit";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	while ($i = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		$i['name']=$userdb[$i['userid']]['name'];
		$i['letrehozas']=$i["date_format(letrehozas, '$date_format_fooldalipost')"];
		$posts[]=$i;
	}
	mysql_free_result($result);
	$query = "SELECT id, cim, content, authorid, date_format(letrehozas, '$date_format_hir') FROM news WHERE active=1 ORDER BY letrehozas DESC LIMIT $news_limit";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	while ($i = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		$i['author']=name2nick($userdb[$i['authorid']]['name']);
		$i['letrehozas']=$i["date_format(letrehozas, '$date_format_hir')"];
		$news[]=$i;
	}
	mysql_free_result($result);
	include("templates/fooldal.php");
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
	global $site_root, $c_text;
	$query = "SELECT id, email, displayname, blogtitle, `limit` FROM users WHERE name='$name'";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$user = mysql_fetch_array($result, MYSQL_ASSOC);
	mysql_free_result($result);
	$nick=name2nick($name);

	$query = "SELECT id  FROM posts WHERE userid='" . $user['id'] . "' ORDER BY letrehozas DESC LIMIT " . $user['limit'];
	$result = mysql_query($query) or die("Ismeretlen felhasználó: $name");
	push_user($user['id'], $user['hits']);
	while ($i = mysql_fetch_array($result, MYSQL_ASSOC))
		$posts[] = $i['id'];
	mysql_free_result($result);

	// a $cuccok lecserelese
	$csere = array(
		'$fooldal' => "$site_root/$name",
		'$archiveurl' => "$site_root/archives/$name",
		'$newurl' => "$site_root/new/$name",
		'$nev' => $nick,
		'$usernev' => $name,
		'$blogcim' => $user['blogtitle'],
		'$email' => $user['email'],
		'$szamlalo' => $user['hits'],
		'$copyright' => $c_text
	);
	
	print(strtr(get_template($user['templateid'], "header"), $csere));
	foreach($posts as $i)
		display_post($i, true);
	print(strtr(get_template($user['templateid'], "footer"), $csere));
}

function delete_post($postid)
{
	global $site_root;
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
			header("Location: $site_root/index.php/" . $user['name']);
		}
		else
		{
			$realm = mt_rand( 1, 1000000000 );
			header( 'WWW-Authenticate: Basic realm='.$realm );
			die("Nem megfelelõ felhasználónév vagy jelszó!");
		}
	}
}

// ha pure true, akkor nincs header meg footer

function display_post($postid, $pure=false)
{
	global $site_root;
	is_numeric($postid) or die("Nem szám: $postid");
	$query = "SELECT userid, content, title, date_format(letrehozas, '$date_format') FROM posts WHERE id=$postid";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$post = mysql_fetch_array($result, MYSQL_ASSOC);
	mysql_free_result($result);
	$query = "SELECT id, name, email, displayname, date_format, blogtitle FROM users WHERE id=" . $post['userid'];
	// $result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$result = mysql_query($query) or die("Nincs ilyen post!");
	$user = mysql_fetch_array($result, MYSQL_ASSOC);
	mysql_free_result($result);
	if ($pure==false)
		push_user($user['id'], $user['hits']);
	$query = "SELECT date_format(letrehozas, '" . $user['date_format'] . "') FROM posts WHERE id=$postid";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$post2=mysql_fetch_array($result, MYSQL_ASSOC);
	$post['letrehozas']=$post2["date_format(letrehozas, '" . $user['date_format'] . "')"];
	mysql_free_result($result);
	if (!is_null($user['displayname']))
		$post['user']=$user['displayname'];
	else
		$post['user']=$user['name'];
	
	// a $cuccok lecserelese, itt vannak post-specifikus cuccok is
	$csere = array(
		'$fooldal' => "$site_root/" . $user['name'],
		'$archiveurl' => "$site_root/archives/" . $user['name'],
		'$newurl' => "$site_root/new/" . $user['name'],
		'$nev' => $name,
		'$usernev' => $user['name'],
		'$blogcim' => $user['blogtitle'],
		'$email' => $user['email'],
		'$szamlalo' => $user['hits'],
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

function edit_post($postid)
{
	if (count($_POST))
	{
	global $site_root;
		is_numeric($_POST['postid']) or die("Nem szám: " . $_POST['postid']);
		$query = "SELECT id, userid, title, content FROM posts WHERE id=" . $_POST['postid'];
		$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
		$post = mysql_fetch_array($result, MYSQL_ASSOC);
		mysql_free_result($result);
		$query = "SELECT name, passwd FROM users where id=" . $post['userid'];
		$result = mysql_query($query) or die("Nincs is ilyen bejegyzés!");
		$user = mysql_fetch_array($result, MYSQL_ASSOC);
		mysql_free_result($result);
			if( !isset($_SERVER['PHP_AUTH_USER']) )
			{
				header("WWW-Authenticate: Basic realm=\"Bejegyzés szerkesztése\"");
				header('HTTP/1.0 401 Unauthorized');
				die("A szerkesztéshez jelszó megadása szükséges!");
			}
			else
			{
				if ($user['name']==$_SERVER['PHP_AUTH_USER'] and 
					md5($_SERVER['PHP_AUTH_PW'])==$user['passwd'])
				{
					$query = "UPDATE posts SET content = '" . addslashes(stripslashes($_POST['content'])) . "' " .	"WHERE id =" . $_POST['postid'];
					$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
					print("Betettem!");
				}
			else
				die("Nem megfelelõ felhasználónév vagy jelszó!");
		}
	}
			{
				$realm = mt_rand( 1, 1000000000 );
				header( 'WWW-Authenticate: Basic realm='.$realm );
	else
			}
	{
		is_numeric($postid) or die("Nem szám: $postid");
		$query = "SELECT id, userid, title, content FROM posts WHERE id=$postid";
		$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
		$post = mysql_fetch_array($result, MYSQL_ASSOC);
		mysql_free_result($result);
		include("templates/edit_form.php");
	}
}

?>

function display_archives($usernev)
{
	global $date_format, $site_root, $c_text;
	$query = "SELECT * FROM users WHERE name='$usernev'";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$user = mysql_fetch_array($result, MYSQL_ASSOC);
	mysql_free_result($result);
	
	if ($user['id']==null)
		die("Nincs ilyen felhaszáló: $usernev");
	$query = "SELECT id, title, content, date_format(letrehozas, \"$date_format\") FROM posts WHERE userid=" . $user['id'];
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	while ($i = mysql_fetch_array($result, MYSQL_ASSOC))
		$posts[] = $i;
	mysql_free_result($result);
	foreach ($posts as $key => $value)
		$posts[$key]['letrehozas'] = 
			$value["date_format(letrehozas, \"$date_format\")"];
	$honapok = array();
	foreach($posts as $i)
		if (!in_array($i['letrehozas'], $honapok))
			$honapok[]=$i['letrehozas'];
			
	// a $cuccok lecserelese
	$csere = array(
		'$fooldal' => "$site_root/" . $user['name'],
		'$archiveurl' => "$site_root/archives/" . $user['name'],
		'$newurl' => "$site_root/new/" . $user['name'],
		'$prefsurl' => "$site_root/prefs/" . $user['name'],
		'$nev' => name2nick($user['name']),
		'$usernev' => $user['name'],
		'$blogcim' => $user['blogtitle'],
		'$email' => $user['email'],
		'$copyright' => $c_text
		'$szamlalo' => $user['hits'],
	);
	
	print(strtr(get_archivetemplate($user['id'], "header"), $csere));
	foreach($honapok as $i)
		display_archivemonth($user, $i);
	print(strtr(get_archivetemplate($user['id'], "footer"), $csere));
}

function first_words($input)
{
	global $postcim_lenght;
	$words = explode(' ', strip_tags($input));
	$words = array_slice($words, 0, $postcim_lenght);
	return(implode(' ', $words) . "...");
}

function display_archivemonth($user, $honap)
{
	global $date_format, $site_root, $date_format_display;
	$userid=$user['id'];
	$query="SELECT * FROM posts WHERE date_format(letrehozas, \"$date_format\" ) = \"$honap\" AND userid =$userid";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	while ($i = mysql_fetch_array($result, MYSQL_ASSOC))
		$posts[] = $i;
	mysql_free_result($result);
	
	// a $cuccok lecserelese
	$csere = array(
	
	$query = "SELECT UNIX_TIMESTAMP(letrehozas) FROM posts WHERE id=" . $posts[0]['id'];
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$honap = mysql_fetch_array($result, MYSQL_ASSOC);
	mysql_free_result($result);
	$honap = strftime($date_format_display, $honap['UNIX_TIMESTAMP(letrehozas)']);
		'$fooldal' => "$site_root/" . $user['name'],
		'$newurl' => "$site_root/new/" . $user['name'],
		'$prefsurl' => "$site_root/prefs/" . $user['name'],
		'$nev' => name2nick($user['name']),
		'$archiveurl' => "$site_root/archives/" . $user['name'],
		'$usernev' => $user['name'],
		'$blogcim' => $user['blogtitle'],
		'$email' => $user['email'],
		'$honap' => $honap
	);
	
	print(strtr(get_archivetemplate($userid, "monthheader"), $csere));
	foreach($posts as $i)
		print(get_archivetemplate($userid, "post"));
	print(strtr(get_archivetemplate($userid, "monthfooter"), $csere));
}

		if(is_null($i['title']))
			$i['title']=first_words($i['content']);
		
// header, monthheader, post, monthfooter v footer lehet. default: post
		'$szamlalo' => $user['hits'],

function get_archivetemplate($id, $type)
			'$archiveurl' => "$site_root/archives/" . $user['name'],
{
	$query = "SELECT archivetemplate from users WHERE id='$id'";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$user = mysql_fetch_array($result, MYSQL_ASSOC);
	mysql_free_result($result);
	
	switch ($type)
	{
	case "header":
		$less = explode("<month>", $user['archivetemplate']);
		return $less[0];
		break;
	case "monthheader":
		$less = explode("<month>", $user['archivetemplate']);
		$eless = explode("<post>", $less[1]);
		return $eless[0];
		break;
			'$szamlalo' => $user['hits'],
	case "post":
		$less = explode("<post>", $user['archivetemplate']);
		$eless = explode("</post>", $less[1]);
		return $eless[0];
		break;
	case "monthfooter":
		$less = explode("</month>", $user['archivetemplate']);
		$eless = explode("</post>", $less[0]);
		return $eless[1];
		break;
	case "footer":
		$less = explode("</month>", $user['archivetemplate']);
		return $less[1];
		break;
	}
}

function edit_prefs($usernev)
{
	global $site_root;
	
	if (count($_POST))
	{
		$query = "SELECT * FROM users WHERE id=" . $_POST['id'];
		$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
		$user = mysql_fetch_array($result, MYSQL_ASSOC);
		mysql_free_result($result);
		if ($user['id']==null)
			die("Nincs ilyen felhaszáló: $usernev");
		// jelszobekeres
		if( !isset($_SERVER['PHP_AUTH_USER']) )
		{
			header("WWW-Authenticate: Basic realm=\"Bejegyzés létrehozása\"");
			header('HTTP/1.0 401 Unauthorized');
			die("A létrehozáshoz jelszó megadása szükséges!");
		}
		else
		{
			if ($user['name']==$_SERVER['PHP_AUTH_USER'] and 
				md5($_SERVER['PHP_AUTH_PW'])==$user['passwd'])
			{
				if (isset($_POST['newpass']))
				{
					if (md5($_POST['newpass']) == md5($_POST['newpass2']))
					{
						$query = "UPDATE users SET passwd = '" . md5($_POST['newpass']) . "'
						WHERE id =" . $user['id'];
						$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
						header("Location: $site_root/" . $user['name']);
					}
					else
						die('A megadott két jelszó nem azonos!');
				}
				else
				{
					$query = "UPDATE users SET
					displayname = '" . addslashes(stripslashes($_POST['displayname'])) . "',
					blogtitle = '" . addslashes(stripslashes($_POST['blogtitle'])) . "',
					email = '" . addslashes(stripslashes($_POST['email'])) . "',
					templatetitle = '" . addslashes(stripslashes($_POST['templatetitle'])) . "',
					template = '" . addslashes(stripslashes($_POST['template'])) . "',
					date_format = '" . addslashes(stripslashes($_POST['date_format'])) . "',
					`limit` ='" . addslashes(stripslashes($_POST['limit'])) . "'
					WHERE id =" . $user['id'];
					$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
					header("Location: $site_root/" . $user['name']);
				}
			}
			else
				die("Nem megfelelõ felhasználónév vagy jelszó!");
		}
	}
	$query = "SELECT * FROM users WHERE name='$usernev'";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$user = mysql_fetch_array($result, MYSQL_ASSOC);
	mysql_free_result($result);
	
	if ($user['id']==null)
		die("Nincs ilyen felhaszáló: $usernev");

	$query = "SELECT id, nev FROM templates";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	while ($i = mysql_fetch_array($result, MYSQL_ASSOC))
					archivetemplate = '" . addslashes(stripslashes($_POST['archivetemplate'])) . "',
		$templates[]= array($i['id'], $i['nev']);
	
	include("templates/edit_prefs.php");
}

function create_post($usernev)
{
	global $site_root;
	
	$query = "SELECT * FROM users WHERE name='$usernev'";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$user = mysql_fetch_array($result, MYSQL_ASSOC);
	mysql_free_result($result);
	if ($user['id']==null)
		die("Nincs ilyen felhaszáló: $usernev");
	// jelszobekeres
	if( !isset($_SERVER['PHP_AUTH_USER']) )
	{
		header("WWW-Authenticate: Basic realm=\"Bejegyzés létrehozása\"");
		header('HTTP/1.0 401 Unauthorized');
			{
				$realm = mt_rand( 1, 1000000000 );
				header( 'WWW-Authenticate: Basic realm='.$realm );
		die("A létrehozáshoz jelszó megadása szükséges!");
			}
	}
	else
	{
		if ($user['name']==$_SERVER['PHP_AUTH_USER'] and 
			md5($_SERVER['PHP_AUTH_PW'])==$user['passwd'])
		{
			$query = " INSERT INTO posts
			(userid, title, content, letrehozas)
			VALUES ('" . $user['id'] . "', NULL , '', NOW())";
			$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
			header("Location: $site_root/index.php/edit/" . mysql_insert_id());
		}
		else
			die("Nem megfelelõ felhasználónév vagy jelszó!");
	}
}

function handle_register($theme)
{
	$query = 'SELECT * FROM `users` where `templatescrore`>5 LIMIT 0, 30';
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	while ($i = mysql_fetch_array($result, MYSQL_ASSOC))
		$templates[] = $i;
	mysql_free_result($result);
	$valid=false;
	foreach($templates as $i)
		if($i['id']==$theme)
			$valid=true;
	if ($theme=="register")
	{
		include("templates/reg_select.php");
	}
	elseif (!$valid)
		die("Nem választható ilyen téma!");
	else
	{
		print("ok");
	}
}
			if (md5($_POST['passwd'])!=md5($_POST['passwd2']))
			{
				include("templates/reg_failure.php");
				die();
			}
			$query = "SELECT * FROM users WHERE name='" . addslashes(stripslashes($_POST['name'])) . "'";
			$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
			if (mysql_num_rows($result)!=0)
			{
				$hiba="A megadott név már foglalt!";
				include("templates/reg_failure.php");
				die();
			}
			if($_POST['name'] == "")
			{
				$hiba="A felhasználónév megadása kötelezõ!";
				include("templates/reg_failure.php");
				die();
			}
			if(!preg_match("/^[a-zA-Z0-9]+$/", $_POST['name']))
			{
				$hiba="A loginnév csak ékezetmentes kis és nagybetûket, valamint számokat tartalmazhat!";
				include("templates/reg_failure.php");
				die();
			}

function click_ad($id)
{
	$query = "SELECT url, clicks FROM ads WHERE id=" . $id;
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$ad = mysql_fetch_array($result, MYSQL_ASSOC) or die('Nincs ilyen reklám!');
	mysql_free_result($result);
	header("Location: " . $ad['url']);
}
}

function push_user($id, $current)
{
	$query = "UPDATE users SET hits=" . ($current+1) . " WHERE id=" . $id;
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());