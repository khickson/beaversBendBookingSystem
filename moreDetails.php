<?php
setcookie("PID", $_GET['PID']);
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
    <div class="subnav"><a href="./frontEnd.php">&lt;&lt;Back</a></div>
    <hr>
    <?php

        //============================================
        // Cabin Name
        //============================================
        if (!empty($_GET['PID']) && is_numeric($_GET['PID'])){
            $PID = intval($_GET['PID']);

            $sql = "SELECT name FROM cabins WHERE PID = ?";
            $stmt = $conn->stmt_init();
            if ($stmt->prepare($sql)){
                $stmt->bind_param('i', $PID);
                $stmt->execute();
                $stmt->bind_result($cabinName);
                if ($stmt->fetch()){
                    echo "<h1>$cabinName</h1>";
                }
            }
            $stmt->close();
        //============================================
        // Image Gallery
        //============================================

            echo "<div class='galleryContainer'>";
            $sql = "SELECT URL, caption FROM images WHERE PID = ?";
            $stmt = $conn->stmt_init();
            if ($stmt->prepare($sql)){
                $stmt->bind_param('i', $PID);
                $stmt->execute();
                $stmt->store_result();
                $numRows = $stmt->num_rows();
                $stmt->bind_result($URL, $caption);
                echo "<div class='carousel'>";
                $count=1;
                $count2=0;
                while($stmt->fetch()){
                    if ($count==1) {
                        $checked = "checked";
                        echo "\n<input id='image$count' type='radio' name='image-selector' checked='$checked'>";
                        echo "\n<label for='image$numRows'>View Image $numRows</label>";
                        echo "\n<img src='$URL' alt='$caption'>";
                    }else{
                        $checked = "";
                        echo "\n<input id='image$count' type='radio' name='image-selector' checked='$checked'>";
                        echo "\n<label for='image$count2'>View Image $count2</label>";
                        echo "\n<img src='$URL' alt='$caption'>";
                    }
                    $count++;
                    $count2++;
                }
                echo "\n<label for='image$numRows'>View image $numRows</label> <label for='image1'>View image 1</label>";
                echo "\n</div>";
            }
            $stmt->close();

        //============================================
        // Cabin Information
        //============================================

            $sql = "SELECT name, pRate, npRate, rooms, sleeps, pets FROM cabins WHERE PID = ?";
            $stmt = $conn->stmt_init();
            if ($stmt->prepare($sql)){
                $stmt->bind_param('i', $PID);
                $stmt->execute();
                $stmt->bind_result($name,$pRate, $npRate, $rooms, $sleeps, $pets);
                if ($stmt->fetch()){
                    echo "<table class='cabinInfo'>";
                    echo "<tr>
                <td>
                    <h2>Price from \$ $npRate per night</h2>
                </td>
            </tr>
            <tr>
                <td>Rooms:</td>";

                    if ($rooms == 0){
                        echo "<td>Value Cabin</td>";
                    }else{
                        echo "<td>$rooms</td>";
                    }

                    echo"</tr>
            <tr>
                <td>Sleeps:</td>
                <td>$sleeps</td>
            </tr>
            <tr>
                <td>Pets allowed:</td>";
                    if ($pets == 1){
                        echo"<td>Yes</td>";
                    }else{
                        echo"<td>No</td>";
                    }
                    echo "
            </tr>
            <tr>
                <td>Weekday Price (Mon-Weds):</td>
                <td>\$$npRate</td>
            </tr>
            <tr>
                <td>Weekend/Holiday Price (Thurs-Sun):</td>
                <td>\$$pRate</td>
            </tr>";
                    if ($rooms == 0){
                        echo "<tr><td>Value cabins do not have hot tubs</td></tr>";
                    }
                    echo "</table>";
                    echo "\n</div>";
                }
                }
            $stmt->close();

        //============================================
        // Handle Cookie
        //============================================

            $fromDate=$_COOKIE['fromdate'];
            $toDate=$_COOKIE['todate'];

            echo "<div class=\"galleryContainer\">
            <div class=\"bookingForm\">
                <h3>Make a reservation:</h3>
                <form action=\"./booking_request.php\" method=\"post\">
                    <div class=\"label\">Dates Requested</div>
                    From: <div class=\"field\"><input type=\"text\" value=\"$fromDate\" name=\"beginDate\"></div>
                    To: <div class=\"field\"><input type=\"text\" value=\"$toDate\" name=\"endDate\"></div>
                    <div class=\"label\">Name</div>
                    <div class=\"fieldFull\"><input type=\"text\" name=\"fullName\"></div>
                    <div class=\"label\">E-Mail</div>
                    <div class=\"fieldFull\"><input type=\"text\" name=\"email\"></div>
                    <div class=\"label\">Phone</div>
                    <div class=\"fieldFull\"><input type=\"text\" class=\"fieldFull\" name=\"phone\"></div>
                    <div class=\"label\">Additional Requirements</div>
                    <div class=\"fieldFull\"><textarea name=\"additional\"></textarea></div>
                    <div class='label'></div>
                    <input type='Submit' name='Submit' value='Submit' class='btn'>
                </form>
            </div>
        ";

            //============================================
            // Full Details
            //============================================
            echo "<div class='fullDetails'>";
            echo "<h3>Full Details</h3>";
            $sql = "SELECT fullDetails FROM detailPage WHERE PID = ?";
            $stmt = $conn->stmt_init();
            if ($stmt->prepare($sql)){
                $stmt->bind_param('i', $PID);
                $stmt->execute();
                $stmt->bind_result($info);
                if ($stmt->fetch()){
                    echo "$info";
                }
            }
            $stmt->close();
            echo "</div></div>";
        }
        $conn->close();
    ?>
</main>
<footer>
    &copy; Copyright 2018 Beavers Bend Luxury Cabins &nbsp;| &nbsp; <a href='login.php'>Admin Login</a>
</footer>
</body>
</html>