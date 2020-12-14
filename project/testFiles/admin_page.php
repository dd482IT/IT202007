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
  $destUserID = null;

  $stmt=$db->prepare("SELECT * from Accounts WHERE account_number = :q");
  $r = $stmt->execute([":q"=> $lastName,]);
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

}
?> 

<h3 class="text-center"><strong>Search for user</strong></h3> 
  <hr>
  <form align="center" method="POST">     
        <div id="search">
            <label>User Account Number</label>
            <input type="text" name="account_number" placeholder="Search.." required>
        </div>
        <input class="btn btn-primary" type ="submit" name="search" value="find profile"/>
  </form> 
  <?php foreach($results as $r):?>
    <div align="center">
      <a type="button" class="btn btn-primary" name="search2" href="<?php echo getURL("profile.php?id=" . $r["userID"] . "&viewer=" . $id)?>">Go To <?php echo ($r["firstName"] . " " .$r["lastName"]) ?> Profile</a>
    </div>   
  <?php endforeach; ?>
<hr> 