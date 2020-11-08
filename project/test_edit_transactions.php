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
    if (isset($id)) {
        $stmt = $db->prepare("UPDATE Transactions set account_number=:account_number, Transaction.id=:transaction_id, 
        act_src_id=:s_id, act_dest_id=:d_id, action_type=:action where id=:id"); //check proper ID
        $r = $stmt->execute([
            ":account_number" => $account_number,
            ":Transaction.id" => $transaction,
            ":source" => $source,
            ":destination" => $d_id,
            ":amount" => $amount,
            ":action" => $action, 
            ":id" => $id
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
//get eggs for dropdown
$db = getDB();
$stmt = $db->prepare("SELECT id,name from TRANSACTIONS LIMIT 10");
$r = $stmt->execute();
$eggs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
    <h3>Edit Transaction</h3>
    <form method="POST">
        <label>Account Number</label>
        <input name="account_number" placeholder="00000000" value="<?php echo $result["account_number"]; ?>"/>
        <label>Transaction</label>
        <select name="transaction_id" value="<?php echo $result["transaction_id"];?>" >
            <option value="-1">None</option>
            <?php foreach ($transaction as $transaction): ?>
                <option value="<?php safer_echo($transaction["id"]); ?>" <?php echo ($result["transaction_id"] == $transaction["id"] ? 'selected="selected"' : ''); ?>
                ><?php safer_echo($transaction["transaction_id"]); ?></option>
            <?php endforeach; ?>
        </select>
        <label>Account Type</label>
        <input type="account_type" name="account_type" value="<?php echo $result["account_type"]; ?>"/>
        <label>Source ID</label>
        <input type="number" min="1" name="s_id" value="<?php echo $result["s_id"]; ?>"/>
        <label>Destination ID</label>
        <input type="number" name="d_id" value="<?php echo $result["d_id"]; ?>"/>
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