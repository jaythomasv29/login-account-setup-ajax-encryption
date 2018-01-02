<?php
//start session
session_start();

//get the data from the form
$userID		=$_POST["UserID"];
$psw1		=md5($_POST["Passwd"]);
$psw2		=md5($_POST["PasswdConfirm"]);
$pwdMatch	=false;

//make sure form is filled out
if(empty($_POST["UserID"]) ||empty($_POST["Passwd"]) || empty($_POST["PasswdConfirm"]) ||empty($_POST["AcctType"]) ||
  empty($_POST["name"]) ||empty($_POST["address"]) || empty($_POST["city"]) || empty($_POST["state"]) || empty($_POST["zip"]) ||
   empty($_POST["phone"]) || empty($_POST["email"]))
{
	$_SESSION["errorMessage"] = "Please complete all of the form fields";
	header("Location:newAccount.php");
	exit;
}

//connect to the database
include("includes/openDbConn.php");

//if the passwords match
if($psw1==$psw2)
{
	//make sure login doesn't exist
	$sql = "SELECT UserID FROM users_356Lab07 WHERE UserID='".$userID."'";
	
	//call query
	$result = mysql_query($sql);
	
	//check to make sure there is a result
	if(empty($result))
	{
		$num_records=0;
	}
	else
	{
		$num_records=mysql_num_rows($result);
	}
	
	if($num_records == 0)
	{
		//encrypt the email address
		//thekey below should go into a variable like $key or $encryptionKey and be stored in a constants.php file
		$iv_size=mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CFB);
		$iv=mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$_SESSION["iv"]=$iv;
		$encryptedEmail=mcrypt_encrypt(MCRYPT_RIJNDAEL_256, "go356phpEncryption", $_POST["email"], MCRYPT_MODE_CFB, $_SESSION["iv"]);
		
		//form insert statement
		$sql="INSERT INTO users_356Lab07(UserID, Password, AcctType, Name, Address, City, State, Zip, Phone, Email) ";
		$sql.="VALUES('".$userID."','".$psw1."','".$_POST["AcctType"]."','".$_POST["name"]."','".$_POST["address"]."','";
		$sql.=$_POST["city"]."','".$_POST["state"]."','".$_POST["zip"]."','".$_POST["phone"]."','".$encryptedEmail."')";
		
		//insert user into the database
		$result=mysql_query($sql);
		
		//message to user
		$_SESSION["errorMessage"]="User added successfully";
		
		//set password match to true
		$pwdMatch = true;
	}
	else
	{
	//message to user
	$_SESSION["errorMessage"]="User already exists in the database";
		
		//set password match to true
		$pwdMatch=false;
	}
}
else
{
	//passwords do not match
	//let them know
	$_SESSION["errorMessage"]="The two passwords you entered do not match";
	
	//set password match to false
	$pwdMatch = false;
}

//close connect to the database
include("includes/closeDbConn.php");

//if pwdMatch is true
if($pwdMatch)
{
	CleanUp();
	
	//redirect to success
	header("Location:index.php");
	exit;
}
else
{
	CleanUp();
	
	//redirect back to new account
	header("Location:newAccount.php");
	exit;
}

//clear any variable you have used before you end this page.
//This is "cleaning up" our variables that are no longer used and
//restoring that memory so that the computer can use it elsewhere.
//Add the code: CleanUp() before all Redirects on the page
function CleanUp()
{
	$userID		="";
	$psw1		="";
	$psw2		="";
	$pwdMatch	="";
	$sql		="";
	$result		="";
}

?>