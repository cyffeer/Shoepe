<?php


session_start();

if (!isset($_SESSION['username'])) {
    
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $shoeName = $_POST['shoe_name'];
    $brand = $_POST['brand'];
    $colorway = $_POST['colorway'];
    $price = $_POST['price'];
    $size = $_POST['size'];
    $quantity = $_POST['quantity'];
    
    
    $sellerUsername = $_SESSION['username'];

    
    $conn = mysqli_connect("localhost", "root", "", "shoepe");

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    
    $targetDir = "images/"; 
    $fileName = basename($_FILES["image"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    
    $allowedTypes = array("jpg", "jpeg", "png", "gif");
    if (in_array($fileType, $allowedTypes)) {
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
            
            $insertQuery = "INSERT INTO shoes (shoename, brand, colorway, price, size, quantity, imagepath, username)
                            VALUES ('$shoeName', '$brand', '$colorway', '$price', '$size', '$quantity', 'images/$fileName', '$sellerUsername')";

            if (mysqli_query($conn, $insertQuery)) {
                
                header("Location: dashboard.php");
                exit();
            } else {
                echo "Error: " . $insertQuery . "<br>" . mysqli_error($conn);
            }
        } else {
            echo "Error uploading the file.";
        }
    } else {
        echo "Invalid file type. Allowed types are jpg, jpeg, png, and gif.";
    }

    mysqli_close($conn);
} else {
    
    header("Location: sell.php");
    exit();
}
?>

