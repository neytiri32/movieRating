<link rel="stylesheet" href="style.css">
<meta charset="UTF-8">

<ul class="topnav">
  <li><a class="active" href="index.php">Home</a></li>
  <?php session_start();
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
      <li><a href="myrec.php">My rewiews</a></li>
      <li class="right" ><a href='reset-password.php'>Profile(<?php echo $_SESSION['username']; ?>)</a></li>
      <li class="right" ><a href='logout.php'>Logout</a></li>
  <?php else : ?>
    <li class="right" ><a href='login.php'>Login</a></li>
  <?php endif; ?>
</ul>
