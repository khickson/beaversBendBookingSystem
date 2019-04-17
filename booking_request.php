<?php
include ("dbconn.inc.php");
$conn = dbConnect();
?>

<?php
$PID = $_COOKIE['PID'];
$status = 0;

if (isset($_POST['Submit'])){
    $required = array("beginDate", "endDate", "fullName", "email", "phone");
    $expected = array("beginDate", "endDate", "fullName", "email", "phone", "additional");
    $missing = array();

    foreach ($expected as $field){
        if (in_array($field, $required) && (!isset($_POST[$field]) || empty($_POST[$field]))){
            array_push($missing, $field);
        }else{
            if (!isset($_POST[$field])){
                ${$field}= "";
            }else{
                ${$field} = $_POST[$field];
            }
        }
    }
    if (empty($missing)){
        $stmt = $conn->stmt_init();
        if ($uid != ""){
        } else{
            $sql = "INSERT INTO reservations (name, dateFrom, dateTo, PID, status, email, phone, requirements) values (?,?,?,?,?,?,?,?)";
            if ($stmt->prepare($sql)){
                $stmt->bind_param('sssiisss', $fullName, $beginDate, $endDate, $PID, $status, $email, $phone, $additional);
                $stmt_prepared = 1;
            }
        }
        if ($stmt_prepared == 1){
            if ($stmt->execute()){
                $output = "<span>Success!</span><p>Thank you for booking with us! We look forward to reaching out to you within the next 24hrs regarding your booking. Talk to you soon!</p>";
            }else{
                $output = "<div>Database operation failed.  Please contact the webmaster.</div>";
            }
        }else{
            $output = "<div>Database query failed.  Please contact the webmaster.</div>";
        }
    } else{
        $output = "<div><p>The following required fields are missing in your form submission.  Please check your form again and fill them out.  <br>Thank you.<br>\n<ul>\n";
        foreach($missing as $m){
            $output .= "<li>{$label[$m]}\n";
        }
        $output .= "</ul></div>\n";
    }
} else{
    $output = "<div>Please begin your Booking Request operation from the <a href='frontEnd.php.php'>Book Now Page</a>.</div>";
}
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
    <?=$output?>
    <p>Return to this cabin's <a href="./moreDetails.php?PID=<?=$PID?>">More Details</a> Page</p>
</main>
<footer>
    &copy; Copyright 2018 Beavers Bend Luxury Cabins &nbsp;| &nbsp; <a href='login.php'>Admin Login</a>
</footer>
</body>
</html>