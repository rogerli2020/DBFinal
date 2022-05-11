<?php
    // $UserID = 2;
    // $TopicID = 2;
    // $topicName = 'CS';
    session_start();
    if (!isset($_SESSION['UserID'])){
        header("refresh:5; login.php"); // redirect after 5 second pause
        echo "You're not logged in. Redirecting you to login page in 5 seconds or click <a href=\"login.php\">here</a>.";
        exit();
    }
    $UserID = $_SESSION['UserID'];
    $TopicID = $_GET['TopicID'];
    $topicName = $_GET['topicName'];

    include 'connection.php';

    $sql = "SELECT * FROM Users NATURAL JOIN user_status WHERE UserID = $UserID";
    $result = $con->query($sql);
    $obj = $result->fetch_assoc();
    $username = $obj["username"];

    $sql = "WITH RECURSIVE CompleteQuestionToTopicTable(TopicID, QID) AS ( SELECT TopicID, QID FROM Question UNION ALL SELECT parent, CompleteQuestionToTopicTable.QID FROM CompleteQuestionToTopicTable JOIN Topic ON CompleteQuestionToTopicTable.TopicID = Topic.TopicID ) SELECT * FROM CompleteQuestionToTopicTable C JOIN Question Q ON C.QID = Q.QID WHERE C.TopicID = $TopicID ORDER BY Q.q_datetime DESC;";
    $result = $con->query($sql);
    $results_count = mysqli_num_rows($result);
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
          echo "<h3 style='color:black'><strong>Questions Under Topic '$topicName':</strong></h3>";

          $parentTopicsQuery = "WITH RECURSIVE parentTopics(TopicID) AS ( SELECT parent FROM Topic WHERE Topic.TopicID = $TopicID UNION ALL SELECT parent FROM parentTopics JOIN Topic ON parentTopics.TopicID = Topic.TopicID WHERE parent IS NOT NULL ) SELECT TopicID, topic_name FROM parentTopics NATURAL JOIN Topic ORDER BY TopicID;";
          $parentTopicsResult = $con->query($parentTopicsQuery);
          $parentTopicCount = mysqli_num_rows($parentTopicsResult);

          if ($parentTopicCount != 0) {

          echo "<h5>Parent topic(s): ";

          while ($parentObj = $parentTopicsResult->fetch_assoc()) {
            $parentID = $parentObj['TopicID'];
            $parentName = $parentObj['topic_name'];
            echo "<a href='browse_by_topics.php?TopicID=$parentID&topicName=$parentName' class='btn btn-info' role='button' style='margin:1px'>$parentName</a>";
          }

          echo "</h5>";
          }
          echo "<br><br><h6>$results_count results found.</h6><br><br>";
        ?>
        
        <div class="row">
          <!-- Main content -->
          <div class="col-lg-9 mb-3">

          <?php
            if ($results_count == 0) {
              echo "<div class='card row-hover pos-relative py-3 px-3 mb-3 border-primary border-top-0 border-right-0 border-bottom-0 rounded-0'>";
              echo "<div class='row align-items-center'>";
                echo "<div class='col-md-8 mb-3 mb-sm-0'>";
                  echo "<p class='text-sm'>No results.</p>";
                echo "</div>";
              echo "</div>";
            echo "</div>";
            }
            while ($obj = $result->fetch_assoc()) {
                $QID = $obj['QID'];
                $qsql = "SELECT * FROM Question NATURAL JOIN Users WHERE QID = $QID";
                $qresult = $con->query($qsql);
                $qobj = $qresult->fetch_assoc();
                $q_datetime = $qobj['q_datetime'];
                $title = $qobj["title"];
                $body = $qobj["body"];
                $question_user = $qobj["username"];
                $question_user_id = $qobj["UserID"];
                echo "<div class='card row-hover pos-relative py-3 px-3 mb-3 border-primary border-top-0 border-right-0 border-bottom-0 rounded-0'>";
                echo "<div class='row align-items-center'>";
                  echo "<div class='col-md-8 mb-3 mb-sm-0'>";
                    echo "<h5><a href='#' class='text-primary'>$title</a></h5>";
                    echo "<p class='text-sm'>";
                    echo "<span class='op-6'>Posted on $q_datetime</span>";
                    echo "<span class='op-6'> by </span>";
                    echo "<a class='text-black' href='user_profile.php?ViewingUserID=$question_user_id'>$question_user</a>";
                    echo "</p>";
                    echo "<p class='text-sm' style='color:black'>$body</p>";
                    if ($qobj["resolved"] == 1) {
                      echo "<p class='text-success mr-2'>Resolved</p>";
                    } else {
                      echo "<p class='text-danger mr-2'>Unresolved</p>";
                    }
                  echo "</div>";
                echo "</div>";
              echo "</div>";
            }
          ?>
            <!-- /End of post 1 -->


            
          </div>
          <?php include 'components/sidebar.php'?>
        </div>
      </div>

</body>
</html>