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

#get the userid of the answer
$sql = "SELECT UserID from answer where AnsID = $AnsID";
$res = $con->query($sql);
$aobj = $res->fetch_assoc();
$auserID = $aobj["UserID"];

#insert/deletion when pressing upvote button
$insertUpvoteSQL = "INSERT INTO Thumbed_up VALUES ($UserID, $AnsID)";
$deleteUpvoteSQL = "DELETE FROM Thumbed_up WHERE AnsID = $AnsID AND UserID = $UserID";

#insertion
if ($type == 'upvote') {
    $upvoteRes = $con->query($insertUpvoteSQL);

} else if ($type == 'cancel') {
    $upvoteRes = $con->query($deleteUpvoteSQL);
}
#check total votes of answer user after insert/deltion
$checknumvotes = "SELECT count(*) as votes from thumbed_up as T, answer as A where T.AnsID = A.AnsID and A.UserID = $auserID";
$check = $con->query($checknumvotes);
$vobj = $check->fetch_assoc();
$votes = $vobj["votes"];

#update status based on total votes
if ($votes > 2){
    $updatestatus = "UPDATE USERS set statusID = 3 where userID = $auserID";
}
else if ($votes > 1){
    $updatestatus = "UPDATE USERS set statusID = 2 where userID = $auserID";
}
else{
    $updatestatus = "UPDATE USERS set statusID = 1 where userID = $auserID";
}
$update = $con->query($updatestatus);

header('Location: question.php?QID='.$QID);
?>
