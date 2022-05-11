<!DOCTYPE html>
<?php
    session_start();
    session_destroy();
    header("refresh:5; login.php"); // redirect after 5 second pause
    echo "You've been logged out. You will be redirected to the log in page in 5 seconds. ";
    echo "If not, click <a href=\"login.php\">here</a>.";
?>