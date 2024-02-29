<?php
session_start();

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Define the target directory where the uploaded file will be saved
    $targetDirectory = "profile_pic/";

    // Create the target directory if it doesn't exist
    if (!file_exists($targetDirectory)) {
        mkdir($targetDirectory, 0777, true);
    }

    // Combine the target directory with the original filename
    $originalFileName = $_FILES["profile_pic"]["name"];
    $targetFile = $targetDirectory . $originalFileName;

    // Check if the file is an actual image or a fake image
    $check = getimagesize($_FILES["profile_pic"]["tmp_name"]);
    if ($check !== false) {
        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetFile)) {
            // Update the user's profile picture in the database
            $conn = mysqli_connect("localhost", "root", "", "shoepe");
            $updateQuery = "UPDATE user SET profile_picture='$targetFile' WHERE username='$username'";
            mysqli_query($conn, $updateQuery);
            mysqli_close($conn);

            echo "Profile picture has been updated successfully!";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "File is not an image.";
    }
} else {
    echo "User not authenticated.";
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Profile Picture Upload</title>
    <link rel="shortcut icon" type="x-icon" href="css/icon.png">
    <link rel="stylesheet" type="text/css" href="css/profile.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #333;
            text-align: center;
            margin: 50px;
        }

        .container {
            max-width: 600px;
            margin: auto;
        }

        .logo {
            width: 50px;
            height: auto;
            margin-right: 10px;
        }

        .back-links {
            margin-top: 20px;
        }

        .back-links a {
            margin-right: 20px;
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <div class="container">
        <img class="logo" src="css/icon.png" alt="" srcset="">
        <h1>Profile Picture Upload</h1>

        <p>
            <?php
            if (isset($_SESSION['username'])) {
                echo "Profile picture has been updated successfully!";
            } else {
                echo "User not authenticated.";
            }
            ?>
        </p>

        <div class="back-links">
            <a href="profile.php">Back to Profile</a>
            <a href="dashboard.php">Back to Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>

</html>

