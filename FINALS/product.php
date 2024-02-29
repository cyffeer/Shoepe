<!DOCTYPE html>
<html>
<style>
    /* Add your styles here */
</style>

<head>
    <title>Shoepe  - Product</title>
    <link rel="shortcut icon" type="x-icon" href="css/icon.png">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
    <div class="navbar">
        <center><img class="logo-product" src="css/icon.png" alt="" srcset=""></center>
    </div>
    <div class="content-box">
        <CENTER>
            <h1>PRODUCT
            <?php
                session_start();

                $id = $_GET['id'];
                $back = isset($_GET['back']) ? $_GET['back'] : '/';

                // Connect to the 'shoes' table
                $conn = mysqli_connect("localhost", "root", "", "shoepe");

                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                // Escape the product ID to prevent SQL injection
                $id = mysqli_real_escape_string($conn, $id);

                // Check if the 'id' exists in the 'shoes' table
                $shoesQuery = "SELECT * FROM shoes WHERE id = '$id'";
                $shoesResult = mysqli_query($conn, $shoesQuery);

                if (mysqli_num_rows($shoesResult) > 0) {
                    // The 'id' exists in the 'shoes' table
                    $row = mysqli_fetch_assoc($shoesResult);
                    $imagepath = $row['imagepath'];

                    echo "<br><br><img src='$imagepath' alt='{$row['shoename']}' style='width: 350px; height:350px;'>";
                    echo "<br><br>Name: " . $row['shoename'];
                    echo "<br><br>Brand: " . $row['brand'];
                    echo "<br><br>Colorway: " . $row['colorway'];
                    echo "<br><br>Price: " . $row['price'];
                    echo "<br><br>Size: " . $row['size'];
                    echo "<br><br>Quantity: " . $row['quantity'];
                    echo "<br><br><a href='checkout.php?id=$id'>Buy Item</a>";
                    echo "<br><a href='dashboard.php'>Go Back</a>";

                    $_SESSION['imagepath'] = $imagepath;
                    $_SESSION['shoename'] = $row['shoename'];
                    $_SESSION['brand'] = $row['brand'];
                    $_SESSION['colorway'] = $row['colorway'];
                    $_SESSION['price'] = $row['price'];
                    $_SESSION['size'] = $row['size'];
                    $_SESSION['quantity'] = $row['quantity'];
                }  else {
                    // No match in the 'shoes' table, close the connection
                    mysqli_close($conn);
                
                    // Connect to the 'cart' table
                    $conn = mysqli_connect("localhost", "root", "", "shoepe");
                
                    if (!$conn) {
                        die("Connection failed: " . mysqli_connect_error());
                    }
                
                    // Check if the 'id' exists in the 'cart' table
                    $cartQuery = "SELECT * FROM cart WHERE shoe_id = '$id'";
                    $cartResult = mysqli_query($conn, $cartQuery);
                
                    if (mysqli_num_rows($cartResult) > 0) {
                        // The 'id' exists in the 'cart' table
                        $cartRow = mysqli_fetch_assoc($cartResult);
                        $imagepath = $cartRow['imagepath'];

                        echo "<br><br><img src='$imagepath' alt='{$cartRow['shoename']}' style='width: 350px; height:350px;'>";
                        echo "<br><br>Name: " . $cartRow['shoename'];
                        echo "<br><br>Brand: " . $cartRow['brand'];
                        echo "<br><br>Colorway: " . $cartRow['colorway'];
                        echo "<br><br>Price: " . $cartRow['price'];
                        echo "<br><br>Size: " . $cartRow['size'];
                        echo "<br><br>Quantity: " . $cartRow['quantity'];
                        echo "<br><br><a href='checkout.php?id=$id'>Buy Item</a>";
                        echo "<br><a href='$back'>Go Back</a>";

                        $_SESSION['imagepath'] = $imagepath;
                        $_SESSION['shoename'] = $cartRow['shoename'];
                        $_SESSION['brand'] = $cartRow['brand'];
                        $_SESSION['colorway'] = $cartRow['colorway'];
                        $_SESSION['price'] = $cartRow['price'];
                        $_SESSION['size'] = $cartRow['size'];
                        $_SESSION['quantity'] = $cartRow['quantity'];
                    } else {
                        // The 'id' does not exist in either the 'shoes' or 'cart' table
                        echo "Product not found in the 'shoes' or 'cart' table!";
                    }
                }

                mysqli_close($conn);
                ?>
        </CENTER>
    </div>
</body>

</html>
