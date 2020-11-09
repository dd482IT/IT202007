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
$result = [];
if (isset($id)) {
    $db = getDB();
    $stmt = $db->prepare("SELECT Transactions.id,Transactions.act_src_id,Transactions.act_dest_id,Transactions.amount, Users.username, Accounts.account_number as Accounts FROM Transactions as Transactions JOIN Users on Transactions.user_id = Users.id LEFT JOIN Transactions Transaction on Transactions.id = Transactions.Account_id where Transcation.id = :id");
    $r = $stmt->execute([":id" => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        $e = $stmt->errorInfo();
        flash($e[2]);
    }
}
?>


<h3>View Transaction</h3>
<?php if (isset($result) && !empty($result)): ?>
    <div class="card">
        <div class="card-title">
            <?php safer_echo($result["account_number"]); ?>
        </div>
        <div class="card-body">
            <div>
                <p>Stats</p>
                <div>Account Type: <?php safer_echo($result["account_type"]); ?></div>
                <div>Transaction Type: <?php safer_echo($result["action"]); ?> - <?php safer_echo($result["mod_max"]); ?></div>
                <div>Transaction: <?php safer_echo($result["transaction"]); ?></div>
                <div>Owned by: <?php safer_echo($result["username"]); ?></div>
            </div>
        </div>
    </div>
<?php else: ?>
    <p>Error looking up id...</p>
<?php endif; ?>
<?php require(__DIR__ . "/partials/flash.php");