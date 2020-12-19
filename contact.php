<?php
session_start();

if (empty($_SESSION['token'])) {
  $_SESSION['token'] = bin2hex(random_bytes(32));
}

$token = $_SESSION['token'];
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

      <a href="#" class="nav-selected">
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

        <p>Found something useful? Spotted a mistake? I'm more than happy to get suggestions, criticism, bug reports, or just about any other feedback you have to offer! Either email:</p>

        <img src="images/resume/email.png" alt="email">

        <p>or use the following form (it's all the same). PGP public key is available <a href="pgp.html">here</a>.</p>

        <form method="POST" enctype="multipart/form-data" action="send.php">
          <div style="width: 40em">
            <hr>

            <p>Message:</p>
            <textarea class="contact-form" name="message"></textarea>

            <br>

            <div>
              <span>Email (if you want a reply):</span>
              <input style="float: right" type="text" name="email" size="25">
            </div>

            <br>

            <div>
              <span>Attachment:</span>
              <input style="float: right" type="file" name="attachment" />
            </div>

            <br>

            <hr>

            <p>Please solve the following:</p>

            <img id="captcha" src="/captcha.php" alt="CAPTCHA image" />
            <input type="hidden" name="token" value="<?php echo $token ?>">

            <br>
            <input type="text" name="captcha_code" size="10" maxlength="6" />
            <a href="#" onclick="document.getElementById('captcha').src = '/captcha.php?' + Math.random(); return false">&nbsp;<i class="fas fa-sync"></i>&nbsp;different image</a>

            <br><br>
            <input type="submit" value="Submit" name="submit">
          </div>
        </form>
      </div>
    </div>
  </body>
</html>
