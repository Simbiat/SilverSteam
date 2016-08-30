<?php
require_once('./commonfunc.php');
if (empty($_GET['text'])) {
	$text="I'm not here for your amusement";
} else {
	$text=decrypt($_GET['text']);
}

$text=wordwrap($text, 30, "\n", TRUE);

//setting the image header in order to proper display the image
header("Content-Type: image/png");
//try to create an image
$im = @imagecreate(460, 215)
    or die("Cannot Initialize new GD image stream");
//set the background color of the image
$background_color = imagecolorallocate($im, 0x00, 0x00, 0x00);
//set the color for the text
$text_color = imagecolorallocate($im, 0xFF, 0xFF, 0xFF);
//adf the string to the image

$font = "verdana.ttf";
$font_size = 20;
$angle = 0;

$splittext = explode ( "\n" , $text );
$lines = count($splittext);

foreach ($splittext as $text) {
	$text_box = imagettfbbox($font_size,$angle,$font,$text);
	$text_width = abs(max($text_box[2], $text_box[4]));
	$text_height = abs(max($text_box[5], $text_box[7]));
	$x = (imagesx($im) - $text_width)/2;
	$y = ((imagesy($im) + $text_height)/2)-($lines-1)*$text_height;
	$lines=$lines-1;
	imagettftext($im, $font_size, $angle, $x, $y, $text_color, $font, $text);
}


imagepng($im);
imagedestroy($im);
?>