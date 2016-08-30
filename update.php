<?php
require_once('./commonfunc.php');
ignore_user_abort(true);
ini_set( "display_errors", 1);
ini_set('output_buffering', 0);
ini_set('implicit_flush', 1);
error_reporting(E_ALL);
ob_end_flush();
ob_start();
set_time_limit(320);

// ######################### REQUIRE BACK-END ############################
require_once "./config.php";
require_once('./css.php');

$depotlist = fopen("./apps/sisinstall/depotlist_full.txt", "r");
if ($depotlist) {
	$gameline = 0;
	$depotlistnew = "./apps/sisinstall/depotlist.txt";
	$idlist = "./apps/sisinstall/appidlist.txt";
	unlink($depotlistnew);
	unlink($idlist);
	while (($line = fgets($depotlist)) !== false) {
		if (preg_match("/\\[appids\\/([0-9]*)\\]/", $line, $matches)) {
			//if (($gameline >= 5 and @$towrite2 != "") or $gameline == 0) {
				if ($gameline != 0 and @$towrite2 != "") {
					//echo nl2br($towrite.$towrite2);
					file_put_contents($depotlistnew, $towrite.$towrite2, FILE_APPEND);
					file_put_contents($idlist, $appid."\r\n", FILE_APPEND);
				}
				$appid = $matches[1];
				$towrite = $line;
				$towrite2 = "";
				$gameline = 1;
			//}
		}
		if (trim($line) == "" and $gameline == 1) {
			$gameline = 0;
		}
		if (preg_match("/(name = )(.*)/", $line, $matches) and $gameline == 1) {
			$towrite = $towrite.$matches[1].preg_replace('/[[:^print:]]/', '', $matches[2])."\r\n";
			$gameline = $gameline + 1;
		}
		if (preg_match("/(gameid = )(.*)/", $line, $matches)) {
			$towrite = $towrite.$line;
			$gameline = $gameline + 1;
		}
		if (preg_match("/(installdir = )(.*)/", $line, $matches)) {
			$towrite = $towrite.$line;
			$gameline = $gameline + 1;
		}
		if (preg_match("/(freeondemand = )(.*)/", $line, $matches)) {
			$towrite = $towrite.$line."\r\n";
			$gameline = $gameline + 1;
		}
		if (preg_match("/\\[appids\\/([0-9]*)\\/([0-9]*)\\]/", $line, $matches)) {
			$appid = $matches[1];
			$depotid = $matches[2];
			if ($towrite2 == "") {
				$towrite2 = $line;
			} else {
				$towrite2 = $towrite2.$line;
			}
			$gameline2 = 1;
		}
		if (preg_match("/(name = )(.*)/", $line, $matches) and $gameline >= 5) {
			$towrite2 = $towrite2.$matches[1].preg_replace('/[[:^print:]]/', '', $matches[2])."\r\n";
			$gameline2 = $gameline2 + 1;
		}
		if (preg_match("/(depotglobalid = )(.*)/", $line, $matches) and $gameline >= 5) {
			$towrite2 = $towrite2.$matches[1].$matches[2]."\r\n\r\n";
			$gameline2 = $gameline2 + 1;
			if (trim($matches[2]) === "(int64)0") {
				$towrite2 = "";
			} else {
				$depotname = $depotid."_".str_replace("(int64)", "", trim($matches[2])).".manifest";
				$dirmatches = glob("./depot manifests/".$appid." - *");
				if (!empty($dirmatches)) {
					foreach ($dirmatches as $dirmatch) {
						$filesmatches = glob($dirmatch."/".$depotname);
						if (!empty($filesmatches)) {
							$towrite2 = "";
						} else {
							$filesmatches2 = glob($dirmatch."/".$depotid."_*.manifest");
							if (!empty($filesmatches2)) {
								foreach ($filesmatches2 as $filematch) {
									unlink($filematch);
								}
							}
						}
					}
				}
			}
		}
    	}
	if ($gameline != 0 and @$towrite2 != "") {
		//echo nl2br($towrite.$towrite2);
		file_put_contents($depotlistnew, $towrite.$towrite2, FILE_APPEND);
	}
	fclose($depotlist);
} else {
	Echo "error opening the file.";
} 



/*
	ob_flush();
	flush();
	set_time_limit(320);
*/

?>