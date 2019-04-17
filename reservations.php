<?php
include("access.php");
include ("dbconn.inc.php");
$conn = dbConnect();
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
    function confirmDel(reserveName, uid) {
        url = "reservation_delete.php?uid=" + uid;
        var agree = confirm("Delete This Reservation For: " + reserveName + " ? ");
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
            <?php
            $sql = "SELECT reservations.UID, reservations.name, reservations.dateFrom, reservations.dateTo, reservations.PID, reservations.status, cabins.PID, cabins.name FROM reservations, cabins WHERE reservations.PID = cabins.PID ORDER BY dateFrom DESC";
            $stmt = $conn->stmt_init();
            if ($stmt->prepare($sql)) {
                $stmt->execute();
                $stmt->bind_result($uid, $reservationName, $dateFrom, $dateTo, $pid, $status, $cabinPID, $cabinName);
                $tblRows = "";
                while ($stmt->fetch()) {
                    $reservationName_js = $reservationName;
                    $tblRows = $tblRows . "<tr><td>$dateFrom</td><td>$dateTo</td><td><a href='./view_reservation.php?uid=$uid&pid=$pid'>$reservationName</a></td><td><a href='http://ctec4321.khh9106.uta.cloud/termProject/moreDetails.php?PID=$pid'>$cabinName</a></td><td><a href='reservationForm.php?uid=$uid'><i class=\"fas fa-edit\"></i> Edit</a> | <a href='javascript:confirmDel(\"$reservationName_js\", $uid)'><i class=\"fas fa-trash-alt\"></i> Delete</a></td></tr>\n";
                }

                $output = "
                <table class='cabinList'>
                    <tr><th>Date From</th><th>Date To</th><th>Reservation Name</th><th>Cabin Name</th><th>Options</th></tr>\n" . $tblRows . "
                </table>
        ";
                $stmt->close();
            }
            ?>
            <div class="flexboxConatiner">
                <?php echo $output ?>
            </div>
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