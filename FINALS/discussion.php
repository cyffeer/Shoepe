<?php
// Include your database connection code here
$conn = mysqli_connect("localhost", "root", "", "shoepe");

// Check the connection
if ($conn === false) {
    echo "Could not connect to the database.";
    die();
}

session_start();

// Function to display opinions
function displayOpinions($conn)
{
    $sql = "SELECT o.*, u.profile_picture 
            FROM opinions o
            JOIN user u ON o.username = u.username
            ORDER BY o.created_at DESC";

    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<div id="opinion-container-' . $row['opinion_id'] . '" class="opinion-container">';
        
        // Display user information
        echo '<div class="user-info">';
        echo '<img src="' . $row['profile_picture'] . '" alt="Profile Picture">';
        echo '<strong>' . $row['username'] . '</strong>';
        echo '</div>';

        echo '<p>' . $row['opinion_text'] . '</p>';
        echo '<button onclick="showReplyForm(' . $row['opinion_id'] . ', \'' . $row['username'] . '\')">Reply</button>';

        // Display replies for each opinion
        displayReplies($conn, $row['opinion_id'], $row['username']);

        echo '</div>';
    }
}

function displayReplies($conn, $opinionId, $opinionUsername)
{
    $sql = "SELECT * FROM replies WHERE opinion_id = $opinionId ORDER BY created_at ASC";
    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<div class="reply-container">';
        echo '<div class="reply">';
        echo '<div class="user-info">';
        echo '<img src="' . getProfilePicture($conn, $row['username']) . '" alt="Profile Picture">';
        echo '<strong>' . $row['username'] . '</strong> replied:';
        echo '</div>';
        echo '<p>' . $row['reply_text'] . '</p>';
        echo '</div>';
        echo '</div>';
    }
}

function getProfilePicture($conn, $username)
{
    $sql = "SELECT profile_picture FROM user WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        return $row['profile_picture'];
    }

    return ''; // Default profile picture if not found
}
// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Handle posting new opinion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_opinion'])) {
    $opinionText = mysqli_real_escape_string($conn, $_POST['opinion_text']);
    $username = $_SESSION['username'];

    // Fetch profile_picture from the user table
    $profilePictureSql = "SELECT profile_picture FROM user WHERE username = '$username'";
    $profilePictureResult = mysqli_query($conn, $profilePictureSql);

    if ($profilePictureRow = mysqli_fetch_assoc($profilePictureResult)) {
        $profilePicture = $profilePictureRow['profile_picture'];

        // Insert opinion with profile_picture into the opinions table
        $sql = "INSERT INTO opinions (username, opinion_text, profile_picture) 
                VALUES ('$username', '$opinionText', '$profilePicture')";

        mysqli_query($conn, $sql);
    } else {
        // Handle case where user's profile_picture is not found
    }
}

// Handle posting new reply
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_reply'])) {
    $replyText = mysqli_real_escape_string($conn, $_POST['reply_text']);
    $username = $_SESSION['username'];
    $opinionId = $_POST['opinion_id'];

    $sql = "INSERT INTO replies (opinion_id, username, reply_text) VALUES ('$opinionId', '$username', '$replyText')";
    mysqli_query($conn, $sql);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/discussion.css">
    <title>Shoepe Discussion</title>
    <link rel="shortcut icon" type="x-icon" href="css/icon.png">
</head>

<body>
    <!-- Include your header content here -->
    <header>
        <div class="container">
            <img class="logo" src="css/icon.png" alt="Shoepe Logo">
            <h1>Shoepe - Discussion</h1>
        </div>
    </header>

    <!-- Include your navigation bar here -->
    <nav>
        <a href="dashboard.php">Home</a>
        <a href="about.php">About</a>
        <a href="contact.php">Contact</a>
        <a href="profile.php">Profile</a>
        <a href="cart.php">Cart</a>
        <a href="sell.php">Sell</a>
        <a href="history.php">History</a>
        <a href="logout.php">Logout</a>
    </nav>

    <div class="container">
        <h2>Opinions</h2>
        <?php displayOpinions($conn); ?>
    </div>

    <div class="container">
        <h2>Post Opinion</h2>
        <form method="POST" action="">
            <textarea name="opinion_text" placeholder="Type your opinion here"></textarea>
            <input type="submit" name="post_opinion" value="Post Opinion">
        </form>
    </div>

    <!-- Include your footer content here -->

    <script>
    function showReplyForm(opinionId, username) {
        var opinionContainer = document.getElementById('opinion-container-' + opinionId);

        if (opinionContainer) {
            var replyForm = document.createElement("form");
            replyForm.method = "POST";
            replyForm.action = "";

            var textarea = document.createElement("textarea");
            textarea.name = "reply_text";
            textarea.placeholder = "Type your reply here";

            var opinionIdInput = document.createElement("input");
            opinionIdInput.type = "hidden";
            opinionIdInput.name = "opinion_id";
            opinionIdInput.value = opinionId;

            var usernameInput = document.createElement("input");
            usernameInput.type = "hidden";
            usernameInput.name = "reply_username";
            usernameInput.value = username;

            var submitButton = document.createElement("input");
            submitButton.type = "submit";
            submitButton.name = "post_reply";
            submitButton.value = "Post Reply";

            replyForm.appendChild(textarea);
            replyForm.appendChild(opinionIdInput);
            replyForm.appendChild(usernameInput);
            replyForm.appendChild(submitButton);

            // Append the reply form to the opinion container
            opinionContainer.appendChild(replyForm);
        } else {
            console.error('Opinion container not found');
        }
    }
</script>

</body>

</html>


