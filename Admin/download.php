<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

if(isset($_POST['dateTaken'])) {
    $dateTaken = $_POST['dateTaken'];
    $filename = "Attendance_list_" . $dateTaken;

    // Query to fetch attendance records for the selected date
    $record = mysqli_query($conn, "SELECT tblattendance.Id, tblattendance.status, tblattendance.date, 
        tblclassarms.classArmName, tblstudents.firstName, tblstudents.lastName
        FROM tblattendance
        INNER JOIN tblclassarms ON tblclassarms.Id = tblattendance.classArmId
        INNER JOIN tblstudents ON tblstudents.Id = tblattendance.studentId
        WHERE tblattendance.date = '$dateTaken'");

    if(mysqli_num_rows($record) > 0) {
        // Send headers to force download of Excel file
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=".$filename.".xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        // Table structure for Excel file
        echo "<table border='1'>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Class Arm</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>";

        $cnt = 1;
        while ($row = mysqli_fetch_array($record)) {
            // Set status based on enum value
            $status = ($row['status'] == 'present') ? "Present" : "Absent";

            echo "<tr>
                    <td>".$cnt."</td>
                    <td>".$row['firstName']."</td>
                    <td>".$row['lastName']."</td>
                    <td>".$row['classArmName']."</td>
                    <td>".$status."</td>
                    <td>".$row['date']."</td>
                </tr>";
            $cnt++;
        }

        echo "</tbody></table>";
    }
}
?>
