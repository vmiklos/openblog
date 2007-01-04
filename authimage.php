<?php
/*
Plugin Name: AuthImage
Plugin URI: http://www.gudlyf.com/index.php?p=376
Description: Creates an authentication image (or phonetic text) to help combat spam in comments.
Version: 2.0.1
Author: Keith McDuffee
Author URI: http://www.gudlyf.com/
*/


if ($_GET['type'] == "text") {
  createAICode("text");
  exit;
} elseif ($_GET['type'] == "image") {
  createAICode("image");
  exit;
}


function checkAICode($code)
{
  session_start();
  $return = ($code == $_SESSION['AI-code']) ? 1 : 0;
  if(!isset($_SESSION['AI-code']))
    $return = 0;

  // set new random code.
  $_SESSION['AI-code'] = randomString('alpha');

  return $return;
}

function createAICode($type)
{
  $code = randomString('alpha');
  session_start();
  $_SESSION['AI-code'] = $code;
	
  if ($type != "text") {
    $font = "atomicclockradio.ttf";

    $im = @imageCreate(155, 50) or die("Cannot Initialize new GD image stream");

    //$background_color = imageColorAllocate($im, 209, 252, 214);
    $background_color = imageColorAllocate($im, 255, 255, 255);
    $text_color = imageColorAllocate($im, 0x00, 0x00, 0x00);

    ImageTTFText($im, 20, 5, 18, 38, $text_color, $font, $code);

    // Date in the past
    header("Expires: Thu, 28 Aug 1997 05:00:00 GMT");

    // always modified
    $timestamp = gmdate("D, d M Y H:i:s");
    header("Last-Modified: " . $timestamp . " GMT");
 
    // HTTP/1.1
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);

    // HTTP/1.0
    header("Pragma: no-cache");

    // dump out the image
    header("Content-type: image/jpeg");
    ImageJPEG($im);

  } else {
    // Show phonetic text instead
    settype($code, "string");
    echo createAIAltText($code);
  }
	
}

function createAIAltText($code) {

  $alt_char = array('a' => 'ay',
		'b' => 'bee',
		'c' => 'see',
		'd' => 'dee',
		'e' => 'eee',
		'f' => 'eff',
		'g' => 'jee',
		'h' => 'aych',
		'i' => 'eye',
		'j' => 'jay',
		'k' => 'kay',
		'l' => 'ell',
		'm' => 'em',
		'n' => 'en',
		'o' => 'oh',
		'p' => 'pea',
		'q' => 'que',
		'r' => 'are',
		's' => 'ess',
		't' => 'tee',
		'u' => 'you',
		'v' => 'vee',
		'w' => 'double-u',
		'x' => 'ecks',
		'y' => 'why',
		'z' => 'zee',
		'0' => 'zero',
		'1' => 'one',
		'2' => 'two',
		'3' => 'three',
		'4' => 'four',
		'5' => 'five',
		'6' => 'six',
		'7' => 'seven',
		'8' => 'eight',
		'9' => 'nine');

  $alt_code = "";

  for($i = 0; $i < 5; $i++) {
    if (!is_numeric($code{$i})) {
      if (ctype_upper($code{$i})) {
        $alt_code = $alt_code . "upper-" . $alt_char[strtolower($code{$i})] . " ";
      } else {
        $alt_code = $alt_code . "lower-" . $alt_char[$code{$i}] . " ";
      }
    } else {
      $alt_code = $alt_code . $alt_char[$code{$i}] . " ";
    }
  }

  return $alt_code;
}

function randomString($type='num',$length=6)
{
  $randstr='';
  srand((double)microtime()*1000000);

  $chars = array ( '1','2','3','4','5','6','7','8','9','0' );
  if ($type == "alpha") {
    array_push ( $chars, '1' );
  }

  for ($rand = 0; $rand < $length; $rand++)
  {
    $random = rand(0, count($chars) -1);
    $randstr .= $chars[$random];
  }
  return $randstr;
}


?>
