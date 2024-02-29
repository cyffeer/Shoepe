<!DOCTYPE html>
<html>
<style>
        
    </style>
    <head>
        <title>Shoepe - Checkout Item</title>
        <link rel="shortcut icon" type="x-icon" href="css/icon.png">
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    
    <body>

        <div class="navbar">
            <center><img class = "logo-product" src="css/icon.png" alt="" srcset=""></center>
            <div class="content-box">
            <center><h1>Checkout Item</h1></center>
        </div>
        
        <?php
        session_start();
        if(isset($_SESSION['username'])){
            //user details
            $username = $_SESSION['username'];
            $fullname = $_SESSION['fullname'];
            $phone = $_SESSION['phone'];
            $address = $_SESSION['address'];

            //id
            $id = $_GET['id'];

            //shoe details
            $shoename = $_SESSION['shoename'];
            $colorway = $_SESSION['colorway'];
            $size = $_SESSION['size'];
            $imagepath = $_SESSION['imagepath'];
            $price = $_SESSION['price'];
            $quantity = $_SESSION['quantity'];
            

            //shipping details
            echo '<div class="content-box">';
            echo "<b>Shipping Details</b><br>";
            echo "<br>Name: " . $fullname;
            echo "<br>Phone: " . $phone;
            echo "<br>Address: " . $address;

            //item/shoe details
            echo "<br><br>";
            echo "<b>Item Details</b><br>";
            echo "<img src='$imagepath' alt='image' style='width: 100px; height:100px;'>";
            echo "<br>Shoename: " . $shoename;
            echo "<br>Colorway: " . $colorway;
            echo "<br>Size: <b>" . $size . "</b>";
            echo "<br>Items left: " . $quantity;

            //shoe summary
            echo "<br><br>";
            echo "<b>Order Summary</b>";
            echo "<br><br>Subtotal: ₱" . $price;
            echo "<br>Shipping Fee: ₱100";
            echo "<br><br><b>Total: ₱" . $price + 100;
            echo "<br><br><a href='purchase.php?id=$id'>Place Order</a>";
            echo '<br><br><a href="dashboard.php">Go back</a>';
            echo "</div>";
        }
        else{
            header("Location:login.php");
            exit();
        }
        ?>  
    </body>

</html>