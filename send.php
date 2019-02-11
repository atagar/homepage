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

      <a href="contact.html">
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
$sent = false;

if (isset($_POST['submit'])) {
  $to = "atagar1@gmail.com";
  $subject = "Comment from www.atagar.com";
  $email_field = $_POST['email'];
  if ($email_field == "") $email_field = "anonymous";
  $message = wordwrap($_POST['message'], 70);

  $body = "E-Mail: $email_field\n Message:\n $message";
  $sent = mail($to, $subject, $body);
}

if ($sent) {
  echo "        <h2>Thanks! Message successfully sent.</h2>
";
} else {
  echo "        <h2>Message failed to be sent. Please contact me via email instead.</h2>
        <img src='images/resume/email.png' alt='email' />
        <br><br>
        <p>Your message was:</p>
        <pre>$message</pre>
";
}
?>
      </div>
    </div>
  </body>
</html>
