<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<!-- ----------------------------------------------------------------------------------------------------------- -->
<?php
if (!has_role("Admin")) {
  //this will redirect to login and kill the rest of this script (prevent it from executing)
  flash("You don't have permission to access this page");
  die(header("Location: login.php"));
}
?>
<!-- ----------------------------------------------------------------------------------------------------------- -->
<?php
//we'll put this at the top so both php block have access to it
  if(isset($_GET["id"])){
    $id = $_GET["id"];
  }
?>
<!-- ----------------------------------------------------------------------------------------------------------- -->
<?php
if(isset($_POST["save"])){
      $account_number = $_POST["account_number"]; //not added to form 
      $account_type = $_POST["account_type"]; //not added to form 
      $balance = $_POST["balance"];
      $db = getDB();

      if(isset($id)){
        $stmt = $db->prepare("UPDATE Accounts set account_number=:account_number, account_type=:account_type, balance=:balance ");
      }
      $r = $stmt->execute([
        ":account_number"=> $account_number,
        ":account_type"=>$account_type,
        ":balance"=>$balance
      ]);

      if($r){
        flash("Updated successfully with id: " . $id);
      }
      else{
        $e = $stmt->errorInfo();
        flash("Error updating: " . var_export($e, true));
      }  
}
?>
<!-- ----------------------------------------------------------------------------------------------------------- -->
<?php
$result = []; 
if(isset($id)){
	$id = $_GET["id"];
	$db = getDB();
	$stmt = $db->prepare("SELECT * FROM Accounts where id = :id");
	$r = $stmt->execute([":id"=>$id]);
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!-- ----------------------------------------------------------------------------------------------------------- -->
<form method="POST">
  <label>Account Number</label>
  <input type="number" placeholder="account_number" value="<?php echo $result["account_number"];?>"/>
  <label>Account Type</label>
    <select account_type="account_type" value="<?php echo $result["account_type"];?>">
      <option value = "checking">checking</option> <?php echo ($result["account_type"] == "checking"?'selected="selected"':'');?>>checking</option>
      <option value = "saving">saving</option> <?php echo ($result["account_type"] == "saving"?'selected="selected"':'');?>>saving</option>
      <option value = "loan">loan</option> <?php echo ($result["account_type"] == "loan"?'selected="selected"':'');?>>loan</option> 
    </select>
  <label>Balance</label> 
  <input type="number" min="5.00" name="Balance" value="<?php echo $result["Balance"];?>" />
  <input type="submit" name="save" value="Update"/>
</form>
<!-- ----------------------------------------------------------------------------------------------------------- -->
<?php require(__DIR__ . "/partials/flash.php");