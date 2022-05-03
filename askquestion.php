<?php
    $UserID = $_GET['UserID'];

    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        include 'connection.php';
        $topic_name = $_POST['topic_name'];
        $body = $_POST['Question'];
        $title = $_POST['title'];

        $sqlid = "SELECT TopicID from Topic where topic_name = '$topic_name'";
        
        $topicres = $con->query($sqlid);
        $row = $topicres->fetch_assoc();
        $topicID = $row["TopicID"];
    
        #$insert = "INSERT INTO Question VALUES (default, '$userid', '$title', '$topicID', '$body', NOW(), 0)";
        $insert = "INSERT INTO Question (QID, UserID, title, TopicID, body, q_datetime, resolved) VALUES (default,$UserID,'$title',$topicID,'$body',NOW(),0)";
        $result = $con->query($insert);      
        header("location: homepage.php?UserID=".$UserID);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ask question</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
    <?php
        include 'components/navbar.php';
    ?>
</head>
<body style="background:#eee;color: #708090;">
    <div class="container">
    <div style="margin-top:50px; margin-bottom: 20px;">
        </div>
        <br>
        <h3 style='color:black'><strong>Ask Question</strong></h3>
        <form action="askquestion.php?UserID=<?php echo $UserID ?>" method="post">
            <div class="form-group">
                <label for="Topic_name">Choose question topic</label>
                <select class="form-control" name="topic_name">
                <option>Computer Science</option>
                <option>Web Development</option>
                <option>Algorithms</option>
                <option>Frontend</option>
                <option>Backend</option>
                <option>Databases</option>
                <option>C++</option>
                </select>
            </div>
            <div class="form-group">
                <label for = "Title">Question Title</label>
                <textarea class = "form-control" name = "title" rows = "1" required></textarea>
            </div>      
            <div class="form-group">
                <label for = "Question">Type your question here</label>
                <textarea class = "form-control" name = "Question" rows = "7" required></textarea>
            </div>    

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="cancel" class="btn btn-secondary ml-2" value="Cancel">
            </div>
        </form>
    </div>    
</body>
</html>