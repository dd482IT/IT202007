<?php require_once(__DIR__ . "/../partials/nav.php"); ?>

<?php
//we'll put this at the top so both php block have access to it
  if(isset($_GET["id"])){
    $id = $_GET["id"];
  }
?>

<form method="POST">
  <label>Loan </label>
  <label>Balance</label>
  <input type="number" min="500.00" name="balance" value="<?php echo $result["balance"];?>" />
	<input class="btn btn-primary" type="submit" name="save" value="Create"/>
</form>

<?php require(__DIR__ . "/../partials/flash.php");