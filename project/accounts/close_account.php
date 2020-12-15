<?php require_once(__DIR__ . "/../partials/nav.php"); ?>

<?php
//we'll put this at the top so both php block have access to it
  if(isset($_GET["id"])){
    $accid = $_GET["id"];
  }
?>






<?php
  getDB();
  $stmt = $db->prepare("SELECT account_number, balance, active FROM Accounts WHERE Accounts.id = :id");
  $stmt->execute([":id"=>$accid]);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  $account_number = $result["account_number"];
  $status = $result["balance"];

  if(isset($_POST["deactivate"])){

    if($status == 1){
      $status = 0;
    }
    elseif($status == 0){
      flash("Account is already deactivated");
    }



    $stmt = $db->prepare("UPDATE Accounts SET active = :active WHERE Accounts.id = :id");
    $r = $stmt->execute([
      ":active" => $status,
      ":id" => $accid
    ]);

    if($r){
      flash("Account has been deactivated");
    }
    else{
      flash("An Error occuered");
    }



  }
?>

<form method="POST">
  <label>Are you deactivating the account number</label> <strong> <?php safer_echo($result["account_number"]);?></strong>
  <div>
  <label>Please Enter the account number to confirm:</label>
  </div>
  <input type="text" name="account_number"/>
  <input class="btn btn-primary" type="submit" name="deactivate" value="Create"/>
</form>





<?php require(__DIR__ . "/../partials/flash.php");