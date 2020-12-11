<?php require_once(__DIR__ . "/partials/nav.php"); 
      require_once(__DIR__ . "/../lib/helpers.php");
?>

<?php
  if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: " . getURL("login.php")));
  }
?>
