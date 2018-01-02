<?php

$mode= $_GET["mode"];

if($mode == "ask")
{
	$un = $_GET["username"];
	
	//connect to the database
	include("includes/openDbConn.php");
	
	//make sure login doesn't exist
	$sql = "SELECT UserID FROM users_356Lab07 WHERE UserID='".$un."'";
	
	//call equery
	$result = mysql_query($sql);
	
	//check to make sure there is a result
	if(empty($result))
		$num_records = 0;
	else
		$num_records == mysql_num_rows($result);
	
	//close connect to the database
	include("includes/closeDbConn.php");
	
	if($num_records==0)
		echo("available");
	else
		echo("not available");
	
}

?>