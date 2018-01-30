<?php
require 'functions.php';
$config = include 'config.php';

ini_set('display_errors', '1');
$posted = (!empty($_POST['posted']));

$inputs = [];
$submittedSuccess = false;
if ($posted) {
  $inputs = validateInput(['name', 'friend', 'email'], $_POST);

  if (!$inputs['errors']) {
    $dbh = connectToDb($config['DB_USER'], $config['DB_PASSWORD'], $config['DB_HOST'], $config['DB_NAME']);
    saveToDatabase($dbh, $inputs['data']);
    sendEmail($inputs['data'], 'Someone thought you would like this!');

    // should really redirect
    header('location: thanks.html');
    $submittedSuccess = true;
  }
}


?>
<!doctype html>
<html lang="">
    <head>
        <!-- From https://html5boilerplate.com -->
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>UniLED</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="manifest" href="site.webmanifest">
        <link rel="apple-touch-icon" href="icon.png">
        <!-- Place favicon.ico in the root directory -->

        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/styles.css">
    </head>
    <body>
        <!--[if lte IE 9]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
        <![endif]-->

        <!-- Add your site or application content here -->
        <div class="container">
          <div class="header">
          </div>
          <div class="main">
            <?php if (!$submittedSuccess): ?>
            <h1>Send to a friend</h1>
            <p class="intro">Share this great deal with friends!</p>

            <div class="body-content">

              <?php if (!empty($inputs['errors'])): ?>
                <div class="form-error">
                  <p>Your form has the following errors!</p>
                  <ul>
                  <?php foreach ($inputs['errors'] as $key => $message): ?>
                    <li><?php echo $message?></li>
                  <?php endforeach; ?>
                  </ul>
                </div>
              <?php endif; ?>

              <form method="post" action="index.php">
                <input type="hidden" name="posted" value="1" />
                <?php echo formRow('Your name *', 'name', $inputs) ?>
                <?php echo formRow('Friend\'s name *', 'friend', $inputs) ?>
                <?php echo formRow('Friend\'s Email *', 'email', $inputs) ?>
                <div class="form-row">
                  <label>&nbsp;</label>
                  <button>Submit</button>
                </div>
              </form>
            </div>
            <?php else: ?>
              SUCCESS!
            <?php endif; ?>
          </div>
        </div>

        <!--  js goes here
        <script src="some/javascript.js"></script>
        -->
    </body>
</html>
