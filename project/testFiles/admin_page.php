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
  $results = $stmt->fetch(PDO::FETCH_ASSOC);
  $frozen = $results["frozen"];
  $accID = $results["accID"];
}
?> 
<!----------------------------------------------------------------------------------------------------------------------------------------------------------------> 
<h3 class="text-center"><strong>Search for Account</strong></h3> 
  <hr>
  <form align="center" method="POST">     
        <div id="search">
            <label>User Account Number</label>
            <input type="text" name="account_number" placeholder="Search.." required>
        </div>
        <input class="btn btn-primary" type ="submit" name="search2" value="find account"/>
  </form> 
  <?php if(isset($results) && !empty($results)):?>
    <div align="center" class="card-text">
      <div><Strong>Account Number:</Strong> <?php safer_echo($results["account_number"]); ?></div>
      <div><Strong>Account ID:</Strong> <?php safer_echo($results["accID"]); ?></div>
      <div><Strong>Account Type:</Strong> <?php safer_echo($results["account_type"]); ?></div>
      <div><Strong>Open on:</Strong> <?php safer_echo($results["opened_date"]); ?></div>
      <div><Strong>Account Balance:</Strong> <?php safer_echo($results["balance"]); ?></div>
      <div><Strong>First Name:</Strong> <?php safer_echo($results["firstName"]); ?></div>
      <div><Strong>Last Name:</Strong> <?php safer_echo($results["lastName"]); ?></div>
      <a type="button" class="btn btn-primary" href="<?php echo getURL("accounts/my_transactions.php?id=" . $results["accID"] . "&viewer=" . $id)?>">Go To <?php echo ($results["firstName"] . " " .$results["lastName"]) ?>  Transactions History</a>
    </div>
  <?php else:?>
      <p>Invalid Account</p>
  <?php endif;?>
  <hr>
  
<!----------------------------------------------------------------------------------------------------------------------------------------------------------------> 
    

<?php

if(isset($_POST["search3"])){
    $db = getDB();
    $frozen = null;
    $results = [];
    $account_number = $_POST["account_number"];
    $stmt=$db->prepare("SELECT frozen, Accounts.id as accID from Accounts WHERE account_number = :q");
    $r = $stmt->execute([":q"=> $account_number]);
    $results = $stmt->fetch(PDO::FETCH_ASSOC);
    $accID = $results["accID"];

    if($results){
    $frozen = $results["frozen"];
    }
    else{
      flash("Error Pulling frozen");
    }

    if($frozen == 0){
        $frozen = 1;
    }
    elseif($frozen == 1){
        $frozen = 0;
    }
    
    $stmt = $db->prepare("UPDATE Accounts set frozen = :frozen where Accounts.id = :accID");
    $r = $stmt->execute([":frozen" => $frozen, ":accID" => $accID]);

    if($r && $frozen == 1){
      flash("Account is now frozen");
    }
    elseif($r && $frozen == 0){
      flash("Account is now unfrozen");
    }else{
      flash("Error Setting frozen");
      
    }


  }
?>
  <h3 class="text-center"><strong>Freeze an Account</strong></h3> 
    <form align="center" method="POST"> 
        <div align="center">
            <label>User Account Number</label>
            <input type="text" name="account_number" placeholder="Search.." required>
        </div>
        <input align="center" class="btn btn-primary" type ="submit" name="search3" value="Freeze/Unfreeze"/>
    </form> 
  <hr>
<!----------------------------------------------------------------------------------------------------------------------------------------------------------------> 

<?php
  if(isset($_POST["search4"])){
    $db = getDB();
    $active = null;
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $stmt=$db->prepare("SELECT active, Users.id as userID FROM Users WHERE Users.lastName LIKE :q AND Users.firstName LIKE :z");
    $r = $stmt->execute([":q"=> $lastName, ":z"=> $firstName]);
    $results = $stmt->fetch(PDO::FETCH_ASSOC);
   

    if($results){
      $active = $results["active"];
      $userID = $results["userID"];
    }
    else{
      flash("Error Pulling active");
    }

    if($active == 0){
        $active = 1;
    }
    elseif($active == 1){
        $active = 0;
    }
    
    $stmt = $db->prepare("UPDATE Users set active = :active where Users.id = :userID");
    $r = $stmt->execute([":active" => $active, ":userID" => $userID]);

    if($r && $active == 1){
      flash("Account is now deactivated");
    }
    elseif($r && $active == 0){
      flash("Account is now activated");
    }else{
      flash("Error Setting active");
      
    }
  }
?>

<h3 class="text-center"><strong>Deactivate an Account</strong></h3> 
    <form align="center" method="POST"> 
        <div align="center">
            <label>User First Name</label>
            <input type="text" name="firstName" placeholder="Search.." required>
            <label>User Last Name</label>
            <input type="text" name="lastName" placeholder="Search.." required>
        </div>
        <input align="center" class="btn btn-primary" type ="submit" name="search4" value="Active/Deactivate"/>
    </form> 
<hr>

<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------>

<?php 
  if(isset($_POST["search5"])){
    $db = getDB();
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $stmt=$db->prepare("SELECT Users.id as userID FROM Users WHERE Users.lastName LIKE :q AND Users.firstName LIKE :z  ");
    $r = $stmt->execute([":q"=> $lastName, ":z"=> $firstName]);
    if($r){
      $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    else{
      flash("There was an error finding the userID");
    }
  }
?> 


<h3 class="text-center"><strong>Open an Account</strong></h3> 
    <form align="center" method="POST"> 
        <div align="center">
        <label>User First Name</label>
            <input type="text" name="firstName" placeholder="Search.." required>
            <label>User Last Name</label>
            <input type="text" name="lastName" placeholder="Search.." required>
        </div>
        <input align="center" class="btn btn-primary" type ="submit" name="search5" value="Find User"/>
    </form> 
  <?php foreach($results as $r):?>
    <div align="center">
      <a type="button" class="btn btn-primary" name="search5" href="<?php echo getURL("accounts/create_accounts.php?id=" . $r["userID"] . "&viewer=" . $id)?>">Account for <?php echo ($r["userID"]) ?> Make Account</a>
    </div>   
  <?php endforeach;?>
<hr> 

