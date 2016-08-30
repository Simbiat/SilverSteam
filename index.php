<?php
require_once('./commonfunc.php');

ini_set( "display_errors", 0);



if (empty($_GET['appid'])) {
	$appid=0;
} else {
	$appid=$_GET['appid'];
}


// ######################### REQUIRE BACK-END ############################
require_once "./config.php";


// ######################## OUTPUT PAGE ############################
	//$navbits = construct_navbits(array('' => 'SilverSteam'));
	//$navbar = render_navbar_template($navbits);

	// ###### YOUR CUSTOM CODE GOES HERE #####
	echo "<title>SilverSteam</title>";
	header( 'Content-type: text/html; charset=utf-8' );
	echo "<meta property=\"og:type\"   content=\"website\" /> 
  <meta property=\"og:url\"    content=\"http://".$_SERVER['SERVER_NAME']."/ssc\" /> 
  <meta property=\"og:title\"  content=\"SilverSteam\" />
  <meta property=\"og:description\"  content=\"Showcase of SilverSteam web-interface\" /> 
  <meta property=\"og:image\"  content=\"http://".$_SERVER['SERVER_NAME']."/ssc/img/logosss.png\" /> ";
	require_once('./css.php');
	echo "<script src=\"./jquery/jquery-2.0.3.min.js\"></script>";
	echo "<script src=\"./jquery/jquery.fastLiveFilter.js\"></script>";

	echo "<script>
			$(function() {
        			$('#search_input').fastLiveFilter('#search_list');
    			});
		</script>";
	echo "
    		<script>
			$(document).ready ( function () {
    				$(document).on ('click', '.intlink', function () {
					$('#dsccent').html('Loading data...');
        				$('#dsccent').load($(this).attr('href'));
					return false;
    				});
			});
    		</script>";
	$HTML="";
	$HTML = $HTML. "<table style=\"height:100%;\" width=100%><tr>";
	$HTML = $HTML. "<td id=\"tdlist\" style=\"vertical-align: top; width:15%\">";
	$HTML=$HTML. "<a class=\"intlink\" title=\"Home screen\" href=\"./news.php\"><img height=\"20px\" width=\"20px\" src=\"./img/home_white_icon.png\"></a>";
	$HTML=$HTML. "<a class=\"intlink\" title=\"F.A.Q.\" href=\"./faq.php\"><img height=\"20px\" width=\"20px\" src=\"./img/white_question_mark.png\"></a>";
	$HTML=$HTML. "<a target=\"_blank\" title=\"DarkSteam Legacy\" href=\"/darksteam\"><img height=\"20px\" width=\"20px\" src=\"./img/visualstudio-2048.png\"></a>";
	$HTML = $HTML. "<br><input alt=\"Search\" id=\"search_input\" placeholder=\"Type to search\">";
	$HTML = $HTML. "<span id=\"dsclist\" name=\"dsclist\"><div id=\"gameitems\" name=\"gameitems\" style=\"overflow-y:auto; height:720px; overflow-x:hidden; text-overflow:clip;\"><ul id=\"search_list\" style=\"text-overflow:clip;\">";
	if ($shownewscache = capcu_fetch("ss_gamelist")) {
		$HTML=$HTML. $shownewscache;
	} else {
		$gamelisthtml="";
		$link = mysqli_connect("$host", "$username", "$password", "$db_name");
		if (!$link) {
			die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
		} else {
			$gamelist = mysqli_query($link, "SELECT appID, parentid, type, GameName, is_free FROM ss__dsgamelist ORDER BY GameName ASC");
			while($row = mysqli_fetch_assoc($gamelist)){
				$color="whitesmoke";
				$colortag="";
				//if ($row['UpToDate'] == 0) {
				//	$color="bronze";
				//	$colortag="[Outdated] ";
				//}
				//if ($row['GenAva'] == 0) {
					$color="slateblue";
					//$colortag="[Unavailable] ";
				//}
				if ($row['is_free'] == 1) {
					$color="gold";
					$colortag="[Free] ";
				}
				if ($row['appID'] == $row['parentid'] and $row['type'] != "dlc") {
					$gamelisthtml=$gamelisthtml. "<li type=\"base\" appid=\"".$row['appID']."\"><a class=\"intlink\" target=\"dsccent\" title=\"".$colortag.$row['GameName']."\" href=\"/ssc/{$row['appID']}\"><font color=".$color.">{$row['GameName']}</font></a></li>";
				} else {
					$gamelisthtml=$gamelisthtml. "<li type=\"dlc\" style=\"display:none;\" appid=\"".$row['appID']."\"><a class=\"intlink\" target=\"dsccent\" title=\"".$colortag.$row['GameName']."\" href=\"/ssc/{$row['appID']}\"><font color=".$color.">{$row['GameName']}</font></a></li>";
				}
			}
		}
		mysqli_close($link);
		capcu_store("ss_gamelist", $gamelisthtml, 86400000);
		$HTML=$HTML. $gamelisthtml;
	}
	$HTML=$HTML. "</ul></div></span></td>";
	$HTML=$HTML. "<td style=\"vertical-align: top; border-left:solid; border-width:1px\"><div style=\"height:720px; overflow-x:hidden; overflow-y:auto;\" id=\"dsccent\" name=\"dsccent\">Loading latest games...</div></td>";
	$HTML=$HTML . "</table>
		<script>
			document.getElementById('tdlist').style.width='10px';
			$(\"#dsccent\").load(\"./news.php\");
		</script>";
echo $HTML;



echo "</body>";
?>