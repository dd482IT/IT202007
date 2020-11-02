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
  <select account_type="account_type">
    <option value = "checking">checking</option>
    <option value =  "saving">saving</option>
    <option value = "loan">loan</option>
  </select>
	<input type="submit" name="save" value="Create"/>
</form>

<?php 

if(isset($_POST["save"])){
    $account_type = $POST["account_type"]; 
    $user= get_user_id();
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO Accounts (account_type, user_id) VALUES(:account_type, :user");
    $r = $stmt->execute([
        ":account_type"=> $account_type,
        ":user" => $user,
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