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

function displayShoesGrid() {
    // Establish database connection
    $conn = mysqli_connect("localhost", "root", "", "shoepe");

    // Query to get total count of shoes
    $sql_count = "SELECT COUNT(*) AS count FROM shoes";
    $result_count = mysqli_query($conn, $sql_count);
    $count = mysqli_fetch_assoc($result_count);
    $counter = $count['count'];

    $shoesPerPage = 8;

    // Determine current page
    if (isset($_GET['page'])) {
        $currentPage = $_GET['page'];
    } else {
        $currentPage = 1;
    }

    // Calculate starting point for query
    $start = ($currentPage - 1) * $shoesPerPage;
    // Query to retrieve shoes for current page
    $sql_for = "SELECT * FROM shoes LIMIT $start, $shoesPerPage";
    $result = mysqli_query($conn, $sql_for);

    if ($result) {
        // Display shoes in a grid
        echo "<div class='grid'>";
        while ($row = mysqli_fetch_assoc($result)) {
            $imagepath = $row['imagepath'];
            $quantity = $row['quantity'];

            echo "<div class='item'>";
            // Display shoe image and details
            if ($quantity == 0) {
                echo "<center><img src='$imagepath' alt='shoe' style='width: 250px; height:250px;'></center>";
                echo '<br><b style="text-decoration: line-through;"><center>' . $row['shoename'] . "</center></b>";
            } else {
                echo "<div class='img-container'>";

                // Add the "Add to Cart" button
                echo "<a class='add-to-cart' href='add_to_cart.php?id={$row['id']}'>";
                echo "<img src='css/addtocart.png' alt='Add to Cart' style='width: 30px; height: 30px;'>";
                echo "</a>";

                // Display the shoe image
                echo "<a href='product.php?id={$row['id']}'><img src='$imagepath' alt='shoe' style='width: 250px; height:250px;'></a>";

                echo "</div>";
                echo "<br><b><center>" . $row['shoename'] . "</center></b>";
            }

            echo "<br><center>" . $row['brand'] . "</center>";
            if ($quantity == 0) {
                echo "<br><center><b>SOLD OUT</b></center>";
            } else {
                echo "<br><center>₱" . $row['price'] . "</center>";
                echo "<br><center>By:" . $row['username'] . "</center>";
            }
            echo "</div>";
        }
        echo "</div>";

        // Pagination
        $totalPages = ceil($counter / $shoesPerPage);

        echo "<div class='pagination'>";
        for ($page = 1; $page <= $totalPages; $page++) {
            if ($page == $currentPage) {
                echo "<span class='current'>$page</span>";
            } else {
                echo "<a href='dashboard.php?page=$page'>$page</a>";
            }
        }
        echo "</div>";
    }
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Shoepe</title>
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
    </style>
</head>

<body>
    <!-- Navbar Section -->
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
    displayShoesGrid();
    ?>
</body>

</html>
