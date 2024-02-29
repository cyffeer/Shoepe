<?php
session_start();

function displayCartItems() {
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];

        $conn = mysqli_connect("localhost", "root", "", "shoepe");

        if (isset($_GET['purchase'])) {
            $cartId = $_GET['purchase'];

            // Remove the purchased item from the cart
            $removeFromCartQuery = "DELETE FROM cart WHERE id = '$cartId'";
            $removeFromCartResult = mysqli_query($conn, $removeFromCartQuery);

            if (!$removeFromCartResult) {
                echo "Error removing item from the cart: " . mysqli_error($conn);
            }
        }

        $cartQuery = "SELECT shoes.*, cart.id as cart_id, cart.quantity as cart_quantity, cart.size as cart_size FROM shoes INNER JOIN cart ON shoes.id = cart.shoe_id WHERE cart.username = '$username'";
        $cartResult = mysqli_query($conn, $cartQuery);

        // Display the user's cart items
        if ($cartResult && mysqli_num_rows($cartResult) > 0) {
            echo "<div class='grid'>";
            while ($cartItem = mysqli_fetch_assoc($cartResult)) {
                echo "<div class='item'>";
                echo "<div class='img-container'>";
                echo "<img src='{$cartItem['imagepath']}' alt='shoe' style='width: 250px; height:250px;'>";

                // Add the "Remove from Cart" button
                echo "<a class='remove-from-cart' href='cart.php?purchase={$cartItem['cart_id']}'>";
                echo "<img src='css/removefromcart.png' alt='Remove from Cart' style='width: 30px; height: 30px;'>";
                echo "</a>";

                echo "</div>";
                echo "<br><b><center>{$cartItem['shoename']}</center></b>";
                echo "<br><center>{$cartItem['brand']}</center>";
                echo "<br><center>₱{$cartItem['price']}</center>";
                echo "<br><center>Size: {$cartItem['cart_size']}</center>";
                echo "<br><center>Quantity: {$cartItem['cart_quantity']}</center>";

                // Ensure 'shoe_id' is available in the $cartItem array
                $shoeId = isset($cartItem['id']) ? $cartItem['id'] : null;

                echo "<br><center><a class='proceed-to-purchase' href='product.php?id={$shoeId}'>Proceed to Product</a></center>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<div class='empty-cart-message'>Your cart is empty</div>";
        }

        mysqli_close($conn);

    } else {
        // Redirect to login page if not logged in
        header("Location: login.php");
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoepe - Your Cart</title>
    <link rel="stylesheet" href="css/cart.css">
    <!-- Update the path to the favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="css/icon.png">
</head>

<body>

    <div class="logo-container">
        <img class="logo" src="css/icon.png" alt="Shoepe Logo">
        <h1>Shoepe</h1>
    </div>

    <div class="navbar">
        <a href="dashboard.php">Home</a>
        <a href="about.php">About</a>
        <a href="contact.php">Contact</a>
        <a href="profile.php">Profile</a>
        <a href="sell.php">Sell</a>
        <a href="discussion.php">Discussion</a>
        <a href="logout.php">Logout</a>
    </div>

    <?php
    displayCartItems();
    ?>

</body>

</html>
