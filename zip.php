<?php
require_once('./commonfunc.php');
ignore_user_abort(true);

set_time_limit(320);
if (empty($_GET['appid'])) {
	require_once('./css.php');
	Echo "No appID provided!";
	exit;
} else {
	$appid=$_GET['appid'];
}
if (empty($_GET['offset'])) {
	$offset = 0;
} else {
	$offset = $_GET['offset'];
}

// ######################### REQUIRE BACK-END ############################
//require_once "../../genfunc/config.php";
//require_once('./css.php');

$dirmatches = glob("./depot manifests/".$appid." - *");
if (!empty($dirmatches)) {
	$zipname = $appid.".zip";
	$zip = new ZipArchive;
	$zip->open("./zip/".$zipname, ZipArchive::CREATE);
	foreach ($dirmatches as $dirmatch) {
		if ($handle = opendir($dirmatch)) {
			while (false !== ($entry = readdir($handle))) {
				if ($entry != "." && $entry != "..") {
					 $zip->addFile($dirmatch ."/". $entry, $entry);
				}
			}
			closedir($handle);
		}
	}
	$zip->close();
	$filename="./zip/".$zipname;
	if (file_exists($filename)) {
		$basename = basename($filename);
		$length   = sprintf("%u", filesize($filename));
		if ( isset($_SERVER['HTTP_RANGE']) ) {
			$partialContent = true;
			preg_match('/bytes=(\d+)-(\d+)?/', $_SERVER['HTTP_RANGE'], $matches);
			$offset = intval($matches[1]);
			$length = intval($matches[2]) - $offset;
		} else {
			$partialContent = false;
		}
		if ( $partialContent ) {
			header('HTTP/1.1 206 Partial Content');
			header('Content-Range: bytes ' . $offset . '-' . ($offset + $length) . '/' . $filesize);
		}
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="' . $basename . '"');
		header('Content-Transfer-Encoding: binary');
		header('Connection: Keep-Alive');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . $length);
		if (!endsWith($filename, "acf")) {
			header('Accept-Ranges: bytes');
		}
		set_time_limit(0);
		if ($fd = fopen ($filename, "r")) {
			fseek($fd, $offset);
			while(!feof($fd)) {
				$buffer = fread($fd, 1024);
				echo $buffer;
			}
			fclose($fd);
		}
		


		//header('Content-Type: application/zip');
		//header("Content-Disposition: attachment; filename='".$zipname."'");
		//header('Content-Length: ' . filesize($zipname));
		//header("Location: http://".$_SERVER['SERVER_NAME']."/scc/zip/".$zipname);
		//readfile("./zip/".$zipname);
		//if (connection_aborted()) {
			unlink("./zip/".$zipname);
		//}
	} else {
		require_once('./css.php');
		Echo "Failed to prepare zip!";
	}
} else {
	require_once('./css.php');
	Echo "No files found!";
}

?>