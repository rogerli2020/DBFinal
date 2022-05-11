<!DOCTYPE html>
<?php
    if (isset($_SESSION['UserID'])){
        header("refresh:5; homepage.php"); // redirect after 5 second pause
        echo "You're already logged in. <br> Redirecting you to your homepage in 5 seconds or click <a href=\"homepage.php\">here</a>.";
    } else {
        header("refresh:0; login.php"); // redirect after 5 second pause
    }
?>