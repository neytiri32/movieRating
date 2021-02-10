
<!DOCTYPE html>
<html>
<head>
  <title>
    My reviews
  </title>

</head>

<body>


  <?php  include('header.php');  ?>

  <div class="movie_list">
    <?php

    session_start();

    require_once "config.php";

    try {
      echo "<h2>My reviews</h2>"; //ol
      $userid = $_SESSION['id'];
      foreach($link->query("SELECT DISTINCT  movie.*, rec_score, recs.time FROM movie JOIN recs ON (movie.id = recs.movie_id) WHERE recs.user_id = $userid ORDER BY recs.time LIMIT 10") as $row) {

        echo "<div style= \"border: 3px solid #0f0; overflow:auto;\">";

        echo "<img src=\"images/" . $row['url_pic'] . "\" style=\"float:left\">";
        echo "<div style=\"float:left\">";
        echo "<p><b>Title</b></p>";
        echo "<p>" . $row['title'] . "</p>";
        echo "<p><b>Date</b></p>";
        echo "<p>" .  $row['date'] . "</p>";
        echo "<p><b>IMDB link</b></p>";
        echo "<a href=\"" .  $row['url_imdb'] . "\">" .  $row['url_imdb'] . "</a>";
        echo "<p><b>Description</b></p>";
        echo "<p>" .  $row['desc'] . "</p>";
        echo "<p><b>Score</b></p>";
        echo "<p style=\"color:red\">" .  $row['rec_score'] . "</p></div>";

        echo "</div>";

      }

    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
    ?>

  </div>
</body>
</html>
