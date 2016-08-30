<?php
require_once('./commonfunc.php');

ini_set( "display_errors", 0);
ini_set('output_buffering', 0);
ini_set('implicit_flush', 1);
ob_end_flush();
ob_start();
set_time_limit(320);

if (empty($_GET['appid'])) {
	$appid=0;
} else {
	$appid=$_GET['appid'];
}

// ######################### REQUIRE BACK-END ############################
require_once "../genfunc/config.php";
require_once('./css.php');
echo "<script src=\"/ssc/jquery/jquery-2.0.3.min.js\"></script>";
echo "<script src=\"/ssc/jquery/jquery.fastLiveFilter.js\"></script>";
header( 'Content-type: text/html; charset=utf-8' );


$link = mysqli_connect("$host", "$username", "$password", "$db_name");
if (!$link) {
	Echo "Failed to conenct to database";
	Exit;
} else {
	$gamegrab = mysqli_query($link, "SELECT appID, parentid, type FROM ss__dsgamelist WHERE appID=\"".$appid."\"");
	if (mysqli_num_rows($gamegrab)) {
		$row = mysqli_fetch_array($gamegrab, MYSQLI_ASSOC);
		if ($row['appID'] !== $row['parentid'] and ($row['type'] == "movie" or $row['type'] == "demo")) {
			$appid=$row['parentid'];
		}
	} else {
		$showgi=$showgi. "Wrong or missing appID! Checking Steam...";
		mysqli_close($link);
		appidgrab($appid, "insert");
		exit;
	}
}
if ($shownewscache = capcu_fetch("ss_gameinfo".$appid)) {
	echo $shownewscache;
	exit;
}


// ######################### Actual output ############################
$showgi="
<script>
$(document).ready ( function () {
	$(document).on ('click', '.toglink', function (event) {
		event.preventDefault();
		var e = document.getElementById($(this).attr('href'));
		if (e.style.display == \"\") {
			e.style.display = \"none\";
			$('html, body').animate({scrollTop:$('#'+$(this).attr('id')).position().top}, 'medium');
		} else {
			e.style.display = \"\";
			$('html, body').animate({scrollTop:$('#'+$(this).attr('id')).position().top}, 'medium');
		}
		return false;
	});
});
</script>";
ginfo($appid, true);
?>