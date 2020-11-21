<?php require_once(__DIR__ . "/../partials/nav.php");
      require_once(__DIR__ . "/../lib/helpers.php");
?>


<?php
$accounts = getDropDown();
?>


    <h3>Create Transaction</h3> 
    <form method="POST">      

        <label>Source Account</label placeholder="0">
            <select name="s_id">
            <?php foreach($accounts as $row):?>
                <option value="<?php echo $row["id"];?>"> 
                <?php echo $row["account_number"];?>
                </option>
            <?php endforeach;?>
            </select>

        <script>
            function showTransferForm(){
                if(document.getElementById('type') == "transfer"){
                    document.getElementById('transfer').style.display='block';
                }else{
                    document.getElementById('transfer').style.display='none';
                    document.getElementById('transfer').disabled = true; 
                }
            }
        </script> 
        <div id="transfer">
            <label>Destination Account </label>
            <select name="d_id">
                <?php foreach($accounts as $row):?>
                    <option value="<?php echo $row["id"];?>">
                    <?php echo $row["account_number"];?>
                    </option>
                <?php endforeach;?>
            </select>
        </div>
        
        <label>Amount</label> 
        <input type="number" min="1.00" name="amount">
        <label>Action</label> 
        <select name="action" id="type" placeholder="withdraw" onclick="showTransferForm()">
            <option value ="deposit">desposit</option>
            <option value ="transfer">transfer</option>
            <option value ="withdrawl">withdraw</option>
        </select>

        <input type ="submit" name="save" value="create"/>
    </form> 


<?php
    if(isset($_POST["save"])){
        //$account_type = $_POST["account_type"];
        $source = $_POST["s_id"]; //ACCOUNT 1 
        $destination = $_POST["d_id"]; //ACCOUNT 2 
        $amount = $_POST["amount"];
        $action  = $_POST["action"];// WITHDRAWAL, DESPOIT, TRANSFER
        $user = get_user_id();
        $db = getDB();
        
        $stmt=$db->prepare("SELECT id FROM Accounts WHERE account_number = '000000000000'");
        $results = $stmt->execute();
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        $world_id = $r["id"];

        switch($action){
            case "deposit":
                doBankAction($world_id, $source, ($amount * -1), $action);
            break;
            case "withdrawl":
                doBankAction($source, $world_id, ($amount * -1), $action);
            break;
            case "transfer":
                doBankAction($source,$destination,($amount *-1), $action);
            break;
        }
          
    }
   


?>
<?php require(__DIR__ . "/../partials/flash.php");
