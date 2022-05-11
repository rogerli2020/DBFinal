<?php
    // $UserID = 2;
    // $keyword = "web";
    // $order_by = 'dt';
    session_start();
    if (!isset($_SESSION['UserID'])){
        header("refresh:5; login.php"); // redirect after 5 second pause
        echo "You're not logged in. Redirecting you to login page in 5 seconds or click <a href=\"login.php\">here</a>.";
        exit();
    }
    $UserID = $_SESSION['UserID'];
    $keyword = $_GET['keyword'];
    $order_by = $_GET['order_by'];    

    include 'connection.php';

    $sql = "SELECT * FROM Users NATURAL JOIN user_status WHERE UserID = $UserID";
    $result = $con->query($sql);
    $obj = $result->fetch_assoc();
    
    $username = $obj["username"];

    $sql = "WITH InTitleTable(QID, dt, IsInTitle) AS ( SELECT QID, q_datetime, CASE WHEN title LIKE '%$keyword%' THEN 1 ELSE 0 END FROM Question ), InBodyTable(QID, dt, IsInBody) AS ( SELECT QID, q_datetime, CASE WHEN body LIKE '%$keyword%' THEN 1 ELSE 0 END FROM Question ), InAnswerCount(QID, IsInAnswerCount) AS ( SELECT Question.QID, COUNT(*) FROM Question JOIN Answer ON Answer.QID = Question.QID WHERE answer_body LIKE '%$keyword%' GROUP BY QID ) SELECT T1.QID, Q.title, Q.q_datetime AS dt, ( CASE WHEN IsInTitle IS NULL THEN 0 ELSE IsInTitle * 10 END + CASE WHEN IsInBody IS NULL THEN 0 ELSE IsInBody * 1 END + CASE WHEN IsInAnswerCount IS NULL THEN 0 ELSE IsInAnswerCount * 0.1 END ) Relevance FROM (InTitleTable T1 LEFT JOIN InBodyTable T2 ON T1.QID = T2.QID) LEFT JOIN InAnswerCount T3 ON T1.QID = T3.QID JOIN Question Q ON Q.QID = T1.QID HAVING NOT Relevance = 0 ORDER BY $order_by DESC;";
    $result = $con->query($sql);
    $results_count = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search</title>
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
          echo "<h3 style='color:black'><strong>Search results for '$keyword':</strong></h3>";
          echo "<h6>$results_count results found.</h6><br><br>";
        ?>

        <form action="search.php?" method="GET">
          <label for="Topic_name">Sort by</label><br>
          <div class="btn-group" style="max-width:25%; margin-bottom:5px">
            <?php
              echo "<input type='hidden' name='keyword' value='$keyword'>";
            ?>
            <select class="form-control" name="order_by">
              <?php 
                if ($order_by == 'Relevance') {
                  echo "<option value='Relevance' selected >Most relevant</option>";
                } else {
                  echo "<option value='Relevance'>Most relevant</option>";
                }
                
                if ($order_by == 'dt') {
                  echo "<option value='dt' selected>Newest first</option>";
                } else {
                  echo "<option value='dt'>Newest first</option>";
                }
              ?>
            </select>
            <button type='submit' class='btn btn-primary'>Sort</button>
          </div>
        </form>
        
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
                $QID = $qobj["QID"];
                $question_user = $qobj["username"];
                $question_user_id = $qobj["UserID"];
                echo "<div class='card row-hover pos-relative py-3 px-3 mb-3 border-primary border-top-0 border-right-0 border-bottom-0 rounded-0'>";
                echo "<div class='row align-items-center'>";
                  echo "<div class='col-md-8 mb-3 mb-sm-0'>";
                    echo "<h5><a href='question.php?QID=$QID' class='text-primary'>$title</a></h5>";
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
                    echo "<div class='text-sm op-5'>";
                      // <a class="text-black mr-2" href="#">#C++</a> 
                      // <a class="text-black mr-2" href="#">#AppStrap Theme</a> 
                      // <a class="text-black mr-2" href="#">#Wordpress</a> 
                    echo "</div>";
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