
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<?php
//we'll be including this on most/all pages so it's a good place to include anything else we want on those pages
require_once(__DIR__ . "/../lib/helpers.php");
?>
<link rel="stylesheet" href="<?php echo getURL("static/css/styles.css");?>">
<nav>
<ul class="nav">
    <li><a href="<?php echo getURL("home.php");?>">Home</a></li>
    <?php if (!is_logged_in()): ?>
        <li><a href="<?php echo getURL("login.php");?>">Login</a></li>
        <li><a href="<?php echo getURL("register.php");?>">Register</a></li>
    <?php endif; ?>
    <?php if (has_role("Admin")): ?>
            <li><a href="<?php echo getURL("testFiles/test_create_accounts.php");?>">Create Account</a></li>
            <li><a href="<?php echo getURL("testFiles/test_list_accounts.php");?>">View Accounts</a></li>
            <li><a href="<?php echo getURL("testFiles/admin_page.php");?>">Admin Page</a></li>
        <?php endif; ?>
    <?php if (is_logged_in()): ?>
        <li><a href="<?php echo getURL("profile.php");?>">Profile</a></li>
        <li><a href="<?php echo getURL("logout.php");?>">Logout</a></li>
        <li><a href="<?php echo getURL("logout.php");?>">Logout</a></li>
        <li><a href="<?php echo getURL("accounts/create_accounts.php");?>">Create Account</a></li>
        <li><a href="<?php echo getURL("accounts/my_accounts.php");?>">My Accounts</a></li>
        <li><a href="<?php echo getURL("accounts/create_transactions.php");?>">Make a Transaction</a></li>
        <li><a href="<?php echo getURL("accounts/send.php");?>">Send Money</a></li>
        <li class="nav-item dropdown">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Loan
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="<?php echo getURL("accounts/manage_loan.php");?>">Action</a>
                    <a class="dropdown-item" href="<?php echo getURL("accounts/apply_for_loan.php");?>">Another action</a>
                    </div>
                </li>
            </div>
        </li>
    <?php endif; ?>
</ul>
</nav>
