<?php
if ($showcss = capcu_fetch("ss_css")) {
	echo $showcss;
} else {
	$showcss="";
$showcss=$showcss. "<style>

body{
border-color: whitesmoke;
color: black;
font-family:Microsoft Sans Serif;
font-size:10pt;
background:#C0C0C0 none;
}

.forumblock{
background-color: transparent;
}

table, th, td, tr{
border-color: whitesmoke;
color: black;
font-family:Microsoft Sans Serif;
font-size:10pt;
}

a:link {text-decoration:none;}
a:link {color:#4169E1;}
a:visited {color:#4169E1;}
a:hover {color:#4169E1;}
a:active {color:#4169E1;}


.back-to-top {
position: fixed;
border-radius: 15px;
bottom: 1pt;
right: 0px;
text-decoration: none;
color: #000000;
background-color: rgba(151, 151, 151, 0.80);
font-size: 12px;
padding: 2pt;
display: none;
}

.back-to-top:hover {    
    background-color: rgba(151, 151, 151, 0.50);
}

ul {
list-style: none;
padding: 0;
}
</style>";
echo $showcss;
capcu_store("ss_css", $showcss, 86400);
}
?>