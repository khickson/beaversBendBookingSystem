<?php
include ("dbconn.inc.php");
$conn = dbConnect();
?>
<?php
// start the session
session_start();

// clear out session value
if (isset($_GET['logout'])){
    $_SESSION['access'] = false;
}

// check to see if there's a form submission of user name and password
if (isset($_POST['username']) && isset($_POST['password']) && !empty($_POST['username']) && !empty($_POST['password'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    //echo "u: $username - p: $password <br>";

    // add additional validation here if necessary
    $sql = "SELECT email, password FROM owners ORDER BY lName";
    $stmt = $conn->stmt_init();
    if ($stmt->prepare($sql)) {
        $stmt->execute();
        $stmt->bind_result($user, $pass);
        while ($stmt->fetch()) {
            if ($username === $user && $password === $pass) {
                // grant access
                $_SESSION['access'] = true;
                // redirect it to the admin page #1
                header('Location: backEnd.php');
                exit;
            } else {
                // error message
                $message = "<div class='error'>The user name and password you provided are incorrect.  Please try again.</div>";
            }
        }
        $stmt->close();
        // validate user name and password
        // -- in this example, only one set of user name and password is valid so it's hard-coded here.  If there are multiple accounts, you may want to check the user input against your database records to grand access

    } else if (isset($_POST['username']) || isset($_POST['password'])) {
        $message = "<div class='error'>Please enter both the user name and password to log in.</div>";

    } else {
        $message = "<div>Please use the form below to log in to the admin page</div>";

    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="./loginstyle.css">
</head>
<body>
<header>
    <img src='http://www.beaversbendcabins4rent.com/wp-content/uploads/2016/08/Logo2-2.png' alt='Beavers Bend Luxury Cabin Rentals'>
</header>
<main>
    <div class="login-card">
        <h1>Log-in</h1><br>
        <?= $message ?>
        <form action="" method="post">
            <input type="text" name="username" placeholder="Username">
            <input type="password" name="password" placeholder="Password">
            <input type="submit" name="login" class="login login-submit" value="login">
        </form>

        <div class="login-help">
            <a href="#">Register</a> • <a href="#">Forgot Password</a>
        </div>
    </div>
</main>
<footer>
    <footer>
        &copy; Copyright 2018 Beavers Bend Luxury Cabins &nbsp;| &nbsp; <a href='./frontEnd.php'>Back To Home</a>
    </footer>
</footer>
</body>
</html>