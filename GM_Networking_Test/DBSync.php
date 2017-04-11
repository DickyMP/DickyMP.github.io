<?php
header("Access-Control-Allow-Origin: *");
/***********************************************************************************************************************************
* gameSync System
* gameSync ver 1.0
* (c) 2012,2015 Destron Media LLC
* Created by Destron 
* 
* Visit http://gs.destronmedia.com for updated information.
* Email support@destronmedia.com for help. 
* License: Free to use for all projects commercial or otherwise. Credit is not required. Donations are welcome :)
**************************************************************************************************************************************/
// When mistakes happen we want the notice returned not the warning. The Warning means nothing to the end user, but we will use the notice
// to return information to the user. You should really log the errors though so you have them.
//==MAN1==
//error_reporting(E_ERROR); 

/*
STEP 1 MySQL Connection Details
If you do not know the following information you will need to get it from your web host.
It will most likely be the same as your cPanel login information. 
Server should stay as localhost unless this file and your MySQL are on different servers (not recomended).
*/
//==MAN2==
$hostname = "localhost"; //This is probably correct
$username = "id1338792_myrgm";    // Your MySQL username
$password = "master53";        // Your MySQL Password
$database = "id1338792_gmtestserver";     // The name of your database
$table = "USER_DATA"; // Your actual table to hold the data
//$table2 = "";  //You can use multiple tables to organize your database!

// Make a MySQL Connection no changes need to be made here
//==MAN3==
$dbh = new PDO("mysql:host=$hostname; dbname=$database", $username, $password);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
/* 
STEP 2 Potential Variables passed from URL - list them all here.
*/
//==MAN4==
$f = $_POST['f']; //na=new account sv=save ld=load onformation and au=authorize access
$pname = $_POST['n'];
//$pip = $_POST['ip'];
//$pnp = $_POST['np'];
//$pop = $_POST['op'];
//$psp = $_POST['sp'];
$pword = $_POST['pass'];

echo $f;
echo $pname;
echo $pword;

//==MAN5==
//$salt = "gameMaker"; // CHANGE THIS TO SOMETHING SECRET
//$epword = crypt($pword,$salt); // This encrypts the password and sets it to a variable.

//==MAN6==
//Functions Start

//This function will create a new user account or save file
//==MAN7==
function create_account($dbh,$table,$pword,$pname)  //declare function Part between () is all the variables you will need for this function.
{
try
	{
		$stmt = $dbh->prepare("INSERT INTO $table (user_id, user_password) VALUES (:pname, :pword)"); //Prepare statement for instert
		$stmt ->bindparam(':pname', $pname, PDO::PARAM_STR);  //Build inserts array
		$stmt ->bindparam(':pword', $epword, PDO::PARAM_STR);
		$stmt->execute();  //Execute array
		echo "1";  //Everything worked!
	}
	catch (PDOExecption $ex)
		{	
			echo "0";  //Something went wrong :(
		}
	$dbh = null;
}

// This function will save information to an existing account
//==MAN8==
function save_info($dbh, $table, $pname, $pip, $pnp, $pop, $psp)
{
try
	{
		$stmt = $dbh->prepare("UPDATE $table SET p_ip=?, p_np=?,p_op=?,p_sp=? WHERE p_name=?");
		$stmt->execute(array($pip,$pnp,$pop,$psp,$pname));
		echo "1";
	}
	catch (PDOExecption $ex)
		{	
			echo "0";
		}
	$dbh = null;
}

// This function will pull account information
//==MAN9==
function load_info($dbh,$table,$pname)
{
	{
		$stmt = $dbh->query("SELECT * FROM $table WHERE p_name = '$pname'");
		$result = $stmt->fetchObject();
		echo $result->p_name.",".$result->p_ip. "," .$result->p_np. "," .$result->p_op. "," . $result->p_sp;
		$dbh = null;
	}
}
// This function will simply check if a user exists in the system - useful to auhorize thier aceess. 
//==MAN10==
function auth($dbh, $table, $epword, $pname)
{
	$stmt = $dbh->query("SELECT COUNT(*) from $table WHERE p_name = '$pname' AND p_word = '$epword'"); 
	$result = $stmt->fetchColumn();
	
	if ($result <= 0)
		{
		echo '0';
		}
	else
		{
		echo '1';	
		}	
}
// This function is used for nothing more than testing that the script and database are working. 
//==MAN11==
function connection_test($dbh,$table,$pname)
{
	$stmt = $dbh->query("SELECT COUNT(*) from $table"); 
	$result = $stmt->fetchColumn();
	
	if ($result <= 0)
		{
		echo 'Could not communicate with database. Check your setup, username & pass, or your database is empty.';
		}
	else
		{
		echo 'Everything seems good you have '. $result .' row(s) in your database!';	
		}
}

// This determines which function to call based on the $f parameter passed in the URL.
//==MAN12==
/*switch($f)
{
	case na: create_account($dbh,$table,$pword,$pname); break;
//	case sv: save_info($dbh, $table,$pname,$pip,$pnp,$pop,$psp); break;
//	case ld: load_info($dbh,$table,$pname); break;
//	case au: auth($dbh,$table,$epword,$pname); break;
//	case ts: connection_test($dbh,$table,$pname); break;
	default: echo"error";
}

?>*/