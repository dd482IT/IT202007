
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
if (isset($_POST["query"])) {
    $query = $_POST["query"];
}
?>

<?php
if (isset($_POST["search"]) && !empty($query)) {
    $db = getDB();
    $stmt=$db->prepare("SELECT id FROM Accounts WHERE account_number like :q");
        $r = $stmt->execute([":q" => "$query"]);
        if($r){
            $results = $stmt->fetch(PDO::FETCH_ASSOC);
            $query = $results["id"]; 
            safer_echo($query);
        }
       


    $stmt = $db->prepare("SELECT Transactions.act_src_id, Users.username as username FROM Transactions as Transactions JOIN Users on Transactions.act_src_id = Users.id LEFT JOIN Accounts on Transactions.act_src_id = Accounts.id WHERE Transactions.act_src_id like :q LIMIT 10");
    $r = $stmt->execute([":q" => "%$query%"]);
    if ($r) {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        flash("Results are successfull");
    }
    else {
        flash("There was a problem fetching the results " . var_export($stmt->errorInfo(), true));
    }
}
?>


<h3>List Transcations</h3>
<form method="POST">
    <input name="query" placeholder="Search" value="<?php safer_echo($query); ?>"/>
    <input type="submit" value="Search" name="search"/>
</form>
<div class="results">
    <?php if (count($results) > 0): ?>
        <div class="list-group">
            <?php foreach ($results as $r): ?>
                <div class="list-group-item">
                    <div>
                        <div>Account Number:</div>
                        <div><?php safer_echo($r["Transactions.act_src_id"]); ?></div>
                    </div>
                    <div>
                        <div>Owner:</div>
                        <div><?php safer_echo($r["username"]); ?></div> <!-- Check this-->
                    </div>
                    <div>
                        <a type="button" href="test_edit_incubator.php?id=<?php safer_echo($r['id']); ?>">Edit</a>
                        <a type="button" href="test_view_incubator.php?id=<?php safer_echo($r['id']); ?>">View</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No results</p>
    <?php endif; ?>
</div>