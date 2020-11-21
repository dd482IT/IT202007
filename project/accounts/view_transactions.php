<?php require_once(__DIR__ . "/../partials/nav.php"); ?>
<?php
//we'll put this at the top so both php block have access to it
if (isset($_GET["id"])) {
    $tranID = $_GET["id"];
    safer_echo($tranID);
}
?>

<?php
$result = [];
if (isset($id)) {
    $db = getDB();
    $user = get_user_id();
    $stmt = $db->prepare("SELECT Transactions.id as tranID, act_src_id, act_dest_id, amount, action_type FROM Transactions WHERE Transactions.id = :q");
    $r = $stmt->execute([":q" => $tranID]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        $e = $stmt->errorInfo();
        flash($e[2]);
    }
    /*
    $stmt2 = $db->prepare("SELECT account_number FROM Accounts WHERE Accounts.id = id");
    $r2 = $stmt2->execute([":id" => $user]);
    $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
    if (!$result2) {
        $e = $stmt->errorInfo();
        flash($e[2]);
    }
    */
}
?>


<h3>View Transaction</h3>
<?php if (isset($result) && !empty($result)): ?>
    <div class="card">
        <div class="card-title">
        </div>
        <div class="card-body">
            <div>
                <p><b>Information</b></p> <!-- match with SELECT ^^^^^^ -->
                <div>Amount:<?php safer_echo($result["amount"]); ?></div>
                <div>Action: Type <?php safer_echo($result["action_type"]); ?> </div>
                <div>Tran ID: <?php safer_echo($result["tranID"]); ?> </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <p>Error looking up id...</p>
<?php endif; ?>
<?php require(__DIR__ . "/../partials/flash.php");