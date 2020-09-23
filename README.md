#IT202
#Daniel

This repository is all for IT202 at NJIT Fall 2020 Semester.
Project Description is provided below. 

Project Name: Simple Bank
Project Summary: This project will create a bank simulation for users. They’ll be able to have various accounts, do standard bank functions like deposit, withdraw, internal (user’s accounts)/external(other user’s accounts) transfers, and creating/closing accounts.
Github Link: https://github.com/dd482IT/IT202007
Website Link:
Your Name: Daniel Daszkiewicz
------------------------------------------------------------------------------------------------------------------------------------------------------
Milestone Features:
	Milestone 1:
User will be able to register a new account
	ID (not shown on UI), Username, email, password (other fields optional)
	Password field should be 60 characters
	Passwords should be hashed (plain text passwords lose points)
Email should be unique
Username should be unique
Form should have proper validations/checks for legitimate data
Email validation
Username validation (length/required)
Password validation (length minimum)
Confirm password match
System should let user know if username or email is taken and allow the user to correct the error without wiping the form
User will be able to login to their account (given they enter the correct credentials)
User can login with email or username
User should see friendly error messages when an account either doesn’t exist or if passwords don’t match
Logging in should fetch the user’s details (and roles) and save them into the session.
Form should have proper validation
Make sure data has been entered in both fields
User will be directed to a dashboard page upon login
This is a protected page (non-logged in users shouldn’t have access)
User will be able to logout
Logging out will redirect to login page
User should see a message that they’ve successfully logged out
Session should be destroyed (so the back button doesn’t allow them back in)
Basic security rules implemented
Function to check if user is logged in
Function should be called on appropriate pages that only allow logged in users
Basic Roles implemented
Have a Roles table	(id, name, description, active)
Have a User Roles table (id, user_id, role_id, created)
Include a function to check if a user has a specific role (we won’t use it for this milestone but it should be usable in the future)
Site should have basic styles/theme applied
Any technical errors or debug output displayed will result in a loss of points
User will be able to see their profile
Email, username, etc
User will be able to edit their profile
Changing username/email should properly check to see if it’s available before allowing the change
Any other fields should be properly validated
Allow password reset (only if the existing correct password is provided)
	Milestone 2:
Project setup steps:
Create a system user (this will never be logged into, it’s just to keep things working per system requirements)
Create a world account in the Accounts table created below
Account_number must be “000000000000”
User_id must be the id of the system user
Account type must be “world”
Dashboard page
Will have links for Create Account, Accounts, Deposit, Withdraw Transfer, Profile
Links that don’t have pages yet should just have href=”#”
Create the Transactions table (see reference below)
Create the Accounts table (id, account_number [unique], user_id, balance (default 0), account_type, created, modified)
User will be able to create a checking account
System will generate a random 12 digit account number
Must be unique
System will associate the account to the user
Account type will be set as checking
Will require a minimum deposit of $5 (from the world account)
Entry will be recorded in the Transaction table in a transaction pair (per notes below)
Account Balance will be updated based on SUM of BalanceChange of AccountSrc
User will see user-friendly error messages when appropriate
User will see user-friendly success message when account is created successfully
Redirect user to their Accounts page
User will be able to list their accounts
Limit results to 5 for now
Show account number, account type and balance
User will be able to click an account for more information (a.ka. Transaction History page)
Show account number, account type, balance
Show transaction history (from Transactions table)
For now limit results to 10 latest
User will be able to deposit/withdraw from their account(s)
Form should have a dropdown of their accounts to pick from
Form should have a field to enter a positive numeric value
For now, allow any deposit value (0 - inf)
For withdraw, add a check to make sure they can’t withdraw more money than the account has
Form should allow the user to record a memo for the transaction
Each transaction is recorded as a transaction pair in the Transaction table per the details below
These will reflect on the transaction history page (Account more info)
After each transaction pair, make sure to update the Account Balance by SUMing the BalanceChange for the AccountSrc
Deposits will be from the “world account”
Withdraws will be to the “world account”
Transaction type should show accordingly (deposit/withdraw)
Show appropriate user-friendly error messages
Show user-friendly success messages
	Milestone 3:
User will be able to transfer between their accounts
Form should include a dropdown first AccountSrc and a dropdown for AccountDest (only accounts the user owns)
Form should include a field for a positive numeric value
System shouldn’t allow the user to transfer more funds than what’s available in AccountSrc
Form should allow the user to record a memo for the transaction
Each transaction is recorded as a transaction pair in the Transaction table
These will reflect in the transaction history page
Show appropriate user-friendly error messages
Show user-friendly success messages
Transaction History page
Will show the latest 10 transactions by default
User will be able to filter transactions between two dates
User will be able to filter transactions by type (deposit, withdraw, transfer)
Transactions should paginate results after the initial 10
User’s profile page should record First and Last name
User will be able to transfer funds to another user’s account
Form should include a dropdown of the current user’s accounts (as AccountSrc)
Form should include a field for the user’s last name
Form should include a field for the last 4 digits of the user’s account number (to lookup AccountDest)
Form should include a field for a positive numerical value
Form should allow the user to record a memo for the transaction
System shouldn’t let the user transfer more than the balance of their account
System will lookup appropriate account based on user’s last name and the last 4 digits
Show appropriate user-friendly error messages
Show user-friendly success messages
Transaction will be recorded with the type as “ext-transfer”
Each transaction is recorded as a transaction pair in the Transaction table
These will reflect in the transaction history page
	Milestone 4:
User will be able to reset their password if they forgot it (on login)
User can set their profile to be public or private (will need another column in Users table)
If public, hide email address from other users
User will be able open a savings account
System will generate a random 12 digit account number
Must be unique
System will associate the account to the user
Account type will be set as savings
Will require a minimum deposit of $5 (from the world account)
Entry will be recorded in the Transaction table in a transaction pair (per notes below)
Account Balance will be updated based on SUM of BalanceChange of AccountSrc
System sets an APY that’ll be used to calculate monthly interest based on the balance of the account
User will see user-friendly error messages when appropriate
User will see user-friendly success message when account is created successfully
Redirect user to their Accounts page


User will be able to take out a loan
System will generate a 12 digit account number
Must be unique
Account type will be set as loan
Will require a minimum value of $500
System will show/set an APY (before the user submits the form)
This will be used to add interest to the loan account
Form will have a dropdown of the user’s accounts of which to deposit the money into
Special Case for Loans:
Loans will show with a positive balance of what’s required to pay off
User will transfer funds to the loan account to pay it off
Transfers will continue to be recorded in the Transactions table
Loan account’s balance will be the balance minus any transfers to this account
Interest will be applied to the current loan balance and add to it.
A loan with 0 balance will be considered paid off and will not accrue interest and will be eligible to be marked as closed
User can’t transfer more money from a loan once it’s been opened
User will see user-friendly error messages when appropriate
User will see user-friendly success message when account is created successfully
Redirect user to their Accounts page
Listing accounts and/or viewing Account Details should show any applicable APY
User will be able to close an account
User must transfer or withdraw all funds before doing so
Account should have a column “active” that will get set as false.
All queries for Accounts should be updated to pull only “active” = true accounts
Closed accounts don’t show up anymore
If the account is a loan, it must be paid off in full first
Admin role (leave this section for last)
Will be able to search for users by firstname and/or lastname
Will be able to look-up specific account numbers.
Will be able to see the transaction history of an account
Will be able to freeze an account (this is similar to disable/delete but it’s a different column)
Frozen accounts still show in results, but they can’t be interacted with.
[Dev note]: Will want to add a column to Accounts table called frozen and default it to false
Update transactions logic to not allow frozen accounts
Will be able to open accounts for specific users
Will be able to deactivate a user
Requires a new column on the Users table
Deactivated users will be restricted from logging in


Notes/References:
Account Number Requirements
Should be 12 characters long
“World” account should be “000000000000” (this is used for deposit/withdraw showing the movement of money outside of the bank)
Each transaction must be recorded as two separate inserts to the transaction table
*Transaction Table Minimum Requirements
Each action for a set of accounts will be in pairs. The colors in the table below highlight what this means.
The first source/dest is the account that triggered the action to the dest account.
The second source/dest is the dest account's half of the transaction info.
source/dest will swap in the second half of the transaction
BalanceChange will invert in the second half of the transaction
Src/Dest are the account id’s affected (Accounts.id, not account_number).
BalanceChange is the difference in the account (deposit subtracts from source for the first part and adds to source for the second part.
TransactionType is a built-in identifier to track the action (i.e., deposit, withdraw, transfer),
Memo user-defined notes
ExpectedTotal is the account’s final value after the transaction, respectively.
The below Transaction/Ledger table should total (SUM) up to zero to show that your bank is in balance. Otherwise, something bad happened with the transaction based on whether it's negative or positive. In that case we either lost money or stole money.

