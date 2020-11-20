<?php require_once(__DIR__ . "/../partials/nav.php");
      require_once(__DIR__ . "/../lib/helpers.php");
?>

<?php
  $user = get_user_id();
  if(isset($user)){
  $results = [];
  $db = getDB();
  $stmt = $db->prepare("SELECT account_number, account_type, balance FROM Accounts WHERE Accounts.user_id = :q LIMIT 5");
  $r = $stmt->execute([":q" => $user]);
    if($r){
      $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    else{
      flash("There was a problem fetching the results"); 
    }
  }
?>

<div class="results">
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