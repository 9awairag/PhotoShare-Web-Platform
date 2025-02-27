
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Login</title>
<link rel="stylesheet" href="css/style.css" />
</head>
<body>
<?php
	include('db.php');
	//$con = new PDO("mysql:host=localhost;dbname=register", "root", "", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    // If form submitted, insert values into the database.
    if (isset($_POST['submit'])){
		if(!empty($_POST['username']) && !empty($_POST['password'])){

		$username = stripslashes($_REQUEST['username']); // removes backslashes
		$username = mysqli_real_escape_string($con,$username); //escapes special characters in a string
		$password = stripslashes($_REQUEST['password']);
		$password = mysqli_real_escape_string($con,$password);
		$passcodeen = hash('sha256', (get_magic_quotes_gpc() ? stripslashes($password) : $password));

	//Checking is user existing in the database or not
	// $query = $con->prepare("SELECT * FROM `users` WHERE username=? and password=?");
	// $query->bindParam(1,$username,PDO::PARAM_STR, 100);
	// $query->bindParam(2,$passcodeen,PDO::PARAM_STR, 100);


	// $result = $query->execute();
	// $query->execute();

	// // //fetching result would go here, but will be covered later
	// $query->store_result();
	
	// echo '<pre>';
	// print_r ($result);
	// die();
        $query = "SELECT * FROM `users` WHERE username='$username' and password='$passcodeen'";
		$result = mysqli_query($con,$query) or die(mysql_error());
		// //$rows = $query->num_rows;
		// $user = $rows['username'];
		// echo $user;
		// $_SESSION['username'] = $user;
		// echo $row['username'];
		// echo "<pre>";
		// print_r($result);
		// echo $rows;
		// die();
		// $rows=$query->num_rows;
		$rows = mysqli_num_rows($result);
        if($rows == "1"){
			while($row = $result->fetch_assoc()){
		// echo "<pre>";
		// print_r($row);
		// echo $rows;
		// die();
			if($_SESSION['username'] = $username && $row['admin'] == 'yes')
			{
				$dbusername=$row['username'];
				// echo $dbusername;
				// die();
				$dbpassword=$row['password'];
				$dbuserid=$row['user_id'];
				$dbadmin = $row['admin'];
				// echo $_SESSION['username'];
			header("Location: admin.php"); // Redirect user to admin.php
		}elseif($_SESSION['username'] = $username && $row['accepted'] == '1'){
			$dbusername=$row['username'];
			$dbpassword=$row['password'];
			$dbuserid=$row['user_id'];
			$dbadmin = $row['admin'];
			$_SESSION['username'];
			header("Location: user.php"); // Redirect user to index.php
			}elseif($_SESSION['username'] = $username && $row['accepted'] == '2'){
	
				echo "Admin Rejected your join request for some reasons!!!";
			}elseif($_SESSION['username'] = $username && $row['accepted'] == '3'){

				echo "Admin Removed you due to some reasons!!!";
			}else{
				echo 'fail';
			}
		// }
		// 	}
			
			if($username==$dbusername && $passcodeen==$dbpassword){
				session_start();
				$_SESSION['username']=$username;
				$_SESSION['user_id']=$dbuserid;
				$_SESSION['admin']=$dbadmin;
			}
		}
	}
			
			else{
				echo "<div class='form'><h3>Username/password is incorrect.</h3><br/>Click here to <a href='login.php'>Login</a></div>";
				}
	}

else{
echo("All fields are required!");
}
}
	
	else{
?>
<div class="form">
<h1>Log In</h1>
<form action="" method="post" name="login">
<input type="text" name="username" placeholder="Username" required />
<input type="password" name="password" placeholder="Password" required />
<input name="submit" type="submit" value="Login" />
</form>
<p>Not registered yet? <a href='registration.php'>Register Here</a></p>
</div>
<?php } ?>


</body>
</html>
