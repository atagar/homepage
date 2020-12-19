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

function validate_attachment($attachment, $max_size) {
  # Performs validation checks, raising a RuntimeException if there's a
  # problem. This is based upon...
  #
  #   https://www.php.net/manual/en/features.file-upload.php#114004
  #   https://github.com/PHPMailer/PHPMailer/blob/master/examples/send_file_upload.phps
  #
  # Parameters:
  #
  #   $attachment     $_FILES entry for this attachment
  #   $max_size       maximum attachment size in megabytes
  #
  # Returns: filesystem path to attachment

  if (!isset($attachment['error']) || is_array($attachment['error'])) {
    throw new RuntimeException('BUG: missing or invalid error attribute');
  }

  switch ($attachment['error']) {
    case UPLOAD_ERR_OK:
      break;
    case UPLOAD_ERR_NO_FILE:
      throw new RuntimeException('no file sent');
    case UPLOAD_ERR_INI_SIZE:
    case UPLOAD_ERR_FORM_SIZE:
      throw new RuntimeException('exceeded file size limit');
    default:
      throw new RuntimeException('unrecognized error');
  }

  if ($attachment['size'] > $max_size * 1024 * 1024) {
    throw new RuntimeException("attachment must be less than $max_size MB");
  }

  $ext = PHPMailer::mb_pathinfo($attachment['name'], PATHINFO_EXTENSION);
  $upload_path = tempnam(sys_get_temp_dir(), hash('sha256', $attachment['name'])) . '.' . $ext;

  if (!move_uploaded_file($attachment['tmp_name'], $upload_path)) {
    throw new RuntimeException('BUG: failed to rename attachment');
  }

  return $upload_path;
}

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

  $attachment_filename = "none";
  if (array_key_exists('attachment', $_FILES)) $attachment_filename = $_FILES['attachment']['name'];

  $email = new PHPMailer();
  $email->isSendmail();
  $email->SetFrom("webserver@atagar.com");
  $email->AddAddress("atagar1@gmail.com");
  $email->Subject = 'Comment from www.atagar.com';
  $email->Body = "E-Mail: $from\nAttachment: $attachment_filename\n\nMessage:\n$message";

  if (array_key_exists('attachment', $_FILES)) {
    try {
      $attachment_path = validate_attachment($_FILES['attachment'], 10);

      if (!$email->addAttachment($attachment_path, 'attachment')) {
        throw new RuntimeException('failed to attach file to email');
      }
    } catch (RuntimeException $exc) {
      $error = $exc->getMessage();
    }
  }

  if (!$error) {
    $sent = $email->send();

    if (!$sent) $error = $email->ErrorInfo;
  }
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
