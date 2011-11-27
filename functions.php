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

function handle_news($id=null)
{
	global $site_root, $users_limit, $posts_limit, $news_limit,
		$date_format_fooldalipost, $date_format_hir, $c_text;
	
	$query = "SELECT id, name, displayname FROM users";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	while ($i = mysql_fetch_array($result, MYSQL_ASSOC))
		$userdb[$i['id']]=$i;
	mysql_free_result($result);
	$query = "SELECT name, displayname, hits FROM users ORDER BY hits DESC LIMIT $users_limit";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	while ($i = mysql_fetch_array($result, MYSQL_ASSOC))
		$toplist[]=$i;
	mysql_free_result($result);
	$query = "SELECT id, name, displayname, blogtitle, email, registration_date FROM users ORDER BY registration_date DESC LIMIT $users_limit";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	while ($i = mysql_fetch_array($result, MYSQL_ASSOC))
		$users[]=$i;
	mysql_free_result($result);
	$query = "SELECT id, userid, title, date_format(letrehozas, '$date_format_fooldalipost') FROM posts WHERE content != '' ORDER BY letrehozas DESC LIMIT $posts_limit";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	while ($i = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		$i['name']=$userdb[$i['userid']]['name'];
		$i['letrehozas']=$i["date_format(letrehozas, '$date_format_fooldalipost')"];
		$posts[]=$i;
	}
	mysql_free_result($result);
	if(is_null($id))
		$query = "SELECT id, cim, content, fullcontent, authorid, date_format(letrehozas, '$date_format_hir') FROM news WHERE active=1 ORDER BY letrehozas DESC LIMIT $news_limit";
	else
		$query = "SELECT id, cim, fullcontent as content, authorid, date_format(letrehozas, '$date_format_hir') FROM news WHERE active=1 and id=$id ORDER BY letrehozas DESC LIMIT $news_limit";
	$result = mysql_query($query) or die('Nincs ilyen hír!');
	while ($i = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		$i['author']=name2nick($userdb[$i['authorid']]['name']);
		$i['letrehozas']=$i["date_format(letrehozas, '$date_format_hir')"];
		$news[]=$i;
	}
	mysql_free_result($result);
	if(is_null($id))
		foreach($news as $key => $value)
			if($value['fullcontent']!="")
				$news[$key]['content'] .= "<p><a href=\"/news/" . $value['id'] . "\">tovább &gt;</a></p>";
	include("templates/fooldal.php");
}

function name2nick($input)
{
	$query = "SELECT displayname FROM users WHERE name='$input'";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$user = mysql_fetch_array($result, MYSQL_ASSOC);
	mysql_free_result($result);
	if ($user['displayname']=="")
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
	$query = "SELECT template from users WHERE id='$id'";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$user = mysql_fetch_array($result, MYSQL_ASSOC);
	mysql_free_result($result);
	
	$less = explode("<post>", $user['template']);
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

function handle_user($name, $param)
{
	global $site_root, $c_text;
	if($param != null and $name != $param)
		print("<!-- search -->\n");
	else
		print("<!-- normal -->\n");
	$query = "SELECT id, email, displayname, blogtitle, `limit`, hits FROM users WHERE name='$name'";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$user = mysql_fetch_array($result, MYSQL_ASSOC);
	mysql_free_result($result);
	$nick=name2nick($name);

	if($param != null and $name != $param)
		$squery="and content like '%$param%' ";
	else
		$lquery="LIMIT " . $user['limit'];
	$query = "SELECT id  FROM posts WHERE userid='" . $user['id'] . "' and content != '' $squery ORDER BY letrehozas DESC " . $lquery;
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
		'$prefsurl' => "$site_root/prefs/$name",
		'$nev' => $nick,
		'$usernev' => $name,
		'$blogcim' => $user['blogtitle'],
		'$email' => $user['email'],
		'$postszam' => count_posts($user['id']),
		'$szamlalo' => $user['hits'],
		'$copyright' => $c_text
	);
	
	print(strtr(get_template($user['id'], "header"), $csere));
	if(count($posts)>0)
		foreach($posts as $i)
			handle_posts($i, true);
	print(strtr(get_template($user['id'], "footer"), $csere));
}

function handle_delcomment($commentid)
{
	global $site_root;
	is_numeric($commentid) or die("Nem szám: $commentid");
	$query = "SELECT postid FROM comments WHERE id=$commentid";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$comment = mysql_fetch_array($result, MYSQL_ASSOC);
	mysql_free_result($result);
	$query = "SELECT userid FROM posts WHERE id=" . $comment['postid'];
	$result = mysql_query($query) or die("Nincs is ilyen hozzászólás!");
	$post = mysql_fetch_array($result, MYSQL_ASSOC);
	mysql_free_result($result);
	$query = "SELECT name, passwd FROM users where id=" . $post['userid'];
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$user = mysql_fetch_array($result, MYSQL_ASSOC);
	mysql_free_result($result);
	if( !isset($_SERVER['PHP_AUTH_USER']) )
	{
		header("WWW-Authenticate: Basic realm=\"OpenBlog.hu\"");
		header('HTTP/1.0 401 Unauthorized');
		die("A törléshez jelszó megadása szükséges!");
	}
	else
	{
		if ($user['name']==$_SERVER['PHP_AUTH_USER'] and 
			md5($_SERVER['PHP_AUTH_PW'])==$user['passwd'])
		{
			$query = "DELETE FROM comments WHERE id=" . $commentid;
			$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
			header("Location: $site_root/posts/" . $comment['postid']);
		}
		else
		{
			$realm = mt_rand( 1, 1000000000 );
			header( 'WWW-Authenticate: Basic realm='.$realm );
			die("Nem megfelelõ felhasználónév vagy jelszó!");
		}
	}
}

function handle_delete($postid)
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
		header("WWW-Authenticate: Basic realm=\"OpenBlog.hu\"");
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
			header("Location: $site_root/" . $user['name']);
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

function handle_comments($commentid, $pure=false)
{
	global $site_root, $c_text;
	is_numeric($commentid) or die("Nem szám: $commentid");
	$query = "SELECT postid, name, contact, title, content, letrehozas FROM comments WHERE id=$commentid";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$comment = mysql_fetch_array($result, MYSQL_ASSOC);
	mysql_free_result($result);

	// felado hozzaadasa a comment tartalmahoz
	if (preg_match("|^http://|", $comment['contact']))
		$href="<a href=\"" . $comment['contact'] . "\">";
	elseif (preg_match("/.+@.+\..+/", $comment['contact']))
		$href="<a href=\"mailto:" . $comment['contact'] . "\">";
	else
		$href="<a href=\"http://" . $comment['contact'] . "\">";
	
	// TODO: ez igy nem tul szep, esetleg lehetne majd finomitani
	$comment['content']="<div>Feladó: $href" . $comment['name'] . "</a>" .
		"</div><div>" . $comment['content'] . "</div>";
	
	$query = "SELECT userid FROM posts WHERE id=" . $comment['postid'];
	$result = mysql_query($query) or die("Nincs ilyen hozzászólás!");
	$post = mysql_fetch_array($result, MYSQL_ASSOC);
	$query = "SELECT id, name, email, displayname, date_format, blogtitle, hits FROM users WHERE id=" . $post['userid'];
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$user = mysql_fetch_array($result, MYSQL_ASSOC);
	mysql_free_result($result);
	if ($pure==false)
		push_user($user['id'], $user['hits']);
	$query = "SELECT date_format(letrehozas, '" . $user['date_format'] . "') FROM comments WHERE id=$commentid";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$post2=mysql_fetch_array($result, MYSQL_ASSOC);
	$post['letrehozas']=$post2["date_format(letrehozas, '" . $user['date_format'] . "')"];
	mysql_free_result($result);
	if (!is_null($user['displayname']))
		$comment['user']=$user['displayname'];
	else
		$comment['user']=$user['name'];
	
	// a $cuccok lecserelese, itt vannak comment-specifikus cuccok is
	$csere = array(
		'$fooldal' => "$site_root/posts/" . $comment['postid'],
		'$archiveurl' => "$site_root/archives/" . $user['name'],
		'$newurl' => "$site_root/new/" . $user['name'],
		'$prefsurl' => "$site_root/prefs/" . $user['name'],
		'$nev' => $comment['user'],
		'$usernev' => $user['name'],
		'$blogcim' => $user['blogtitle'],
		'$email' => $user['email'],
		'$postszam' => count_posts($user['id']),
		'$szamlalo' => $user['hits'],
		'$deleteurl' => "$site_root/" . "delcomment/$commentid",
		'$commenturl' => "$site_root/comment/" . $comment['postid'],
		'$editurl' => "$site_root/" . "editcomment/$commentid",
		'$posturl' => "$site_root/" . "comments/$commentid",
		'$ido' => $comment['letrehozas'],
		'$postcim' => $comment['title'],
		'$post' => $comment['content'],
		'$copyright' => $c_text
	);
	
	if (!$pure)
		print(strtr(get_template($user['id'], "header"), $csere));
	print(preg_replace('| \(\$commentnum.*\)|', '', strtr(get_template($user['id'], "post"), $csere)));
	if (!$pure)
		print(strtr(get_template($user['id'], "footer"), $csere));
}

// fake fuggveny mivel eleg gazos lenne ha csakugy atirhatnank masok velemenyet
function handle_editcomment($commentid)
{
	die("A hozzászólások nem szerkeszthetõek. A bejegyzések tulajdonosai <a href=\"/delcomment/$commentid\">törölhetik</a> a hozzászólásokat.");
}

// ha pure true, akkor nincs header meg footer

function handle_posts($postid, $pure=false)
{
	global $site_root, $c_text;
	is_numeric($postid) or die("Nem szám: $postid");
	$query = "SELECT userid, content, title FROM posts WHERE id=$postid";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$post = mysql_fetch_array($result, MYSQL_ASSOC);
	mysql_free_result($result);
	$query = "SELECT id, name, email, displayname, date_format, blogtitle, hits FROM users WHERE id=" . $post['userid'];
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
	$commentids = get_commentids($postid);
	
	// a $cuccok lecserelese, itt vannak post-specifikus cuccok is
	$csere = array(
		'$fooldal' => "$site_root/" . $user['name'],
		'$archiveurl' => "$site_root/archives/" . $user['name'],
		'$newurl' => "$site_root/new/" . $user['name'],
		'$prefsurl' => "$site_root/prefs/" . $user['name'],
		'$nev' => $post['user'],
		'$usernev' => $user['name'],
		'$blogcim' => $user['blogtitle'],
		'$email' => $user['email'],
		'$postszam' => count_posts($user['id']),
		'$szamlalo' => $user['hits'],
		'$deleteurl' => "$site_root/" . "delete/$postid",
		'$commenturl' => "$site_root/" . "comment/$postid",
		'$commentnum' => count($commentids),
		'$editurl' => "$site_root/" . "edit/$postid",
		'$posturl' => "$site_root/" . "posts/$postid",
		'$ido' => $post['letrehozas'],
		'$postcim' => $post['title'],
		'$post' => $post['content'],
		'$copyright' => $c_text
	);
	
	if (!$pure)
		print(strtr(get_template($user['id'], "header"), $csere));
	print(strtr(get_template($user['id'], "post"), $csere));
	if (!$pure)
	{
		if (is_array($commentids))
			foreach($commentids as $i)
				handle_comments($i, true);
		print(strtr(get_template($user['id'], "footer"), $csere));
	}
}

function get_commentids($postid)
{
	$query = "SELECT id FROM comments WHERE postid=$postid";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	while ($i = mysql_fetch_array($result, MYSQL_ASSOC))
		$comments[] = $i;
	mysql_free_result($result);
	if (is_array($comments))
		foreach($comments as $i)
			$ids[]=$i['id'];
	return($ids);
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
	global $upload_dir, $site_root;
	if (!count($_FILES))
		include("templates/upload_form.php");
	else
	{
		$query="INSERT INTO uploads (name, type, ownerid, data) VALUES('" . $_FILES['feltoltes']['name'] . "', '" . $_FILES['feltoltes']['type'] . "', '1', '" . addslashes(file_get_contents($_FILES['feltoltes']['tmp_name'])) . "');";
		$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
		$id=mysql_insert_id();
		if(!@unlink($_FILES['feltoltes']['tmp_name']))
		{
			$query="delete from uploads where id=" . mysql_insert_id();
			$result = mysql_query($query) or die('Hiba a lekérdezésben: ' .mysql_error());
			die("Túl nagy a feltölteni kívánt file!");
		}
		include("templates/upload_success.php");
	}
}

function handle_edit($postid)
{
	global $site_root, $c_text;
	if (count($_POST))
	{
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
			header("WWW-Authenticate: Basic realm=\"OpenBlog.hu\"");
			header('HTTP/1.0 401 Unauthorized');
			die("A szerkesztéshez jelszó megadása szükséges!");
		}
		else
		{
			if ($user['name']==$_SERVER['PHP_AUTH_USER'] and 
				md5($_SERVER['PHP_AUTH_PW'])==$user['passwd'])
			{
				if($_POST['title'] == "")
					$_POST['title']="null";
				else
					$_POST['title']="'" . addslashes(stripslashes($_POST['title'])). "'";
				$query = "UPDATE posts SET content = '" . addslashes(stripslashes(urldecode(str_replace("\r", " ", str_replace("\n", " ", str_replace("\r\n", " ", $_POST['rte1'])))))) . "', title = " . $_POST['title'] . " WHERE id =" . $_POST['postid'];
				$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
				header("Location: $site_root/" . $user['name']);
			}
			else
			{
				$realm = mt_rand( 1, 1000000000 );
				header( 'WWW-Authenticate: Basic realm='.$realm );
				die("Nem megfelelõ felhasználónév vagy jelszó!");
			}
		}
	}
	else
	{
		is_numeric($postid) or die("Nem szám: $postid");
		$query = "SELECT id, userid, title, content FROM posts WHERE id=$postid";
		$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
		$post = mysql_fetch_array($result, MYSQL_ASSOC);
		$post['title']=htmlspecialchars($post['title']);
		$post['content']=addslashes($post['content']);
		mysql_free_result($result);
		include("templates/edit_form.php");
	}
}

function handle_xmlpost($id)
{
	$query = "SELECT id, title, content, UNIX_TIMESTAMP(letrehozas) from posts where id = $id";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$post = mysql_fetch_array($result, MYSQL_ASSOC);
	mysql_free_result($result);

	if ($post['id']==null)
		die("Nincs ilyen post: $id");
	header('Content-Type: application/xml; charset=iso-8859-2');
	header('Cache-Control: max-age: 0, no-cache, must-revalidate');
	print("<?xml version=\"1.0\" encoding=\"iso-8859-2\" ?>
<!DOCTYPE openblogpost [
        <!ELEMENT title (#PCDATA)>
        <!ELEMENT text (#PCDATA)>
        <!ELEMENT comment (#PCDATA)>
        <!ATTLIST comment
                commentid CDATA #REQUIRED>
]>
<openblogpost>
<title>");
if ($post['title'] != "")
	print($post['title']);
else
	print(htmlspecialchars(first_words($post['content'])));
print("</title>
	<text>" . htmlspecialchars($post['content']) . "</text>\n");
print("<date>" . $post['UNIX_TIMESTAMP(letrehozas)'] . "</date>");
	$query = "select id, name, content, contact, UNIX_TIMESTAMP(letrehozas) from comments where postid = $id";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	while ($i = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		$email = $url = "";
		if(strpos($i['contact'], '@') !== false)
			$email = $i['contact'];
		else
			$url = $i['contact'];
		$date = $i['UNIX_TIMESTAMP(letrehozas)'];
		print("\t<comment id=\"" . $i['id'] . "\" author=\"" . $i['name'] . "\" email=\"$email\" url=\"$url\" date=\"$date\">" . htmlspecialchars($i['content']) . "</comment>\n");
	}
print("</openblogpost>\n");
}

function handle_xmlposts($usernev)
{
	$query = "SELECT * FROM users WHERE name='$usernev'";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$user = mysql_fetch_array($result, MYSQL_ASSOC);
	mysql_free_result($result);

	if ($user['id']==null)
		die("Nincs ilyen felhaszáló: $usernev");
	$query = "SELECT id, title, content, unix_timestamp(letrehozas) FROM posts WHERE userid=" . $user['id'] . " and content != '' ORDER BY unix_timestamp(letrehozas) DESC";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	while ($i = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		$query="SELECT count(id) FROM `comments` WHERE postid=" . $i['id'];
		$result2 = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
		$cnum = mysql_fetch_array($result2, MYSQL_ASSOC);
		mysql_free_result($result2);
		$i['comments'] = $cnum['count(id)'];
		$posts[] = $i;
	}
	mysql_free_result($result);
	header('Content-Type: application/xml; charset=iso-8859-2');
	header('Cache-Control: max-age: 0, no-cache, must-revalidate');
	print("<?xml version=\"1.0\" encoding=\"iso-8859-2\" ?>
<!DOCTYPE openblogposts [
        <!ELEMENT post (title)>
        <!ATTLIST post
                postid CDATA #REQUIRED
                pubdate CDATA #REQUIRED
                comments CDATA #REQUIRED>
        <!ELEMENT title (#PCDATA)>
]>
<openblogposts>\n");
	if($posts)
		foreach($posts as $i)
		{
			print("<post postid=\"" . $i['id'] . "\" pubdate=\"" . date(DATE_RFC2822, $i['unix_timestamp(letrehozas)']) . "\" comments=\"".$i['comments']."\">\n");
			print("\t<title>".$i['title']."</title>\n");
			print("</post>\n");
		}
	print("</openblogposts>\n");
}

function handle_archives($usernev)
{
	global $date_format, $site_root, $c_text;
	$query = "SELECT * FROM users WHERE name='$usernev'";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$user = mysql_fetch_array($result, MYSQL_ASSOC);
	mysql_free_result($result);
	
	if ($user['id']==null)
		die("Nincs ilyen felhaszáló: $usernev");
	$query = "SELECT id, title, content, date_format(letrehozas, \"$date_format\") FROM posts WHERE userid=" . $user['id'] . " and content != '' ORDER BY letrehozas DESC";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	while ($i = mysql_fetch_array($result, MYSQL_ASSOC))
		$posts[] = $i;
	mysql_free_result($result);
	if($posts)
		foreach ($posts as $key => $value)
			$posts[$key]['letrehozas'] =
				$value["date_format(letrehozas, \"$date_format\")"];
	$honapok = array();
	if($posts)
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
		'$postszam' => count_posts($user['id']),
		'$szamlalo' => $user['hits'],
		'$copyright' => $c_text
	);
	
	print(strtr(get_archivetemplate($user['id'], "header"), $csere));
	foreach($honapok as $i)
		display_archivemonth($user, $i);
	print(strtr(get_archivetemplate($user['id'], "footer"), $csere));
}

function first_words($input, $idezet=false)
{
	$input = preg_replace("/\. .*/", '', $input);
	if ($idezet)
	{
		global $postidezet_lenght;
		$lenght=$postidezet_lenght;
	}
	else
	{
		global $postcim_lenght;
		$lenght=$postcim_lenght;
	}
	$words = explode(' ', strip_tags($input));
	$words = array_slice($words, 0, $lenght);
	return(implode(' ', $words));
}

function display_archivemonth($user, $honap)
{
	global $date_format, $site_root, $date_format_display;
	$userid=$user['id'];
	$query="SELECT id, userid, content, title, date_format(letrehozas, '" . $user['date_format'] . "') FROM posts WHERE date_format(letrehozas, \"$date_format\" ) = \"$honap\" AND userid=$userid and content != '' ORDER BY letrehozas DESC";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	while ($i = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		$i['letrehozas']=$i["date_format(letrehozas, '" . $user['date_format'] . "')"];
		$posts[] = $i;
	}
	mysql_free_result($result);

	if($posts[0]==null)
		return;
	$query = "SELECT UNIX_TIMESTAMP(letrehozas) FROM posts WHERE id=" . $posts[0]['id'];
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$honap = mysql_fetch_array($result, MYSQL_ASSOC);
	mysql_free_result($result);
	$honap = strftime($date_format_display, $honap['UNIX_TIMESTAMP(letrehozas)']);
	
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
		'$postszam' => count_posts($user['id']),
		'$szamlalo' => $user['hits'],
		'$honap' => $honap
	);
	
	print(strtr(get_archivetemplate($userid, "monthheader"), $csere));
	foreach($posts as $i)
	{
		if(is_null($i['title']))
			$i['title']=first_words($i['content']);
		$i['idezet']=first_words($i['content'], true);
		
		// a $cuccok lecserelese, itt vannak post-specifikus cuccok is
		$csere2 = array(
			'$fooldal' => "$site_root/" . $user['name'],
			'$archiveurl' => "$site_root/archives/" . $user['name'],
			'$newurl' => "$site_root/new/" . $user['name'],
			'$prefsurl' => "$site_root/prefs/" . $user['name'],
			'$nev' => name2nick($user['name']),
			'$usernev' => $user['name'],
			'$blogcim' => $user['blogtitle'],
			'$email' => $user['email'],
			'$postszam' => count_posts($user['id']),
			'$szamlalo' => $user['hits'],
			'$deleteurl' => "$site_root/delete/" . $i['id'],
			'$commenturl' => "$site_root/comment/" . $i['id'],
			'$editurl' => "$site_root/edit/" . $i['id'],
			'$posturl' => "$site_root/posts/" . $i['id'],
			'$ido' => $i['letrehozas'],
			'$postcim' => $i['title'],
			'$postidezet' => $i['idezet'],
			'$post' => $i['content'],
			'$honap' => $honap
		);
		print(strtr(get_archivetemplate($userid, "post"), $csere2));
	}
	print(strtr(get_archivetemplate($userid, "monthfooter"), $csere));
}

// header, monthheader, post, monthfooter v footer lehet. default: post

function get_archivetemplate($id, $type)
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

function handle_prefs($usernev)
{
	global $site_root, $c_text;
	
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
			header("WWW-Authenticate: Basic realm=\"OpenBlog.hu\"");
			header('HTTP/1.0 401 Unauthorized');
			die("A beállítások módosításához jelszó megadása szükséges!");
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
					archivetemplate = '" . addslashes(stripslashes($_POST['archivetemplate'])) . "',
					date_format = '" . addslashes(stripslashes($_POST['date_format'])) . "',
					`limit` ='" . addslashes(stripslashes($_POST['limit'])) . "',
					want_antispam =" . (int)($_POST['want_antispam'] == "on") . "
					WHERE id =" . $user['id'];
					$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
					header("Location: $site_root/" . $user['name']);
				}
			}
			else
			{
				$realm = mt_rand( 1, 1000000000 );
				header( 'WWW-Authenticate: Basic realm='.$realm );
				die("Nem megfelelõ felhasználónév vagy jelszó!");
			}
		}
	}
	$query = "SELECT * FROM users WHERE name='$usernev'";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$user = mysql_fetch_array($result, MYSQL_ASSOC);
	mysql_free_result($result);
	
	if ($user['id']==null)
		die("Nincs ilyen felhaszáló: $usernev");
	include("templates/edit_prefs.php");
}

function handle_rss($usernev)
{
	global $site_root, $site_name;
	
	$query = "SELECT * FROM users WHERE name='$usernev'";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$user = mysql_fetch_array($result, MYSQL_ASSOC);
	if ($user['id']==null)
		die("Nincs ilyen felhaszáló: $usernev");
	$query = "SELECT * FROM posts WHERE userid='" . $user['id'] . "' and content != '' ORDER BY letrehozas DESC LIMIT " . $user['limit'];
	$result = mysql_query($query) or die("Hiba a lekérdezésben: $name");
	while ($i = mysql_fetch_array($result, MYSQL_ASSOC))
		$posts[] = $i;
	$rss = array();
	$csere=array(
		'&' => "&amp;",
		'<' => "&lt;",
		'>' => "&gt;",
		'\'' => "&apos;",
		'"' => "&quot;"
		);
	foreach ($posts as $i)
	{
		if(is_null($i['title']))
			$i['title']=first_words($i['content']);
		$rss[] = array(strtr($i['title'], $csere),
			strtr($i['content'], $csere),
			$site_name . "/posts/" . $i['id']);
	}
	mysql_free_result($result);
	header('Content-Type: application/xml; charset=iso-8859-2');
	print('<?xml version="1.0"  encoding="iso-8859-2"?>
<rss version="2.0">
<channel>');
print("<title>" . $user['blogtitle'] . "</title>");
print("<description>" . $user['blogtitle'] . " - az utolsó " . $user['limit'] . " bejegyzés</description>");
print("<link>$site_name/" . $user['name'] . "</link>");
foreach ($rss as $i)
	print("<item>\n<title>" . $i[0] . "</title>\n" .
		"<description>" . $i[1] . "</description>\n" .
		"<link>" . $i[2] . "</link>\n</item>");
print("</channel>\n</rss>");
}

function handle_postrss($id)
{
	global $site_root, $site_name;
	
	$query = "SELECT * FROM posts WHERE id=$id";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$post = mysql_fetch_array($result, MYSQL_ASSOC);
	if ($post['id']==null)
		die("Nincs ilyen post!");
	$query = "SELECT blogtitle, name, `limit` FROM users WHERE id=" . $post['userid'];
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$user = mysql_fetch_array($result, MYSQL_ASSOC);
	mysql_free_result($result);
	$query = "SELECT * FROM comments WHERE postid='" . $post['id'] . "' ORDER BY letrehozas DESC LIMIT " . $user['limit'];
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	while ($i = mysql_fetch_array($result, MYSQL_ASSOC))
		$comments[] = $i;
	$rss = array();
	$csere=array(
		'&' => "&amp;",
		'<' => "&lt;",
		'>' => "&gt;",
		'\'' => "&apos;",
		'"' => "&quot;"
		);
	foreach ($comments as $i)
	{
		if($i['title'] == "")
			$i['title']=first_words($i['content']);
		$rss[] = array(strtr($i['title'], $csere),
			strtr($i['content'], $csere),
			$site_name . "/posts/" . $i['postid']);
	}
	mysql_free_result($result);
	header('Content-Type: application/xml; charset=iso-8859-2');
	print('<?xml version="1.0"  encoding="iso-8859-2"?>
<rss version="2.0">
<channel>');
print("<title>" . $user['blogtitle'] . "</title>");
print("<description>" . $user['blogtitle'] . " - az utolsó " . $user['limit'] . " hozzászólás</description>");
print("<link>$site_name/" . $user['name'] . "</link>");
foreach ($rss as $i)
	print("<item>\n<title>" . $i[0] . "</title>\n" .
		"<description>" . $i[1] . "</description>\n" .
		"<link>" . $i[2] . "</link>\n</item>");
print("</channel>\n</rss>");
}

function handle_commentrss($usernev)
{
	global $site_root, $site_name;
	
	$query = "SELECT id, blogtitle, name, `limit` FROM users WHERE name='" . $usernev. "'";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$user = mysql_fetch_array($result, MYSQL_ASSOC);
	if ($user['id']==null)
		die("Nincs ilyen felhasználó!");
	mysql_free_result($result);
	$query = "select comments.title, comments.content, comments.postid from comments, posts where comments.postid = posts.id and posts.userid = ".$user['id']." order by comments.letrehozas desc limit ".$user['limit'];
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	while ($i = mysql_fetch_array($result, MYSQL_ASSOC))
		$comments[] = $i;
	$rss = array();
	$csere=array(
		'&' => "&amp;",
		'<' => "&lt;",
		'>' => "&gt;",
		'\'' => "&apos;",
		'"' => "&quot;"
		);
	foreach ($comments as $i)
	{
		if($i['title'] == "")
			$i['title']=first_words($i['content']);
		$rss[] = array(strtr($i['title'], $csere),
			strtr($i['content'], $csere),
			$site_name . "/posts/" . $i['postid']);
	}
	mysql_free_result($result);
	header('Content-Type: application/xml; charset=iso-8859-2');
	print('<?xml version="1.0"  encoding="iso-8859-2"?>
<rss version="2.0">
<channel>');
print("<title>" . $user['blogtitle'] . "</title>");
print("<description>" . $user['blogtitle'] . " - az utolsó " . $user['limit'] . " hozzászólás</description>");
print("<link>$site_name/" . $user['name'] . "</link>");
foreach ($rss as $i)
	print("<item>\n<title>" . $i[0] . "</title>\n" .
		"<description>" . $i[1] . "</description>\n" .
		"<link>" . $i[2] . "</link>\n</item>");
print("</channel>\n</rss>");
}

function handle_comment($postid)
{
	global $site_root, $c_text;
	if(count($_POST))
	{
		if(strlen($_POST['name'])==0)
			$errstr="A név kitöltése kötelezõ!";
		else if(strlen($_POST['contact'])==0)
			$errstr="A kontakt kitöltése kötelezõ!";
		else if(strlen($_POST['content'])==0)
			$errstr="A tartalom kitöltése kötelezõ!";
	}
	if(isset($_POST['postid']))
		$postid = $_POST['postid'];
	$query = "SELECT users.want_antispam FROM users, posts WHERE users.id = posts.userid and posts.id = $postid";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$user = mysql_fetch_array($result, MYSQL_ASSOC);
	mysql_free_result($result);
	if (count($_POST) and strlen($errstr)==0)
	{
		if($user['want_antispam'])
		{
			include("authimage.php");
			if(checkAICode($_POST['code'])==0)
				die("Rossz ellenõrzõ kód!");
		}
		$query = "INSERT INTO comments
		(postid, name, contact, title, content, letrehozas)
		VALUES ('" . addslashes(stripslashes($_POST['postid'])) .
		"', '" . addslashes(stripslashes($_POST['name'])) .
		"', '" . addslashes(stripslashes($_POST['contact'])) .
		"', '" . addslashes(stripslashes($_POST['title'])) .
		"', '" . addslashes(stripslashes($_POST['content'])) .
		"', NOW())";
		"1098, 'jani', 'foo@bar.baz', 'cim', 'blah', NOW())";
		mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
		header("Location: $site_root/posts/" . $_POST['postid']);
	}
	else
	{
		if(count($_POST))
			$postid=$_POST['postid'];
		$query = "SELECT id FROM posts WHERE id=$postid";
		$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
		$post = mysql_fetch_array($result, MYSQL_ASSOC);
		mysql_free_result($result);
		if ($post === false)
			die("Nincs ilyen bejegyzés!");
		include("templates/edit_comment.php");
	}
}

function handle_new($usernev)
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
		header("WWW-Authenticate: Basic realm=\"OpenBlog.hu\"");
		header('HTTP/1.0 401 Unauthorized');
		die("A létrehozáshoz jelszó megadása szükséges!");
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
			header("Location: $site_root/edit/" . mysql_insert_id());
		}
		else
		{
			$realm = mt_rand( 1, 1000000000 );
			header( 'WWW-Authenticate: Basic realm='.$realm );
			die("Nem megfelelõ felhasználónév vagy jelszó!");
		}
	}
}

function handle_register($theme)
{
	global $site_root, $sablonkuszob, $c_text, $handlers;
	$query = "SELECT * FROM users where templatescrore>$sablonkuszob";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	while ($i = mysql_fetch_array($result, MYSQL_ASSOC))
		$templates[] = $i;
	mysql_free_result($result);
	$valid=false;
	foreach($templates as $i)
		if($i['id']==$theme)
			$template=$i;
	if ($theme=="register")
	{
		if (count($_POST))
		{
			if($_POST['name'] == "")
			{
				$hiba="A felhasználónév megadása kötelezõ!";
				include("templates/reg_failure.php");
				die();
			}
			if(in_array($_POST['name'], $handlers))
			{
				$hiba="A megadott név már foglalt!";
				include("templates/reg_failure.php");
				die();
			}
			if(!preg_match("/^[a-zA-Z0-9]+$/", $_POST['name']))
			{
				$hiba="A loginnév csak ékezetmentes kis és nagybetûket, valamint számokat tartalmazhat!";
				include("templates/reg_failure.php");
				die();
			}
			if (md5($_POST['passwd'])!=md5($_POST['passwd2']))
			{
				$hiba="Nem egyezik a megadott két jelszó!";
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
			$query = "SELECT * FROM users WHERE id=" . $_POST['tid'];
			$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
			$template = mysql_fetch_array($result, MYSQL_ASSOC);
			mysql_free_result($result);
			$query = "INSERT INTO users
			(name, passwd, displayname, blogtitle, `limit`, email,
			templatetitle, template, archivetemplate, `date_format`,
			registration_date)
			VALUES ('" . addslashes(stripslashes($_POST['name'])) .
			"', '" . md5($_POST['passwd']) .
			"', '" . addslashes(stripslashes($_POST['displayname'])) .
			"', '" . addslashes(stripslashes($_POST['blogtitle'])) .
			"', " . addslashes(stripslashes($_POST['limit'])) .
			", '" . addslashes(stripslashes($_POST['email'])) .
			"', '" . addslashes($template['templatetitle']) .
			"', '" . addslashes($template['template']) .
			"', '" . addslashes($template['archivetemplate']) .
			"', '" . addslashes(stripslashes($_POST['date_format'])) .
			"', NOW())";
			$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
			header("Location: $site_root/");
		}
		else
			include("templates/reg_select.php");
	}
	elseif (is_null($template))
		die("Nem választható ilyen téma!");
	else
		include("templates/reg_form.php");
}

function print_ad()
{
	$query = "SELECT * FROM ads";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$adnum = mysql_num_rows($result);
	mysql_free_result($result);
	
	$id=mt_rand(1,$adnum);
	$query = 'SELECT * FROM ads where id=' . $id;
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$ad = mysql_fetch_array($result, MYSQL_ASSOC);
	mysql_free_result($result);
	print('<a target=_blank href="/adclick/' . $id  . '"><img src="/ad/' . $id. '" alt="' . $ad['title'] . '"></a>');
}

function handle_ad($id)
{
		$query = "SELECT name, type, data, displays FROM ads WHERE id=" . $id;
		$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
		$ad = mysql_fetch_array($result, MYSQL_ASSOC) or die('Nincs ilyen reklám!');
		mysql_free_result($result);
		$query = "UPDATE ads SET displays=" . ($ad['displays']+1) . " WHERE id=" . $id;
		$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
		header("Content-Type: " . $ad['type']);
		header("Content-Length: " . strlen($ad['data']));
		header("Content-Disposition: attachment; filename=\"" . $ad['name']. "\"");
		print($ad['data']);
}

function handle_adclick($id)
{
	$query = "SELECT url, clicks FROM ads WHERE id=" . $id;
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$ad = mysql_fetch_array($result, MYSQL_ASSOC) or die('Nincs ilyen reklám!');
	mysql_free_result($result);
	$query = "UPDATE ads SET clicks=" . ($ad['clicks']+1) . " WHERE id=" . $id;
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	header("Location: " . $ad['url']);
}

function push_user($id, $current)
{
	$query = "UPDATE users SET hits=" . ($current+1) . " WHERE id=" . $id;
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
}

function count_posts($id)
{
	$query = "SELECT * FROM posts WHERE userid=" . $id . " and content != ''";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$postnum = mysql_num_rows($result);
	mysql_free_result($result);
	return $postnum;
}

function handle_list($id)
{
	global $plimit, $c_text, $date_format_fooldalipost, $posts_limit;
	if ($id == "list" or $id == "")
		$id=0;
	if (is_numeric($id))
	{
		if ($id >= 0)
			$limit=$id;
		else
			$limit=0;
	}
	else
	{
		$sterm="WHERE `name` LIKE '%$id%' ";
		$limit=0;
	}
	$query = "SELECT * FROM `users` $sterm ORDER BY `name` ASC LIMIT $limit, $plimit";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	while ($i = mysql_fetch_array($result, MYSQL_ASSOC))
		$users[] = $i;
	mysql_free_result($result);

	$query = 'SELECT count(*) FROM `users`';
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	$count = mysql_fetch_array($result, MYSQL_ASSOC);
	$max = $count['count(*)'];
	mysql_free_result($result);
	$prev = $limit - $plimit;
	if ($prev < 0)
		$prev = "";
	$pprev = "";
	$next = $limit + $plimit;
	if ($next > $max - $plimit)
		$next = $max - $plimit;
	$nnext = $max - $plimit;

	// posts doboz
	$query = "SELECT id, name, displayname FROM users";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	while ($i = mysql_fetch_array($result, MYSQL_ASSOC))
		$userdb[$i['id']]=$i;
	mysql_free_result($result);
	$query = "SELECT id, userid, title, date_format(letrehozas, '$date_format_fooldalipost') FROM posts WHERE content != '' ORDER BY letrehozas DESC LIMIT $posts_limit";
	$result = mysql_query($query) or die('Hiba a lekérdezésben: ' . mysql_error());
	while ($i = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		$i['name']=$userdb[$i['userid']]['name'];
		$i['letrehozas']=$i["date_format(letrehozas, '$date_format_fooldalipost')"];
		$posts[]=$i;
	}
	mysql_free_result($result);
	include("templates/user_list.php");
	
}

function handle_authimage()
{
	include("authimage.php");
	createAICode("image");
}

?>
