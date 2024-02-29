<?php
session_start();

if (isset($_GET['id']) && isset($_SESSION['username'])) {
    $shoeId = $_GET['id'];
    $username = $_SESSION['username'];

    // Assume size and quantity are obtained from the form or set to default values
    $size = $_POST['size'] ?? 'Default Size';
    $quantity = $_POST['quantity'] ?? 1;

    $conn = mysqli_connect("localhost", "root", "", "shoepe");

    // Check if the shoe is available
    $checkAvailabilityQuery = "SELECT * FROM shoes WHERE id=? AND quantity > 0";
    $stmtAvailability = mysqli_prepare($conn, $checkAvailabilityQuery);
    mysqli_stmt_bind_param($stmtAvailability, "i", $shoeId);
    mysqli_stmt_execute($stmtAvailability);
    $availabilityResult = mysqli_stmt_get_result($stmtAvailability);

    if ($availabilityResult && mysqli_num_rows($availabilityResult) > 0) {
        // Fetch shoe details
        $shoeDetails = mysqli_fetch_assoc($availabilityResult);

        // Shoe is available, add it to the user's cart
        $sizeStr = strval($size); // Convert float to string

        $addToCartQuery = "INSERT INTO cart (shoe_id, username, shoename, brand, colorway, price, size, quantity, imagepath) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtAddToCart = mysqli_prepare($conn, $addToCartQuery);
        mysqli_stmt_bind_param(
            $stmtAddToCart,
            "issssdiss",
            $shoeId,
            $username,
            $shoeDetails['shoename'],
            $shoeDetails['brand'],
            $shoeDetails['colorway'],
            $shoeDetails['price'],
            $sizeStr,
            $quantity,
            $shoeDetails['imagepath']
        );

        $addToCartResult = mysqli_stmt_execute($stmtAddToCart);

        if ($addToCartResult) {
            // Redirect to cart page after successful addition
            header("Location: cart.php");
            exit();
        } else {
            echo "Error adding to cart: " . mysqli_error($conn);
        }
    } else {
        // Shoe is not available
        echo "This shoe is currently unavailable.";
    }

    mysqli_close($conn);
} else {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}
?>



