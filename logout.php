<?php

session_start();
require_once 'header.php';
require_once 'nav.php'; 

?>

<style>

  <?php include 'style.css' ?>

</style>

<div class="logout-content">

  <h2>You have been Logged out</h2>
  <a class="btn btn-primary align-right" name="login" href="loginpage.php" role="button">Click here to Login</a>

</div>

<?php

require_once 'footer.php'; 

?>