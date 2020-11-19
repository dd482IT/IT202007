<?php require_once(__DIR__ . "/../partials/nav.php"); ?> b
<?php
$query = "";
$results = [];
//we'll put this at the top so both php block have access to it
  if(isset($_GET["id"])){
    $id = $_GET["id"];
    //echo var_export("This is the get id + " . $id, true);

  }
?>
<?php
  $db = getDB();
  $user = get_user_id();
  $stmt = $db->prepare("SELECT account_number, account_type, balance, user_id as id FROM Accounts WHERE id =:q LIMIT 5");
  $r = $stmt->execute([":q" => $user]);

  if($r){
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  else{
    flash("There was a problem fetching the results"); 
  }
?>
<form method="POST">
</form>
<div class="results">
    <div>This is the ID</div>
    <div><?php echo safer_echo($user, true);?></div>
    <?php if (count($results) > 0): ?>
        <div class="list-group">
            <?php foreach ($results as $r): ?>
                <div class="list-group-item">
                    <div>
                    </div>
                    <div>
                        <div>Account Number:</div>
                        <div><?php safer_echo($r["account_number"]); ?></div>
                    </div>
                    <div>
                        <div>Account Type:</div>
                        <div><?php safer_echo($r["account_type"]); ?></div>
                    </div>
                    <div>
                        <div>Balance:</div>
                        <div><?php safer_echo($r["balance"]); ?></div>
                    </div>
                    <div>
                        <div>Owner Id:</div>
                        <div><?php safer_echo($r["id"]); ?></div>
                    </div>
                    <div>
                        <a type="button" href="test_edit_accounts.php?id=<?php safer_echo($r['id']); ?>">Edit</a>
                        <a type="button" href="test_view_accounts.php?id=<?php safer_echo($r['id']); ?>">View</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No results</p>
    <?php endif; ?>
</div>
<?php require(__DIR__ . "/../partials/flash.php");