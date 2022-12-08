<?php 

session_start();
if (!isset($_SESSION['UserID'])){
    header("refresh:5; login.php"); // redirect after 5 second pause
    echo "You're not logged in. Redirecting you to login page in 5 seconds or click <a href=\"login.php\">here</a>.";
    exit();
}

include 'connection.php';
$QID = $_GET['qid'];
$ansid = $_GET['ansid'];
  
#inertion into best answer the chosen answerid and the current quesitonid
$insert = "INSERT INTO bestanswer
(QID, AnsID)  
VALUES ($QID, $ansid)";
$result = $con->query($insert);

#make question resolved in question table
$update = "UPDATE Question
SET resolved = 1
WHERE QID = $QID";
$result = $con->query($update);

header('Location: question.php?QID='.$QID);
?>
