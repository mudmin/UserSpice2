<?php
/*
UserCake Responsive 2.5.0
by Dan Hoover

based on
UserCake Version: 2.0.2
http://usercake.com

UserCake created by: Adam Davis
UserCake V2.0 designed by: Jonathan Cassels

Please note that this version uses technology that some consider
to be outdated. This version is designed as a cosmetic upgrade for
users of 2.0.2 and as a path towards development of version 3.0 and beyond
*/
session_start();
$md5_hash = md5(rand(0,99999));
$security_code = substr($md5_hash, 25, 5);
$enc = md5($security_code);
$_SESSION['captcha'] = $enc;

$width = 150;
$height = 30;

$image = ImageCreate($width, $height);
$white = ImageColorAllocate($image, 255, 255, 255);
$black = ImageColorAllocate($image, 0, 0, 0);
$grey = ImageColorAllocate($image, 200, 200, 200);

ImageFill($image, 0, 0, $white);
ImageString($image, 10, 5, 0, $security_code, $black);

header("Content-Type: image/png");
ImagePng($image);
ImageDestroy($image);

?>
