<?php
require_once "config.php";

$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

   if(empty(trim($_POST["username"]))){
       $username_err = "Please enter a username.";
   } else{
       $sql = "SELECT id FROM users WHERE name = ?";

       if($stmt = mysqli_prepare($link, $sql)){
           mysqli_stmt_bind_param($stmt, "s", $param_username);

           $param_username = trim($_POST["username"]);

           if(mysqli_stmt_execute($stmt)){
               mysqli_stmt_store_result($stmt);

               if(mysqli_stmt_num_rows($stmt) == 1){
                   $username_err = "This username is already taken.";
               } else{
                   $username = trim($_POST["username"]);
               }
           } else{
               echo "Something went wrong.";
           }
           mysqli_stmt_close($stmt);
       }
   }

   // Validate
   if(empty(trim($_POST["password"]))){
       $password_err = "Please enter a password.";
   } elseif(strlen(trim($_POST["password"])) < 6){
       $password_err = "Password must have atleast 6 characters.";
   } else{
       $password = trim($_POST["password"]);
   }

   if(empty(trim($_POST["confirm_password"]))){
       $confirm_password_err = "Please confirm password.";
   } else{
       $confirm_password = trim($_POST["confirm_password"]);
       if(empty($password_err) && ($password != $confirm_password)){
           $confirm_password_err = "Password did not match.";
       }
   }

   if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){

       $sex = trim($_POST["sex"]);
       $age = trim($_POST["age"]);
       $occupation = trim($_POST["occupation"]);

       $sql = "INSERT INTO users (name, edad, sex, ocupacion, passwd) VALUES (?, ?, ?, ?, ?)";

       if($stmt = mysqli_prepare($link, $sql)){
           mysqli_stmt_bind_param($stmt, "sssss", $username, intval($age), $sex, $occupation ,$password);

           if(mysqli_stmt_execute($stmt)){
               header("location: login.php");
           } else{
               echo "Something went wrong. Please try again.";
           }

           mysqli_stmt_close($stmt);
       }
   }
   mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Sign Up</title>
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
   <style type="text/css">
       body{ font: 14px sans-serif; }
       .wrapper{ width: 350px; padding: 20px; }
   </style>
</head>
<body>

  <?php  include('header.php');  ?>
   <div class="wrapper">
       <h2>Sign Up</h2>
       <p>Please fill this form to create an account.</p>
       <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
           <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
               <label>Username</label>
               <input type="text" name="username" class="form-control" value="<?php $username; ?>">
               <span class="help-block"><?php echo $username_err; ?></span>
           </div>
           <div class="form-group">
               <label>Age</label>
               <input type="number" name="age" class="form-control" value="<?php echo $age; ?>">
           </div>
           <div class="form-group">
               <label>Sex</label>
               <select name="sex">
                <option value="M">M</option>
                <option value="F">F</option>
              </select>
           </div>
           <div class="form-group">
               <label>Occupation</label>
               <select name="occupation">
                 <option value="administrator">administrator</option>
                 <option value="artist">artist</option>
                 <option value="doctor">doctor</option>
                 <option value="educator">educator</option>
                 <option value="engineer">engineer</option>
                 <option value="entertainment">entertainment</option>
                 <option value="executive">executive</option>
                 <option value="healthcare">healthcare</option>
                 <option value="homemaker">homemaker</option>
                 <option value="lawyer">lawyer</option>
                 <option value="librarian">librarian</option>
                 <option value="marketing">marketing</option>
                 <option value="none">none</option>
                 <option value="other">other</option>
                 <option value="programmer">programmer</option>
                 <option value="retired">retired</option>
                 <option value="salesman">salesman</option>
                 <option value="scientist">scientist</option>
                 <option value="student">student</option>
                 <option value="technician">technician</option>
                 <option value="writer">writer</option>
               </select>
           </div>
           <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
               <label>Password</label>
               <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
               <span class="help-block"><?php echo $password_err; ?></span>
           </div>
           <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
               <label>Confirm Password</label>
               <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
               <span class="help-block"><?php echo $confirm_password_err; ?></span>
           </div>
           <div class="form-group">
               <input type="submit" class="btn btn-primary" value="Submit">
               <input type="reset" class="btn btn-default" value="Reset">
           </div>
           <p><a href="login.php">Login</a>.</p>
       </form>
   </div>
</body>
</html>
