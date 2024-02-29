<?php
// process_post.php

// Implement your database connection code here
$host = "localhost";
$user = "root";
$password = "";
$database = "shoepe";

$conn = mysqli_connect($host, $user, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to get the user_id from the session
function getUserId() {
    // Implement your user authentication logic
    // For demonstration, let's assume you have a session variable storing user_id.
    session_start();
    if (isset($_SESSION['user_id'])) {
        return $_SESSION['user_id'];
    } else {
        // Redirect to the login page if the user is not authenticated
        header("Location: login.php");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = getUserId(); // Get the user_id

    $opinion_text = mysqli_real_escape_string($conn, $_POST['opinion']); // Sanitize the input

    // Insert the new opinion into the database
    $insert_query = "INSERT INTO opinions (user_id, opinion_text, created_at) VALUES ($user_id, '$opinion_text', NOW())";

    // Execute the query
    if (mysqli_query($conn, $insert_query)) {
        // Redirect back to the discussion page upon success
        header("Location: discussion.php");
        exit();
    } else {
        echo "Error: " . $insert_query . "<br>" . mysqli_error($conn);
    }
}

// Close the database connection
mysqli_close($conn);
?>
