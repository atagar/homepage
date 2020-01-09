<?php
// Our configured variant of securimage/securimage_show.php

require_once dirname(__FILE__) . '/securimage/securimage.php';

$img = new Securimage();

$img->captcha_type = Securimage::SI_CAPTCHA_MATHEMATIC; // simple math problem
$img->perturbation = .75;
$img->use_random_baseline = true;
$img->use_random_boxes = true;
$img->num_lines = mt_rand(2, 4);

if (!empty($_GET['namespace'])) $img->setNamespace($_GET['namespace']);
$img->show();  // outputs the image and content headers to the browser
?>
