<?php
 include('db.php');
include("auth.php"); //include auth.php file on all secure pages 

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
<h2>Welcome to Group: <?=$_GET['group_name'];?>!</h2>

<p><a href="dashboard.php">Dashboard</a></p>
<a href="logout.php">Logout</a>
</div>
    <form action="" enctype="multipart/form-data" method="post">
        <fieldset>
            <legend>UPLOAD PHOTOS:</legend>
            <label>Select a Image: <input id="file1" name="file" type="file"></label><br>
            Caption:<input type="text" name="caption"><br>
            <input value="Upload" type="submit" name="submit">
        </fieldset>
        <h2> UPLOADS:</h2><hr size = '5' color= 'black'>
   
        </form>
    <div class="form"> 
    <script type="text/javascript">
        function displayImage(img) {
            document.getElementById("Images").src = img;
        }
    </script>
</div>

<?php
 global $group_id;
 $group_id= $_GET['varname'];

if (isset($_POST['submit'])) {
    $filepath = "images/" . $_FILES["file"]["name"];

    $fileName = $_FILES["file"]["name"];
    $filesize = $_FILES["file"]["size"];
    $uploadOk = 1;
    $caption = $_POST['caption'];
    $user_id = $_SESSION['user_id'];
    
    // echo "<pre>";
    // print_r($_GET);
    // die();

    $imageFileType = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
    
    //Check if user Selected a File or not
    if(!empty($fileName)){   

    // Allow 4 file format only
    $supported_file = array( 'gif', 'jpg', 'jpeg', 'png');
    if (!in_array($imageFileType, $supported_file)) {
        echo "Sorry, only JPG,GIF,JPEG,PNG files are allowed. ";
        $uploadOk = 0;
    }
}
    //Check if $uploadOk becomes 0 
    if ($uploadOk == 0) {
        echo "Your file was not uploaded."."<br>";
    } else{
        (move_uploaded_file($_FILES["file"]["tmp_name"], $filepath)) ;       
    }


//////////////////////////////////////////////////////////////
        
        $uniqid = uniqid();
		$post_date = date("Y-m-d H:i:s");
        $query = "INSERT into `pics` (post_id, posted_by, group_id, datetime, size, caption, img_path) VALUES ('$uniqid', '$user_id', '$group_id', '$post_date', '$filesize', '$caption', '$filepath')";
        $result = mysqli_query($con,$query);
    } 
?>

<!-- for delete button funtion -->
<?php 
if (isset($_POST['delete'])) {
            //  echo "<pre>";
            // print_r($_POST);
            // die();
            $post_id = $_POST['delete'];
             $query1 = "delete from `pics` where post_id= '$post_id' ";
              mysqli_query($con,$query1);
              $query2 = "delete from `comments` where post_id= '$post_id' ";
              mysqli_query($con,$query2);
    }
    ?>

<!-- //////////////////////// -->

<?php

    $query1= "SELECT u.fullname,u.user_id,u.admin,
    p.post_id, p.posted_by,p.group_id, p.datetime, p.size, p.caption,p.img_path
    FROM users u
    join pics p 
    on p.posted_by=u.user_id
    where group_id = '$group_id' 
    order by datetime DESC";
    $result1 = $con->query($query1);


    // $query1 = "SELECT * FROM pics";
    // $result1 = $con->query($query1);

    while ($row = $result1->fetch_assoc()){
    //////////////////////for getting full name for created by:////////////
        // $posted_by = $row['posted_by'];
        // $query2 = "SELECT * FROM users where user_id = '$posted_by'";
        // $result2 = $con->query($query2);  
        // $row2 = $result2->fetch_assoc();
    //////////////////////////////////////////////////////////////////////////
    $post_id = $row['post_id'];
    $user_id = $row['user_id'];
    $admin = $row['admin'];
?>
  <form action="" enctype="multipart/form-data" method="post">
      Created on:<?php echo $row['datetime'];?><br>
      Created by:<?php echo $row['fullname'];?>
       <div>
       <img src="<?php echo $row['img_path'];?>"alt="" height="200" style="height=100 width=100" id="Photos" /></div>
       <font color = "red"><?php echo $row['caption'];?></font><br>
       <!-- delete button add -->
      <button value="<?php echo $row['post_id']; ?>" type="submit" name="delete">Delete</button><br><br>
       <?php echo "<a href=comment_admin.php?varname=$post_id>Comments</a>";?></td><br><br> 
       <hr color='black' size='5'> 
       <?php    
}
 ?>
 </form>


</body>

</html>



