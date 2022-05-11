<?php
    // $UserID = 2;
    // $ViewingUserID = 2;
    $UserID = $_GET['UserID'];  
    $ViewingUserID = $_GET['ViewingUserID'];  

    include 'connection.php';

    $sql = "SELECT * FROM Users NATURAL JOIN user_status WHERE UserID = $UserID";
    $result = $con->query($sql);
    $obj = $result->fetch_assoc();
    $username = $obj["username"];

    $sql = "SELECT * FROM Users NATURAL JOIN User_status WHERE UserID = $ViewingUserID";
    $result = $con->query($sql);
    $results_count = mysqli_num_rows($result);

    $obj = $result->fetch_assoc();
    $pusername = $obj["username"];
    $pfirst = $obj["first_name"];
    $plast = $obj["last_name"];
    $pprofile = $obj["user_profile"];
    $pemail = $obj["email"];
    $pcity = $obj["city"];
    $pstate = $obj["state"];
    $pcountry = $obj["country"];
    $puser_status = $obj["status_name"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Browse By Topic</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
    <?php include 'components/navbar.php'?>
</head>
<body style="background:#eee;color: #708090;">

    <div class="container">
        <div style="margin-top:50px; margin-bottom: 20px;">
        </div>
        <br>
        <?php
          echo "<div>";
          $randColorVal = (ord($pusername[55]) * 12 + ord($pfirst[0]) * 2 + ord($plast[0]) * 5 + 152) % 360;
          echo "<div style='background-color:hsl($randColorVal, 50%, 50%); color:white; width:70px; height:70px; font-size:30px; text-align: center; vertical-align: middle; line-height: 70px; float:left; margin-right: 10px; border-radius: 5px'>$pfirst[0]$plast[0]";
          echo "</div>";
          echo "<h3 style='color:black'><strong>$pfirst $plast</strong></h3>";
          echo "<h5>$pprofile</h5>";
          echo "</div>";
          echo "<br><br><h6><strong>Username: </strong>$pusername</h6>";
          echo "<h6><strong>Contact Email: </strong>$pemail</h6>";
          echo "<h6><strong>Lives in: </strong>$pcity, $pstate, $pcountry</h6>";
          echo "<h6><strong>User Status: </strong>$puser_status</h6>";
        ?>
        
        <br>
        <p>Their recent activities:</p>
        <div class="row">
          <!-- Main content -->
          <div class="col-lg-9 mb-3">

            <?php
            $sql = "WITH UserQuestions (QID, AID, qa_datetime) AS ( SELECT QID, NULL, q_datetime FROM Question WHERE UserID = $ViewingUserID ), UserAnswers (QID, AID,qa_datetime) AS ( SELECT NULL, AnsID, a_datetime FROM Answer WHERE UserID = $ViewingUserID ) SELECT * FROM UserQuestions UNION SELECT * FROM UserAnswers ORDER BY qa_datetime DESC";
            $result = $con->query($sql);
            if (mysqli_num_rows($result) == 0) {
              echo "<div class='card row-hover pos-relative py-3 px-3 mb-3 border-primary border-top-0 border-right-0 border-bottom-0 rounded-0'>";
              echo "<div class='row align-items-center'>";
                echo "<div class='col-md-8 mb-3 mb-sm-0'>";
                  echo "<p class='text-sm'>No activities.</p>";
                echo "</div>";
              echo "</div>";
            echo "</div>";
            }
            while ($obj = $result->fetch_assoc()) {
              $qa_datetime = $obj['qa_datetime'];
              $qa_qid = $obj['QID'];
              $qa_aid = $obj['AID'];
              if ($qa_qid != "") {
                $qsql = "SELECT * FROM Question WHERE QID = $qa_qid";
                $qresult = $con->query($qsql);
                $qobj = $qresult->fetch_assoc();
                $title = $qobj["title"];
                $body = $qobj["body"];
                echo "<div class='card row-hover pos-relative py-3 px-3 mb-3 border-primary border-top-0 border-right-0 border-bottom-0 rounded-0'>";
                echo "<div class='row align-items-center'>";
                  echo "<div class='col-md-8 mb-3 mb-sm-0'>";
                    echo "<p class='text-sm'>They asked this question on $qa_datetime: </p>";
                    echo "<h5><a href='#' class='text-primary'>$title</a></h5>";
                    if ($qobj["resolved"] == 1) {
                      echo "<p class='text-success mr-2'>Resolved</p>";
                    } else {
                      echo "<p class='text-danger mr-2'>Unresolved</p>";
                    }
                    echo "<p class='text-sm' style='color:black'>$body</p>";
                  echo "</div>";
                echo "</div>";
              echo "</div>";
              } else {
                $asql = "SELECT * FROM (Answer JOIN Question ON Answer.QID = Question.QID) JOIN Users ON Question.UserID = Users.UserID WHERE AnsID = $qa_aid";
                $aresult = $con->query($asql);
                $aobj = $aresult->fetch_assoc();
                $title = $aobj["title"];
                $question_user = $aobj["username"];
                $body = $aobj["answer_body"];
                $q_datetime = $aobj["q_datetime"];
                echo "<div class='card row-hover pos-relative py-3 px-3 mb-3 border-primary border-top-0 border-right-0 border-bottom-0 rounded-0'>";
                echo "<div class='row align-items-center'>";
                  echo "<div class='col-md-8 mb-3 mb-sm-0'>";
                    echo "<p class='text-sm'>They answered this question on $qa_datetime: </p>";
                    echo "<h5><a href='#' class='text-primary'>$title</a></h5>";
                    echo "<p class='text-sm'>";
                      echo "<span class='op-6'>Posted on $q_datetime</span>";
                      echo "<span class='op-6'> by </span>";
                      echo "<a class='text-black' href='#'>$question_user</a>";
                    echo "</p>";
                    if ($qobj["resolved"] == 1) {
                      echo "<p class='text-success mr-2'>Resolved</p>";
                    } else {
                      echo "<p class='text-danger mr-2'>Unresolved</p>";
                    }
                    echo "<p class='text-sm' style='color:black'><strong>Their answer:</strong> $body</p>";
                  echo "</div>";
                echo "</div>";
              echo "</div>";

              }
            }

            ?>
          </div>
          <?php include 'components/sidebar.php'?>
        </div>
      </div>

</body>
</html>