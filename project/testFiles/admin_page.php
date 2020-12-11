<?php require_once(__DIR__ . "/../partials/nav.php");
require_once(__DIR__ . "/../lib/helpers.php");
?>

<?php
  if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: " . getURL("login.php")));
  }
?>

<?php 
if(isset($_POST["search"])){
  $db = getDB();
  $firstName = $_POST["firstName"];
  $lastName = $_POST["lastName"];
  $destUserID = null;

  $stmt=$db->prepare("SELECT Users.id as userID FROM Users WHERE Users.lastName LIKE :q AND Users.firstName LIKE :z");
  $results = $stmt->execute([":q"=> $lastName, ":z"=> $firstName]);
  $r = $stmt->fetch(PDO::FETCH_ASSOC);
  if($r){
    $destUserID = $r["userID"];
  }
  else{
    flash("Name not found");
  }
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
        <a type="button" name="search" href="<?php echo getURL("profile.php?id=" . $destUserID); ?>">Go To Profile</a>
    <hr> 
  </form> 