<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shoepe - Contact Us</title>
  <link rel="shortcut icon" type="x-icon" href="css/icon.png">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <style>
    body {
      background-color: #333;
      text-align: center;
    }

    .books-container {
      display: flex;
      justify-content: center;
      margin-top: 20px; /* Adjust as needed */
    }

    .book {
      position: relative;
      border-radius: 10px;
      width: 330px;
      height: 300px;
      background-color: #000;
      box-shadow: 1px 1px 12px #000; /* Removed the glowing effect */
      transform: preserve-3d;
      perspective: 2000px;
      display: inline-flex;
      margin-right: 20px;
      align-items: center;
      justify-content: center;
      color: #fff;
    }

    p {
      font-size: 20px;
      font-weight: bolder;
    }

    .book em {
      font-size: 20px;
      font-weight: bolder;
      margin-left: 30px;
    }

    footer {
      background: #fff;
      width: 330px;
      color: #000;
      margin-left: auto;
      margin-right: auto;
    }
  </style>
</head>

<body>
  <a href="dashboard.php" class="logo-button">
    <img class="logo-product" src="css/icon.png" alt="">
  </a>

  <div class="books-container">
    <div class="book">
      <em>Cyfer Nikolai Supleo<br><br>
        09086950606<br>
        github/cyffeer<br>
        202110320@fit.edu.ph
      </em>
    </div>
  </div>

  <footer>
    <p>&copy; 2024 Shoepe. All rights reserved.</p>
  </footer>
</body>
</html>
