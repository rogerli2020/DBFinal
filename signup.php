<?php
$Success = false;
$Taken = false;
$Failed = false;

session_start();
if (isset($_SESSION['UserID'])){
    header("refresh:5; homepage.php"); // redirect after 5 second pause
    echo "You're already logged in. Log out first to create a new account. <br> Redirecting you to your homepage in 5 seconds or click <a href=\"homepage.php\">here</a>.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    include 'connection.php';
    $username = $_POST["username"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    $city = $_POST["city"];
    $state = $_POST["state"];
    $country = $_POST["country"];
    $profile = $_POST["profile"];


    $sql = "SELECT * from users where username = '$username'";
    $result = $con->query($sql);
    
    if (mysqli_num_rows($result) == 0){
        if($password == $confirm_password){
            $insert = "INSERT INTO Users
            (UserID, username, first_name, last_name, email, user_password, city, state, country, user_profile, statusID)  
            VALUES (default, '$username', '$first_name', '$last_name', '$email', '$password', '$city', '$state', '$country', '$profile', 1)";
            $result = $con->query($insert);
            if ($result){
                $Success = "Success! You can now <a href='login.php'>log in</a>";
            }
        }
        else{
            $Failed = "Passwords do not match";
        }
    }
    else{
        $Taken = "Username is taken";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
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
        <?php 
        if ($Success){
            echo '<h1>'. "$Success".'</h1>';
        }
        else if ($Taken){
            echo '<h1>'. "$Taken". '</h1>';
        }
        else{
            echo '<h1>'. "$Failed".'</h1>';
        }
        ?>
        <h3 style='color:black'><strong>Sign Up</strong></h3>
        <p>Please fill this form to create an account.</p>
        <form action="signup.php" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="text" name="password" required>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="text" name="confirm_password" required>
            </div>
            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="first_name" required>
            </div>    
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="last_name" required>
            </div>
            <div class="form-group">
                <label>email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>City</label>
                <input type="text" name="city" required>
            </div>
            <div class="form-group">
                <label>State</label>
                <input type = "text" name = state required>
            </div>    
            <div class="form-group">
                <label>country</label>
                <input type="text" name="country" required>
            </div>
            <div class="form-group">
                <label for = "profile">Enter a short description of yourself</label>
                <textarea class = "form-control" name = "profile" rows = "5" required></textarea>
            </div>    

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>