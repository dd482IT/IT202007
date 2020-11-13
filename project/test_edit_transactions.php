<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>
<?php
//we'll put this at the top so both php block have access to it
if (isset($_GET["id"])) {
    $id = $_GET["id"];
}
?>
<?php
//saving
if (isset($_POST["save"])) {
    $account_number=$_POST["account_number"];
    $transaction =$_POST["transaction_id"];
    if($transaction <= 0){
        $transaction = null; 
    }
    $source = $_POST["s_id"];
    $destination = $_POST["d_id"];
    $amount = $_POST["amount"];
    $action  = $_POST["action"];
    $user = get_user_id();
    $db = getDB();
    if (isset($id)) { //balance and trasnaction type
        $stmt = $db->prepare("UPDATE Transactions set account_number=:account_number, Transaction.id=:transaction_id, 
        act_src_id=:s_id, act_dest_id=:d_id, action_type=:action where id=:id"); //check proper ID
        $r = $stmt->execute([
            ":amount" => $amount,
            ":action" => $action, 
        
            ":id" => $transaction
        ]);
        if ($r) {
            flash("Updated successfully with id: " . $id);
        }
        else {
            $e = $stmt->errorInfo();
            flash("Error updating: " . var_export($e, true));
        }
    }
    else {
        flash("ID isn't set, we need an ID in order to update");
    }
}
?>
<?php
//fetching
$result = [];
if (isset($id)) {
    $id = $_GET["id"];
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM TRANSACTIONS where id = :id");
    $r = $stmt->execute([":id" => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
}

?>
<?php
$accounts = getDropDown();
?>
    <h3>Edit Transaction</h3>
    <form method="POST">
        <label>Account Number</label>
        <input name="account_number" value="<?php echo $result["account_number"]; ?>"/>
        <label>Transaction</label>
        <select name="transaction_id" value="<?php echo $result["transaction_id"];?>" >
            <option value="-1">None</option>
            <?php foreach ($transaction as $transaction): ?>
                <option value="<?php safer_echo($transaction["id"]); ?>" <?php echo ($result["transaction_id"] == $transaction["id"] ? 'selected="selected"' : ''); ?>
                ><?php safer_echo($transaction["transaction_id"]); ?></option>
            <?php endforeach; ?>
        </select> 
        <label>Source Account</label>
            <select name="s_id">
                <?php foreach($accounts as $row):?>
                    <option value="<?php echo $row["id"];?>"> 
                    <?php echo $row["account_number"];?>
                    </option>
                <?php endforeach;?>
            </select>
        <label>Destination Account</label>
            <select name="d_id">
                <?php foreach($accounts as $row):?>
                    <option value="<?php echo $row["id"];?>">
                    <?php echo $row["account_number"];?>
                    </option>
                <?php endforeach;?>
            </select>      
        <label>Amount</label> 
        <input type="number" name="amount" value="<?php echo $result["amount"]; ?>"/>
        <label>Action</label> 
        <input name="action" placeholder="deposit" value="<?php echo $result["action"]; ?>"/>
                <select>
                <option value = "deposit">desposit</option>
                <option value =  "transfer">transfer</option>
                <option value = "withdraw">withdraw</option>
                </select> 
        <input type="submit" name="save" value="Update"/>
    </form>


<?php require(__DIR__ . "/partials/flash.php");