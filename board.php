<?php
/*
Username : Viji
Password : viji
Full Name: Vijayalakshmi
*/
session_start();
?>
<html>
<head><title>Message Board</title></head>
<style>
	div {
    height:100%;
}
textarea {
	width: 50%;
	height: 10%;
    padding: 12px 20px;
    margin: 8px ;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
	align: center;
}

button[type=submit] {
    width: 10%;
    background-color: #4CAF50;
    color: white;
    padding: 14px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
	align: center;
}
input[type=submit] {
    width: 24%;
    background-color: #4CAF50;
    color: white;
    padding: 14px 20px;
    margin: 8px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
	align: center;
}
</style>
<body>
<h2 align="center">The Message Board</h2>
<?php
error_reporting(E_ALL);
ini_set('display_errors','On');
try {
  $dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  $dbh->beginTransaction();
  $dbh->commit();
} 
catch (PDOException $e) {
  print "Error!: " . $e->getMessage() . "<br/>";
  die();
}
if(isset($_POST['logout']))
{
	session_unset();
	header("Location:login.php");
	exit();
}
if(isset($_POST['msg']) && isset($_POST['newpost']))
{   if($_POST['msg']!='')
	{
		$dbh->exec("INSERT INTO posts VALUES('".uniqid()."',null,'".$_SESSION['users']."',now(),'".$_POST['msg']."')")
		or die(print_r($dbh->errorInfo(), true));
		header("Location:board.php");
		exit();
	}
	else
	{
		print '<p>Please enter the message</p>';
	}
}
else if (isset($_POST['reply']) && isset($_POST['msg']))
{	
	if($_POST['msg']!='')
	{	$sql="INSERT INTO posts VALUES('".uniqid()."','".$_POST['reply']."','".$_SESSION['users']."',now(),'".$_POST['msg']."')";
		$result=$dbh->prepare($sql);
		$result->execute();
	}
	else
	{
		print '<p>Please enter the message</p>';
	}
}
if(isset($_SESSION['users']))
{
	print '
	<p>Message:</p><textarea name="msg" rows=10 cols=60 form="post_message" ></textarea>
	<form id="post_message" action="board.php" method="POST">
	<input type="submit" name="newpost" value="New Post"/>
	<input type="submit" name="logout" value="Logout">
	<fieldset><br/>
	<legend style="width:500px,height=500px">Messages</legend>';
	$sql="SELECT p.id,u.username,u.fullname,p.datetime,p.message,p.replyto FROM users u,posts p WHERE u.username=p.postedby order by p.datetime desc";
	foreach($dbh->query($sql) as $row)
	{
		$reply='';
		if($row['replyto']!='')
		{   
			$reply="Reply to".$row['replyto'];
		}
		else
			$reply='New Post';
		print '<tr><td>Date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row['datetime'].'<br>';
		print 'Message ID:&nbsp;&nbsp;'.$row['id'].'<br>';
		print 'Username:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row['username'].'<br>';
		print 'Fullname:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row['fullname'].'<br>';
		print 'ReplyTo:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$reply.'<br>';
		print 'Message:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row['message'].'<br>'; 
		print '<br><button type="submit" name="reply" method="POST" value='.$row['0'].' formaction="board.php?replyto='.$row['id'].'">Reply</button></td></tr><br><br/>';
	}
	print '</fieldset>';
	print '</form>';
	print '</div>';
}
else
{
	header("Location: login.php");
	exit();
}
?>
</body>
</html>
 