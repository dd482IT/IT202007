<?php require_once(__DIR__ . "/../partials/nav.php");
      require_once(__DIR__ . "/../lib/helpers.php");
?>

<?php
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}
if(isset($_GET["id"])){
    $id = $_GET["id"];
}

$page = 1;
$per_page = 10;
if(isset($_GET["page"])){
    try {
        $page = (int)$_GET["page"];
    }
    catch(Exception $e){
    }
}

    $user = get_user_id();
    if(isset($user)){
    $results = [];
    $db = getDB();
    $stmt = $db->prepare("SELECT count(*) as total from Accounts where Accounts.user_id = :id");
    $stmt->execute([":id"=>get_user_id()]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $total = 0;
    if($result){
        $total = (int)$result["total"];
    }
    $total_pages = ceil($total / $per_page);
    $offset = ($page-1) * $per_page;



    $stmt = $db->prepare("SELECT Accounts.user_id as UserID, Accounts.id as AccID, account_number, account_type, balance,apy, active FROM Accounts WHERE Accounts.user_id = :q LIMIT :offset, :count");
    $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
    $stmt->bindValue(":count", $per_page, PDO::PARAM_INT);
    $stmt->bindValue(":q", get_user_id());
    $stmt->execute();
    $e = $stmt->errorInfo();
    if($e[0] != "00000"){
        flash(var_export($e, true), "alert");
    }
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
?>
<div class="container-fluid">
    <h3> <strong>My Accounts</strong></h3>
    <div class="row">
    <div class="card-group">
<?php if($results && count($results) > 0):?>
    <?php foreach($results as $r):?>
        <div class="col-auto mb-3">
            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <div class="card-title">
                        <strong>Account Number</strong>: <?php safer_echo($r["account_number"]);?>
                    </div>
                    <div class="card-text">
                        <?php if($r["active"] == "1"):?>
                        <?php if($r["account_type"] == "loan"):?>
                            <div> <strong>Remaining Balance: </strong><?php safer_echo(abs($r["balance"]));?></div>
                            <div> <strong>Current Apy:</strong> <?php safer_echo($r["apy"] * 100 . "%");?></div>
                        <?php else:?>
                            <div> <strong> Current Balance: </strong><?php safer_echo($r["balance"]);?></div>
                        <?php endif; ?>    
                        <?php if(isset($r["account_type"])):?>
                            <strong>Account Type</strong> <?php safer_echo($r["account_type"]);?>
                            <a type="button" href="<?php echo getURL("accounts/my_transactions.php?id=" . $r["AccID"]); ?>">View Transaction History</a>
                        <?php else:?>
                            Not Set
                        <?php endif; ?>
                        <?php if($r["balance"] == 0):?>
                            <a type="button" class="page-link" href="<?php echo getURL("accounts/close_account.php?id=" . $r["AccID"]); ?>"> Close Account</a>
                        <?php endif;?>
                        <?php else:?>
                        <div><strong> Account has been closed</strong></div>
                        <?php endif;?>
                        
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach;?>

<?php else:?>
<div class="col-auto">
    <div class="card">
       You don't have any accounts
    </div>
</div>
<?php endif;?>
    </div>
    </div>
        <nav aria-label="My Accounts">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php echo ($page-1) < 1?"disabled":"";?>">
                    <a class="page-link" href="?page=<?php echo $page-1;?>" tabindex="-1">Previous</a>
                </li>
                <?php for($i = 0; $i < $total_pages; $i++):?>
                <li class="page-item <?php echo ($page-1) == $i?"active":"";?>"><a class="page-link" href="?page=<?php echo ($i+1);?>"><?php echo ($i+1);?></a></li>
                <?php endfor; ?>
                <li class="page-item <?php echo ($page) >= $total_pages?"disabled":"";?>">
                    <a class="page-link" href="?page=<?php echo $page+1;?>">Next</a>
                </li>
            </ul>
        </nav>
    </div>

<?php require(__DIR__ . "/../partials/flash.php");

/*<div class="results">
    <?php if (count($results) > 0): ?>
        <div class="list-group">
            <?php foreach ($results as $r): ?>
                <div class="list-group-item">
                    <div>
                        <div><strong>Account Number:</strong></div>
                        <div><?php safer_echo($r["account_number"]); ?></div>
                    </div>
                    <div>
                        <div><strong>Account Type:</strong></div>
                        <div><?php safer_echo($r["account_type"]); ?></div>
                    </div>
                    <div>
                        <div><strong>Balance:</strong></div>
                        <div><?php safer_echo($r["balance"]); ?></div>
                    </div>
                    <div>
                        <a type="button" href="<?php echo getURL("accounts/my_transactions.php?id=" . $r["AccID"]); ?>">View Transaction History</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No results</p>
    <?php endif; ?>
</div>
*/