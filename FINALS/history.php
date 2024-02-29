<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Function to display shoes grid
function displayShoesGrid() {
    // Establish database connection
    $conn = mysqli_connect("localhost", "root", "", "shoepe");

    // Get the username of the current user
    $username = $_SESSION['username'];

    // Query to get total count of shoes bought by the current user
    $sql_count = "SELECT COUNT(*) AS count FROM history WHERE username_buyer = '$username'";
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

    // Query to retrieve shoes bought by the current user for the current page
    $sql_for = "SELECT * FROM history WHERE username_buyer = '$username' LIMIT $start, $shoesPerPage";
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
                echo "<center><img src='$imagepath' alt='shoe' style='width: 150px; height:150px;'></center>";
                echo '<br><b style="text-decoration: line-through;"><center>' . $row['shoename'] . "</center></b>";
            } else {
                echo "<div class='img-container'>";

                // Display the shoe image
                echo "<a href='review.php?id={$row['id']}'><img src='$imagepath' alt='shoe' style='width: 150px; height:150px;'></a>";

                echo "</div>";
                echo "<br><b><center>" . $row['shoename'] . "</center></b>";
            }

            echo "<br><center>" . $row['brand'] . "</center>";
            if ($quantity == 0) {
                echo "<br><center><b>SOLD OUT</b></center>";
            } else {
                echo "<br><center>₱" . $row['price'] . "</center>";
                echo "<br><center>By:" . $row['username_owner'] . "</center>";
                echo "<br><center>Bought by:" . $row['username_buyer'] . "</center>";

                // Display star rating and review description
                if ($row['review'] > 0 && $row['review'] <= 5) {
                    echo "<div class='review'>";
                    echo "<img src='css/star.png' alt='star' style='width: 15px; height: 15px;'>";

                    // Display star rating based on user input
                    for ($i = 2; $i <= $row['review']; $i++) {
                        echo "<img src='css/star.png' alt='star' style='width: 15px; height: 15px;'>";
                    }
                    echo "<br>";
                    echo "<p>{$row['review_descp']}</p>"; // Display review description
                    echo "</div>";
                } else {
                    echo "<p>No review yet.</p>";
                }
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoepe - History</title>
    <link rel="shortcut icon" type="x-icon" href="css/icon.png">
    <link rel="stylesheet" href="css/history.css">
</head>
<body>
    <div class="navbar">
        <a href="dashboard.php" class="logo-button"><img class="logo-product" src="css/icon.png" alt="" srcset=""><h1> Shoepee</h1></a>
        <div class="navigation">
            <ul>
                <li><a href="dashboard.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="sell.php">Sell</a></li>
                <li><a href="history.php">History</a></li>
                <li><a href="discussion.php">Discussion</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>

    <div class="content">
        <h2>My Purchase History</h2>
        <!-- Call the function to display shoes grid -->
        <?php displayShoesGrid(); ?>
    </div>

</body>
</html>
