<?php
//session start
session_start();

//connect to the database
include("includes/openDbConn.php");

//get the data from the form
$userID		=$_POST["UserID"];
$password	=md5($_POST["Passwd"]);

//form query to check user's credentials
$sql="SELECT * FROM Users_356Lab07 WHERE UserID='".$userID."' AND Password='".$password."'";

//call query
$result=mysql_query($sql);

//check to make sure there is a result
if(empty($result))
{
	$num_records =0;
}
else
{
	$num_records=mysql_num_rows($result);
}

//close connection to the database
include("includes/closeDbConn.php");

//if there's a record, then login is successful
if($num_records==1)
{
	CleanUp();
	$_SESSION["errorMessage"]="";
	$_SESSION["login"]=$userID;
	header("Location:success.php");
	exit;
}
else
{
	CleanUp();
	$_SESSION["errorMessage"]="Either your login or password were incorrect";
	header("Location:index.php");
	exit;
}

//clear any variable you have used before you end this page.
//This is "cleaning up" our variables that are no longer used and
//restoring that memory so that the computer can use it elsewhere.
//Add the code: CleanUp() before all Redirects on the page
function CleanUp()
{
	$userID			="";
	$password		="";
	$sql			="";
	$result			="";
	$num_records	="";
}

?>