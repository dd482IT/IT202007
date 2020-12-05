
<?php require_once(__DIR__ . "/../partials/nav.php"); ?>
<?php
$query = "";
$results = [];
$results2 = [];

if(isset($_GET["id"])){ // ASK PROFFESOR 
  $user = $_GET["id"];
}
else{
  safer_echo("The id was not pulled");
}
?>

<?php
if (isset($user) && !empty($user)) {
    $db = getDB();
    //$stmt=$db->prepare("SELECT amount, action_type, created, act_src_id, act_dest_id, Transactions.id as tranID FROM Transactions as Transactions JOIN Accounts ON Transactions.act_src_id = Accounts.id WHERE Accounts.id = :q LIMIT 10");
    $r;

    if(isset($_POST["filter"])){
        $startDate = $_POST["trans-start"];
        $endDate = $_POST["trans-end"];
        $type = $_POST["action"];
        $params = [];
        $query = "SELECT amount, action_type, created, act_src_id, act_dest_id, Transactions.id as tranID FROM Transactions as Transactions JOIN Accounts ON Transactions.act_src_id = Accounts.id WHERE Accounts.id = :q";

        if(!empty($type)){
            $query .= " AND action_type = :x";
            $params[":x"] = $type;
        }

        if(!empty($date) && !empty($endDate)){
            $query .= " AND created BETWEEN :y AND :z";
            $params[":y"] = $startDateype;
            $params[":z"] = $endDate;
        }
        $params[":q"] = $user;
        $query .= " LIMIT 10";
        $stmt=$db->prepare($query);
        $r = $stmt->execute($params);
    }
    else{
        $stmt=$db->prepare("SELECT amount, action_type, created, act_src_id, act_dest_id, Transactions.id as tranID FROM Transactions as Transactions JOIN Accounts ON Transactions.act_src_id = Accounts.id WHERE Accounts.id = :q LIMIT 10");
        $r = $stmt->execute([":q"=> $user]);
        
        flash("Date Not Valid");
    }


    //$r = $stmt->execute([ ":q" => $user]);
    if ($r) {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        flash("Results are successfull");
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
    <lable for="type_filter"> Action Type: </label> 
        <select class="type_filter" name="action" id="type_filter" placeholder="">
                <option value ="transfer">transfer</option>
                <option value ="deposit">desposit</option>
                <option value ="withdrawl">withdraw</option>
                <option value ="">All</option>
        </select> 
    <label for="startDate">Start date:</label>
        <input class ="startDate" type="date" id="startDate" name="trans-start" value="2018-07-22" min="2000-01-01" max="2099-12-31">
    <label for="endDate">End date:</label>
        <input type="date" id="endDate" name="trans-end" value="2018-07-22" min="2000-01-01" max="2099-12-31">
    <input type="submit" name="filter" value="Create"/>
</form>

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
<?php require(__DIR__ . "/../partials/flash.php");