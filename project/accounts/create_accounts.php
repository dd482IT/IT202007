<?php require_once(__DIR__ . "/../partials/nav.php"); ?>

<?php
//we'll put this at the top so both php block have access to it
  if(isset($_GET["id"])){
    $id = $_GET["id"];
    echo var_export("This is the get id + " . $id, true);

  }
?>

<form method="POST">
  <label>Account Type</label>
  <select name="account_type">
    <option value = "checking">checking</option>
    <option value =  "saving">saving</option>
    <option value = "loan">loan</option>
    <option value = "world">world</option>
  </select>
  <label>Balance</label>
  <input type="number" min="5.00" name="balance" value="<?php echo $result["balance"];?>" />
	<input type="submit" name="save" value="Create"/>
</form>

<?php 
$i = 0; 
$max = 100; 
if(isset($_POST["save"])){
    $account_number;
    $account_type = $_POST["account_type"]; 
    $user= get_user_id();
    $balance = $_POST["balance"];
    $db = getDB();

  while($i < $max){
    $account_number = (String)rand(100000000000,999999999999);
    $stmt = $db->prepare("INSERT INTO Accounts (account_number, account_type, user_id, balance) VALUES(:account_number, :account_type, :user, :balance)");
    $r = $stmt->execute([
        ":account_number" => $account_number,
        ":account_type"=> $account_type,
        ":user" => $user,
        ":balance" => $balance
    ]);

    if($r){
      flash("Created successfully with id: " . $db->lastInsertId());
      break;
    }
    else{
      $e = $stmt->errorInfo();
      flash("Error creating: " . var_export($e, true));
    }
    $i++;
  }  
  header("Location: " . getURL("accounts/my_accounts.php"));
}
?> 
<?php require(__DIR__ . "/../partials/flash.php");