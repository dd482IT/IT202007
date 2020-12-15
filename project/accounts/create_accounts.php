<?php require_once(__DIR__ . "/../partials/nav.php"); ?>

<?php
//we'll put this at the top so both php block have access to it
  if(isset($_GET["id"])){
    $id = $_GET["id"];

  }
  $viewerID = null; 
    if(isset($_GET["viewer"])){
        $viewerID = $_GET["viewer"];
    }
?>

<script> //https://stackoverflow.com/questions/16611774/how-to-change-max-or-min-value-of-input-type-date-from-js
function changeMin(){
    var input = document.getElementById("balance");
    if(document.getElementById("accType").value == "loan"){
      input.setAttribute("min", 500.00);
    }
  }
</script>

<form method="POST">
  <label>Account Type</label>
  <select id="accType" name="account_type">
    <option value ="checking">checking</option>
    <option value ="saving">saving</option>
    <option value ="loan" selected>loan</option>
  </select>
  <label>Balance</label>
  <input id="balance" type="number" min="5.00" name="balance" value="<?php echo $result["balance"];?>" />
  <input class="btn btn-primary" type="submit" name="save" value="Create"/>
</form>

<?php 
$i = 0; 
$max = 100; 
if(isset($_POST["save"])){
    $db = getDB();
    $account_number;
    $account_type = $_POST["account_type"]; 
    $user= get_user_id();
    $balance = $_POST["balance"];
    $apy;
    if($account_type == "saving"){
      $apy = 0.01;
    }

    if($account_type == "loan"){
      $apy = 0.07;
    }
        

  while($i < $max){
    $account_number = (String)rand(100000000000,999999999999);
    $stmt = $db->prepare("INSERT INTO Accounts (account_number, account_type, user_id, apy) VALUES(:account_number, :account_type, :user, :apy)");
    $r = $stmt->execute([
        ":account_number" => $account_number,
        ":account_type"=> $account_type,
        ":user" => $user,
        ":apy" =>$apy,
    ]);

    if($r){
      flash("Created successfully with id: " . $db->lastInsertId());

      if($account_type == "loan"){
        $balance = $balance * -1;
      }


      openAccount($account_number, $balance);
      break;
    }
    else{
      $e = $stmt->errorInfo();
      flash("Error creating your account");
    }
    $i++;
  }
  if($viewerID == null)
  {
  header("Location: " . getURL("accounts/my_accounts.php"));
  }
  else{
    header("Location: " . getURL("testFiles/admin_page.php"));
    flash("Account Made");
  }
}
?> 
<?php require(__DIR__ . "/../partials/flash.php");