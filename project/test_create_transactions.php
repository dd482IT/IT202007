<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<?php
if(!has_role("Admin")){
    flash("You dont have permission to access this page");
    die(header("Location: login.php"));
}
?>

<?php
$accounts = getDropDown();
echo $accounts;
?>

    <h3>Create Transaction</h3> 
    <form method="POST"> 
        <label>Account Number</label>
            <input name="account_number" placeholder="00000000"> <!-- Check-->
        <label>Select Your Account </label> 
        <label>Source ID</label placeholder="0">
            <input type="number" name="s_id">
        <label>Destination ID </label>
            <input name="d_id" placeholder="1">
        <label>Amount</label> 
        <input type="number" min="1.00" name="amount">
        <label>Action</label> 
        <select name="action" placeholder="withdraw">
            <option value = "deposit">desposit</option>
            <option value =  "transfer">transfer</option>
            <option value = "withdraw">withdraw</option>
        </select>

        <input type ="submit" name="save" value="create"/>
    </form> 


<?php
    if(isset($_POST["save"])){
        $accounts;
        $account_number = $_POST["account_number"];
        $account_type = $_POST["account_type"];
        $source = $_POST["s_id"];
        $destination = $_POST["d_id"];
        $amount = $_POST["amount"];
        $action  = $_POST["action"];
        $user = get_user_id();
        $db = getDB();
        $stmt = $db ->prepare("SELECT SUM(AMOUNT) AS Total FROM TRANSACTIONS WHERE Transactions.act_src_id = :id");
            $r = $stmt->execute([
                ":id" => $source
            ]);
            $results = $stmt->fetch(PDO::FETCH_ASSOC);
            $source_total = $results["Total"];
            $destination_total = $results["Total"];
            // do same thing for dest total
            


            
        $stmt = $db ->prepare("INSERT INTO Transactions (account_number, account_type, act_src_id, act_dest_id, amount, action_type, user_id) 
        VALUES (:account_number, :account_type, :s_id, :d_id, :amount, :action_type, :user)"); // :user?
          $r = $stmt->execute([
            ":account_number" => $name, 
            ":account_type" => $account_type,
            ":s_id" => $source,
            ":d_id" => $destination,
            ":amount" => $amount,
            ":action" => $action, 
            ":user" => $user, 
          ]);
    }
   


?>
<?php require(__DIR__ . "/partials/flash.php");
