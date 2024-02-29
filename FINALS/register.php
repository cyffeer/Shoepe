<!DOCTYPE html>
<html>
<style>
      
</style>
    <head>
        <title>Shoepe - Register</title>
        <link rel="shortcut icon" type="x-icon" href="css/icon.png">
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
    <div class="navbar">
            <center><img class = "logo-product" src="css/icon.png" alt="" srcset=""></center>

    </div>
        <div class = "formreg">
        <h3>Register Here!!</h3>
        <form action="register_challenge.php" method="post">
            Username: <br><input type="text" name="username" id="username" autofocus required><br><br>
            Password: <br><input type="password" name="password" id="password" placeholder="Atleast 8 characters" autofocus required minlength="8"><br><br>
            Confirm Password: <br><input type="password" name="confirmpass" id="confirmpass" placeholder="Atleast 8 characters" autofocus required minlength="8"><br><br>
            Email: <br><input type="text" name="email" id="email" autofocus required><br><br>
            Full Name: <br><input type="text" name="fullname" id="fullname" autofocus required><br><br>
            Phone No.: <br><input type="text" name="phone" id="phone" autofocus required><br><br>
            Address: <br><input type="text" name="address" id="address" autofocus required><br><br>
            <input type="submit" value="Register">
        </form>
        <br><br>
        Already a user?<br>
        <div class = "button">
        <a href="login.php">Login Here!</a>
        </div>
        <div class = "button">
        <a href="dashboard.php">Back to Homepage</a>
        </div>
        </div>
    </body>
</html>