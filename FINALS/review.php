<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Check if the 'id' parameter is set
if (!isset($_GET['id'])) {
    header("Location: history.php");
    exit();
}

// Retrieve the 'id' parameter
$id = $_GET['id'];

// Database connection
$conn = mysqli_connect("localhost", "root", "", "shoepe");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if review already exists for the specified history entry
$sql = "SELECT review, review_descp FROM history WHERE id = '$id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$existingReview = $row['review'];
$reviewDescp = $row['review_descp'];

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $existingReview ? 'Shoepe - Edit Review' : 'Shoepe - Leave a Review'; ?></title>
    <link rel="shortcut icon" type="x-icon" href="css/icon.png">
    <link rel="stylesheet" href="css/review.css">
</head>
<body>
    <div class="navbar">
        <div class="logo-container">
            <img class="logo" src="css/icon.png" alt="Logo">
            <h1> Shoepee</h1>
        </div>
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

    <div class="container">
        <div class="content">
            <h2><?php echo $existingReview ? 'Edit Review' : 'Leave a Review'; ?></h2>
            <form action="submit_review.php" method="post">
                <input type="hidden" name="history_id" value="<?php echo $id; ?>">
                <label for="rating">Rating (1-5 stars):</label>
                <input type="number" name="rating" id="rating" min="1" max="5" required>
                <br>
                <label for="review_descp">Review Description:</label><br>
                <textarea name="review_descp" id="review_descp" rows="4" cols="50" required><?php echo $reviewDescp; ?></textarea>
                <br>
                <input type="submit" value="<?php echo $existingReview ? 'Edit Review' : 'Submit Review'; ?>">
            </form>
        </div>
    </div>

</body>
</html>
