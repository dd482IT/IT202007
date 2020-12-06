
<?php require_once(__DIR__ . "/../partials/nav.php"); ?>
<?php
$query = "";
$results = [];
$results2 = [];

if(isset($_GET["id"])){ // ASK PROFFESOR 
  $accID = $_GET["id"]; // THE ACCOUNT ID
}
else{
  safer_echo("The id was not pulled");
}
?>

<?php
if (isset($accID) && !empty($accID)) {
    $page = 1;
    $per_page = 10;
    if(isset($_GET["page"])){
        try {
            $page = (int)$_GET["page"];
        }
        catch(Exception $e){
    
        }
    }
    $db = getDB();
    $stmt = $db->prepare("SELECT count(*) as total FROM Transactions as Transactions JOIN Accounts ON Transactions.act_src_id = Accounts.id WHERE Accounts.id = :q");
    $stmt->execute([":q"=>$accID]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $total = 0;
    if($result){
        $total = (int)$result["total"];
    }      
    $total_pages = ceil($total / $per_page);
    $offset = ($page-1) * $per_page;
    //$stmt=$db->prepare("SELECT amount, action_type, created, act_src_id, act_dest_id, Transactions.id as tranID FROM Transactions as Transactions JOIN Accounts ON Transactions.act_src_id = Accounts.id WHERE Accounts.id = :q LIMIT 10");
    $r;

    if(isset($_POST["filter"])){
        $typeCheck = false;
        $dateCheck = false;
        $startDate = $_POST["trans-start"];
        $endDate = $_POST["trans-end"];
        $type = $_POST["action_type"];
        $action = "desposit";

        $params = [];
        $query = "SELECT amount, action_type, created, act_src_id, act_dest_id, Transactions.id as tranID FROM Transactions JOIN Accounts ON Transactions.act_src_id = Accounts.id WHERE Accounts.id = :q";

        if(!empty($type)){
            $query .= " AND action_type = :x";
            $params[":x"] = $type;
            $typeCheck = true;
        }

        if(!empty($date) && !empty($endDate)){
            $query .= " AND created BETWEEN :y AND :z";
            $params[":y"] = $startDate;
            $params[":z"] = $endDate;
            $dateCheck = true;
        }

        $query .= " LIMIT :offset, :count";
        $stmt=$db->prepare($query);
        $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
        $stmt->bindValue(":count", $per_page, PDO::PARAM_INT);
        if( $typeCheck){
            $stmt->bindValue(":x", $type);
        }
        if($dateCheck){
            $stmt->bindValue(":y", $startDate);
            $stmt->bindValue(":z", $endDate);
        }
        $stmt->bindValue(":q", $accID);
        $r = $stmt->execute();
    }
    else{
        $stmt=$db->prepare("SELECT amount, action_type, created, act_src_id, act_dest_id, Transactions.id as tranID FROM Transactions JOIN Accounts ON Transactions.act_src_id = Accounts.id WHERE Accounts.id = :q LIMIT :offset, :count");
        $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
        $stmt->bindValue(":count", $per_page, PDO::PARAM_INT);
        $stmt->bindValue(":q", $accID);
        $r = $stmt->execute();
    }


    //$r = $stmt->execute([ ":q" => $accID]);
    if ($r) {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        safer_echo($results);
        if (isset($results["action_type"])){
            $action = $results["action_type"];
        }
        if($results != false){
            flash("Results are successfull");
        }
        else{
            flash("Date is invalid");
        }
    }
    else {
        flash("There was a problem listing your transactions");
        echo var_export($stmt->errorInfo(), true);
    }
}
?>

<form method="POST">
<h3> <strong>List Transcations </strong></h3>
<div class="filter">
    <h3> Filter </h3> 
    <label for="type_filter"> Action Type: </label> 
        <select name="action_type" value="<?php safer_echo($action); ?>">
                <option value ="transfer" <?php safer_echo($action == "transfer" ? 'selected="selected"' : ''); ?>>transfer</option>
                <option value ="deposit" <?php safer_echo($action == "deposit" ? 'selected="selected"' : ''); ?>>desposit</option>
                <option value ="withdrawl" <?php safer_echo($action == "withdrawl" ? 'selected="selected"' : ''); ?>>withdraw</option>
                <option value="" <?php safer_echo($action == "" ? 'selected="selected"' : ''); ?>>All</option>
        </select>
    <label for="startDate">Start date:</label>
        <input class ="startDate" type="date" id="startDate" name="trans-start" min="2000-01-01" max="2099-12-31">
    <label for="endDate">End date:</label>
        <input type="date" id="endDate" name="trans-end" min="2000-01-01" max="2099-12-31">
    <input type="submit" name="filter" value="Submit"/>
</form>

<div class="container-fluid">
    <h3>My Transactions</h3>
    <div class="row">
    <div class="card-group">
<?php if($results && count($results) > 0):?>
    <?php foreach($results as $r):?>
        <div class="col-auto mb-3">
            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <div class="card-title">
                        Account Source ID <?php safer_echo($r["act_src_id"]);?>
                    </div>
                    <div class="card-text">
                        <div>Action Type: <?php safer_echo($r["action_type"]); ?></div>
                        <?php if(isset($r["amount"])):?>
                            Amount: <?php safer_echo($r["amount"]);?>
                        <?php else:?>
                            Not Set
                        <?php endif; ?>
                    </div>
                    <div class="card-footer">
                        <a type="button" href="<?php echo getURL("accounts/view_transactions.php?id=" . $r["tranID"]); ?>">More Details</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach;?>

<?php else:?>
<div class="col-auto">
    <div class="card">
       You don't have any transactions.
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
                <li class="page-item <?php echo ($page+1) >= $total_pages?"disabled":"";?>">
                    <a class="page-link" href="?page=<?php echo $page+1;?>">Next</a>
                </li>
            </ul>
        </nav>
    </div>



    
<?php require(__DIR__ . "/../partials/flash.php");
/*
<div class="results">
<?php if (count($results) > 0): ?>
    <div class="list-group">
        <?php foreach ($results as $r): ?>
            <div class="list-group-item">
                <div>
                    <div><strong>Action Type:</strong></div>
                    <div><?php safer_echo($r["action_type"]); ?></div>
                </div>
                <div>
                    <div><strong>Source:</strong></div>
                    <div><?php safer_echo($r["act_src_id"]); ?></div>
                </div>
                <div>
                    <div><strong>Destination:</strong></div>
                    <div><?php safer_echo($r["act_dest_id"]); ?></div>
                </div>
                <div>
                    <div><strong>Amount:</strong></div>
                    <div><?php safer_echo($r["amount"]); ?></div>
                </div>
                <div>
                    <a type="button" href="<?php echo getURL("accounts/view_transactions.php?id=" . $r["tranID"]); ?>">More Details</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>No results</p>
<?php endif; ?>
</div>
</div>
*/