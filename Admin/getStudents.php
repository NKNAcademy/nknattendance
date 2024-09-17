<?php
include '../Includes/dbcon.php';
$classId = $_POST['classId'];
$classArmId = $_POST['classArmId'];

$studentQuery = "SELECT * FROM tblstudents WHERE classId = '$classId' AND classArmId = '$classArmId' ORDER BY firstName ASC";
$studentResult = $conn->query($studentQuery);

echo '<option value="">--Select Student--</option>';
while ($studentRow = $studentResult->fetch_assoc()) {
    echo '<option value="'.$studentRow['Id'].'">'.$studentRow['firstName'].' '.$studentRow['lastName'].'</option>';
}
?>
