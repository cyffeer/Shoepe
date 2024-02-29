<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="shortcut icon" type="x-icon" href="css/icon.png">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #333;
            color: #fff;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #333;
            color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        h2 {
            text-align: center;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            text-align: center;
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <img class="logo" src="css/icon.png" alt="Logo" width="50">
        <h2>User Registration</h2>

<?php
session_start();

$_SESSION['username'] = $_POST['username'];
$username = $_SESSION['username'];

$_SESSION['password'] = $_POST['password'];
$password = $_SESSION['password'];

$confirmpass = $_POST['confirmpass'];

$_SESSION['email'] = $_POST['email'];
$email = $_SESSION['email'];

$_SESSION['fullname'] = $_POST['fullname'];
$fullname = $_SESSION['fullname'];

$_SESSION['phone'] = $_POST['phone'];
$phone = $_SESSION['phone'];

$_SESSION['address'] = $_POST['address'];
$address = $_SESSION['address'];

$conn = mysqli_connect("localhost", "root", "", "shoepe");
$sql_user= "SELECT * from user where username = '$username'";
$sql = "INSERT INTO user(username, password, email, fullname, phone, address, profile_picture) VALUES('$username', sha1('$password'), '$email', '$fullname', '$phone', '$address', 'profile_pic/default.jpg')";

if($conn == false){
    echo "could not connect";
    die();
}

if($password == $confirmpass){
    $user_result = mysqli_query($conn,$sql_user);
    $row = mysqli_fetch_assoc($user_result);
    if(!empty($row['username'])){
        echo "User already exits. Please use a different username";
        echo '<br><br><a href="register.php">Try again</a>';
    }
    else{
        $result = mysqli_query($conn, $sql);
        if($result == true){
            echo "<b1>Success! User has been registered!";
            echo '<br><br><a href="dashboard.php">Go back to Homepage</a><br><br>
            <a href="login.php">Login Now</a>';
        }
        else{
            echo "Query Failed";
            echo '<br><br><a href="register.php">Try again</a>';
        }
    }
}
else{
    echo "<b1>Error. Passwords do not match...";
    echo '<br><br><a href="register.php">Try again</a>';
}

session_destroy();
?>
<div class="back-link">
            <a href="dashboard.php">Back to Homepage</a>
        </div>
    </div>
</body>

</html>
