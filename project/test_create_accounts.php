<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
  if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
  }
?>

<form method="POST">
  <label>Account_Type</label>
  <select name="account_type">
    <option value = "checking">checking</option>
    <option value =  "saving">saving</option>
    <option value = "loan">loan</option>
    <option value = "world">world</option>
  </select>
  <label>Balance</label>
  <input type="number" min="5.00" name="Balance" value="<?php echo $result["balnce"];?>" />
	<input type="submit" name="save" value="Create"/>
</form>

<?php 

if(isset($_POST["save"])){
    $account_type = $_POST["account_type"]; 
    $user= get_user_id();
    $balance = $_POST["POST"];
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO Accounts (account_type, user_id, balance) VALUES(:account_type, :user, :balance");
    $r = $stmt->execute([
        ":account_type"=> $account_type,
        ":user" => $user,
        ":balance" => $balance
    ]);

    if($r){
      flash("Created successfully with id: " . $db->lastInsertId());
    }
    else{
      $e = $stmt->errorInfo();
      flash("Error creating: " . var_export($e, true));
    }

}   

?> 
<?php require(__DIR__ . "/partials/flash.php");?>