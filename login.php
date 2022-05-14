<?php
$Success = false;
$Failed = false;

session_start();
if (isset($_SESSION['UserID'])){
    header("refresh:5; homepage.php"); // redirect after 5 second pause
    echo "You're already logged in. Redirecting you to your homepage in 5 seconds or click <a href=\"homepage.php\">here</a>.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    include 'connection.php';
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT UserID FROM Users WHERE username = '$username' and user_password = '$password'";
    $result = $con->query($sql);
    if ($result -> num_rows > 0){
        $row = $result->fetch_assoc();
        session_start();
        $_SESSION['UserID'] = $row["UserID"];
        $_SESSION['username'] = $username;
        header("location: homepage.php");
    }
    else
        $Failed = "Incorrect Username or Password";
    $con -> close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body style="background:#eee;color: #708090;">

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href='#'><strong><i>WebpageLogo</i></strong></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                </ul>
            </div>
            </nav>
    <!-- navbar-end -->

    <div class="container">
    <div style="margin-top:50px; margin-bottom: 20px;">
        </div>
    <h3 style='color:black'><strong>Log In</strong></h3>
        <p>Do not have an account? <a href='signup.php'>Click here</a> to sign up.</p>
        <?php 
        if ($Failed){
            echo '<p><strong>'. "$Failed".'</strong></p>';
        }
        ?>
        <br>
        <form action="login.php" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" >
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="text" name="password">
            </div>
            
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
        </form>
    </div>    
</body>
</html>