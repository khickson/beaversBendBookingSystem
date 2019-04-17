<?php
include("access.php");
include ("dbconn.inc.php");
$conn = dbConnect();

?>
<!doctype html>
<html lang="en">
<head>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <title>Beavers Bend Luxury Cabins | Book Now</title>
</head>
<body>
<header>
    <img src='http://www.beaversbendcabins4rent.com/wp-content/uploads/2016/08/Logo2-2.png' alt='Beavers Bend Luxury Cabin Rentals'>
</header>

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
            <h3>Most Popular</h3>
            <table>
                <?php
                $sql = "SELECT reservations.PID, cabins.image, cabins.name, count(*) as num from reservations INNER JOIN cabins on reservations.PID = cabins.PID Group by reservations.PID order by num DESC Limit 3";
                $stmt = $conn->stmt_init();
                if ($stmt->prepare($sql)){
                    $stmt->execute();
                    $stmt->store_result();
                    $numRows = $stmt->num_rows();
                    if ($numRows == 0){
                        echo "<h3>Currently there are no reservations...";
                    }
                    $stmt->bind_result($PID, $cabinImage, $cabinName, $cabinCount);
                    while ($stmt->fetch()){
                        echo "<tr>
                    <td>
                        <img src='$cabinImage' alt=''>
                    </td>
                    <td>
                        $cabinName<br>$cabinCount Reservations
                    </td>
                </tr>";
                    }
                    $stmt->close();
                }
                ?>
            </table>
        </div>
        <div class="aside">
            <h3>Latest Reservations</h3>
            <table>
                <?php
                $sql = "SELECT reservations.name, reservations.dateFrom, reservations.dateTo, reservations.PID, reservations.status, cabins.PID, cabins.name FROM reservations, cabins WHERE reservations.PID = cabins.PID ORDER BY UID DESC LIMIT 4";
                $stmt = $conn->stmt_init();
                if ($stmt->prepare($sql)){
                    $stmt->execute();
                    $stmt->store_result();
                    $numRows = $stmt->num_rows();
                    if ($numRows == 0){
                        echo "<h3>Currently there are no reservations...";
                    }
                    $stmt->bind_result($reserveName, $reserveDateFrom, $reserveDateTo, $reservePID, $reserveStatus, $cabinsPID, $cabinName2);
                    while ($stmt->fetch()){
                        echo "<tr>
                    <td>
                        <ul>
                            <li>$reserveName</li>
                            <li>From: $reserveDateFrom</li>
                            <li>To: $reserveDateTo</li>";
                        if ($reserveStatus==1){
                            echo "<li>Status: Confirmed</li>";
                        }else{
                            echo "<li>Status: Pending</li>";
                        }
                        echo "<li>$cabinName2</li>
                        </ul>
                    </td>
                </tr>";
                        }
                    }
                    $stmt->close();
                ?>
            </table>
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