<!DOCTYPE html>
<html>

<head>
    <title>Shoepe - User Profile</title>
    <link rel="shortcut icon" type="x-icon" href="css/icon.png">
    <link rel="stylesheet" type="text/css" href="css/profiles.css">
    <style>
    
</style>
</head>

<body>

    <?php
    session_start();
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        $conn = mysqli_connect("localhost", "root", "", "shoepe");

        $userQuery = "SELECT * FROM user WHERE username='$username'";
        $userResult = mysqli_query($conn, $userQuery);

        if ($userResult && mysqli_num_rows($userResult) > 0) {
            $userData = mysqli_fetch_assoc($userResult);

            // Added container for the navbar with box shadow
            echo '<div class="navbar-container">';
            echo '<div class="navbar">';
            echo '<div class="logo-container">';
            echo '<a href="dashboard.php"><img class="logo" src="css/icon.png" alt="" srcset=""></a>';
            echo '<div class="welcome2">';
            echo '<h1>Shoepe</h1>';
            echo '</div>';
            echo '</div>';

            // Add the navbar links
            echo '<div class="nav-links">';
            echo '<a href="dashboard.php">Home</a>';
            echo '<a href="about.php">About</a>';
            echo '<a href="contact.php">Contact</a>';
            echo '<a href="cart.php">Cart</a>';
            echo '<a href="sell.php">Sell</a>';
            echo '<a href="history.php">History</a>';
            echo '<a href="discussion.php">Discussion</a>';
            echo '<a href="logout.php">Logout</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';

            echo "<h2>User Profile</h2>";
            echo '<p class="user-details"><strong>Username:</strong> <span class="username">' . $userData['username'] . '</span></p>';
            echo '<p class="user-details email"><strong>Email:</strong> <span class="user-email">' . $userData['email'] . '</span></p>';

            // Display the current profile picture
            $profilePicture = $userData['profile_picture'];
            echo "<img src='$profilePicture' alt='profilepic' class='profile-pic'></div>";

            // Check if the button to change profile picture is clicked
            if (isset($_POST['change_picture'])) {
                // Form to upload a new profile picture
                echo '<form action="upload_profile_pic.php" method="post" enctype="multipart/form-data">';
                echo '<h3><label for="profile_pic">Change Profile Picture:</label></h3>';
                echo '<input type="file" name="profile_pic" id="profile_pic">';
                echo '<input type="submit" value="Upload">';
                echo '<input type="button" value="Cancel" onclick="location.href=\'profile.php\'">';
                echo '</form>';
            } else {
                // Button to change profile picture
                echo '<form action="" method="post">';
                echo '<input type="submit" name="change_picture" value="Change Profile Picture">';
                echo '</form>';
            }

            $shoesQuery = "SELECT * FROM shoes WHERE username='$username'";
            $shoesResult = mysqli_query($conn, $shoesQuery);

            if ($shoesResult && mysqli_num_rows($shoesResult) > 0) {
                echo "<h2>Shoes Listed for Sale</h2>";
                echo "<div class='grid'>";
                while ($shoeData = mysqli_fetch_assoc($shoesResult)) {
                    $imagepath = $shoeData['imagepath'];
                    $quantity = $shoeData['quantity'];

                    echo "<div class='item'>";
                    echo "<div class='img'><center><img src='$imagepath' alt='shoe' style='width: 250px; height:250px;'></center></div>";
                    echo "<br><b><center>" . $shoeData['shoename'] . "</center></b>";
                    echo "<br><center>" . $shoeData['brand'] . "</center>";
                    if ($quantity == 0) {
                        echo "<br><center><b>SOLD OUT</b></center>";
                    } else {
                        echo "<br><center>₱" . $shoeData['price'] . "</center>";
                    }
                    echo "</div>";
                }
                echo "</div>";
            } else {
                echo "<h2><p>No shoes listed for sale.</p></h2>";
            }
        } else {
            echo "<h2><p>User not found.</p></h2>";
        }
    } else {
        echo "<p>Please log in to view your profile.</p>";
    }
    ?>
</body>

</html>

