<?php
$Success = false;
$Taken = false;
$Failed = false;

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
                $Success = "Success! You can now login";
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
<body>
    <?php 
        if ($Success){
            echo '<h1>'. "$Success".'</h1>';
        }
        else if ($Taken){
            echo '<h1>'. "$Taken". '</h1';
        }
        else{
            echo '<h1>'. "$Failed".'</h1>';
        }
        ?>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="signup.php" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" >
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="text" name="password">
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="text" name="confirm_password" >
            </div>
            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="first_name" >
            </div>    
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="last_name">
            </div>
            <div class="form-group">
                <label>email</label>
                <input type="email" name="email" >
            </div>
            <div class="form-group">
                <label>City</label>
                <input type="text" name="city">
            </div>
            <div class="form-group">
                <label>State</label>
                <input type = "text" name = state>
            </div>    
            <div class="form-group">
                <label>country</label>
                <input type="text" name="country" >
            </div>
            <div class="form-group">
                <label for = "profile">Enter a short description of yourself</label>
                <textarea class = "form-control" name = "profile" rows = "5"></textarea>
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