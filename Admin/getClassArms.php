<?php
include '../Includes/dbcon.php';
$classId = $_POST['classId'];

$classArmQuery = "SELECT * FROM tblclassarms WHERE classId = '$classId' ORDER BY classArmName ASC";
$classArmResult = $conn->query($classArmQuery);

echo '<option value="">--Select Class Arm--</option>';
while ($classArmRow = $classArmResult->fetch_assoc()) {
    echo '<option value="'.$classArmRow['Id'].'">'.$classArmRow['classArmName'].'</option>';
}
?>
