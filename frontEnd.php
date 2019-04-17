<?php
setcookie("fromdate", $_GET['fromDate'], time()+3600);
setcookie("todate", $_GET['toDate'], time()+3600);
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
    <div class="formContainer">
        <h2>Search For Availability</h2>
        <form action="" method="get">
            <div class='label'>Dates:</div>
            <div class='field'><input type='text' name='fromDate' placeholder='From: YYYY-MM-DD'></div>
            <div class='field'><input type='text' name='toDate' placeholder='To: YYYY-MM-DD'></div>
            <div class='label'>Number of Guests:</div>
            <div class='field'>
                <select name='sleeps'>
                    <option value=''>-Sleeps-</option>
                    <option value='1'>1</option>
                    <option value='2'>2</option>
                    <option value='3'>3</option>
                    <option value='4'>4</option>
                    <option value='5'>5</option>
                    <option value='6'>6</option>
                    <option value='7'>7</option>
                    <option value='8'>8</option>
                    <option value='9'>9</option>
                    <option value='10'>10</option>
                    <option value='11'>11</option>
                    <option value='12'>12</option>
                    <option value='13'>13</option>
                    <option value='14'>14</option>
                    <option value='15'>15</option>
                    <option value='16'>16</option>
                    <option value='17'>17</option>
                    <option value='18'>18</option>
                    <option value='19'>19</option>
                    <option value='20'>20</option>
                    <option value='21'>21</option>
                    <option value='22'>22</option>
                    <option value='23'>23</option>
                    <option value='24'>24</option>
                    <option value='25'>25</option>
                    <option value='26'>26</option>
                    <option value='27'>27</option>
                    <option value='28'>28</option>
                </select>
            </div>
            <div class='label'></div>
            <input type='Submit' name='Search' value='Search' class='btn'>
        </form>
    </div>
    <?php
    $reservedArray = array();
    if (empty($_GET['Search'])){
        $sql = "SELECT PID, name, pRate, npRate, rooms, sleeps, description, featured, image, pets FROM cabins WHERE featured=1 ORDER BY name ASC";
        $stmt = $conn->stmt_init();
        if ($stmt->prepare($sql)){
            echo "<h1>Featured Listings:</h1>";
            $stmt->execute();
            $stmt->store_result();
            $numRows = $stmt->num_rows();
            if ($numRows == 0){
                echo "<h3>Currently there are no featured listings... Please search...";
            }
            $stmt->bind_result($PID, $cabinName, $pRate, $npRate, $rooms, $sleeps, $description, $featured, $image, $pets);
            while ($stmt->fetch()){
                echo "<table class='cabinContainer>'";
                echo "<tr><td>";
                echo "<a href='moreDetails.php?PID=$PID'><img src='$image' alt='Beavers Bend Luxury Cabin Rentals | $cabinName' class='cabinPic'></a>";
                echo "</td><td>";
                echo "<h2 class='cabinName'>$cabinName</h2>";
                echo "<div class='information'>\nRooms: $rooms\nSleeps: $sleeps\nPriced From: \$$npRate a night</div>";
                echo "<div class='description'>$description ... &nbsp; <a href='moreDetails.php?PID=$PID'>More Details</a></div>";
                echo "</td></tr></table>";
            }
            $stmt->close();
        }
    }
    if(!empty($_GET['fromDate']) && !empty($_GET['toDate']) && !empty($_GET['sleeps'])){
        $people = intval($_GET['sleeps']);
        $from = $_GET['fromDate'];
        $to = $_GET['toDate'];
        $sql = "SELECT PID FROM reservations WHERE NOT (? > dateFrom OR ? < dateTo)";
        #$sql = "SELECT dateFrom, dateTo, PID, status FROM reservations WHERE dateFrom >= STR_TO_DATE('$fromDate', '%d,%m,%Y') AND dateTo <= STR_TO_DATE('$toDate', '%d,%m,%Y') ORDER BY PID ASC";
        $stmt = $conn->stmt_init();
        if ($stmt->prepare($sql)){
            $stmt->bind_param('ss', $from, $to);
            $stmt->execute();
            $stmt->bind_result($PID);
            while ($stmt->fetch()){
                array_push($reservedArray, $PID);
            }
            $stmt->close();
        }
        $sql = "SELECT PID, name, pRate, npRate, rooms, sleeps, description, featured, image, pets FROM cabins ORDER BY name ASC";
        $stmt = $conn->stmt_init();
        if ($stmt->prepare($sql)){
            echo "<h1>Available Listings:</h1>";
            $stmt->execute();
            $stmt->bind_result($PID, $cabinName, $pRate, $npRate, $rooms, $sleeps, $description, $featured, $image, $pets);
            while ($stmt->fetch()){
                if (in_array($PID,$reservedArray)){
                    continue;
                }elseif($sleeps >= $people && $sleeps <= $people + 5){
                    echo "<table class='cabinContainer>'";
                    echo "<tr><td>";
                    echo "<a href='moreDetails.php?PID=$PID'><img src='$image' alt='Beavers Bend Luxury Cabin Rentals | $cabinName' class='cabinPic'></a>";
                    echo "</td><td>";
                    echo "<h2 class='cabinName'>$cabinName</h2>";
                    echo "<div class='information'>\nRooms: $rooms\nSleeps: $sleeps Priced From: \$$npRate a night</div>";
                    echo "<div class='description'>$description ... &nbsp; <a href='moreDetails.php?PID=$PID'>More Details</a></div>";
                    echo "</td></tr></table>";
                }
            }
        }
    }
    ?>

</main>
<footer>
    &copy; Copyright 2018 Beavers Bend Luxury Cabins &nbsp;| &nbsp; <a href='login.php'>Admin Login</a>
</footer>
</body>
</html>