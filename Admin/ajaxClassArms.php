<?php
include '../Includes/dbcon.php';

//echo "Debug: classId = $cid, teacherId = $teacherId<br>";
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$cid = intval($_GET['classId']);
$teacherId = isset($_GET['teacherId']) ? intval($_GET['teacherId']) : null;

$query = "SELECT ca.*, CASE WHEN tca.teacherId IS NOT NULL THEN 1 ELSE 0 END as isAssigned 
          FROM tblclassarms ca
          LEFT JOIN tblteacherclassarms tca ON ca.Id = tca.classArmId AND tca.teacherId = '$teacherId'
          WHERE ca.classId = $cid";

$queryss = mysqli_query($conn, $query);

if (!$queryss) {
    die("Query failed: " . mysqli_error($conn));
}

$countt = mysqli_num_rows($queryss);

if ($countt > 0) {
    while ($row = mysqli_fetch_array($queryss)) {
        echo '<div class="form-check">';
        echo '<input class="form-check-input" type="checkbox" name="classArmsId[]" value="'.$row['Id'].'" '.($row['isAssigned'] ? 'checked' : '').'>';       
        echo '<label class="form-check-label">'.$row['classArmName'].'</label>';
        echo '</div>';
    }
} else {
    echo 'No class arms available.';
}
?>


