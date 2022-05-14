<?php
    $QID = $_GET['QID'];
    session_start();
    if (!isset($_SESSION['UserID'])){
        header("refresh:5; login.php"); // redirect after 5 second pause
        echo "You're not logged in. Redirecting you to login page in 5 seconds or click <a href=\"login.php\">here</a>.";
        exit();
    }
    $UserID = $_SESSION['UserID'];
    include 'connection.php';

    $sql = "SELECT * FROM Users NATURAL JOIN user_status WHERE UserID = $UserID";
    $result = $con->query($sql);
    $obj = $result->fetch_assoc();
    
    $username = $obj["username"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Question</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
    <?php include 'components/navbar.php'?>

</head>
<body style="background:#eee;color: #708090;">

    <div class="container">
        <br>
        <div class="row">
          <!-- Main content -->
          <div class="col-lg-9 mb-3">

            <?php
            $qsql = "SELECT * FROM Question natural join users WHERE QID = $QID";
            $qresult = $con->query($qsql);
            $qobj = $qresult->fetch_assoc();
            $title = $qobj["title"];
            $username = $qobj["username"];
            $askUserID = $qobj["UserID"];
            $body = $qobj["body"];
            $qdatetime = $qobj["q_datetime"];

                echo "<div class='card row-hover pos-relative py-3 px-3 mb-3 border-primary border-top-0 border-right-0 border-bottom-0 rounded-0'>";
                echo "<div class='row align-items-center'>";
                  echo "<div class='col-md-8 mb-3 mb-sm-0'>";
                  echo "<p class='text-sm'><a href='user_profile.php?ViewingUserID=$askUserID'>$username</a> asked this question on: $adatetime </p>";
                    echo "<h5><a href='question.php?QID=$QID' class='text-primary'>$title</a></h5>";
                    echo "<p class='text-sm' style='color:black'>$body</p>";
                    if ($qobj["resolved"] == 1) {
                        echo "<p class='text-success mr-2'>Resolved</p>";
                      } else {
                        echo "<p class='text-danger mr-2'>Unresolved</p>";
                      }
                  echo "</div>";
                echo "</div>";
              echo "</div>";


              if ($qobj["resolved"] == 1) {
                echo "<hr>";
                echo "<p style='margin-bottom:5px;margin-top:10px;color:MediumSeaGreen'><strong>BEST ANSWER</strong></p>";
                $resolvedsql = "SELECT * from bestanswer natural join answer natural join users where QID = $QID";
                $aresult = $con->query($resolvedsql);
                $baobj = $aresult->fetch_assoc();
                $adatetime = $baobj["a_datetime"];
                $username = $baobj["username"];
                $questionUserID = $baobj["UserID"];
                $answer_body = $baobj["answer_body"];
                $ansid = $baobj["AnsID"];
                

                $votesql = "SELECT count(*) as votes from thumbed_up where ansid = $ansid";
                $vresult = $con->query($votesql);
                $vobj = $vresult->fetch_assoc();
                $votes = $vobj["votes"];

              echo "<div class='card row-hover pos-relative py-3 px-3 mb-3 border-primary border-top-0 border-right-0 border-bottom-0 rounded-0'>";
                echo "<div class='row align-items-center'>";
                echo "<h6><class='text-primary' style='margin-left:10px'>$votes Upvotes</h6>";
                  echo "<div class='col-md-8 mb-3 mb-sm-0'>";
                  echo "<p class='text-sm'><a href='user_profile.php?ViewingUserID=$questionUserID'>$username</a> answered this question on: $adatetime </p>";
                  echo "<p class='text-sm' style='color:black'>$answer_body</p>";

                  echo "</div>";
                echo "</div>";
              echo "</div>";
            }

            $asql = "SELECT* from answer natural join users where QID = $QID and ansid not in (select ansid from bestanswer)";
            $aresult = $con->query($asql);
            if ((mysqli_num_rows($aresult) == 0) && ($qobj["resolved" == 0])) {
                echo "<div class='card row-hover pos-relative py-3 px-3 mb-3 border-primary border-top-0 border-right-0 border-bottom-0 rounded-0'>";
                echo "<div class='row align-items-center'>";
                  echo "<div class='col-md-8 mb-3 mb-sm-0'>";
                    echo "<p class='text-sm'>No Answers Yet.</p>";
                  echo "</div>";
                echo "</div>";
              echo "</div>";
            }
            else{
              // if ((mysqli_num_rows($aresult) > 1) && ($qobj["resolved" == 1])) {
              if ((mysqli_num_rows($aresult) > 0) && $qobj["resolved"] == 1) {
                echo "<hr>";
                echo "<p style='margin-bottom:5px'>OTHER ANSWER(S)</p>";
              }
            while ($aobj = $aresult->fetch_assoc()) {
                $username = $aobj["username"];
                $adatetime = $aobj["a_datetime"];
                $answer_body = $aobj["answer_body"];
                $ansid = $aobj["AnsID"];
                $questionUserID = $aobj["UserID"];

                $votesql = "SELECT count(*) as votes from thumbed_up where ansid = $ansid";
                $vresult = $con->query($votesql);
                $vobj = $vresult->fetch_assoc();
                $votes = $vobj["votes"];
                
                echo "<div class='card row-hover pos-relative py-3 px-3 mb-3 border-primary border-top-0 border-right-0 border-bottom-0 rounded-0'>";
                echo "<div class='row align-items-center'>";
                echo "<h6><class='text-primary' style='margin-left:10px'>$votes Upvotes</h6>";
                  echo "<div class='col-md-8 mb-3 mb-sm-0'>";
                    echo "<p class='text-sm'><a href='user_profile.php?ViewingUserID=$questionUserID'>$username</a> answered this question on: $adatetime </p>";
                    echo "<p class='text-sm' style='color:black'>$answer_body</p>";

                  echo "</div>";
                echo "</div>";
                if (($qobj["resolved"] == 0) && ($qobj["UserID"] == $UserID)){
                    echo "<a href='resolve.php?ansid=$ansid&qid=$QID'>Resolve</a>";
                  } 
              echo "</div>";
            }
        }
            ?>
                <div class='card row-hover pos-relative py-3 px-3 mb-3 border-primary border-top-0 border-right-0 border-bottom-0 rounded-0'>

                    <h5 style='color:black'><strong>Your Answer</strong></h5>
                    <?php
                    echo "<form action='answer.php?QID=$QID' method='post'>"
                    ?>
                        <div class="form-group">
                            <label for = "Answer">Type your answer here:</label>
                            <textarea class = "form-control" name = "Answer" rows = "7" required></textarea>
                        </div>    
            ​
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" value="Submit">
                        </div>
                    </form>

                </div>


          </div>
          <?php include 'components/sidebar.php'?>
        </div>
      </div>

</body>
</html>