<?php
include '../Includes/dbcon.php';

$classId = intval($_GET['classId']);
$studentId = isset($_GET['studentId']) ? intval($_GET['studentId']) : null;

$query = "SELECT ca.*, CASE WHEN sca.studentId IS NOT NULL THEN 1 ELSE 0 END as isAssigned 
          FROM tblclassarms ca
          LEFT JOIN tblstudent_classarms sca ON ca.Id = sca.classArmId AND sca.studentId = '$studentId'
          WHERE ca.classId = $classId";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$count = mysqli_num_rows($result);

if ($count > 0) {
    while ($row = mysqli_fetch_array($result)) {
        echo '<div class="form-check">';
        echo '<input class="form-check-input" type="checkbox" name="classArmsId[]" value="'.$row['Id'].'" '.($row['isAssigned'] ? 'checked' : '').'>';       
        echo '<label class="form-check-label">'.$row['classArmName'].'</label>';
        echo '</div>';
    }
} else {
    echo 'No class arms available.';
}
?>

