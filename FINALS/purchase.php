<!DOCTYPE html>
<html>
<head>
    <title>Shoepe - Purchase</title>
    <link rel="shortcut icon" type="x-icon" href="css/icon.png">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
       
    </style>
</head>
<body>
<div class="navbar">
    <center><a href="dashboard.php" class="logo-button"><img class="logo-product" src="css/icon.png" alt="" srcset=""></a></center>
</div>

<?php
session_start();
$imagepath = $_SESSION['imagepath'];
$shoename = $_SESSION['shoename'];
$id = $_GET['id'];
$quantity = $_SESSION['quantity'];
$username = $_SESSION['username'];

// Update quantity in the 'shoes' table
$connShoes = mysqli_connect("localhost", "root", "", "shoepe");
if (!$connShoes) {
    die("Connection to 'shoes' table failed: " . mysqli_connect_error());
}

$updateShoesQuery = "UPDATE shoes SET quantity = $quantity-1 WHERE id = $id";
$updateShoesResult = mysqli_query($connShoes, $updateShoesQuery);

if ($updateShoesResult) {
    // Insert the item into the history table
    $insertIntoHistoryQuery = "INSERT INTO history (shoe_id, shoename, brand, colorway, price, size, quantity, imagepath, username_owner, username_buyer) SELECT id, shoename, brand, colorway, price, size, 1, imagepath, username, '$username' FROM shoes WHERE id = $id";
    $insertIntoHistoryResult = mysqli_query($connShoes, $insertIntoHistoryQuery);

    if (!$insertIntoHistoryResult) {
        echo "Error inserting item into history table: " . mysqli_error($connShoes);
    }

    // Close the connection to 'shoes' table
    mysqli_close($connShoes);

    // Print item details
    echo "<br><br>";
    echo '<div class="content-box">';
    echo '<center><h1>Item bought!</h1></center>';
    echo "Thank you for purchasing " . $shoename;
    echo "<center><img src='$imagepath' alt='image' style='width: 100px; height:100px;'></center>";
    echo '<center><br><a href="dashboard.php">Back to Homepage</a><br><br></center>';
    echo '</div>';

    // Open a new connection to 'cart' table
    $connCart = mysqli_connect("localhost", "root", "", "shoepe");
    if (!$connCart) {
        die("Connection to 'cart' table failed: " . mysqli_connect_error());
    }

    // Remove the item from the 'cart' table
    $deleteFromCartQuery = "DELETE FROM cart WHERE shoe_id = $id";
    $deleteFromCartResult = mysqli_query($connCart, $deleteFromCartQuery);

    if (!$deleteFromCartResult) {
        // Display an error message if the deletion from 'cart' fails
        echo "Error deleting item from the cart: " . mysqli_error($connCart);
    }
    // Fetch the price of the shoe from the 'shoes' table
    $priceQuery = "SELECT price FROM shoes WHERE id = $id";
    $priceResult = mysqli_query($connCart, $priceQuery);

    if ($priceResult) {
        $priceRow = mysqli_fetch_assoc($priceResult);
        $price = $priceRow['price'];
    } else {
        echo "Error fetching price: " . mysqli_error($connCart);
    }

    // Store the sales data
    $checkoutDate = date('Y-m-d'); // Get the current date

    // Prepare an SQL statement to insert the sales data
    $salesQuery = "INSERT INTO sales (shoe_id, date, amount) VALUES (?, ?, ?)";

    // Prepare and execute the statement
    $stmt = $connCart->prepare($salesQuery);
    $stmt->bind_param("iss", $id, $checkoutDate, $price);
    $stmt->execute();

    // Pass the shoe id to the Python script
    $output = shell_exec("python script/sales.py $id");

    // Close the connection to 'cart' table
    mysqli_close($connCart);
} else {
    // Display an error message if the update to 'shoes' fails
    echo "Error updating quantity in the shoes table: " . mysqli_error($connShoes);
    mysqli_close($connShoes);
}
?>
</body>
</html>
