<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoepe - Sell Your Shoes</title>
    <link rel="shortcut icon" type="x-icon" href="css/icon.png">
    <link rel="stylesheet" type="text/css" href="css/sell.css">
</head>

<body>
<a href="dashboard.php">
        <div class="navbar">
            <center><img class="logo-product" src="css/icon.png" alt="" srcset=""></center>
        </div>
    </a>

    <div class="form">
        <form action="process_sell.php" method="post" enctype="multipart/form-data">
            <h3>Sell Your Shoes</h3>
            <div class="field">
                <label for="shoe_name">Shoe Name:</label>
                <input type="text" name="shoe_name" id="shoe_name" autofocus required>
            </div>
            <div class="field">
                <label for="brand">Brand:</label>
                <input type="text" name="brand" id="brand" required>
            </div>
            <div class="field">
                <label for="colorway">Colorway:</label>
                <input type="text" name="colorway" id="colorway" required>
            </div>
            <div class="field">
                <label for="price">Price:</label>
                <input type="number" name="price" id="price" required>
            </div>
            <div class="field">
                <label for="size">Size:</label>
                <input type="text" name="size" id="size" required>
            </div>
            <div class="field">
                <label for="quantity">Quantity:</label>
                <input type="number" name="quantity" id="quantity" required>
            </div>
            <div class="field">
                <label for="image">Upload Image:</label>
                <input type="file" name="image" id="image" accept="image/*" required>
            </div>
            <div class="btn">
                <input type="submit" value="Sell Now">
            </div>
        </form>
    </div>
</body>

</html>

