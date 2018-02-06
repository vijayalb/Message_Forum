<?php
/*
Username : Viji
Password : viji
Full Name: Vijayalakshmi
*/

session_start();
error_reporting(E_ALL);
ini_set('display_errors','On');
if(isset($_POST['login']))
{
	try{
		$dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		print_r($dbh);
		$dbh->beginTransaction();
		$uname=trim($_POST['user_name']);
		$pswrd=trim($_POST['password']);
		$db_pswd=$pswrd;
		$stmt=$dbh->prepare('SELECT * FROM users WHERE username=:username AND password=:password');
		$stmt->execute(array(':username' => $uname,':password' => $db_pswd));
		if($stmt->rowCount()>0)
		{   print 
			$_SESSION['users']=$uname;
			header("Location:board.php");
			exit();
		}
		else
		{
			$error="User does not exist";
			echo $error;
		}
	}
	catch (PDOException $e)
	{
		print "Error!: " . $e->getMessage() . "<br/>";
		die();
	} 
}
?>
<html>
<style>
input[type=text], select {
	width: 100%;
    padding: 12px 20px;
    margin: 8px ;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
	align: center;
}

input[type=submit] {
    width: 100%;
    background-color: #4CAF50;
    color: white;
    padding: 14px 20px;
    margin: 8px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
	align: center;
}

input[type=submit]:hover {
    background-color: #45a049;
}
form {
    display: inline-block;
    text-align: center;
}
</style>
<head><title>Login</title></head>
<body>
<div style="width: 380px; margin: 200px auto 0 auto;">
<form action="login.php" method="post">
<h2>User Login</h2>
<label>Username:</label>
<input type="text" name="user_name" id="uname"><br/>
<label>Password:</label>
<input type="text" name="password" id="pswd"><br/>
<input type="submit" name="login" value="Login">
</form></div>
</body>
</html>