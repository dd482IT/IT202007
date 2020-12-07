<?php require_once(__DIR__ . "/../partials/nav.php");
      require_once(__DIR__ . "/../lib/helpers.php");
?>


<?php
$accounts = getDropDown();
?>

    <h3 class="text-center"><strong>Send Money</strong></h3> 
    <hr>
    <form align="center" method="POST">     
        <label>Source Account</label placeholder="0">
            <select name="s_id">
            <?php foreach($accounts as $row):?>
                <option value="<?php echo $row["id"];?>"> 
                <?php echo $row["account_number"];?>
                </option>
            <?php endforeach;?>
            </select>
        <div id="transfer">
            <label>User Account # Last 4 </label>
            <input type="text" name="destID" placeholder="Search.." required>
            <label>User Last Name</label>
            <input type="text" name="destName" placeholder="Search.." required>
        </div>
        <label>Amount</label> 
        <input type="number" min="1.00" name="amount" required>
        <label>Memo</label>
        <input type="text" name="memo">
        <input class="btn btn-primary" type ="submit" name="save" value="create"/>
    <hr> 
    </form> 


<?php
    if(isset($_POST["save"])){
        //$account_type = $_POST["account_type"];
        $source = $_POST["s_id"]; //ACCOUNT 1 
        $destLast4 = $_POST["destID"]; //ACCOUNT 2 
        $destLastName = $_POST["destName"];
        $amount = $_POST["amount"];
        $memo = $_POST["memo"];
        $user = get_user_id();
        $db = getDB();
        $destUserID;
        $destAccID;
        $destination;
        $destAccNum;
        $action = "extransfer";
        //SELECT Accounts.id, user_id FROM Accounts WHERE account_number LIKE '%8068'

        //FINDS THE USER ID BY COMPARING THE NAME 
        $stmt=$db->prepare("SELECT Users.id as userID FROM Users WHERE Users.lastName LIKE :q");
        $results = $stmt->execute([":q"=> $destLastName]);
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        if($r["userID"]){
        $destUserID = $r["userID"];
        }
        else{
          flash("Name not found");
        }
        //FINDS THE ACCOUNT NUMBER AND ID BY COMPARING THE LAST 4, NAME NEEDS TO BE COMPARED FIRST^^^
        $stmt=$db->prepare("SELECT Accounts.id as accID, account_number FROM Accounts WHERE Accounts.user_id = :userID AND account_number LIKE :q");
        $results = $stmt->execute([":userID"=> $destUserID, ":q"=> "%$destLast4"]);
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        if($r["accId"] && $r["account_number"]){
          $destination = $r["accID"];
          $destAccNum = $r["account_number"];
        }
        else{
          flash("Account Number Not Found");
        }


        $stmt=$db->prepare("SELECT balance FROM Accounts WHERE Accounts.id = :q");
        $results = $stmt->execute(["q"=> $source]);
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        $balance = $r["balance"];

        if(isset($memo) && !empty($memo)){
            $memo = "empty";
        }
        

        if($amount <= $balance){
          doBankAction($source,$destination,($amount *-1), $action, $memo);
        }
        elseif($amount > $balance){
          flash("Balance Too Low");
        }
        else{
          flash("Other Error");
        }
    }
   


?>
<?php require(__DIR__ . "/../partials/flash.php");
