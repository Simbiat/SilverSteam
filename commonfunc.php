<?php
$allowsdirs = array(
"C:\Program Files (x86)\Steam\steamapps\common",
"C:\Program Files (x86)\Steam\depotcache"
);
$allowsdirsl = array(
"C:\Program Files (x86)\Steam\steamapps\common\\",
"C:\Program Files (x86)\Steam\depotcache\\"
);
$allowsdirsm = array(
"C:\Program Files (x86)\Steam\steamapps\\",
"C:\Program Files (x86)\Steam\depotcache\\"
);
$steamapikey="85290620D633293917D575E4C7E93E75";

ini_set( "display_errors", 1);
ini_set( "display_warnings", 1);
ini_set( "display_notices", 1);
require_once('gifunc.php');


// ####################### CRYPT MODULE ###########################
$rijnKey = "\x1\x2\x3\x4\x5\x6\x7\x8\x9\x10\x11\x12\x13\x14\x15\x16";
$rijnIV = "\x1\x2\x3\x4\x5\x6\x7\x8\x9\x10\x11\x12\x13\x14\x15\x16";
function Decrypt($s){
global $rijnKey, $rijnIV;

if ($s == "") { return $s; }

// Turn the cipherText into a ByteArray from Base64
try {
$s = str_replace("BIN00101011BIN", "+", $s);
$s = base64_decode($s);
$s = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $rijnKey, $s, MCRYPT_MODE_CBC, $rijnIV);
} catch(Exception $e) {
// There is a problem with the string, perhaps it has bad base64 padding
// Do Nothing
}
//return preg_replace('/[^(u25D0-u25FF)]*/','', preg_replace('/[^(u25A0-u25CF)]*/','', $s));
$block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, 'cbc');
$pad = ord($s[($len = strlen($s)) - 1]);
return substr($s, 0, strlen($s) - $pad);
}

function Encrypt($s){
global $rijnKey, $rijnIV;

// Have to pad if it is too small
$block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, 'cbc');
$pad = $block - (strlen($s) % $block);
$s .= str_repeat(chr($pad), $pad);

$s = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $rijnKey, $s, MCRYPT_MODE_CBC, $rijnIV);
$s = base64_encode($s);
$s = str_replace("+", "BIN00101011BIN", $s);
return $s;
}

function startsWith($haystack, $needle)
{
    return $needle === "" || strpos($haystack, $needle) === 0;
}

function endsWith($haystack, $needle)
{
    return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
}

function FileSizeConvert($bytes)
 {
     $bytes = floatval($bytes);
         $arBytes = array(
             0 => array(
                 "UNIT" => "TB",
                 "VALUE" => pow(1024, 4)
             ),
             1 => array(
                 "UNIT" => "GB",
                 "VALUE" => pow(1024, 3)
             ),
             2 => array(
                 "UNIT" => "MB",
                 "VALUE" => pow(1024, 2)
             ),
             3 => array(
                 "UNIT" => "KB",
                 "VALUE" => 1024
             ),
             4 => array(
                 "UNIT" => "B",
                 "VALUE" => 1
             ),
         );

     foreach($arBytes as $arItem)
     {
         if($bytes >= $arItem["VALUE"])
         {
             $result = $bytes / $arItem["VALUE"];
             $result = str_replace(".", "," , strval(round($result, 2)))." ".$arItem["UNIT"];
             break;
         }
     }
     return $result;
 }


function checkRemoteFile($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    // don't download content
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if(curl_exec($ch)!==FALSE)
    {
        return true;
    }
    else
    {
        return false;
    }
}

function bytesToSize($bytes, $precision = 2)
{  
    $kilobyte = 1024;
    $megabyte = $kilobyte * 1024;
    $gigabyte = $megabyte * 1024;
    $terabyte = $gigabyte * 1024;
   
    if (($bytes >= 0) && ($bytes < $kilobyte)) {
        return $bytes . ' B';
 
    } elseif (($bytes >= $kilobyte) && ($bytes < $megabyte)) {
        return round($bytes / $kilobyte, $precision) . ' KB';
 
    } elseif (($bytes >= $megabyte) && ($bytes < $gigabyte)) {
        return round($bytes / $megabyte, $precision) . ' MB';
 
    } elseif (($bytes >= $gigabyte) && ($bytes < $terabyte)) {
        return round($bytes / $gigabyte, $precision) . ' GB';
 
    } elseif ($bytes >= $terabyte) {
        return round($bytes / $terabyte, $precision) . ' TB';
    } else {
        return $bytes . ' B';
    }
}

function real_filesize($file_path)
{
    $fs = new COM("Scripting.FileSystemObject");
    return $fs->GetFile($file_path)->Size;
}
function getTitle($Url){
    $str = file_get_contents($Url);
    if(strlen($str)>0){
        preg_match("/\<title\>(.*)\<\/title\>/",$str,$title);
        return $title[1];
    }
}
function multi_implode($array, $glue) {
	$ret = '';
	foreach ($array as $item) {
		if (is_array($item)) {
			$ret .= multi_implode($item, $glue) . $glue;
		} else {
			if (!is_int($item) and !ctype_digit($item)) {
				$ret .= $item . $glue;
			}
		}
	}
		$ret = substr($ret, 0, 0-strlen($glue));
		return $ret;
}


function appidgrab($appid, $action) {
	global $host, $username, $password, $db_name, $steamapikey;
	$refreshpage=true;
	if ($action == "update") {
		$action = "UPDATE";
		$addedon = "AddedOn";
		$updatedon = "UpdatedOn";
		$where = " WHERE appID=\"".$appid."\"";
	} else if ($action == "insert") {
		$action = "INSERT INTO";
		$addedon = time();
		$updatedon = time();
		$where = "";
	} else if ($action == "check") {
		$refreshpage=false;
		$link = mysqli_connect("$host", "$username", "$password", "$db_name");
		if (!$link) {
			die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
		} else {
			$gamecheck = mysqli_query($link, "SELECT appID FROM ss__dsgamelist WHERE appID=\"".$appid."\"");
			if (mysqli_num_rows($gamecheck)) {
				$action = "UPDATE";
				$addedon = "AddedOn";
				$updatedon = time();
				$where = " WHERE appID=\"".$appid."\"";
			} else {
				$action = "INSERT INTO";
				$addedon = time();
				$updatedon = time();
				$where = "";
			}
		}
	}
	$link = mysqli_connect("$host", "$username", "$password", "$db_name");
	if (!$link) {
		die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
	} else {
		headerimg($appid);
		$jsonstring=@file_get_contents("http://store.steampowered.com/api/appdetails/?key=".$steamapikey."&cc=us&appids=".$appid);
		$fullappinfo=json_decode($jsonstring, true);
		if ($fullappinfo[$appid]['success']) {
			if (isset($fullappinfo[$appid]['data']['fullgame']['appid'])) {
				$parentid=$fullappinfo[$appid]['data']['fullgame']['appid'];
			} else {
				$parentid=$appid;
			}
			$type=$fullappinfo[$appid]['data']['type'];
			$GameName=$fullappinfo[$appid]['data']['name'];
			$is_free=$fullappinfo[$appid]['data']['is_free'];
			if (isset($fullappinfo[$appid]['data']['price_overview']) and $is_free == true) {
				$is_free=false;
			}
			$alldlcids="";
			if (isset($fullappinfo[$appid]['data']['dlc'])) {
				$alldlcinfo=$fullappinfo[$appid]['data']['dlc'];
				foreach ($alldlcinfo as $dlc) {
					$dlcstring=@file_get_contents("http://store.steampowered.com/api/appdetails/?key=".$steamapikey."&cc=us&appids=".$dlc);
					$dlcinfo=json_decode($dlcstring, true);
					if ($dlcinfo[$dlc]['success']) {
						appidgrab($dlc, "check");
					}
				}
			} else {
				$alldlcids="";
			}
			@$reqage=$fullappinfo[$appid]['data']['required_age'];
			@$offwebsite=$fullappinfo[$appid]['data']['website'];
			@$reqmin=$fullappinfo[$appid]['data']['pc_requirements']['minimum'];
			@$reqrec=$fullappinfo[$appid]['data']['pc_requirements']['recommended'];
			@$developers=multi_implode($fullappinfo[$appid]['data']['developers'], ";");
			@$publishers=multi_implode($fullappinfo[$appid]['data']['publishers'], ";");
			@$genres=multi_implode($fullappinfo[$appid]['data']['genres'], ";");
			@$screens=multi_implode($fullappinfo[$appid]['data']['screenshots'], ";");
			if ($screens != "") {
				$pscarray=explode(";", $screens);
				$screens = "";
				foreach ($pscarray as $pscreen) {
					if (stripos($pscreen, "1920x1080") !== false) {
						$screen = substr($pscreen, 0, strpos($pscreen, "?"));
						$screens=$screens.$screen.";";
					}
				}
				$screens=trim($screens, ";");
			}
			@$reldate=strtotime($fullappinfo[$appid]['data']['release_date']['date']);
			@$language=str_replace(", ", "<br>",str_replace("<br><strong>*</strong>languages with full audio support", "", $fullappinfo[$appid]['data']['supported_languages']));
			if (!is_numeric($reldate)) {
				$reldate=0;
			}
			@$gamedesc=$fullappinfo[$appid]['data']['detailed_description'];
			$gamefeats=multi_implode($fullappinfo[$appid]['data']['categories'], ";");

			$acfupdate = mysqli_query($link, $action." ss__dsgamelist SET 
			appID=".$appid.",
			parentid=".$parentid.",
			type=\"".$type."\",
			GameName=\"".mysqli_real_escape_string($link, trim($GameName))."\",
			AddedOn=".$addedon.",
			UpdatedOn=".$updatedon.",
			is_free=".intval($is_free).",
			Language=\"".mysqli_real_escape_string($link, $language)."\",
			gamedesc=\"".mysqli_real_escape_string($link, $gamedesc)."\",
			gamefeatures=\"".mysqli_real_escape_string($link, $gamefeats)."\",
			reqage=\"".mysqli_real_escape_string($link, $reqage)."\",
			offwebsite=\"".mysqli_real_escape_string($link, $offwebsite)."\",
			reqmin=\"".mysqli_real_escape_string($link, $reqmin)."\",
			reqrec=\"".mysqli_real_escape_string($link, $reqrec)."\",
			developers=\"".mysqli_real_escape_string($link, $developers)."\",
			publishers=\"".mysqli_real_escape_string($link, $publishers)."\",
			genres=\"".mysqli_real_escape_string($link, $genres)."\",
			screenshots=\"".mysqli_real_escape_string($link, $screens)."\",
			releasedate=\"".mysqli_real_escape_string($link, $reldate)."\""
			.$where);
			if (mysqli_affected_rows($link)>0) {
				if ($action == "UPDATE") {
					$acfupdate2 = mysqli_query($link, $action." ss__dsgamelist SET UpdatedOn=".time().$where);
				}
				if ($refreshpage == true) {
					capcu_delete("ss_gameinfo".$appid);
					echo "<script>if (!document.getElementById(\"dsccent\")) {
								location.reload();
							} else {
								$('#dsccent').load('/ssc/".$appid."');
							}
						</script>";
				}
			}
		} else {
			if ($action != "UPDATE") {
				echo "<b>".$appid."</b> does not seem to be a valid ID on Steam side as well.<br><br>";
			}
		}
		mysqli_close($link);
	}
}

function headerimg($appid) {
	//global $steamapikey;
	//if (file_exists("./img/headers/".$appid.".jpg")) {
	//	return "/ssc/img/headers/".$appid.".jpg";
	//} else {
	//	if (checkRemoteFile("http://cdn.akamai.steamstatic.com/steam/apps/".$appid."/header.jpg?key=".$steamapikey) === true) {
	//		$content = file_get_contents("http://cdn.akamai.steamstatic.com/steam/apps/".$appid."/header.jpg?key=".$steamapikey);
	//		$fp = fopen("./img/headers/".$appid.".jpg", "w");
	//		fwrite($fp, $content);
	//		fclose($fp);
	//		if (file_exists("./img/headers/".$appid.".jpg")) {
	//			return "/ssc/img/headers/".$appid.".jpg";
	//		} else {
				return "http://cdn.akamai.steamstatic.com/steam/apps/".$appid."/header.jpg";
	//		}
	//	}
	//}
}
function screenshotimg($appid, $imgadd) {
	global $steamapikey;
	if (strpos($imgadd, "?")) {
		$imgadd = substr($imgadd, 0, strpos($imgadd, "?"));
	}
	$newimgname = $appid."_".substr($imgadd, strrpos($imgadd, '/') + 1);
	if (file_exists("./img/screens/".$newimgname)) {
		return "/ssc/img/screens/".$newimgname;
	} else {
		if (checkRemoteFile($imgadd) === true) {
			$content = file_get_contents($imgadd);
			$fp = fopen("./img/screens/".$newimgname, "w");
			fwrite($fp, $content);
			fclose($fp);
			if (file_exists("./img/screens/".$newimgname)) {
				return "/ssc/img/screens/".$newimgname;
			} else {
				return $imgadd;
			}
		}
	}
}
function outputstatus($text) {
	echo "
		<script>
			$('#dsccent').html('".$text."');
		</script>
	";
}




function capcu_delete($filename) {
	if (extension_loaded('apcu') === true) {
		apcu_delete($filename);
	} else {
		if (file_exists("./cache/".$filename.".txt")) {
			@unlink("./cache/".$filename.".txt");
		}
	}
}
function capcu_store($varname, $vardata, $timestore) {
	if (extension_loaded('apcu') === true) {
		apcu_store($varname, $vardata, $timestore);
	} else {
		if (!is_dir("./cache")) {
			mkdir("./cache");
		}
		@file_put_contents("./cache/".$varname.".txt", $vardata);
	}
}
function capcu_fetch($varname) {
	if (extension_loaded('apcu') === true) {
		return apcu_fetch($varname);
	} else {
		$dir = new DirectoryIterator("./cache/");
		foreach ($dir as $fileinfo) {
			if (!$fileinfo->isDot()) {
				if (time()-filemtime("./cache/".$fileinfo->getFilename()) > 86400) {
					@unlink("./cache/".$fileinfo->getFilename());
				}
			}
		}
		if (file_exists("./cache/".$varname.".txt")) {
			if (time()-filemtime($filename) > 86400) {
				return false;
			} else {
				return file_get_contents("./cache/".$varname.".txt");
			}
		} else {
			return false;
		}
	}
}







class compareImages
{
	private function mimeType($i)
	{
		/*returns array with mime type and if its jpg or png. Returns false if it isn't jpg or png*/
		$mime = getimagesize($i);
		$return = array($mime[0],$mime[1]);
      
		switch ($mime['mime'])
		{
			case 'image/jpeg':
				$return[] = 'jpg';
				return $return;
			case 'image/png':
				$return[] = 'png';
				return $return;
			default:
				return false;
		}
    }  
    
	private function createImage($i)
	{
		/*retuns image resource or false if its not jpg or png*/
		$mime = $this->mimeType($i);
      
		if($mime[2] == 'jpg')
		{
			return imagecreatefromjpeg ($i);
		} 
		else if ($mime[2] == 'png') 
		{
			return imagecreatefrompng ($i);
		} 
		else 
		{
			return false; 
		} 
    }
    
	private function resizeImage($i,$source)
	{
		/*resizes the image to a 8x8 squere and returns as image resource*/
		$mime = $this->mimeType($source);
      
		$t = imagecreatetruecolor(8, 8);
		
		$source = $this->createImage($source);
		
		imagecopyresized($t, $source, 0, 0, 0, 0, 8, 8, $mime[0], $mime[1]);
		
		return $t;
	}
    
    private function colorMeanValue($i)
	{
		/*returns the mean value of the colors and the list of all pixel's colors*/
		$colorList = array();
		$colorSum = 0;
		for($a = 0;$a<8;$a++)
		{
		
			for($b = 0;$b<8;$b++)
			{
			
				$rgb = imagecolorat($i, $a, $b);
				$colorList[] = $rgb & 0xFF;
				$colorSum += $rgb & 0xFF;
				
			}
			
		}
		
		return array($colorSum/64,$colorList);
	}
    
    private function bits($colorMean)
	{
		/*returns an array with 1 and zeros. If a color is bigger than the mean value of colors it is 1*/
		$bits = array();
		 
		foreach($colorMean[1] as $color){$bits[]= ($color>=$colorMean[0])?1:0;}

		return $bits;

	}
	
    public function compare($a,$b)
	{
		/*main function. returns the hammering distance of two images' bit value*/
		$i1 = $this->createImage($a);
		$i2 = $this->createImage($b);
		
		if(!$i1 || !$i2){return false;}
		
		$i1 = $this->resizeImage($i1,$a);
		$i2 = $this->resizeImage($i2,$b);
		
		imagefilter($i1, IMG_FILTER_GRAYSCALE);
		imagefilter($i2, IMG_FILTER_GRAYSCALE);
		
		$colorMean1 = $this->colorMeanValue($i1);
		$colorMean2 = $this->colorMeanValue($i2);
		
		$bits1 = $this->bits($colorMean1);
		$bits2 = $this->bits($colorMean2);
		
		$hammeringDistance = 0;
		
		for($a = 0;$a<64;$a++)
		{
		
			if($bits1[$a] != $bits2[$a])
			{
				$hammeringDistance++;
			}
			
		}
		  
		return $hammeringDistance;
	}
}
?>