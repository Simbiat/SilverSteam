<?php
require_once('./commonfunc.php');
ignore_user_abort(true);
ini_set( "display_errors", 1);
ini_set('output_buffering', 0);
ini_set('implicit_flush', 1);
error_reporting(E_ALL);
ob_end_flush();
ob_start();
set_time_limit(3200);

// ######################### REQUIRE BACK-END ############################
require_once "./config.php";
require_once('./css.php');

$depotlist = fopen("./apps/sisinstall/appidlist.txt", "r");
if ($depotlist) {
	while (($line = fgets($depotlist)) !== false) {
		sleep(15);
		set_time_limit(3200);
		appidgrab(trim($line), "check");
		capcu_delete("ss_gameinfo".trim($line));
    	}
	capcu_delete("ss_gamelist");
	capcu_fetch("ss_news");
	fclose($depotlist);
	echo "Done!";
} else {
	Echo "error opening the file.";
}

?>