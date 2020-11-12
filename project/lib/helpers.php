<?php
session_start();//we can start our session here so we don't need to worry about it on other pages
require_once(__DIR__ . "/db.php");
//this file will contain any helpful functions we create
//I have provided two for you
function is_logged_in() {
    return isset($_SESSION["user"]);
}

function has_role($role) {
    if (is_logged_in() && isset($_SESSION["user"]["roles"])) {
        foreach ($_SESSION["user"]["roles"] as $r) {
            if ($r["name"] == $role) {
                return true;
            }
        }
    }
    return false;
}

/*
function get_role(){ //added by Daniel Daszkiewicz, 10/18/2020
    if (is_logged_in() && isset($_SESSION["user"]["roles"])){
        return $_SESSION["user"]["roles"];  
    }
}
*/
function get_username() {
    if (is_logged_in() && isset($_SESSION["user"]["username"])) {
        return $_SESSION["user"]["username"];
    }
    return "";
}

function get_email() {
    if (is_logged_in() && isset($_SESSION["user"]["email"])) {
        return $_SESSION["user"]["email"];
    }
    return "";
}

function get_user_id() {
    if (is_logged_in() && isset($_SESSION["user"]["id"])) {
        return $_SESSION["user"]["id"];
    }
    return -1;
}

function safer_echo($var) {
    if (!isset($var)) {
        echo "";
        return;
    }
    echo htmlspecialchars($var, ENT_QUOTES, "UTF-8");
}

//for flash feature
function flash($msg) {
    if (isset($_SESSION['flash'])) {
        array_push($_SESSION['flash'], $msg);
    }
    else {
        $_SESSION['flash'] = array();
        array_push($_SESSION['flash'], $msg);
    }

}

function getMessages() {
    if (isset($_SESSION['flash'])) {
        $flashes = $_SESSION['flash'];
        $_SESSION['flash'] = array();
        return $flashes;
    }
    return array();
}


function getAccountType()
{
    switch ($n) {
        case "checking":
            echo "Checking";
            break;
        case "saving":
            echo "Saving";
            break;
        case "loan":
            echo "Loan";
            break;
        case "world":
            echo "World";
            break;
        default:
            echo "Unsupported state: " . safer_echo($n);
            break;
        }

}


function getDropDown(){
    $user = get_user_id();
    $db = getDB();
    $stmt = $db->prepare("SELECT account_number as accs FROM Accounts WHERE Accounts.user_id = :id");
    $r = $stmt->execute([
        ":id"=>$user
    ]);  

    if($r){
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results; 
    }
    else{
     flash("There was a problem fetching the accounts");
    }

}

function doBankAction($acc1, $acc2, $amount, $action)
{
    $db = getDB();
    $user = get_user_id();

    $stmt = $db ->prepare("SELECT SUM(AMOUNT) AS Total FROM Transactions WHERE Transactions.act_src_id = :id");
            $r = $stmt->execute([
                ":id" => $acc1
            ]);
            $results = $stmt->fetch(PDO::FETCH_ASSOC);
            $source_total = $results["Total"]; // ERROR HERE 

    $stmt = $db ->prepare("SELECT SUM(AMOUNT) AS Total FROM Transactions WHERE Transactions.act_src_id = :id");
            $r = $stmt->execute([
                ":id" => $acc2
            ]);
            $results = $stmt->fetch(PDO::FETCH_ASSOC);
            $destination_total = $results["Total"]; // ERROR HERE 


    $stmt = $db ->prepare("INSERT INTO Transactions (act_src_id, act_dest_id, amount, action_type, expected_total) 
        VALUES (:s_id, :d_id, :amount, :action_type, :expected_total) (:s_id2, :d_id2, :amount2, :action_type, :expected_total2)" );
        //since this is called in create then it doesnt need to be called here
            
                $r = $stmt->execute([
                    //first half 
                    "s_id" => $acc1,
                    "d_id" => $acc2,
                    "amount" => $amount,
                    "action_type" => $action,
                    ":expected_total" => $source_total,
                    //second half
                    "s_id2" => $acc2,
                    "d_id2" => $acc1,
                    "amount2" => ($amount*-1),
                    "action_type" => $action,
                    ":expected_total2" => $destination_total
                ]);
                if ($r) {
                    flash("Created successfully with id: " . $db->lastInsertId());
                }
                else {
                    $e = $stmt->errorInfo();
                    flash("Error creating: " . var_export($e, true));
                }
        
}
//end flash

?>