<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $conn = mysqli_connect("localhost", "root", "", "shoepe");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Retrieve form data
    $history_id = $_POST['history_id'];
    $rating = $_POST['rating'];
    $review_descp = $_POST['review_descp'];

    // Update review and review description in history table
    $updateQuery = "UPDATE history SET review = '$rating', review_descp = '$review_descp' WHERE id = '$history_id'";
    $updateResult = mysqli_query($conn, $updateQuery);

    if ($updateResult) {
        // Review successfully updated
        header("Location: history.php");
        exit();
    } else {
        // Error updating review
        echo "Error: " . mysqli_error($conn);
    }

    // Close database connection
    mysqli_close($conn);
} else {
    // Redirect to history page if form is not submitted
    header("Location: history.php");
    exit();
}
?>
