<?php 

session_start();
if (!isset($_SESSION['UserID'])){
    header("refresh:5; login.php"); // redirect after 5 second pause
    echo "You're not logged in. Redirecting you to login page in 5 seconds or click <a href=\"login.php\">here</a>.";
    exit();
}

include 'connection.php';
$UserID = $_SESSION['UserID'];
$AnsID = $_GET['AnsID'];
$type = $_GET['type'];
$QID = $_GET['QID'];

$insertUpvoteSQL = "INSERT INTO Thumbed_up VALUES ($UserID, $AnsID)";
$deleteUpvoteSQL = "DELETE FROM Thumbed_up WHERE AnsID = $AnsID AND UserID = $UserID";

if ($type == 'upvote') {
    $upvoteRes = $con->query($insertUpvoteSQL);
} else if ($type == 'cancel') {
    $upvoteRes = $con->query($deleteUpvoteSQL);
}

header('Location: question.php?QID='.$QID);
?>