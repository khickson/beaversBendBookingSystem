<?php
include("access.php");
include ("dbconn.inc.php");
$conn = dbConnect();
?>

<?php
if (isset($_GET['ownerID'])){
    $ownerID = intval($_GET['ownerID']);
    if ($ownerID > 0){
        $sql = "SELECT fName, lName, address, phone, email, manager, password FROM owners where ownerID = ?";
        $stmt = $conn->stmt_init();
        if ($stmt->prepare($sql)) {
            $stmt->bind_param('i', $ownerID);
            $stmt->execute();
            $stmt->bind_result($fName, $lName, $address, $phone, $email, $manager, $password);
            $stmt->store_result();
            if ($stmt->num_rows == 1) {
                $stmt->fetch();
            } else {
                $errMsg = "<div>Information on the record you requested is not available.  If it is an error, please contact the webmaster.  Thank you.</div>";
                $uid = "";
            }
        }else{
            $uid = "";
            $errMsg = "<div> If you are expecting to edit an exiting item, there are some error occured in the process -- the selected product is not recognizable.  Please follow the link below to the product adminstration interface or contact the webmaster.  Thank you.</div>";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Backend Demo</title>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <title>Beavers Bend Luxury Cabins | Administration</title>
</head>

<body>
<header>
    <img src='http://www.beaversbendcabins4rent.com/wp-content/uploads/2016/08/Logo2-2.png' alt='Beavers Bend Luxury Cabin Rentals'>
</header>

<script>
    function confirmDel(ownerName, ownerID) {
        url = "user_delete.php?ownerID=" + ownerID;
        var agree = confirm("Delete This User: " + ownerName + " ? ");
        if (agree){
            location.href = url;
        }else{
            return;
        }
    }
</script>

<nav>
    <a href='frontEnd.php'>Front-End Demo</a> <a href='backEnd.php'>Back-End Demo</a>
</nav>
<main>
    <div class="gridContainer">
        <div class="headerRow">
            <i class="fas fa-4x fa-home"></i>
            <div class="numLg"><?php
                $sql = "SELECT COUNT(*) FROM cabins";
                $stmt = $conn->stmt_init();
                if ($stmt->prepare($sql)){
                    $stmt->execute();
                    $stmt->bind_result($count);
                    if($stmt->fetch()){
                        echo "$count";
                    }
                    $stmt->close();
                }
                ?></div> Properties
        </div>
        <div class="headerRow">
            <i class="fas fa-4x fa-calendar-alt"></i>
            <div class="numLg"><?php
                $sql = "SELECT COUNT(*) FROM reservations";
                $stmt = $conn->stmt_init();
                if ($stmt->prepare($sql)){
                    $stmt->execute();
                    $stmt->bind_result($count);
                    if($stmt->fetch()){
                        echo "$count";
                    }
                    $stmt->close();
                }
                ?></div> <span>Reservations</span>
        </div>
        <div class="headerRow">
            <i class="fas fa-4x fa-user"></i>
            <div class="numLg"><?php
                $sql = "SELECT COUNT(*) FROM owners";
                $stmt = $conn->stmt_init();
                if ($stmt->prepare($sql)){
                    $stmt->execute();
                    $stmt->bind_result($count);
                    if($stmt->fetch()){
                        echo "$count";
                    }
                    $stmt->close();
                }?></div>Users
        </div>
        <div class="links">
            <ul class="subNav">
                <li>
                    <a href="backEnd.php">
                        <i class="fas fa-2x fa-tachometer-alt"></i> &nbsp;  <strong>Dashboard</strong>
                    </a>
                </li>
                <li>
                    <a href="properties.php">
                        <i class="fas fa-2x fa-folder-open"></i> &nbsp; <strong>Properities</strong>
                    </a>
                </li>
                <li>
                    <a href="reservations.php">
                        <i class="fas fa-2x fa-calendar"></i> &nbsp; <strong>Reservations</strong>
                    </a>
                </li>
                <li>
                    <a href="users.php">
                        <i class="fas fa-2x fa-users"></i> &nbsp; <strong>Users</strong>
                    </a>
                </li>
                <li>
                    <a href="login.php?logout">
                        <i class="fas fa-2x fa-sign-out-alt"></i> &nbsp; <strong>Logout</strong>
                    </a>
                </li>
            </ul>
        </div>
        <div class="section">
            <h3>Reservation Management</h3>
            <form action="user_edit.php" method="POST" class="PropForm">
                <input type="hidden" name="ownerID" value="<?=$ownerID?>">
                <table>
                    <tr>
                        <td>First Name: </td><td><input type="text" name="fName" value="<?=$fName?>"></td>
                    </tr>
                    <tr>
                        <td>Last Name: </td><td><input type="text" name="lName" value="<?=$lName?>"></td>
                    </tr>
                    <tr>
                        <td>Address: </td><td><input type="text" name="address" value="<?=$address?>"></td>
                    </tr>
                    <tr>
                        <td>Phone No: </td><td><input type="text" name="phone" value="<?=$phone?>"></td>
                    </tr>
                    <tr>
                        <td>Email: </td><td><input type="text" name="email" value="<?=$email?>"></td>
                    </tr>
                    <tr>
                        <td>Property Manager: </td><td>
                            <select name="manager">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Password: </td><td><input type="text" name="password" value=""></td>
                    </tr>
                </table>
                <input type="submit" name="Submit" value="Add User" class="subPropBTN">
            </form>
        </div>
        <div class="aside">
            <h3>Reservation Sub-Menu</h3>
            <a href="./userForm.php"> <button class=subPropBTN>Add User</button></a>
        </div>
    </div>
</main>
<div class="foot">
    <footer>
        &copy; Copyright 2018 Beavers Bend Luxury Cabins &nbsp;| &nbsp; <a href='login.php'>Admin Login</a>
    </footer>

</div>
</body>

</html>