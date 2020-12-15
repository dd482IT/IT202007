<?php require_once(__DIR__ . "/../partials/nav.php"); ?>

<?php
//we'll put this at the top so both php block have access to it
  if(isset($_GET["id"])){
    $id = $_GET["id"];
  }
?>




<form method="POST">
  <label>Are you deactivating the account number <?php echo $account_number;?> </label>
  <label>Please Enter the account number Type</label>
  <input type="number" name="account_number"/>
  <input class="btn btn-primary" type="submit" name="save" value="Create"/>
</form>

<?php
  getDB();
  $stmt = $db->prepare("SELECT account_number, balance, active FROM Accounts Accounts.id = :id");
  $stmt->execute([":id"=>get_user_id()]);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  $account_number = $result["account_number"];





?>




<?php require(__DIR__ . "/../partials/flash.php");