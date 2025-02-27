<?php
include("auth.php"); //include auth.php file on all secure pages 
require('db.php');
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
        <h2>Welcome <?= $_SESSION['username']; ?>!</h2>
        <a href="logout.php">Logout</a>
    </div>
    <form action="" enctype="multipart/form-data" method="post">
        <fieldset>
            <legend>Groups:</legend>
            <?php
            $user_id = $_SESSION['user_id'];

            $query = "SELECT p.group_id,
            p.group_name, u.user_id
            FROM pic_groups p
            join user_groups u 
            on p.group_id=u.group_id
            where u.user_id = '$user_id'";

            $result = $con->query($query);
            // $query = $con->prepare($query);

            // $query->bindParam(1, $user_id, PDO::PARAM_STR, 100);

            // $result = $query->execute();
            // $query->execute();
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                //////////////////////////////////for storing group id for next page//////////////////
                // $group_id = $row['group_id'];
                // $_SESSION['group_id'] = $group_id;
                ///////////////////////////////////////////////////////////////////////////////////////



                ?>
                <table>
                    <tr>
                        <td>
                            <?php
                                $group_id = $row['group_id'];
                                $group_name = $row['group_name'];
                                ?>
                            <?php echo "<a href=index.php?varname=$group_id>$group_name</a>"; ?></td>
                    </tr>
                </table>
            <?php
            }
            ?>
        </fieldset>
        <?php
        // if (isset($_POST['submit'])) {
        //     //  echo "<pre>";
        //     // print_r($_POST);
        //     // die();
        //     $group_id = $_POST['submit'];
        //     $_SESSION['group_id'] = $group_id;
        // header("Location: index.php");
        //  $query = "update `users` set accepted = '1' where user_id = '$id' ";
        //  $result = mysqli_query($con,$query);
        //  ///////////////// for updating user_groups table/////////////////
        //  $query1 = "update `user_groups` set accepted = '1' where user_id = '$id' ";
        //  $result1 = mysqli_query($con,$query1);
        //  if($result){
        //     echo "<h3>user accpeted </h3>";
        //     header('location: admin.php');
        // }else{
        //     echo "FAIL!!!";
        // }

        // }
        ?>

    </form>
</body>

</html>