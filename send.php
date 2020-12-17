<?php
session_start();
$expected_token = $_SESSION['token'];
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
  "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
  <head>
    <title>Contact</title>
    <link rel="shortcut icon" href="images/favicon.png">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" type="text/css" href="fontawesome/web-fonts-with-css/css/fontawesome.css">
    <link rel="stylesheet" type="text/css" href="fontawesome/web-fonts-with-css/css/solid.css">
  </head>

  <body>
    <div class="nav">
      <a href="index.html">
        <i class="fas fa-home"></i>
        HOME
      </a>

      <a href="https://blog.atagar.com/">
        <i class="fas fa-book"></i>
        BLOG
      </a>

      <a href="resume.html">
        <i class="fas fa-user"></i>
        RESUME
      </a>

      <a href="https://nyx.torproject.org/">
        <i class="fas fa-chart-area"></i>
        NYX
      </a>

      <a href="https://stem.torproject.org/">
        <i class="fas fa-leaf"></i>
        STEM
      </a>

      <a href="contact.php">
        <i class="fas fa-envelope"></i>
        CONTACT
      </a>
    </div>

    <div id="main">
      <div id="home" class="section">
        <div class="title">
          <h1>Contact</h1>
          <span class="title-quote">&quot;The Internet: where men are men, women are men, and children are FBI agents.&quot;</span>
          <hr>
        </div>

<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/securimage/securimage.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require $_SERVER['DOCUMENT_ROOT'] . '/phpmailer/src/Exception.php';
require $_SERVER['DOCUMENT_ROOT'] . '/phpmailer/src/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/phpmailer/src/SMTP.php';

$sent = false;
$error = "";

$message = wordwrap($_POST['message'], 70);
$image = new Securimage();

if (empty($_POST['token'])) {
  $error = "no XSRF token";
} else if (!hash_equals($expected_token, $_POST['token'])) {
  $error = "XSRF token invalid";
} else if ($image->check($_POST['captcha_code']) == false) {
  $error = "captcha incorrect";
} else if (isset($_POST['submit'])) {
  $from = $_POST['email'];
  if ($from == "") $from = "anonymous";

  $email = new PHPMailer();
  $email->isSendmail();
  $email->SetFrom("webserver@atagar.com");
  $email->AddAddress("atagar1@gmail.com");
  $email->Subject = 'Comment from www.atagar.com';
  $email->Body = "E-Mail: $from\n\nMessage:\n$message";

  $sent = $email->send();

  if (!$sent) $error = $email->ErrorInfo;
}

if ($sent) {
  echo "        <h2>Thanks! Message successfully sent.</h2>
";
} else {
  echo "        <h2>Message couldn't send ($error). Please reach me by email instead.</h2>
        <img src='images/resume/email_large.png' alt='email' />
        <br><br>
        <h2>Your message was:</h2>
        <pre>$message</pre>
";
}
?>
      </div>
    </div>
  </body>
</html>
