<?php
session_start();

// if user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
   header("location: login.php");
   exit;
}

require_once "config.php";

$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

   if(empty(trim($_POST["new_password"]))){
       $new_password_err = "Please enter new password.";
   } elseif(strlen(trim($_POST["new_password"])) < 6){ //6 characters rule
       $new_password_err = "Password must have minimum 6 characters.";
   } else{
       $new_password = trim($_POST["new_password"]);
   }

   if(empty(trim($_POST["confirm_password"]))){
       $confirm_password_err = "Please confirm the password.";
   } else{
       $confirm_password = trim($_POST["confirm_password"]);
       if(empty($new_password_err) && ($new_password != $confirm_password)){
           $confirm_password_err = "Password did not match.";
       }
   }

   if(empty($new_password_err) && empty($confirm_password_err)){
      // prepare statement
       $sql = "UPDATE users SET passwd = ? WHERE id = ?";

       if($stmt = mysqli_prepare($link, $sql)){
           mysqli_stmt_bind_param($stmt, "si", $new_password, $_SESSION["id"]);

           if(mysqli_stmt_execute($stmt)){
               // passwd updated, destroy session, redirect to login
               session_destroy();
               header("location: login.php");
               exit();
           } else{
               echo "Something went wrong, please try again.";
           }

           // Close statement
           mysqli_stmt_close($stmt);
       }
   }

   // Close connection
   mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Reset Password</title>
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
   <style type="text/css">
       body{ font: 14px sans-serif; }
       .wrapper{ width: 350px; padding: 20px; }
   </style>
</head>
<body>
  <?php  include('header.php');  ?>
   <div class="wrapper">
     <?php

     $userid =  $_SESSION['id'];
     $sql = "SELECT * FROM users WHERE id = '$userid'";
     $result = mysqli_query($link,$sql);
     $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

     echo "<p><h2>About me</h2></p>";
     echo "<p><b>Name</b></p>";
     echo "<p>" . $row['name'] . "</p>";
     echo "<p><b>Age</b></p>";
     echo "<p>" . $row['edad'] . "</p>";
     echo "<p><b>Sex</b></p>";
     echo "<p>" . $row['sex'] . "</p>";
     echo "<p><b>Occupation</b></p>";
     echo "<p>" . $row['ocupacion'] . "</p>";
     ?>
     <h2>Reset Password</h2>
     <p>Please fill out this form to reset your password.</p>
     <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
         <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
             <label>New Password</label>
             <input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>">
             <span class="help-block"><?php echo $new_password_err; ?></span>
         </div>
         <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
             <label>Confirm Password</label>
             <input type="password" name="confirm_password" class="form-control">
             <span class="help-block"><?php echo $confirm_password_err; ?></span>
         </div>
         <div class="form-group">
             <input type="submit" class="btn btn-primary" value="Submit">
             <a class="btn btn-link" href="index.php">Cancel</a>
         </div>
     </form>
   </div>
</body>
</html>
