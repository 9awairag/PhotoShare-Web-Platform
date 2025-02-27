<?php
include("auth.php"); //include auth.php file on all secure pages 
require('db.php');


if($_SESSION['admin'] == 'no'){
    die('You are unauthorized to view this content');
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Welcome Home</title>
    <!-- <link rel="stylesheet" href="css/style.css" />   -->
</head>

<body>

<div class="form">
        <p>Welcome <?php echo $_SESSION['username']; ?>!</p>

        <a href="logout.php">Logout</a>
    </div>
    <form action="" enctype="multipart/form-data" method="post">
        <fieldset>
            <legend>Create Group:</legend>
            Group Name:<input type="text" name="group_name"><br>
            No. of Members:<input type="text" name="member_cnt"><br>
            No. of items:<input type="text" name="item_cnt"><br>
            Group Space:<input type="text" name="group_space">(MB)<br>
            <!-- <input value="Create" type="submit" name="create"> -->
            <button value="Create" type="submit" name="create">Create</button>
        </fieldset>
<!-- limit user upload count and size of content uploaded by users -->
        <!-- <fieldset>
            <legend>Set User Limits:</legend>
            No. of items/user:<input type="text" name="useritem_cnt"><br>
            allowd Space/user:<input type="text" name="user_space">(MB)<br>
            <button value="Set" type="submit" name="set">Set</button>
        </fieldset> -->
  
        <fieldset>
            <legend>Join Requests:</legend>
            <?php
            $query1 = "SELECT * FROM users where accepted = '0'";
            $result1 = $con->query($query1);
            // output data of each row
            while ($row = $result1->fetch_assoc()) {
            ?>
            <table>
             <tr>
            <td><?php echo $row["fullname"] . " wants to Join" . "<br>";?>
            <button value="<?php echo $row['user_id']; ?>" type="submit" name="accept">Accept</button>
            <button value="<?php echo $row['user_id']; ?>" type="submit" name="reject">Reject</button><br><br></td>
            </tr>
            </table>
            <?php
            }
            ?>
        </fieldset>
    <!-- </form>

    <form action="" enctype="multipart/form-data" method="post"> -->
        <fieldset>
            <legend>Active Users List:</legend>
            <?php
            $query1 = "SELECT * FROM users where accepted = '1'";
            $result1 = $con->query($query1);
            // output data of each row
            while ($row = $result1->fetch_assoc()) {
            ?>
             <table>
             <tr>
            <td> <?php echo $row["fullname"];?>
            <button value="<?php echo $row['user_id']; ?>" type="submit" name="remove">Remove</button><br><br></td>
            </tr>
            </table>
            <?php
            }
            ?>
        </fieldset>
            <!-- /////////////////////////FORM FOR DISPLAYING USER GROUPS/////////////////////////  -->
        <fieldset>
            <legend>Groups:</legend>
            <?php
            $query1 = "SELECT * FROM pic_groups";
            $result1 = $con->query($query1);
            // output data of each row
            while ($row = $result1->fetch_assoc()) {
            ?>
             <table>
             <tr>
            <td>                
            <?php
            $group_id = $row['group_id'];
            $group_name = $row['group_name'];
            // $_SESSION['group_name'] = $group_name;
            ?>
            <?php echo "<a href=index_admin.php?varname=$group_id&group_name=$group_name>$group_name</a>";?></td>
            </tr>
            </table>
            <?php
            }
            ?>
        </fieldset>


<?php

        if (isset($_POST['accept'])) {
            //  echo "<pre>";
            // print_r($_SESSION);
            // die();
            $id = $_POST['accept'];
             $query = "update `users` set accepted = '1' where user_id = '$id' ";
             $result = mysqli_query($con,$query);
             ///////////////// for updating user_groups table/////////////////
             $query1 = "update `user_groups` set accepted = '1' where user_id = '$id' ";
             $result1 = mysqli_query($con,$query1);
             if($result){
                echo "<h3>user accpeted </h3>";
                header('location: admin.php');
            }else{
                echo "FAIL!!!";
            }
        
    }

    if (isset($_POST['reject'])) {
        // echo "<pre>";
        // print_r($_POST);
        // die();
        $id = $_POST['reject'];  
        $query = "update `users` set accepted = '2' where user_id = '$id' ";
        $result = mysqli_query($con,$query);
        // print_r ($result);
        if($result){
           echo "<h3>user rejected </h3>";
           header('location: admin.php');
       }else{
           echo "FAIL!!!";
       }
   
}

if (isset($_POST['remove'])) {
    // echo "<pre>";
    // print_r($_SESSION);
    // die();
    $id = $_POST['remove'];  
    $query = "update `users` set accepted = '3' where user_id = '$id' ";
    $result = mysqli_query($con,$query);
    // print_r ($result);
      ///////////////// for updating user_groups table/////////////////
        $query1 = "update `user_groups` set accepted = '3' where user_id = '$id' ";
        $result1 = mysqli_query($con,$query1);
    if($result){
       echo "<h3>user removed </h3>";
       header('location: admin.php');
   }else{
       echo "FAIL!!!";
   }

}

if (isset($_POST['create'])) {
    // echo "<pre>";
    // print_r($_POST);
    // die();
    $uniqid = uniqid(); 
    $group_name= $_POST['group_name']; 
    $user_count = $_POST['member_cnt']; 
    $item_count = $_POST['item_cnt']; 
    $group_space = $_POST['group_space'] *1000000; 
    $query = "INSERT into `pic_groups` (group_id, group_name, user_limit, item_limit, group_space, leader_id) VALUES ('$uniqid', '$group_name', '$user_count', '$item_count', '$group_space','')";
    $result = mysqli_query($con,$query);
    if($result){
       echo "<h3>Group Created </h3>";
       header('location: admin.php');
       echo "<h3>Group Created </h3>";
   }else{
       echo "FAIL!!!";
   }

}

?>

 
    </form>
</body>

</html>