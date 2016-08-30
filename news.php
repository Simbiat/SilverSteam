<?php
require_once('./commonfunc.php');

ini_set( "display_errors", 0);

require_once "../genfunc/config.php";

	echo "<title>SilverSteam</title>";
	echo "<meta property=\"og:type\"   content=\"website\" /> 
  <meta property=\"og:url\"    content=\"http://".$_SERVER['SERVER_NAME']."/ssc\" /> 
  <meta property=\"og:title\"  content=\"SilverSteam\" />
  <meta property=\"og:description\"  content=\"Showcase of SilverSteam web-interface\" /> 
  <meta property=\"og:image\"  content=\"http://".$_SERVER['SERVER_NAME']."/ssc/img/logo.png\" /> ";
	require_once('./css.php');
	echo "<script src=\"/ssc/jquery/jquery-2.0.3.min.js\"></script>";
	echo "<script src=\"/ssc/jquery/jquery.fastLiveFilter.js\"></script>";
/*
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
*/
	// ###### LATEST GAMES ######

	echo "<span id=\"latest\" name=\"latest\">";
	if ($showlg = capcu_fetch("ss_news")) {
		require_once('./css.php');
		echo $showlg;
	} else {
		//require_once('./css.php');
		$showlg="";
		$link = mysqli_connect("$host", "$username", "$password", "$db_name");
		if (!$link) {
			die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
		} else {
			//$showlg=$showlg. "<link href=\"/ssc/jquery/nestedstyle.css\" rel=\"stylesheet\" type=\"text/css\" media=\"screen\" />";
			$showlg=$showlg. "<table>";
			$showlg=$showlg. "<tr>";
			$showlg=$showlg. "<td style=\"height:720px;vertical-align: top;border-right:solid;border-left:solid;border-width:1px\" align=left width=33%>";
			//$newsget1 = mysqli_query($link, "(SELECT ss__vb_node.nodeid as id, ss__vb_node.htmltitle as title, ss__vb_node.publishdate as date, ss__vb_node.publishdate as added, ss__vb_text.rawtext as text FROM ss__vb_node INNER JOIN ss__vb_text ON ss__vb_node.nodeid=ss__vb_text.nodeid WHERE ss__vb_node.parentid=17 order by ss__vb_node.publishdate LIMIT 25) UNION ALL (SELECT ss__dsgamelist.appid as id, ss__dsgamelist.GameName as title, ss__dsgamelist.UpdatedOn as date, ss__dsgamelist.AddedOn as added, ss__dsgamelist.appID as text FROM ss__dsgamelist WHERE UpdatedOn != \"\" ORDER By ss__dsgamelist.UpdatedOn DESC LIMIT 25) ORDER By date DESC");
			$newsget1 = mysqli_query($link, "SELECT ss__dsgamelist.appid as id, ss__dsgamelist.GameName as title, ss__dsgamelist.UpdatedOn as date, ss__dsgamelist.AddedOn as added, ss__dsgamelist.appID as text FROM ss__dsgamelist WHERE UpdatedOn != \"\" ORDER By ss__dsgamelist.UpdatedOn DESC LIMIT 25");
			if (mysqli_num_rows($newsget1)) {
				require_once('./css.php');
				$showlg=$showlg . "<div id=\"newsdiv\" name=\"newsdiv\">";
				while ($row = mysqli_fetch_array($newsget1, MYSQLI_ASSOC)){
					if ($row['text'] !== $row['id']) {
						$newstext=str_ireplace("[b]", "<b>", $row['text']);
						$newstext=str_ireplace("[/b]", "</b>", $newstext);
						$newstext=str_ireplace("[i]", "<i>", $newstext);
						$newstext=str_ireplace("[/i]", "</i>", $newstext);
						$newstext=str_ireplace("[u]", "<u>", $newstext);
						$newstext=str_ireplace("[/u]", "</u>", $newstext);
						$newstext=str_ireplace("[left]", "<div align=\"left\">", $newstext);
						$newstext=str_ireplace("[/left]", "</div>", $newstext);
						$newstext=str_ireplace("[right]", "<div align=\"right\">", $newstext);
						$newstext=str_ireplace("[/right]", "</div>", $newstext);
						$newstext=str_ireplace("[center]", "<div align=\"center\">", $newstext);
						$newstext=str_ireplace("[/center]", "</div>", $newstext);
						$newstext=str_ireplace("[color=", "<font color=", $newstext);
						$newstext=str_ireplace("[/color]", "</font>", $newstext);
						$newstext=str_ireplace("[size=", "<font size=", $newstext);
						$newstext=str_ireplace("[/size]", "</font>", $newstext);
						$newstext=str_ireplace("[font=", "<font face=", $newstext);
						$newstext=str_ireplace("[/font]", "</font>", $newstext);
						$newstext=str_ireplace("[url=", "<a href=", $newstext);
						$newstext=str_ireplace("[/url]", "</a>", $newstext);
						$newstext=str_ireplace("[attach]", "", $newstext);
						$newstext=str_ireplace("[/attach]", "", $newstext);
						$newstext=str_ireplace("]", ">", $newstext);
						$newstext=str_ireplace("[url>", "", $newstext);
						$newstext=str_ireplace("\">", "", $newstext);
						$newstext = preg_replace("~[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]~", "<a href=\"\\0\">\\0</a>", $newstext);
						$showlg=$showlg. nl2br("<b><font size=4 color=gold>".$row['title']."</font></b><br><i><font size=1 color=darkviolet>Published on ".date("d/m/Y", $row['date'])." at ".date("H:i", $row['date'])."</font></i><BR>".$newstext)."<BR><BR>";
					} else {
						$showlg=$showlg. "<a class=\"intlink\" href=\"/ssc/".$row['id']."\"><b><font size=4 color=gold>\"".$row['title']."\" ";
						if ($row['date']-$row['added']<86400) {
							$showlg=$showlg. "added";
						} else {
							$showlg=$showlg. "updated";
						}
						$showlg=$showlg. "</font></b></a><br><i><font size=1 color=darkviolet>Published on ".date("d/m/Y", $row['date'])." at ".date("H:i", $row['date'])."</font></i><BR><a class=\"intlink\" href=\"/ssc/".$row['id']."\"><img src=\"".headerimg($row['id'])."\"></a><BR><BR>";
					}
				}
			}
			$showlg=$showlg. "</td>";
			$showlg=$showlg. "<td style=\"height:720px;vertical-align: top;border-right:solid;border-left:solid;border-width:1px\" align=left width=33%>";
			$newsget1 = mysqli_query($link, "SELECT * From ss__news ORDER By date DESC LIMIT 25");
			if (mysqli_num_rows($newsget1)) {
				require_once('./css.php');
				$showlg=$showlg . "<div id=\"newsdiv\" name=\"newsdiv\">";
				while ($row = mysqli_fetch_array($newsget1, MYSQLI_ASSOC)){
					if ($row['text'] !== $row['id']) {
						$newstext=str_ireplace("[b]", "<b>", $row['text']);
						$newstext=str_ireplace("[/b]", "</b>", $newstext);
						$newstext=str_ireplace("[i]", "<i>", $newstext);
						$newstext=str_ireplace("[/i]", "</i>", $newstext);
						$newstext=str_ireplace("[u]", "<u>", $newstext);
						$newstext=str_ireplace("[/u]", "</u>", $newstext);
						$newstext=str_ireplace("[left]", "<div align=\"left\">", $newstext);
						$newstext=str_ireplace("[/left]", "</div>", $newstext);
						$newstext=str_ireplace("[right]", "<div align=\"right\">", $newstext);
						$newstext=str_ireplace("[/right]", "</div>", $newstext);
						$newstext=str_ireplace("[center]", "<div align=\"center\">", $newstext);
						$newstext=str_ireplace("[/center]", "</div>", $newstext);
						$newstext=str_ireplace("[color=", "<font color=", $newstext);
						$newstext=str_ireplace("[/color]", "</font>", $newstext);
						$newstext=str_ireplace("[size=", "<font size=", $newstext);
						$newstext=str_ireplace("[/size]", "</font>", $newstext);
						$newstext=str_ireplace("[font=", "<font face=", $newstext);
						$newstext=str_ireplace("[/font]", "</font>", $newstext);
						$newstext=str_ireplace("[url=", "<a href=", $newstext);
						$newstext=str_ireplace("[/url]", "</a>", $newstext);
						$newstext=str_ireplace("[attach]", "", $newstext);
						$newstext=str_ireplace("[/attach]", "", $newstext);
						$newstext=str_ireplace("]", ">", $newstext);
						$newstext=str_ireplace("[url>", "", $newstext);
						$newstext=str_ireplace("\">", "", $newstext);
						$newstext = preg_replace("~[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]~", "<a href=\"\\0\">\\0</a>", $newstext);
						$showlg=$showlg. nl2br("<a href=\"/articles/".$row['id']."\"><b><font size=4 color=gold>".$row['title']."</font></b></a><br><i><font size=1 color=darkviolet>Published on ".date("d/m/Y", $row['date'])." at ".date("H:i", $row['date'])."</font></i><BR>".$newstext)."<BR><BR>";
					} else {
						$showlg=$showlg. "<a class=\"intlink\" href=\"/ssc/".$row['id']."\"><b><font size=4 color=gold>\"".$row['title']."\" ";
						if ($row['date']-$row['added']<86400) {
							$showlg=$showlg. "added";
						} else {
							$showlg=$showlg. "updated";
						}
						$showlg=$showlg. "</font></b></a><br><i><font size=1 color=darkviolet>Published on ".date("d/m/Y", $row['date'])." at ".date("H:i", $row['date'])."</font></i><BR><a class=\"intlink\" href=\"/ssc/".$row['id']."\"><img src=\"".headerimg($row['id'])."\"></a><BR><BR>";
					}
				}
			}
			$showlg=$showlg. "</td></tr></table>
				";
			mysqli_close($link);
		}
		echo $showlg;
		capcu_store("ss_news", $showlg, 43200);
	}
	echo "</span>";


echo "</body>";
?>