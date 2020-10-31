<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php

if (!has_role("Admin")) {
  //this will redirect to login and kill the rest of this script (prevent it from executing)
  flash("You don't have permission to access this page");
  die(header("Location: login.php"));
}
?>
<?php
//we'll put this at the top so both php block have access to it
if(isset($_GET["id"])){
	$id = $_GET["id"];
}
?>

<?php
if(isset($_POST["save"])){
        $

}


?>




<form method="POST">
	

<input type="submit" name="save" value="Update"/>
</form>

<?php require(__DIR__ . "/partials/flash.php");