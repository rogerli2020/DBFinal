<?php
    session_start();
    if (!isset($_SESSION['UserID'])){
        header("refresh:5; login.php"); // redirect after 5 second pause
        echo "You're not logged in. Redirecting you to login page in 5 seconds or click <a href=\"login.php\">here</a>.";
        exit();
    }
    $UserID = $_SESSION['UserID'];
    $QID = $_GET['QID'];
    
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        include 'connection.php';
        
        $answer_body = addslashes($_POST['Answer']);

        $insert = "INSERT INTO Answer (AnsID, QID, UserID, answer_body, a_datetime) VALUES (default, $QID, $UserID, '$answer_body', NOW())";
        $result = $con->query($insert);      
        header("location: question.php?QID=$QID");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Answer Question</title>
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


        <h3 style='color:black'><strong>Answer Question</strong></h3>
        <?php
        echo "<form action='answer.php?QID=$QID' method='post'>"
        ?>
            <div class="form-group">
                <label for = "Answer">Type your answer to the question here:</label>
                <textarea class = "form-control" name = "Answer" rows = "7" required></textarea>
            </div>    
​
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="button" class="btn btn-secondary ml-2" value="Cancel" onclick="history.back()">
            </div>
        </form>


    </div>    
</body>
</html>