<?php require_once(__DIR__ . "/../partials/nav.php");
      require_once(__DIR__ . "/../lib/helpers.php");
?>


<?php
$accounts = getDropDown();
?>

    <h3 class="text-center"><strong>Send Money</strong></h3> 
    <hr>
    <form align="center" method="POST">     
        <label>Source User Account</label placeholder="0">
            <select name="s_id">
            <?php foreach($accounts as $row):?>
                <option value="<?php echo $row["id"];?>"> 
                <?php echo $row["account_number"];?>
                </option>
            <?php endforeach;?>
            </select>
        <div id="transfer">
            <label>Destination User Account </label>
            <select name="d_id">
            </select>
        </div>


        <label>Amount</label> 
        <input type="number" min="1.00" name="amount">
        <label>Memo</label>
        <input type="text" name="memo">
        <input class="btn btn-primary" type ="submit" name="save" value="create"/>
    <hr> 
    </form> 


<?php
    if(isset($_POST["save"])){
        //$account_type = $_POST["account_type"];
        $source = $_POST["s_id"]; //ACCOUNT 1 
        $destination = $_POST["d_id"]; //ACCOUNT 2 
        $amount = $_POST["amount"];
        $action  = $_POST["action"];// WITHDRAWAL, DESPOIT, TRANSFER
        $memo = $_POST["memo"];
        $user = get_user_id();
        $db = getDB();

        $stmt=$db->prepare("SELECT balance FROM Accounts WHERE Accounts.id = :q");
        $results = $stmt->execute(["q"=> $source]);
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        $balance = $r["balance"];

        if(!isset($memo) && empty($memo)){
            $memo = "empty";
        }
        

        if($amount <= $balance){
          doBankAction($source,$destination,($amount *-1), $action, $memo);
        }
        elseif($amount > $balance){
          flash("Balance Too Low");
        } 
    }
   


?>
<?php require(__DIR__ . "/../partials/flash.php");
