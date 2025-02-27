
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Registration</title>
<link rel="stylesheet" href="css/style.css" />
</head>
<body>
<?php
	require('db.php');
    // If form submitted, insert values into the database.
    if (isset($_POST['submit'])){
        if(!empty($_POST['checkbox'])){
        $checkbox = $_POST['checkbox'];
            // echo "<pre>";
            // print_r($_POST);
            // die();
            $status = 1;
        $fullname = stripslashes($_REQUEST['fullname']);
		$fullname = mysqli_real_escape_string($con,$fullname);
		$username = stripslashes($_POST['username']); // removes backslashes
		$username = mysqli_real_escape_string($con,$username); //escapes special characters in a string	
		$password = stripslashes($_POST['password']);
        $password = mysqli_real_escape_string($con,$password);

        ///////////////////////////////////////////pasword and user name check///////////////////////////////////////////////

//         $pass = mysqli_real_escape_string($db_link, $_POST['password']);
// $repass = mysqli_real_escape_string($db_link, $_POST['repeat_password']);
// $user = mysqli_real_escape_string($db_link, $_POST['name']);

$uppercase = preg_match('@[A-Z]@', $password);
$lowercase = preg_match('@[a-z]@', $password);
$number    = preg_match('@[0-9]@', $password);

// if (strlen($username) < 6 || strlen($username) > 12) {
    if ($username == '' || strlen($username) < 6 || strlen($username) > 12 || !ctype_alnum($username)) {

    echo "The User ID must be at least 6 characters long and atmost 12 charcters long and can't contain special symbols."."<br>";
    $status = 0;
} 

if(!$uppercase || !$lowercase || !$number || !ctype_alnum($password) || strlen($password) < 8) {

    echo "The password must contain at least 1 lowercase letter, 1 uppercase letter and 1 number.";
    echo "The password can't contain special symbols.";
    $status = 0;
}
// }  elseif ($username == '' && strlen($username) < 6 && strlen($username) > 12 && !ctype_alnum($username)) {

if ($status == 1){
        ///////////////////////////////////////////////////////////////////////////////////////////
        $passcodeen = hash('sha256', (get_magic_quotes_gpc() ? stripslashes($password) : $password));
        $uniqid = uniqid();
        $trn_date = date("Y-m-d H:i:s");
        $no = 'no';
        $int = '0';
        // $result = $con->prepare("INSERT into `users` (user_id, fullname, username, password, trn_date, admin, accepted) VALUES (?, ?, ?, ?, ?, ?, ?)");
        // $result->bind_param("ssssssi", $uniqid, $fullname,$username,$passcodeen,$trn_date,$no,$int);
        // $result->execute();
        // $result->close();

        $query = "INSERT into `users` (user_id, fullname, username, password, trn_date, admin, accepted) VALUES ('$uniqid', '$fullname', '$username', '$passcodeen', '$trn_date', 'no', '')";
        $result = mysqli_query($con,$query);
        if($result){
            echo "<div class='form'><h3>You are registered successfully, wait for confirmation.</h3><br/>Click here to <a href='login.php'>Login</a></div>";
        }
    
        ///////////////////////////////// for inserting values in user_groups table for user registered in multiple groups///////////
        
        foreach ($checkbox as $key=>$value){

        // $result1 = $con->prepare("INSERT into `user_groups` (group_id, user_id, accepted) VALUES (?,?,?)");
        // $result1->bind_param("ssi", $value, $uniqid, $int);
        // $result1->execute();
        // $result1->close();
            $query1 = "INSERT into `user_groups` (group_id, user_id, accepted) VALUES ('".$value."', '$uniqid', '0')";
            $result1 = mysqli_query($con,$query1);
            //     echo "<pre>";
            // print_r($result1);
            // die();
        }

    }   
    }
    else{
        echo "You must select group/groups to join!!";
    }
}else{
?>
<div class="form">
<h1>Registration</h1>

<table>
<tr>
<td>
<form name="registration" action="" method="post">
<input type="text" name="fullname" placeholder="Fullname" required />
<input type="text" name="username" placeholder="Username" required />
<input type="password" name="password" placeholder="Password" required /><br>
<font color="light red">[password must contain at least 1 lowercase letter, 1 uppercase letter and 1 number]</font><br>
Select group/groups to join:<br>
<?php
// $query1 = $con->prepare("SELECT * FROM pic_groups");
// $query1->execute();
// $query1 = "SELECT * FROM pic_groups";
// $result1 = $con->query($query1);

$query1 = "SELECT * FROM pic_groups";
$result1 = $con->query($query1);


    // output data of each row for checkbox
    while($row = $result1->fetch_assoc()) {
    
?>
<input value="<?php echo $row['group_id']; ?>"type="checkbox" name="checkbox[]">
<?php
   echo "".$row["group_name"]. "<br>";
}
?>
<br><button value="Register" type="submit" name="submit">Register</button>
<!-- <input type="submit" name="submit" value="Register" /> -->
</td>
</tr>
</table>
</form>
</div>
<?php } ?>
</body>
</html>
