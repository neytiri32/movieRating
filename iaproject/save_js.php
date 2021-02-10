<?php
   include 'config.php';
   $userid=$_POST['user_id'];
   $movieid=$_POST['movie_id'];
   $rec=$_POST['rec_score'];
   $req=$_POST['req'];
   if($req = "insert"){
     $sql = "INSERT INTO 'recs' ( 'user_id', 'movie_id', 'rec_score','time')
     VALUES ('$userid','$movieid','$rec','CURRENT_TIME')";
   }elseif ($req = "update"){
     $sql = "UPDATE 'recs' SET rec_score = '$rec', time = 'CURRENT_TIME'
     WHERE user_id = '$userid' AND movie_id = '$movieid'";
   }
   if (mysqli_query($conn, $sql)) {
     echo json_encode(array("statusCode"=>200));
   }
   else {
     echo json_encode(array("statusCode"=>201));
   }
   mysqli_close($conn);
?>
