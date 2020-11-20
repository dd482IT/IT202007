
<?php require_once(__DIR__ . "/../partials/nav.php"); ?>
<?php
$query = "";
$results = [];
$results2 = [];

if(isset($_GET["AccID"])){
  $user = $_GET["AccID"];
}
?>

<?php
if (isset($id) && !empty($id)) {
    $db = getDB();
    $stmt=$db->prepare("SELECT amount, action_type, created, act_src_id, act_dest_id, id as tranID FROM Transactions WHERE act_src_id =:q");
    $r = $stmt->execute([ ":q" => $user]);
    if ($r) {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        flash("Results are successfull");
    }
    else {
        flash("There was a problem fetching the results " . var_export($stmt->errorInfo(), true));
        echo var_export($stmt->errorInfo(), true);
    }
}
?>


<h3>List Transcations</h3>
<div class="results">
    <?php if (count($results) > 0): ?>
        <div class="list-group">
            <?php echo var_export($results, true)?>
            <?php foreach ($results as $r): ?>
                <div class="list-group-item">
                    <div>
                        <div>Action Type:</div>
                        <div><?php safer_echo($r["action_type"]); ?></div>
                    </div>
                    <div>
                        <div>Source:</div>
                        <div><?php safer_echo($r["act_src_id"]); ?></div>
                    </div>
                    <div>
                        <div>Destination:</div>
                        <div><?php safer_echo($r["act_dest_id"]); ?></div>
                    </div>
                    <div>
                        <div>Amount:</div>
                        <div><?php safer_echo($r["amount"]); ?></div>
                    </div>
                    <div>
                        <div>Transaction ID:</div>
                        <div><?php safer_echo($r["tranID"]); ?></div>
                    </div>
                    <div>
                        <a type="button" href="test_edit_transactions.php?id=<?php safer_echo($r['tranID']); ?>">Edit</a>
                        <a type="button" href="test_view_transactions.php?id=<?php safer_echo($r['tranID']); ?>">View</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No results</p>
    <?php endif; ?>
</div>
<?php require(__DIR__ . "/../partials/flash.php");