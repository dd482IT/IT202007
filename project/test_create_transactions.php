<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<?php
if(!has_role("Admin")){
    flash("You dont have permission to access this page");
    die(header("Location: login.php"));
}
?>

<?php
$accounts = getDropDown();
?>

    <h3>Create Transaction</h3> 
    <form method="POST">      

        <label>Source Account</label placeholder="0">
            <?php foreach($accounts as $index=>$row):?>
                <option name="s_id" value="<?php echo $index;?>">
                <?php echo $row["accs"];?>
                </option>
            <?php endforeach;?>
        <label>Destination Account </label>
            <?php foreach($accounts as $index=>$row):?>
                <option name="d_id" value="<?php echo $index;?>">
                <?php echo $row["accs"];?>
                </option>
            <?php endforeach;?>

        <label>Amount</label> 
        <input type="number" min="1.00" name="amount">
        <label>Action</label> 
        <select name="action" placeholder="withdraw">
            <option value ="deposit">desposit</option>
            <option value ="transfer">transfer</option>
            <option value ="withdraw">withdraw</option>
        </select>

        <input type ="submit" name="save" value="create"/>
    </form> 


<?php
    if(isset($_POST["save"])){
        //$account_type = $_POST["account_type"];
        $world = "000000000000";
        $source = $_POST["s_id"]; //ACCOUNT 1 
        $destination = $_POST["d_id"]; //ACCOUNT 2 
        $amount = $_POST["amount"];
        $action  = $_POST["action"];// WITHDRAWAL, DESPOIT, TRANSFER
        $user = get_user_id();
        $db = getDB();

        /*
        $stmt = $db ->prepare("SELECT SUM(AMOUNT) AS Total FROM TRANSACTIONS WHERE Transactions.act_src_id = :id");
            $r = $stmt->execute([
                ":id" => $source
            ]);
            $results = $stmt->fetch(PDO::FETCH_ASSOC);
            $source_total = $results["Total"];
            $destination_total = $results["Total"];        
        $stmt = $db ->prepare("INSERT INTO Transactions (account_number, account_type, act_src_id, act_dest_id, amount, action_type, expected_total user_id) 
        VALUES (:account_number, :account_type, :s_id, :d_id, :amount, :action_type, :expected_total :user) (:account_number2, :account_type2, :s_id2, :d_id2, :amount2, :action_type2, :expected_total2 :user2)" ); //missing values of other account or world in this case
        */

        switch($type){
            case "deposit":
                doBankAction($world, $source, ($amount * -1), $type);
            break;
            case "withdrawl":
                doBankAction($source, $world, ($amount * -1), $type);
            break;
            case "transfer":
                doBankAction($source,$destination,($amount*-1), $type);
            break;
        }
        


        

        if ($r) {
            flash("Created successfully with id: " . $db->lastInsertId());
        }
        else {
            $e = $stmt->errorInfo();
            flash("Error creating: " . var_export($e, true));
        }

          
    }
   


?>
<?php require(__DIR__ . "/partials/flash.php");
