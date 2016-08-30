<?php
require_once('./commonfunc.php');

require_once "../genfunc/config.php";

// #######################################################################
// ######################## START MAIN SCRIPT ############################
// #######################################################################

require_once('./css.php');
if (empty($_GET['id'])) {
	if ($showfaq = capcu_fetch("ss_faqlist")) {
		echo $showfaq;
	} else {
		$showfaq="";
		$showfaq=$showfaq. "<title>F.A.Q.</title>";

		function faqproc($faqvarname, $level) {
			global $link;
			global $showfaq;
			$faqroot = mysqli_query($link, "SELECT title, id FROM ss__faq WHERE parentid=\"".$faqvarname."\" order by id ASC");
			if (mysqli_num_rows($faqroot)) {
				while ($row = mysqli_fetch_array($faqroot, MYSQLI_ASSOC)){
					$faqtitle = mysqli_query($link, "SELECT * FROM ss__faq WHERE id=".$row['id']);
					if (mysqli_num_rows($faqtitle)) {
						while ($rowname = mysqli_fetch_array($faqtitle, MYSQLI_ASSOC)){
							$faqroot2 = mysqli_query($link, "SELECT * FROM ss__faq WHERE parentid=\"".$row['id']."\" order by id ASC");
							if (mysqli_num_rows($faqroot2)) {
								$showfaq=$showfaq. "<li>".str_repeat("---", $level).$rowname['title']."</li>";
							} else {
								$showfaq=$showfaq. "<li>".str_repeat("---", $level)."<a target=\"faqanswer\" title=\"".$rowname['title']."\" href=\"./faq.php?id=".$row['id']."\">".$rowname['title']."</a></li>";
							}
							faqproc($row['id'], $level +1);
						}
					}
				}
			}
		}
		$showfaq=$showfaq. "
				<script type=\"text/javascript\" src=\"jquery/jquery-2.0.3.min.js\"></script>
				<script src=\"jquery/jquery.fastLiveFilter.js\"></script>
				<script>
					$(function() {
        					$('#faq_input').fastLiveFilter('#faq_list');
    					});
				</script>
			";
		$showfaq=$showfaq. "
<div style=\"overflow: auto; max-height: 720px;\"><table><tr><td style=\"vertical-align: top\" width=30%><div><input alt=\"Search\" id=\"faq_input\" placeholder=\"Type to search\"></div><div style=\"overflow-x:hidden; overflow-y:auto; max-height: 720px;\"><ul id=\"faq_list\">";
$link = mysqli_connect("$host", "$username", "$password", "$db_name");
if (!$link) {
	die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
} else {
	$faqroot = mysqli_query($link, "SELECT title, id FROM ss__faq WHERE parentid=0 order by id ASC");
	if (mysqli_num_rows($faqroot)) {
		while ($row = mysqli_fetch_array($faqroot, MYSQLI_ASSOC)){
			$faqtitle = mysqli_query($link, "SELECT * FROM ss__faq WHERE id=".$row['id']);
			if (mysqli_num_rows($faqtitle)) {
				while ($rowname = mysqli_fetch_array($faqtitle, MYSQLI_ASSOC)){
					$showfaq=$showfaq. "<li>".$rowname['title']."</li>";
					faqproc($row['id'], 1);
				}
			}
		}
	}
}
$showfaq=$showfaq. "</div></td><td style=\"vertical-align: top\"><div style=\"overflow: auto; max-height: 720px;\"><iframe width=100% height=720px frameborder=0 id=\"faqanswer\" src=\"./faq.php?id=welcomemessage\"></iframe></div></td></tr></table></div>";
echo $showfaq;
capcu_store("ss_faqlist", $showfaq, 86400);
}
} else {
	$faqtoshow = $_GET['id'];
if ($showfaq = capcu_fetch("ss_faq_item_".$faqtoshow)) {
	echo $showfaq;
} else {
	$showfaq="";
	$showfaq=$showfaq. "<title>F.A.Q.</title>";
	if ($faqtoshow == "welcomemessage") {
		$showfaq=$showfaq. "Select the question of interest to the left to view the answer to it";
	} else {
		$link = mysqli_connect("$host", "$username", "$password", "$db_name");
		if (!$link) {
			die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
		} else {
			$faqtitle = mysqli_query($link, "SELECT text FROM ss__faq WHERE id=".$faqtoshow);
			if (mysqli_num_rows($faqtitle)) {
				while ($rowname = mysqli_fetch_array($faqtitle, MYSQLI_ASSOC)){
					$showfaq=$showfaq. $rowname['text']."<BR>";
				}
			}
		}
	}
echo $showfaq;
capcu_store("ss_faq_item_".$faqtoshow, $showfaq, 86400);
}
}

?>