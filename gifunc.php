<?php
function ginfo($appid, $toecho) {
global $host, $username, $password, $db_name, $showgi, $steamapikey;
$link = mysqli_connect("$host", "$username", "$password", "$db_name");
if (!$link) {
	die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
} else {
	$gamegrab = mysqli_query($link, "SELECT * FROM ss__dsgamelist WHERE appID=\"".$appid."\"");
	if (mysqli_num_rows($gamegrab)) {
		//outputstatus("Checking for updates...");
		//ob_flush();
		//flush();
		appidgrab($appid, "update");
		$row = mysqli_fetch_array($gamegrab, MYSQLI_ASSOC);
		$showgi=$showgi. "<title>SilverSteam: ".$row['GameName']."</title>";
		$showgi=$showgi. "<meta property=\"og:type\"   content=\"website\" /> 
			<meta property=\"og:url\"    content=\"http://".$_SERVER['SERVER_NAME']."/ssc/".$appid."\" /> 
			<meta property=\"og:title\"  content=\"SilverSteam: ".$row['GameName']."\" />
			<meta property=\"og:description\"  content=\"SilverSteam: Details for ".$row['GameName']."\" /> ";
		$showgi=$showgi. "<script src=\"/ssc/jquery/jquery-2.0.3.min.js\"></script>";
		if (checkRemoteFile("http://cdn.akamai.steamstatic.com/steam/apps/".$row['appID']."/header.jpg?key=".$steamapikey) === true) {
			$showgi=$showgi. "<meta property=\"og:image\" content=\"".headerimg($appid)."\" />";
			$showgi=$showgi. "<center><a class=\"intlink\" href=\"/ssc/".$appid."\"><img style=\"max-width:460;max-height:215;\" width=60% src=\"".headerimg($appid)."\" alt=\"".$row['GameName']."\" title=\"".$row['GameName']."\"></a></center>";
		} else {
			$showgi=$showgi. "<meta property=\"og:image\" content=\"/ssc/headergen.php?text=".Encrypt($row['GameName'])."\">";
			$showgi=$showgi. "<center><a class=\"intlink\" href=\"/ssc/".$appid."\"><img style=\"max-width:460;max-height:215;\" width=60% src=\"/ssc/headergen.php?text=".Encrypt($row['GameName'])."\" alt=\"".$row['GameName']."\" title=\"".$row['GameName']."\"></a></center>";
		}
		$parentscreen = "";
		if ($row['gamefeatures'] != "") {
			$showgi=$showgi. "<center><table width=460px><tr><td align=center>";
			if (stripos($row['gamefeatures'], "captions") !== false) {
				$showgi=$showgi. "<img src=\"/ssc/feats/steamfeat_captions_available.png\" alt=\"Close captions available\" title=\"Close captions available\">";
			}
			if (stripos($row['gamefeatures'], "coop") !== false or stripos($row['gamefeatures'], "co-op") !== false) {
				$showgi=$showgi. "<img src=\"/ssc/feats/steamfeat_co_op.png\" alt=\"Internet or LAN co-op mode\" title=\"Internet or LAN co-op mode\">";
			}
			if (stripos($row['gamefeatures'], "commentary") !== false) {
				$showgi=$showgi. "<img src=\"/ssc/feats/steamfeat_commentary_available.png\" alt=\"Commentary available\" title=\"Commentary available\">";
			}
			if (stripos($row['gamefeatures'], "controller") !== false) {
				$showgi=$showgi. "<img src=\"/ssc/feats/steamfeat_full_controller_support.png\" alt=\"Gamepad support\" title=\"Gamepad support\">";
			}
			if (stripos($row['gamefeatures'], "hdr") !== false) {
				$showgi=$showgi. "<img src=\"/ssc/feats/steamfeat_hdr_available.png\" alt=\"HDR available\" title=\"HDR available\">";
			}
			if (stripos($row['gamefeatures'], "level editor") !== false or stripos($row['gamefeatures'], "sdk") !== false) {
				$showgi=$showgi. "<img src=\"/ssc/feats/steamfeat_includes_level_editor.png\" alt=\"Includes level editor or SDK\" title=\"Includes level editor or SDK\">";
			}
			if (stripos($row['gamefeatures'], "mods") !== false) {
				$showgi=$showgi. "<img src=\"/ssc/feats/steamfeat_mods.gif\" alt=\"Supports mods\" title=\"Supports mods\">";
			}
			if (stripos($row['gamefeatures'], "mmo") !== false or stripos($row['gamefeatures'], "multi-player") !== false or stripos($row['gamefeatures'], "multiplayer") !== false) {
				$showgi=$showgi. "<img src=\"/ssc/feats/steamfeat_multi_player.gif\" alt=\"Multiplayer\" title=\"Multiplayer\">";
			}
			if (stripos($row['gamefeatures'], "singleplayer") !== false or stripos($row['gamefeatures'], "single-player") !== false) {
				$showgi=$showgi. "<img src=\"/ssc/feats/steamfeat_single_player.png\" alt=\"Singleplayer\" title=\"Singleplayer\">";
			}
			if (stripos($row['gamefeatures'], "stats") !== false) {
				$showgi=$showgi. "<img src=\"/ssc/feats/steamfeat_stats.png\" alt=\"Statistics\" title=\"Statistics\">";
			}
			if (stripos($row['gamefeatures'], "achievements") !== false) {
				$showgi=$showgi. "<img src=\"/ssc/feats/steamfeat_steam_achievements.png\" alt=\"Achievements\" title=\"Achievements\">";
			}
			if (stripos($row['gamefeatures'], "cloud") !== false) {
				$showgi=$showgi. "<img src=\"/ssc/feats/steamfeat_steam_cloud.png\" alt=\"Steam cloud\" title=\"Steam cloud\">";
			}
			if (stripos($row['gamefeatures'], "leaderboards") !== false) {
				$showgi=$showgi. "<img src=\"/ssc/feats/steamfeat_steam_leaderboards.gif\" alt=\"Leaderboards\" title=\"Leaderboards\">";
			}
			if (stripos($row['gamefeatures'], "trading cards") !== false) {
				$showgi=$showgi. "<img src=\"/ssc/feats/steamfeat_steam_trading_cards.png\" alt=\"Steam trading cards\" title=\"Steam trading cards\">";
			}
			if (stripos($row['gamefeatures'], "workshop") !== false) {
				$showgi=$showgi. "<img src=\"/ssc/feats/steamfeat_steam_workshop.png\" alt=\"Steam workshop\" title=\"Steam workshop\">";
			}
			if (stripos($row['gamefeatures'], "anti-cheat") !== false) {
				$showgi=$showgi. "<img src=\"/ssc/feats/steamfeat_valve_anti_cheat_enabled.gif\" alt=\"Valve anti-cheat\" title=\"Valve anti-cheat\">";
			}
			$showgi=$showgi. "</td></tr></table></center>";
		}
		$showgi=$showgi. "<br>";


		$showgi=$showgi."<b>".$row['GameName']."</b> is a ";
		if ($row['type'] == "game") {
			$showgi=$showgi."game ";
		} else {
			if ($row['appID'] !== $row['parentid']) {
				if ($row['type'] == "dlc") {
					$showgi=$showgi."DLC ";
				} else if ($row['type'] == "mod") {
					$showgi=$showgi."MODification ";
				} else {
					$showgi=$showgi.$row['type']." ";
				}
				$pgamegrab = mysqli_query($link, "SELECT GameName, screenshots FROM ss__dsgamelist WHERE appID=\"".$row['parentid']."\"");
				if (mysqli_num_rows($pgamegrab)) {
					while ($row2 = mysqli_fetch_array($pgamegrab, MYSQLI_ASSOC)) {
						$parentscreen = $row2['screenshots'];
						$showgi=$showgi. "for \"<a class=\"intlink\" href=\"/ssc/".$row['parentid']."\"><b>".$row2['GameName']."</b></a>\"";
					}
				}
			} else {
				$showgi=$showgi.$row['type']." ";
			}
		}
		if ($row['developers'] != "") {
			if (strpos($row['developers'], ";") !== false) {
    				$developers=explode(";", $row['developers']);
				$showgi=$showgi. " developed by ";
				for ($i = 0; $i <= (count($developers)-1); $i++) {
					if ($i < (count($developers)-1)) {
						$showgi=$showgi. "<i>".$developers[$i]."</i>, ";
					} else {
						$showgi=$showgi. "and <i>".$developers[$i]."</i>";
					}
				}
			} else {
				$showgi=$showgi. " developed by <i>".$row['developers']."</i>";
			}
		}
		if ($row['publishers'] != "") {
			if (strpos($row['publishers'], ";") !== false) {
    				$publishers=explode(";", $row['publishers']);
				$showgi=$showgi. " and published by ";
				for ($i = 0; $i <= (count($publishers)-1); $i++) {
					if ($i < (count($publishers)-1)) {
						$showgi=$showgi. "<i>".$publishers[$i]."</i>, ";
					} else {
						$showgi=$showgi. "and <i>".$publishers[$i]."</i>";
					}
				}
			} else {
				$showgi=$showgi. " and published by <i>".$row['publishers']."</i>";
			}
		}
		if ($row['releasedate'] != "" and $row['releasedate'] != 0) {
			$showgi=$showgi. " with formal release date as per Steam <i>".date("d.m.Y", $row['releasedate'])."</i>.";
		} else {
			$showgi=$showgi. ".";
		}
		if ($row['reqage'] != "" and $row['reqage'] != "0") {
			$showgi=$showgi. " This content is recommended for people of age <i>".$row['reqage']."</i> or older.";
		}
		if ($row['gamedesc'] != "") {
			$showgi=$showgi. " It is described by developer/publisher as <a class=\"toglink\" id=\"adescription\" target=_self href=\"description\">follows</a>.<div id=\"description\" style=\"display:none\"><br><table border=\"1\"><tr><td>".$row['gamedesc']."</td></tr></table><br></div>";
		}
		$showgi=$showgi. "<br>";
		if ($row['genres'] != "") {
			if (strpos($row['genres'], ";") !== false) {
    				$genres=explode(";", $row['genres']);
				$showgi=$showgi. "The main genre niches are registered as ";
				for ($i = 0; $i <= (count($genres)-1); $i++) {
					if ($i < (count($genres)-1)) {
						$showgi=$showgi. "<i>".$genres[$i]."</i>, ";
					} else {
						$showgi=$showgi. "and <i>".$genres[$i]."</i>.";
					}
				}
			} else {
				$showgi=$showgi. "The main genre niche is registered as <i>".$row['genres']."</i>.";
			}
		}
		if ($row['Language'] != "") {
			if (strpos($row['Language'], "<br>") !== false) {
    				$Language=explode("<br>", $row['Language']);
				$showgi=$showgi. " Steam version provides the following languages ";
				for ($i = 0; $i <= (count($Language)-1); $i++) {
					if ($i < (count($Language)-1)) {
						$showgi=$showgi. "<i>".$Language[$i]."</i>, ";
					} else {
						$showgi=$showgi. "and <i>".$Language[$i]."</i>";
					}
				}
			} else {
				$showgi=$showgi. " Steam version provides the following language <i>".$row['Language']."</i>";
			}
			if (strpos($row['Language'], "*") !== false) {
				$showgi=$showgi. " <span title=\"Asterisk (*) denotes languages with full audio support\"><a target=_self href=\"".$_SERVER["REQUEST_URI"]."#\"><sup style=\"font-size:xx-small; vertical-align:super;\">[?]</sup></a></span>";
			}
			$showgi=$showgi. ".";
		}
		if ($row['reqmin'] != "") {
			$showgi=$showgi. "<br>This game has following <a class=\"toglink\" id=\"areqmin\" target=_self href=\"reqmin\">minimum requirements</a><div id=\"reqmin\" style=\"display:none\"><br>".$row['reqmin']."</div>";
		}
		if ($row['reqrec'] != "") {
			$showgi=$showgi. "<br>For best experience your gamestation should meet these <a class=\"toglink\" id=\"areqrec\" target=_self href=\"reqrec\">recommended specifications</a><div id=\"reqrec\" style=\"display:none\"><br>".$row['reqrec']."</div>";
		}
		$dlcgamegrab = mysqli_query($link, "SELECT GameName, appID FROM ss__dsgamelist WHERE parentid=\"".$appid."\" and (type=\"dlc\" or type=\"mod\") ORDER By GameName");
		if (mysqli_num_rows($dlcgamegrab)) {
			$showgi=$showgi. "<br>These <a class=\"toglink\" id=\"adlcinfo\" target=_self href=\"dlcinfo\">DLC</a> may enhance your playthrough<div id=\"dlcinfo\" style=\"table-layout: fixed;display:none\"><br>";
			while ($row3 = mysqli_fetch_array($dlcgamegrab, MYSQLI_ASSOC)) {
				$showgi=$showgi. "<a class=\"intlink\" href=\"/ssc/".$row3['appID']."\" title=\"".$row3['GameName']."\"><img width=153px height=71px src=\"".headerimg($row3['appID'])."\"></a><br><br>";
			}
			$showgi=$showgi. "</div>";
		}
		if ($row['screenshots'] != "") {
			$screenshots = $row['screenshots'];
		} else {
			$screenshots = "";
		}
		/*
		if ($parentscreen != "") {
			$pscarray=explode(";", $parentscreen);
			$childarray=explode(";", $screenshots);
			foreach ($pscarray as $pscreen) {
				$pscreens = $row['parentid']."_".substr($pscreen, strrpos($pscreen, '/') + 1);
				screenshotimg($row['parentid'], $pscreen);
				foreach ($childarray as $cscreen) {
					$cscreens = $appid."_".substr($cscreen, strrpos($cscreen, '/') + 1);
					screenshotimg($appid, $cscreen);
					//$class = new compareImages;
					//if ($class->compare("./img/screens/".$cscreens,"./img/screens/".$pscreens)<10) {
					//	$screenshots=str_replace($cscreen.";", "", $screenshots);
					//	$screenshots=str_replace($cscreen, "", $screenshots);
					//}
				}
			}
		}
		*/
		if ($screenshots != "") {
			$showgi=$showgi. "<br>These <a class=\"toglink\" id=\"ascreens\" target=_self href=\"screens\">screenshots</a> may give you a taste of what the game looks like<div id=\"screens\" style=\"display:none\"><br>";
			$scrarray=explode(";", $screenshots);
			foreach ($scrarray as $dep) {
				if (stripos($dep, "1920x1080") !== false) {
					if (strpos($dep, "?")) {
						$depnew = substr($dep, 0, strpos($dep, "?"));
					} else {
						$depnew = $dep;
					}
					$depnew = substr($depnew, strrpos($depnew, '/') + 1);
					$showgi = $showgi. "<a target=_blank href=\"http://cdn.akamai.steamstatic.com/steam/apps/".$appid."/".$depnew."\"><img style=\"max-width:600;\" width=90% title=\"Click me for new tab\" src=\"http://cdn.akamai.steamstatic.com/steam/apps/".$appid."/".$depnew."\"></a><br><br>";
					//$showgi = $showgi. "<a target=_blank href=\"http://cdn.akamai.steamstatic.com/steam/apps/".$appid."/".$depnew."\"><img style=\"max-width:600;\" width=90% title=\"Click me for new tab\" src=\"screens/".$appid."/".$depnew."\"></a><br><br>";
					//$showgi=$showgi. "<a target=_blank href=\"".screenshotimg($appid, $dep)."\"><img style=\"max-width:600;\" width=90% title=\"Click me for new tab\" src=\"".screenshotimg($appid, $dep)."\"></a><br><br>";
				}
			}
			$showgi=$showgi. "</div>";
		}
		if ($row['type'] == "movie" ) {
			$showgi=$showgi. "<br>Watch the <a target=_blank href=\"http://steamcdn-a.akamaihd.net/steam/apps/".$appid."/movie_max.webm\" title=\"".$row['GameName']."\">movie</a>";
		} else {
			$moviegamegrab = mysqli_query($link, "SELECT GameName, appID FROM ss__dsgamelist WHERE parentid=\"".$appid."\" and type=\"movie\" ORDER By GameName");
			if (mysqli_num_rows($moviegamegrab)) {
				$showgi=$showgi. "<br>These <a class=\"toglink\" id=\"amovies\" target=_self href=\"movies\">trailers</a> may show you content's best sides<div id=\"movies\" style=\"table-layout: fixed;display:none\"><br>";
				while ($row3 = mysqli_fetch_array($moviegamegrab, MYSQLI_ASSOC)) {
					$showgi=$showgi. "<a target=_blank href=\"http://steamcdn-a.akamaihd.net/steam/apps/".$row3['appID']."/movie_max.webm\" title=\"".$row3['GameName']."\"><img width=153px height=71px src=\"".headerimg($row3['appID'])."\"></a><br><br>";
				}
				$showgi=$showgi. "</div>";
			}
		}
		if ($row['type'] == "demo" ) {
			$showgi=$showgi. "<br>Try the <a target=_blank href=\"steam://install/".$appid."\" title=\"".$row['GameName']."\">demo</a>";
		} else if ($row['is_free'] == 1 ) {
			$showgi=$showgi. "<br>Try the <a target=_blank href=\"steam://install/".$appid."\" title=\"".$row['GameName']."\">game</a>";
		} else {
			$demogamegrab = mysqli_query($link, "SELECT GameName, appID FROM ss__dsgamelist WHERE parentid=\"".$appid."\" and type=\"demo\" ORDER By GameName");
			if (mysqli_num_rows($demogamegrab)) {
				$showgi=$showgi. "<br>This <a class=\"toglink\" id=\"amovies\" target=_self href=\"demo\">demo version</a> may help you actually try the content<div id=\"demo\" style=\"table-layout: fixed;display:none\"><br>";
				while ($row3 = mysqli_fetch_array($demogamegrab, MYSQLI_ASSOC)) {
					$showgi=$showgi. "<a target=_blank href=\"steam://install/".$row3['appID']."\" title=\"".$row3['GameName']."\"><img width=153px height=71px src=\"".headerimg($row3['appID'])."\"></a><br><br>";
				}
				$showgi=$showgi. "</div>";
			}
		}
		$showgi=$showgi. "<br>";
		if ($row['offwebsite'] != "") {
			$showgi=$showgi. "This content has <a target=_blank href=\"".$row['offwebsite']."\">this</a> website registered as official one. ";
		}
                $showgi=$showgi. "<a target=_blank href=\"http://store.steampowered.com/app/".$row['appID']."\">Here</a> it's possible to try to open the content's page on Steam, but some regional restrictions may apply.<br>
				This content uses ID ".$appid." in Steam, which can be used, for example, to get some basic information in JSON format via this <a target=_blank href=\"http://store.steampowered.com/api/appdetails/?appids=".$appid."\">link</a>.";
		if ($row['UpdatedOn'] != "" and $row['UpdatedOn'] != "NULL") {
			$showgi=$showgi. "<br>Last update [check] by SilverSteam was done on ".date("d.m.Y", $row['UpdatedOn'])." at ".date("H:i", $row['UpdatedOn']);
		}
		$dirmatches = glob("./depot manifests/".$appid." - *");
		if (!empty($dirmatches)) {
			foreach ($dirmatches as $dirmatch) {
				$filesmatches = glob($dirmatch."/*.*");
				if (!empty($filesmatches)) {
					$showgi=$showgi. "<br>";
					$showgi=$showgi. "SilverSteam has following <a class=\"toglink\" id=\"adepots\" target=_self href=\"depots\">depots</a> available for download<div id=\"depots\" style=\"display:none\"><br>";
					$showgi=$showgi. "<a href=\"/ssc/zip/".$appid."\" target=\"_blank\">Download all</a><br><br>";
					foreach ($filesmatches as $filematch) {
						$fileexpl = explode("/",$filematch);
						$showgi=$showgi. "<a href=\"/ssc/depot manifests/".$fileexpl[count($fileexpl)-2]."/".$fileexpl[count($fileexpl)-1]."\">".$fileexpl[count($fileexpl)-1]."</a><br>";
					}
					$showgi=$showgi. "<br></div>";
				}
			}
		}

		$showgi=$showgi. "
			<a target=_self href=\"".$_SERVER["REQUEST_URI"]."#\" class=\"back-to-top\">Back to Top</a>
			<script>jQuery(document).ready(function() {
				var offset = $(window).height()/100;
				var duration = 500;
				jQuery(window).scroll(function() {
					if (jQuery(this).scrollTop() > offset) {
						jQuery('.back-to-top').fadeIn(duration);
					} else {
						jQuery('.back-to-top').fadeOut(duration);
					}
				});
    				jQuery('.back-to-top').click(function(event) {
					event.preventDefault();
					jQuery('html, body').animate({scrollTop: 0}, duration);
					return false;
				})
			});</script>
			";
		capcu_store("ss_gameinfo".$appid, $showgi, 86400);
		if ($toecho === true) {
			echo $showgi;
		}
		mysqli_close($link);
	}
}
}
?>