<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../Includes/dbcon.php';
include '../Includes/session.php';

// Debugging: Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}




?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="img/logo/attnlg.jpg" rel="icon">
  <?php include 'Includes/title.php'; ?>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
  <link href="css/delete.css" rel="stylesheet">
</head>

<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
    <?php include 'Includes/sidebar.php'; ?>
    <!-- Sidebar -->

    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
        <?php include 'Includes/topbar.php'; ?>
        <!-- Topbar -->

        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Manage Sessions</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Manage Sessions</li>
            </ol>
          </div>

          <!-- Filters Form -->
     

          <!-- Sessions Table -->
          <div class="row">
            <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Sessions</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive p-3">
                        <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Session Name</th>
                                    <th>Week</th>
                                    <th>Month</th>
                                    <th>Year</th>
                                    <th>Teachers</th>
                                    <th>Student Count</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                  // Calculate current week and month dynamically
                                  $currentMonth = date('F'); // Current month name (e.g., September)
                                  $currentYear = date('Y'); // Current year (e.g., 2024)
                                  $dayOfMonth = date('j'); // Day of the month (1–31)
                                  $week = ceil($dayOfMonth / 7); // Week of the month (1–4)


                                  // Query to get the sessions
                                  $query = "
                                  SELECT 
                                      st.Id, 
                                      st.classArm, 
                                      st.classArmId, 
                                      '$week' as week, 
                                      '$currentMonth' as month, 
                                      '$currentYear' as year,
                                      GROUP_CONCAT(DISTINCT CONCAT_WS(' ', t.firstName, t.lastName) ORDER BY t.lastName SEPARATOR ', ') AS teachers,
                                      COUNT(DISTINCT sca.studentId) AS student_count
                                  FROM 
                                      tblsessionterm st
                                  LEFT JOIN 
                                      tblteacherclassarms tca ON st.classArmId = tca.classArmId
                                  LEFT JOIN 
                                      tblclassteacher t ON tca.teacherId = t.Id
                                  LEFT JOIN 
                                      tblstudent_classarms sca ON st.classArmId = sca.classArmId
                                  GROUP BY 
                                      st.Id, st.classArm, st.classArmId, st.week, st.month, st.year
                                  ORDER BY 
                                      st.year, st.month, st.week, st.classArm
                                  ";

                                  // Debugging: Print the query

                                  // Execute query
                                  $rs = $conn->query($query);

                                  if (!$rs) {
                                  die('Error executing query: ' . $conn->error);
                                  }
                                $sn = 0; 
                                if ($rs->num_rows > 0) {
                                    while ($row = $rs->fetch_assoc()) {
                                        $sn++;
                                        echo "
                                        <tr>
                                            <td>$sn</td>
                                            <td>{$row['classArm']}</td>
                                            <td>{$row['week']}</td>
                                            <td>{$row['month']}</td>
                                            <td>{$row['year']}</td>
                                            <td>{$row['teachers']}</td>
                                            <td>{$row['student_count']}</td>
                                            <td><a href='createStudents.php?sessionId={$row['classArmId']}' class='btn btn-sm btn-info'>Show List</a></td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='8' class='text-center'>No Record Found!</td></tr>";
                                } 
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
        </div>
        <!---Container Fluid-->
      </div>
      <!-- Footer -->
      <?php include 'Includes/footer.php'; ?>
      <!-- Footer -->
    </div>
  </div>

  <!-- Scroll to top -->
  <a class="scroll-to-top rounded" href="#page-top" >
  <img src="img/new logo initial version/Up.png" width="25px" >
  </a>

 

  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
  <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script>
    //$(document).ready(function () {
    //  $('#dataTableHover').DataTable(); // ID From dataTable with Hover
    //});

    $(document).on("click", ".delete", function(e) {
      var link = $(this).attr("href");
      e.preventDefault();
      if(confirm("Are you sure you want to delete this student?")) {
        window.location.href = link;
      }
    });
  </script>
</body>

</html>

