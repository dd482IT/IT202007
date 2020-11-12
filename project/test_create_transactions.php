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
            <select name="s_id">
            <?php foreach($accounts as $row):?>
                <option value="<?php echo $row["id"];?>"> 
                <?php echo $row["account_number"];?>
                </option>
            <?php endforeach;?>
            </select>
        <label>Destination Account </label>
            <select name="d_id">
            <?php foreach($accounts as $row):?>
                <option value="<?php echo $row["id"];?>">
                <?php echo $row["account_number"];?>
                </option>
            <?php endforeach;?>
            </select>
        <label>Amount</label> 
        <input type="number" min="1.00" name="amount">
        <label>Action</label> 
        <select name="action" placeholder="withdraw">
            <option value ="deposit">desposit</option>
            <option value ="transfer">transfer</option>
            <option value ="withdrawl">withdraw</option>
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


        switch($action){
            case "deposit":
                doBankAction($world, $source, ($amount * -1), $action);
            break;
            case "withdrawl":
                doBankAction($source, $world, ($amount * -1), $action);
            break;
            case "transfer":
                doBankAction($source,$destination,($amount*-1), $action);
            break;
        }
        


        
        /*
        if ($r) {
            flash("Created successfully with id: " . $db->lastInsertId());
        }
        else {
            $e = $stmt->errorInfo();
            flash("Error creating: " . var_export($e, true));
        }
        */
          
    }
   


?>
<?php require(__DIR__ . "/partials/flash.php");
