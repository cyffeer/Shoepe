<!DOCTYPE html>
<html>
<head>
    <title>Shoepe Sales Plot</title>
    <link rel="shortcut icon" type="x-icon" href="css/icon.png">
    <link rel="stylesheet" type="text/css" href="css/dbstyle.css">
    <style>
        .navigation a {
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            display: inline-block;
            padding: 10px 15px;
            border: 1px solid transparent;
            transition: border 0.3s ease;
        }

        .navigation a:hover {
            background-color: #ddd;
            color: black;
            border: 1px solid #333;
        }
    
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h2 {
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .plot-container {
            margin-top: 20px;
        }
        .plot-image {
            max-width: 100%;
            height: auto;
            border: 1px solid #ccc;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="navbar">
        <!-- Logo Section -->
        <div class="logo-container">
            <img class="logo" src="css/icon.png" alt="" srcset="">
            <H1>Shoepe</H1>
        </div>

        <?php
        displayWelcomeSection();
        ?>
    </div>

    <?php
    displayNavigation();

    ?>
    <div class="container">
        <h2>Sales Plot</h2>
        <?php

function displayWelcomeSection() {
    // Start the session
    session_start();
    // Check if user is logged in
    if (isset($_SESSION['username'])) {
        // Get username from session
        $username = $_SESSION['username'];
        // Establish database connection
        $conn = mysqli_connect("localhost", "root", "", "shoepe");

        // Query to get user data
        $userQuery = "SELECT * FROM user WHERE username='$username'";
        $userResult = mysqli_query($conn, $userQuery);

        // Check if user data exists
        if ($userResult && mysqli_num_rows($userResult) > 0) {
            // Fetch user data
            $userData = mysqli_fetch_assoc($userResult);
            // Get profile picture
            $profilePicture = $userData['profile_picture'];

            // Display welcome message and profile picture
            echo '<div class="welcome1">';
            echo "<img src='$profilePicture' alt='profilepic'>";
            echo '<b>Welcome,<b>' . $username . '</b>';
            echo '</div>';
        } else {
            // Display error if user not found
            echo "<p>User not found.</p>";
        }

        // Close database connection
        mysqli_close($conn);
    } else {
        // If user not logged in, display empty welcome section
        echo '<div class="welcome1">';
        echo '</div>';
    }
}

function displayNavigation() {
    echo '<div class="navigation">';
    echo '<nav>';
    echo '<ul>';
    // Navigation Links
    $links = array(
        array('href' => 'dashboard.php', 'label' => 'Home'),
        array('href' => 'about.php', 'label' => 'About'),
        array('href' => 'contact.php', 'label' => 'Contact')
    );

    // Check if user is logged in
    if (isset($_SESSION['username'])) {
        // If logged in, add additional links
        $links[] = array('href' => 'profile.php', 'label' => 'Profile');
        $links[] = array('href' => 'cart.php', 'label' => 'Cart');
        $links[] = array('href' => 'sell.php', 'label' => 'Sell');
        $links[] = array('href' => 'history.php', 'label' => 'History');
        $links[] = array('href' => 'discussion.php', 'label' => 'Discussion');
        $links[] = array('href' => 'logout.php', 'label' => 'Logout');
    } else {
        // If not logged in, add login and register links
        $links[] = array('href' => 'login.php', 'label' => 'Login');
        $links[] = array('href' => 'register.php', 'label' => 'Register');
    }

    foreach ($links as $link) {
        echo '<li><a href="' . $link['href'] . '">' . $link['label'] . '</a></li>';
    }
    echo '</ul>';
    echo '</nav>';
    echo '</div>';
}
        // Connect to MySQL
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "shoepe";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Get the shoe ID from the form submission
        if (isset($_POST['shoe_id'])) {
            $shoeId = $_POST['shoe_id'];

            // Execute generate_sales_plot.py to generate the latest plot
            $python = "C:/Users/Cyfer/AppData/Local/Microsoft/WindowsApps/python3.12.exe";
            $plot_script = "c:/xampp/htdocs/FINALS/generate_sales_plot.py";  // Adjust the path
            $command = "$python $plot_script $shoeId";
            $output = shell_exec($command);

            // Check if there was an error executing the script
            if (empty($output)) {
                echo "<p class='error'>Error executing Python script.</p>";
            }

            // Get the latest plot filename for the shoe ID
            $sql = "SELECT plot_filename FROM shoe_plots WHERE shoe_id = $shoeId ORDER BY created_at DESC LIMIT 1";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $plot_filename = $row["plot_filename"];

                // Extract the relative path from the absolute path
                $relative_path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $plot_filename);

                // Display the plot image
                echo "<h3>Sales Plot for Shoe ID: $shoeId</h3>";
                echo "<div class='plot-container'>";
                echo "<img src='$relative_path' alt='Sales Plot' class='plot-image'>";
                echo "</div>";
            } else {
                echo "<p class='error'>No sales was found for Shoe ID: $shoeId</p>";
            }
        } else {
            echo "<p class='error'>Error: Shoe ID not provided.</p>";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
