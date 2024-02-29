<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>Login Processing</title>
    <style>
        body {
            background-color: #333; 
            color: #fff; 
            font-family: Arial, sans-serif; 
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            text-align: center;
        }

        .logo {
            width: 100px; 
            height: auto; 
            margin-bottom: 20px;
        }

        .message {
            margin-bottom: 20px;
        }

        a {
            color: #fff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <img class="logo" src="css/icon.png" alt="Logo"> 
        <div class="message">
            <?php
            session_start();
            $_SESSION['username'] = $_POST['username'];
            $username = $_SESSION['username'];

            $_SESSION['password'] = $_POST['password'];
            $password = sha1($_SESSION['password']);

            $conn = mysqli_connect("localhost", "root", "", "shoepe");
            $sql = "SELECT * from user where username = '$username' and password = '$password'";

            if ($conn == false) {
                echo "Could not connect";
                die();
            }

            $result = mysqli_query($conn, $sql);

            if ($result == true) {
                $row = mysqli_fetch_assoc($result);
                if (empty($row['username'])) {
                    echo "Username or Password is incorrect.";
                    echo '<br><br><a href="login.php">Try again</a>';
                    session_destroy();
                } else {
                    $_SESSION['fullname'] = $row['fullname'];
                    $_SESSION['phone'] = $row['phone'];
                    $_SESSION['address'] = $row['address'];
                    header("Location: dashboard.php");
                    exit();
                }
            } else {
                echo "Query Failed";
                echo '<br><br><a href="login.php">Try again</a>';
                session_destroy();
            }
            ?>
        </div>
    </div>
</body>

</html>
