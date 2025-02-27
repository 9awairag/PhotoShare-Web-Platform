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

<form action="" enctype="multipart/form-data" method="post">
        <fieldset>
            <legend>Comment Section:</legend>

            <textarea name='comment' class='textarea' rows="1" cols="50"></textarea><br>
            <button type='submit' name='submit'>post comment</button>
        </fieldset>

<?php
global $post_id;
if(!empty($_GET['varname'])){


$post_id= $_GET['varname'];

$query1 = "SELECT * FROM pics where post_id = '$post_id'";
    $result1 = $con->query($query1);
    $row = $result1->fetch_assoc();
    // $query1 = $con->prepare($query1);
    // $query1->bindParam(1, $post_id, PDO::PARAM_STR, 100);
    // $query1->execute();
    // $row = $query1->fetch();

?>
<!-- image section -->
 <div>
       <img src="<?php echo $row['img_path'];?>"alt="" height="100" style="height=50 width=50" id="Photos" />
</div>
<?php
}else{
    echo 'you have not selected any image to comment on!!';

}
?>
<!-- handeling post comment button -->
<?php
if (isset($_POST['submit'])) {
      if (!empty($_POST['comment'])) {
        try {
            //  echo "<pre>";
            // print_r($_SESSION);
            // die();
          $uniqid = uniqid();
          $user_id = $_SESSION['user_id'];
          if(strlen($_POST['comment']) > 400){
            echo 'Comment can not be more than 400 characters!!';
            die();
             }
          $comment = $_POST['comment'];
          $datetime = date("Y-m-d H:i:s");

        $query = "INSERT into `comments` (comment_id, posted_by, post_id, datetime, comment) VALUES ('$uniqid', '$user_id', '$post_id', '$datetime', '$comment')";
         mysqli_query($con,$query);

        //  $result1 = $con->prepare("INSERT into `comments` (comment_id, posted_by, post_id, datetime, comment) VALUES (?,?,?,?,?)");
        //  $result1->bind_param("sssss", $uniqid, $user_id, $post_id, $post_id ,$comment);
        //  $result1->execute();
        //  $result1->close();
        //   $dbh->exec('insert into posts values("' . uniqid() . '", null ,"' . $username . '"  , now() , "' . $message . '")')
        //     or die(print_r($dbh->errorInfo(), true));

 
        } catch (PDOException $e) {
          print "Error!: " . $e->getMessage() . "<br/>";
          die();
        }
      } else
        echo "Please enter comment to be posted!";
    }

?>

<!-- for delete button funtionality -->
<?php 
if (isset($_POST['delete'])) {
            //  echo "<pre>";
            // print_r($_POST);
            //die();
            $comment_id = $_POST['delete'];
             $query1 = "delete from `comments` where comment_id = '$comment_id' ";
             $result = mysqli_query($con,$query1);
        
    }
    ?>

<?php

    $query1= "SELECT u.fullname,u.user_id,
    c.comment_id, c.posted_by, c.post_id, c.datetime, c.comment
    FROM users u
    join comments c
    on c.posted_by=u.user_id
    where post_id = '$post_id'
    order by datetime DESC";
    $result1 = $con->query($query1);
    // $query1 = $con->prepare($query1);

    // $query1->bindParam(1, $post_id, PDO::PARAM_STR, 100);

    // $result = $query->execute();
    // $query1->execute();
    
    while ($row = $result1->fetch_assoc()){
    $user_id = $row['user_id'];
    $comment_id = $row['comment_id']
?>
 <form action="" enctype="multipart/form-data" method="post">
      <b><?php echo $row['fullname'];?></b>:<?php echo $row['comment'];?><br>
      <?php if ($user_id == $_SESSION['user_id'] ){ ?>
      <button value="<?php echo $row['comment_id']; ?>" type="submit" name="delete">Delete</button><br><br>
<?php
      }
    }
    ?>


          </form>

</form>
</body>
</html>