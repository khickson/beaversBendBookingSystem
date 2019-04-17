<?php
include("access.php");
error_reporting(0);
include ("dbconn.inc.php");
$conn = dbConnect();

if (array_key_exists('upload', $_POST)) {
    $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
    $detectedType = exif_imagetype($_FILES['image']['tmp_name']);
    $error = !in_array($detectedType, $allowedTypes);

    if ($error == True) {
        echo "Invalid format... Please try again...";
    } else {
        define('UPLOAD_DIR', '/home/khhutacl/ctec4321.khh9106.uta.cloud/termProject/images/cabinIcons/');

        $filename = trim(str_replace(" ","_", $_FILES['image']['name']));
        $_FILES['image']['name'] = $filename;
        // move the file to the upload folder and rename it
        if (move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_DIR . $_FILES['image']['name'])) {
            // upload successful
            $message = "The selected file has been successfully uploaded.";
        } else {
            // something is wrong
            $message = "We have encountered issues in uploading this file.  Please try again later or contact the web master. ";
        }
    }
}
?>

<?php
if(isset($_FILES['image']) && !empty($_FILES['image'])) {
    $file = $_FILES['image']['name'];
    $imageURL = "http://ctec4321.khh9106.uta.cloud/termProject/images/cabinIcons/$file";
    $success = "<img src='http://ctec4321.khh9106.uta.cloud/termProject/images/cabinIcons/$file' alt='' class='successImg'>";
};
?>

<?php

if (isset($_GET['pid'])){
    $pid = intval($_GET['pid']);
    if ($pid > 0){
        $sql = "SELECT name, pRate, npRate, rooms, sleeps, description, featured, image, ownerID, pets from cabins WHERE PID = ?";

        $stmt = $conn->stmt_init();
        if ($stmt->prepare($sql)){
            $stmt->bind_param('i', $pid);
            $stmt->execute();
            $stmt->bind_result($name, $pRate, $npRate, $rooms, $sleeps, $description, $featured, $image, $ownerID, $pets);
            $stmt->store_result();
            if ($stmt->num_rows==1){
                $stmt->fetch();
            } else{
                $errMsg = "<div>Information on the record you requested is not available.  If it is an error, please contact the webmaster.  Thank you.</div>";
                $pid = ""; // reset $pid
            }
        }else {
            // reset $pid
            $pid = "";
            // compose an error message
            $errMsg = "<div> If you are expecting to edit an exiting item, there are some error occured in the process -- the selected product is not recognizable.  Please follow the link below to the product adminstration interface or contact the webmaster.  Thank you.</div>";
        }
        $stmt->close();
    }
}
?>

<?php

function OwnerOptionList($selectedownerID){
    $list = "";
    global $conn;
    $sql = "SELECT ownerID, fName, lName FROM owners ORDER BY fName";
    $stmt = $conn->stmt_init();
    if ($stmt->prepare($sql)){
        $stmt->execute();
        $stmt->bind_result($ownerID, $fName, $lName);
        while ($stmt->fetch()){
            if ($ownerID == $selectedownerID){
                $selected = "selected";
            } else{
                $selected = "";
            }
            $list = $list."<option value='$ownerID' $selected>$fName $lName</option>";
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
            <?=$success?>
            <?php if(isset($_GET['pid'])){
                echo "<img src='$image' alt='' class='successImg'>";
            }?>
            <h4>Add A Property:</h4>
            <table>
            <tr>
                <td>Image:</td>
                <td>
                    <form action='' method='post' enctype='multipart/form-data' name='uploadImage' id='uploadImage'><input type='file' name='image' id='image'><input type='submit' name='upload' id='upload' value='Upload' />
                    </form>
                </td>
            </tr>
            <form action='property_edit.php' method='POST' class='PropForm'>
                <input type="hidden" name="PID" value="<?=$pid?>">
                <input type="hidden" name="image" value="<?=$imageURL?>">
                    <tr>
                        <td>Property Name:</td><td><input type='text' name='propName' value="<?=$name?>"></td>
                    </tr>
                    <tr>
                        <td>Prime Rate:</td><td><input type='text' name='primeRate' value="<?=$pRate?>"></td>
                    </tr>
                    <tr>
                        <td>Non-Prime Rate:</td><td><input type='text' name='nonPrimeRate' value="<?=$npRate?>"></td>
                    </tr>
                    <tr>
                        <td>Number of Rooms:</td><td><input type='text' name='roomNum' value="<?=$rooms?>"></td>
                    </tr>
                    <tr>
                        <td>Sleeps:</td><td><input type='text' name='sleepNum' value="<?=$sleeps?>"></td>
                    </tr>
                    <tr>
                        <td>Description:</td><td><textarea name='description' id='' cols='30' rows='10'><?=$description?></textarea></td>
                    </tr>
                    <tr>
                        <td>Featured:</td>
                        <td><select name='featured' class='drop'>
                                <option value="">Select One</option>
                                <option value="1">YES</option>
                                <option value="0">NO</option>
                            </select></td>
                    </tr>
                    <tr>
                        <td>Pets Allowed:</td>
                        <td><select name='petAllowed' class='drop'>
                                <option value="">Select One</option>
                                <option value="1">YES</option>
                                <option value="0">NO</option>
                            </select></td>
                    </tr>
                    <tr>
                        <td>Owner:</td>
                        <td>
                            <select name='propOwner' class='drop'>
                                <?= OwnerOptionList($ownerID) ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <input type='submit' name='Submit' Value='Add Property' class='subPropBTN'>
            </form>";
        </div>
        <div class="aside">
            <h3>Properties Sub-Menu</h3>
            <form action="" method="POST" class="subPropForm">
                <input type="submit" name="addProp" value="Add Property" class=subPropBTN>
            </form>
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