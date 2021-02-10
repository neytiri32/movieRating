<?php
// Database credentials
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'alumno');
define('DB_PASSWORD', 'alumno123');
define('DB_NAME', 'moviesDB');

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>
