<?php require_once(__DIR__ . "/../partials/nav.php");
require_once(__DIR__ . "/../lib/helpers.php");
?>

<?php
  if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: " . getURL("login.php")));
  }

  $id = get_user_id();
    if(isset($_GET["id"])){
        $id = $_GET["id"];
    }
  
?>



<?php 
$results =[];
if(isset($_POST["search"])){
  $db = getDB();
  $firstName = $_POST["firstName"];
  $lastName = $_POST["lastName"];
  $destUserID = null;

  $stmt=$db->prepare("SELECT firstName, lastName, Users.id as userID FROM Users WHERE Users.lastName LIKE :q AND Users.firstName LIKE :z");
  $r = $stmt->execute([":q"=> $lastName, ":z"=> $firstName]);
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

}
?> 

<h3 class="text-center"><strong>Search for user</strong></h3> 
  <hr>
  <form align="center" method="POST">     
        <div id="search">
            <label>User First Name</label>
            <input type="text" name="firstName" placeholder="Search.." required>
            <label>User Last Name</label>
            <input type="text" name="lastName" placeholder="Search.." required>
        </div>
        <input class="btn btn-primary" type ="submit" name="search" value="find profile"/>
  </form> 
  <?php foreach($results as $r):?>
    <div align="center">
      <a type="button" class="btn btn-primary" name="search" href="<?php echo getURL("profile.php?id=" . $r["userID"] . "&viewer=" . $id)?>">Go To <?php echo ($r["firstName"] . " " .$r["lastName"]) ?> Profile</a>
    </div>   
  <?php endforeach; ?>
<hr> 


<?php 
$results =[];
if(isset($_POST["search2"])){
  $db = getDB();
  $account_number = $_POST["account_number"];
  $userID = null;
 

  $stmt1=$db->prepare("SELECT Accounts.user_id as userID from Accounts WHERE account_number = :q");
  $r1 = $stmt1->execute([":q"=> $account_number]);
 
  if($r1){
    $results1 = $stmt1->fetch(PDO::FETCH_ASSOC);
    $userID = $results1["userID"];
  }
  else{
    flash("Error Pulling");
  }
  
  $stmt=$db->prepare("SELECT account_number, account_type, firstName, lastName, Accounts.id as accID, opened_date, balance, frozen from Users JOIN Accounts on Accounts.user_id = Users.id WHERE Users.id = :q");
  $r = $stmt->execute([":q"=> $userID]);
  if($r){
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  else{
    flash("Error pulling account");
  }
  $frozen = $results["frozen"];
  $accID = $results["accID"];


  if(isset($_POST["frozen"]) && !empty($frozen)){
      $frozen = 0;
      flash("This account is now frozen");
  }
  else{
      $frozen = 1;
      flash("This account is now unfrozen");
  }
  $stmt = $db->prepare("UPDATE Accounts set frozen = :frozen where id = :id");
  $r = $stmt->execute([":id" => $accID, ":frozen" => $frozen]);



}
?> 

<h3 class="text-center"><strong>Search for Account</strong></h3> 
  <hr>
  <form align="center" method="POST">     
        <div id="search">
            <label>User Account Number</label>
            <input type="text" name="account_number" placeholder="Search.." required>
        </div>
        <input class="btn btn-primary" type ="submit" name="search2" value="find account"/>
  </form> 
  <?php foreach($results as $r):?>
    <div align="center" class="card-text">
      <div><Strong>Account Number:</Strong> <?php safer_echo($r["account_number"]); ?></div>
      <div><Strong>Account ID:</Strong> <?php safer_echo($r["accID"]); ?></div>
      <div><Strong>Account Type:</Strong> <?php safer_echo($r["account_type"]); ?></div>
      <div><Strong>Open on:</Strong> <?php safer_echo($r["opened_date"]); ?></div>
      <div><Strong>Account Balance:</Strong> <?php safer_echo($r["balance"]); ?></div>
      <div><Strong>First Name:</Strong> <?php safer_echo($r["firstName"]); ?></div>
      <div><Strong>Last Name:</Strong> <?php safer_echo($r["lastName"]); ?></div>
      <input type="checkbox" name="freeze" <?php echo $r["frozen"] == "1"?"checked='checked'":"";?> />    
                <label for="freeze">Freeze the Profile?</label><br>
      <a type="button" class="btn btn-primary" name="search" href="<?php echo getURL("accounts/my_transactions.php?id=" . $r["accID"] . "&viewer=" . $id)?>">Go To <?php echo ($r["firstName"] . " " .$r["lastName"]) ?>  Transactions History</a>
    </div>
  <?php endforeach;?>
<hr>  


