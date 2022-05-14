<?php 
include 'connection.php';
$QID = $_GET['qid'];
$ansid = $_GET['ansid'];
  
$insert = "INSERT INTO bestanswer
(QID, AnsID)  
VALUES ($QID, $ansid)";
$result = $con->query($insert);


$update = "UPDATE Question
SET resolved = 1
WHERE QID = $QID";
$result = $con->query($update);

header('Location: question.php?QID='.$QID);
?>