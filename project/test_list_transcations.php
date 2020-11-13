
<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>
<?php
$query = "";
$results = [];
$results2 = [];
if (isset($_POST["query"])) {
    $query = $_POST["query"];
}
?>

<?php
if (isset($_POST["search"]) && !empty($query)) {
    $db = getDB();
    $stmt=$db->prepare("SELECT id FROM Accounts WHERE account_number like :q");
        $r = $stmt->execute([":q" => "$query"]);
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        $query = $results["id"]; 
        
       


    $stmt = $db->prepare("SELECT Transactions.act_src_id as id, Users.username as username FROM Transactions as Transactions JOIN Users on Transactions.act_src_id = Users.id LEFT JOIN Accounts on Transactions.act_src_id = Accounts.id WHERE Transactions.act_src_id =:q LIMIT 10");
    $r = $stmt->execute([":q" => "$query"]);
    if ($r) {
        $results2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
        flash("Results are successfull");
    }
    else {
        flash("There was a problem fetching the results " . var_export($stmt->errorInfo(), true));
        echo var_export($stmt->errorInfo(), true);
    }
}
?>


<h3>List Transcations</h3>
<form method="POST">
    <input name="query" placeholder="Search" value="<?php safer_echo($query); ?>"/>
    <input type="submit" value="Search" name="search"/>
</form>
<div class="results">
    <?php if (count($results2) > 0): ?>
        <div class="list-group">
            <?php echo var_export($results2, true); ?>
            <?php foreach ($results2 as $r): ?>
                <div class="list-group-item">
                    <div>
                        <div>Account Number:</div>
                        <div><?php safer_echo($r["id"]); ?></div>
                    </div>
                    <div>
                        <div>Owner:</div>
                        <div><?php safer_echo($r["username"]); ?></div> <!-- Check this-->
                    </div>
                    <div>
                        <a type="button" href="test_edit_transactions.php?id=<?php safer_echo($r['id']); ?>">Edit</a>
                        <a type="button" href="test_view_transactions.php?id=<?php safer_echo($r['id']); ?>">View</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No results</p>
    <?php endif; ?>
</div>