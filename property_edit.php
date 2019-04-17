<?php
include("access.php");
include ("dbconn.inc.php");
$conn = dbConnect();
?>

<?php
    if (isset($_POST['Submit'])){
        $required = array("image", "propName", "primeRate", "nonPrimeRate", "roomNum", "sleepNum", "description", "propOwner");
        $expected = array("PID", "image", "propName", "primeRate", "nonPrimeRate", "roomNum", "sleepNum", "description", "featured", "petAllowed", "propOwner");

        $label= array("PID" => "Property ID", "image"=>"Image", "propName"=>"Property Name", "primeRate"=>"Prime Rate", "nonPrimeRate"=>"Non-Prime Rate", "roomNum"=>"Number of Rooms", "sleepNum"=>"Sleeps Up To", "description"=>"Description", "featured"=>"Featured", "petAllowed"=>"Pets Allowed", "propOwner"=>"Property Owner");

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
            if ($pid != ""){
                $pid = intval($pid);
                $sql = "UPDATE cabins SET name = ?, pRate = ?, npRate = ?, rooms = ?, sleeps = ?, description = ?, featured = ?, image = ?, ownerID = ?, pets = ?";

                if ($stmt->prepare($sql)){
                    $stmt->bind_param('sddiisisii', $propName, $primeRate, $nonPrimeRate, $roomNum, $sleepNum, $description, $featured, $image, $propOwner, $petAllowed);
                    $stmt_prepared = 1;
                }
            } else{
                $sql = "INSERT INTO cabins (name, pRate, npRate, rooms, sleeps, description, featured, image, ownerID, pets) values (?,?,?,?,?,?,?,?,?,?)";
                if ($stmt->prepare($sql)){
                    $stmt->bind_param('sddiisisii',$propName, $primeRate, $nonPrimeRate, $roomNum, $sleepNum, $description,$featured, $image, $propOwner, $petAllowed);
                    $stmt_prepared = 1;
                }
            }
            if ($stmt_prepared == 1){
                if ($stmt->execute()){
                    $output = "<span>Success!</span><p>The following informationhas been saved in the database:</p>";
                    foreach($expected as $key){
                        $output .= "<b>" . key($key) ."</b>: {$_POST[$key]}<br>";
                    }
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
        $output = "<div>Please begin your property management operation from the <a href='properties.php'>Property Managment Page</a>.</div>";
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
            <h3>Property Management</h3>
            <?=$output?>
        </div>
        <div class="aside">
            <h3>Properties Sub-Menu</h3>
            <a href="./propertyForm.php"> <button class=subPropBTN>Add Property</button></a>
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