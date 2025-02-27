<?php
 include('db.php');
include("auth.php"); //include auth.php file on all secure pages 

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
<h2>Welcome <?=$_SESSION['username'];?>!</h2>

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

 if(empty($_GET['varname'])){
echo 'you have not selected any group!!';
die();
 }
 $group_id= $_GET['varname'];

if (isset($_POST['submit'])) {
    $filepath = "images/" . $_FILES["file"]["name"];

    $fileName = $_FILES["file"]["name"];
    $filesize = $_FILES["file"]["size"];
    $uploadOk = 1;

    if(strlen($_POST['caption']) > 60){
        echo 'Caption is too long!!';
        die();
         }
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

        /////checks for limits/////////////////////
        //for total posts uploaded to group
        $query = "SELECT * FROM `pics` WHERE  group_id ='$group_id'";
		$result = mysqli_query($con,$query) or die(mysql_error());
        $group_img_count = $result->num_rows;
        echo $group_img_count ."<br>";
        // die();

        //total size of content uploaded to group
        $query2 = "SELECT SUM(size) AS content_size FROM `pics` where group_id ='$group_id' " ;
        $result2 = mysqli_query($con,$query2) or die(mysql_error()); 
        $row2 = $result2->fetch_assoc(); 
        $group_size_uploaded = $row2['content_size'];
        echo $group_size_uploaded ."<br>";

        //max allowed posts per group
        $query3 = "SELECT * FROM `pic_groups` WHERE  group_id ='$group_id'" ;
        $result3 = mysqli_query($con,$query3) or die(mysql_error()); 
        $row3= $result3->fetch_assoc(); 
        $Max_posts_group = $row3['item_limit'];
        echo $Max_posts_group ."<br>";
        // die();

        //max size allowd per group
        $query4 = "SELECT * FROM `pic_groups` WHERE  group_id ='$group_id'" ;
        $result4 = mysqli_query($con,$query4) or die(mysql_error()); 
        $row4= $result4->fetch_assoc(); 
        $Max_size_group = $row4['group_space'];
        echo $Max_size_group ."<br>";
        // die();

        //total images uploaded by user
        $query5 = "SELECT * FROM `pics` WHERE  posted_by ='$user_id'" ;
        $result5 = mysqli_query($con,$query5) or die(mysql_error()); 
        $user_img_count = $result5->num_rows; 
        echo $user_img_count ."<br>";
        // die();

        //total size of images uploaded by user
        $query6 = "SELECT SUM(size) AS content_size_user FROM `pics` where posted_by ='$user_id' " ;
        $result6 = mysqli_query($con,$query6) or die(mysql_error()); 
        $row6 = $result6->fetch_assoc(); 
        $user_size_uploaded = $row6['content_size_user'];
        echo $user_size_uploaded ."<br>";
        // die();

        //max images allowed per user
        $query7 = "SELECT * FROM `user_limit`" ;
        $result7 = mysqli_query($con,$query7) or die(mysql_error()); 
        $row7= $result7->fetch_assoc(); 
        $Max_posts_user = $row7['user_post_limit'];
        echo $Max_posts_user ."<br>";
        // die();

        //max size allowed per user
        $query8 = "SELECT * FROM `user_limit`" ;
        $result8 = mysqli_query($con,$query8) or die(mysql_error()); 
        $row8= $result8->fetch_assoc(); 
        $Max_size_user = $row8['user_size_limit'];
        echo $Max_size_user ."<br>";
        die();
        //////////////////////////////////

        if($group_img_count <= $Max_posts_group){

            Echo 'max posts limit reached for the group';

        } elseif($group_size_uploaded  <= $Max_size_group){

            echo 'max size limit reached for the group';

            }elseif($Max_posts_user  <= $user_img_count){

                echo 'your limit to upload images reached to max';
            }elseif($Max_size_user  <= $user_size_uploaded){

                echo 'you have to max size avaible/user';
            }else{
        
        (move_uploaded_file($_FILES["file"]["tmp_name"], $filepath)) ;  

            }
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
            // print_r($_SESSION);
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
    // $result1 = $con->query($query1);
    // $query1 = $con->prepare($query1);
    // $query1->bindParam(1, $group_id, PDO::PARAM_STR, 100);

    // $query1->execute();
    // $query1 = "SELECT * FROM pics";
    $result1 = $con->query($query1);

    while ($row = $result1->fetch_assoc()){
        // while ($row = $query1->fetch()){

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
       <?php if ($user_id == $_SESSION['user_id'] AND $admin = 'no' ){ ?>
      <button value="<?php echo $row['post_id']; ?>" type="submit" name="delete">Delete</button><br><br>
      <?php }?>

       <?php echo "<a href=comments.php?varname=$post_id>Comments</a>";?></td><br><br> 
       <hr color='black' size='5'> 
       <?php    
}
 ?>
 </form>


</body>

</html>



