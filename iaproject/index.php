<!DOCTYPE html>
<html>
<head>
  <title>
    MoviesApp
  </title>
</head>

<body>

  <?php  include('header.php');  ?>

    <?php
    session_start();

    require_once "config.php";

    $userid = $_SESSION['id'];

    try {

      echo "<h2>Movies</h2>";
      foreach($link->query("SELECT DISTINCT movie.*, AVG(rec_score) as average FROM movie JOIN recs ON (movie.id = recs.movie_id) GROUP BY movie_id limit 10") as $row) {
        echo "<div style= \"border: 3px solid #0f0; overflow:auto;\">";

        echo "<img src=\"images/" . $row['url_pic'] . "\" style=\"float:left\">";
        echo "<div id='div1' style=\"float:left\">";
        echo "<p><b>Title: </b>";
        echo $row['title'] . "</p>";
        echo "<p><b>Date: </b>";
        echo $row['date'] . "</p>";
        echo "<p><b>IMDB link: </b>";
        echo "<a href=\"" .  $row['url_imdb'] . "\">" .  $row['url_imdb'] . "</a>";
        echo "<p><b>Description</b></p>";
        echo "<p>" .  $row['desc'] . "</p>";

        $movieid = $row['id'];
        $sql2 = "SELECT DISTINCT rec_score, movie_id FROM recs WHERE user_id = $userid AND movie_id = $movieid";
        $result2 = mysqli_query($link,$sql2);
        $row2 = mysqli_fetch_array($result2,MYSQLI_ASSOC);

        echo "<form name='myForm".$movieid."' id='myForm".$movieid."'>";
        if(mysqli_num_rows($result2) == 1){
          echo "<p><b>My review</b></p>";
          echo "<p style=\"color:red\">" .  round($row2['rec_score'], 3) . "</p>";
          $rq = 'update';
          echo "<input type='button' id='btn1' value='Change my review' onclick='addreview(".$movieid.",".$userid.", this.id)'>";
        } elseif (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
          echo "<p><b>My review</b></p>";
          $rq = 'insert';
          echo "<button type='button' id='btn2' onclick = 'addreview(".$movieid.",".$userid.", this.id)'>Add my review</button>";
        }{
        }
        echo "</form>";


        if( $row['average'] != ""){
          echo "<p><b>Total score</b></p>";
          echo "<p style=\"color:red\">" .  round($row['average'], 3) . "</p>";
        }

        echo "</div></div>";

      }

    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
    ?>

  </div>

  <script>
      function addreview(movieid, userid, btnid) {
        // so my page does not refresh after pressing button
        event.preventDefault();

        var str = "myForm"+movieid;
        var str2 = "input"+movieid;
        var txtbox = document.getElementById(str2);
        // so we dont create multiple textboxes
        if(txtbox == null){
          var myForm = document.getElementById(str);
          var input = document.createElement("input");
          input.type = "number";
          input.setAttribute("id", str2);
          input.setAttribute("min", "1");
          input.setAttribute("max", "10");
          myForm.appendChild(input);
        } else {
            if(txtbox.value > 10 || txtbox.value < 1){
              alert("Value must be in a range 1-10");
            } else {
              var user_id = userid;
          		var movie_id = movieid;
          		var rec_score = txtbox.value;
              var reqst = "";

              if(btnid = "btn1"){
                reqst = "update";
              }else if (btnid = "btn2") {
                reqst = "insert";
              }

              if(user_id!="" && movie_id!="" && rec_score!="" && reqst!=""){
                $.ajax({
                  url: "save_js.php",
                  type: "POST",
                  data: {
                    user_id: user_id,
                    movie_id: movie_id,
                    rec_score: rec_score,
                    reqst: reqst
                  },
                  cache: false,
                  success: function(dataResult){
                    var dataResult = JSON.parse(dataResult);
                    if(dataResult.statusCode==200{
                      alert("Review saved !");
                    }
                    else if(dataResult.statusCode==201){
                       alert("Error occured !");
                    }

                  }
                }


               }
          }
        }
      }
  </script>
</body>
</html>
