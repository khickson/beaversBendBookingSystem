<?php
include("access.php");
include ("dbconn.inc.php");
$conn = dbConnect();
?>

<?php
if (isset($_GET['uid'])){
    $uid = intval($_GET['uid']);
    if ($uid > 0){
        $sql = "SELECT name, dateFrom, dateTo, PID, status FROM reservations where UID = ?";
        $stmt = $conn->stmt_init();
        if ($stmt->prepare($sql)) {
            $stmt->bind_param('i', $uid);
            $stmt->execute();
            $stmt->bind_result($name, $dateFrom, $dateTo, $PID, $status);
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

<?php
function CabinOptionList($selectedPID){
    $list = "";
    global $conn;
    $sql = "SELECT PID, name FROM cabins ORDER BY name ASC";
    $stmt = $conn->stmt_init();
    if ($stmt->prepare($sql)){
        $stmt->execute();
        $stmt->bind_result($PID, $cabinName);
        while ($stmt->fetch()){
            if ($PID == $selectedPID){
                $selected = "selected";
            } else{
                $selected = "";
            }
            $list = $list."<option value='$PID' $selected>$cabinName</option>";
        }
    }
    $stmt->close();
    return $list;
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
    function confirmDel(reservationName, uid) {
        url = "reservation_delete.php?uid=" + uid;
        var agree = confirm("Delete This Reservation For: " + reservationName + " ? ");
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
            <form action="reservation_edit.php" method="POST" class="PropForm">
                <input type="hidden" name="UID" value="<?=$uid?>">
                <table>
                    <tr>
                        <td>Reservation Name: </td><td><input type="text" name="reserveName" value="<?=$name?>"></td>
                    </tr>
                    <tr>
                        <td>Date From: </td><td><input type="text" name="fromDate" placeholder='From: YYYY-MM-DD' value="<?=$dateFrom?>"></td>
                    </tr>
                    <tr>
                        <td>Date To: </td><td><input type="text" name="toDate" placeholder='From: YYYY-MM-DD' value="<?=$dateTo?>"></td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td><select name='status' class='drop'>
                                <option value="">Select One</option>
                                <option value="1">Confirmed</option>
                                <option value="0">Pending</option>
                            </select></td>
                    </tr>
                    <tr>
                        <td>Cabin Reserving</td>
                        <td>
                            <select name='cabinChoice' class='drop'>
                                <?= CabinOptionList($PID)?>
                            </select>
                        </td>
                    </tr>
                </table>
                <input type="submit" name="Submit" value="Add Reservation" class="subPropBTN">
            </form>
        </div>
        <div class="aside">
            <h3>Reservation Sub-Menu</h3>
            <a href="./reservationForm.php"> <button class=subPropBTN>Add Reservation</button></a>
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