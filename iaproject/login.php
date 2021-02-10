<?php
session_start();

// if user is logged in
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}

require_once "config.php";

$username = $password = "";
$username_err = $password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }

    //if username and pass are entered
    if(empty($username_err) && empty($password_err)){
      $myusername = mysqli_real_escape_string($link,$_POST['username']);
      $mypassword = mysqli_real_escape_string($link,$_POST['password']);

      // I am using name instead of username because there is no username in database. Creating unique username for every user would take a lot of time
      $sql = "SELECT id, name FROM users WHERE name = '$myusername' and passwd = '$mypassword' LIMIT 1";
      $result = mysqli_query($link,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      if(mysqli_num_rows($result) == 1){
            session_start();

            // session variables
            $_SESSION["loggedin"] = true;
            $_SESSION["id"] = $row['id'];
            $_SESSION["username"] = $myusername;

            // redirect
            header("location: index.php");
        } else{
            echo "Something went wrong, please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <?php  include('header.php');  ?>

    <div class="wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p><a href="register.php">Sign up</a></p>
        </form>
    </div>
</body>
</html>
