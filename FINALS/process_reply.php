<?php
// process_reply.php

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

// Function to get the user_id from the session or other authentication mechanism
function getUserId() {
    session_start();

    // Check if the user is logged in
    if (isset($_SESSION['user_id'])) {
        return $_SESSION['user_id'];
    } else {
        // Redirect to the login page if the user is not authenticated
        header("Location: login.php");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the user_id using the authentication logic
    $user_id = getUserId();

    // Implement input validation to get the opinion_id from the URL parameter
    $opinion_id = isset($_GET['opinion_id']) ? (int)$_GET['opinion_id'] : 0;
    if ($opinion_id <= 0) {
        // Handle invalid input, for example, redirect to an error page
        header("Location: error.php");
        exit();
    }

    $reply_text = mysqli_real_escape_string($conn, $_POST['reply']); // Sanitize the input

    // Insert the new reply into the database using prepared statements
    $insert_query = "INSERT INTO replies (opinion_id, user_id, reply_text, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = mysqli_prepare($conn, $insert_query);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "iis", $opinion_id, $user_id, $reply_text);

    // Execute the query
    if (mysqli_stmt_execute($stmt)) {
        // Redirect back to the discussion page upon success
        header("Location: discussion.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    // Close the statement
    mysqli_stmt_close($stmt);
}

// Close the database connection
mysqli_close($conn);
?>


