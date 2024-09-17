
<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

//------------------------SAVE--------------------------------------------------

if(isset($_POST['save'])){
  $firstName = $_POST['firstName'];
  $lastName = $_POST['lastName'];
  $classId = $_POST['classId'];
  $dateCreated = date("Y-m-d");

  $query = mysqli_query($conn, "INSERT INTO tblstudents(firstName, lastName, password, classId, dateCreated) 
                                VALUES('$firstName', '$lastName', '12345', '$classId',   DATE_FORMAT(NOW(), '%d/%m/%Y '))");

  if ($query) {
    $studentId = mysqli_insert_id($conn);
    
    if(isset($_POST['classArmsId']) && is_array($_POST['classArmsId'])){
      foreach($_POST['classArmsId'] as $classArmId){
        $insertStudentClassArmQuery = mysqli_query($conn, "INSERT INTO tblstudent_classarms (studentId, classArmId) VALUES ('$studentId', '$classArmId')");
        if(!$insertStudentClassArmQuery){
          $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error occurred while assigning class arms!</div>";
          break;
        }
      }
    }
    
    $statusMsg = "<div class='alert alert-success'  style='margin-right:700px;'>Created Successfully!</div>";
  }
  else {
    $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
  }
}

//---------------------------------------EDIT-------------------------------------------------------------






//--------------------EDIT------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
  $Id = $_GET['Id'];

  $query = mysqli_query($conn, "SELECT * FROM tblstudents WHERE Id ='$Id'");
  $row = mysqli_fetch_array($query);

  if(isset($_POST['update'])){
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $classId = $_POST['classId'];

    $query = mysqli_query($conn, "UPDATE tblstudents SET firstName='$firstName', lastName='$lastName',
                                  password='12345', classId='$classId' WHERE Id='$Id'");
    
    if ($query) {
      // First, remove all existing class arm assignments
      mysqli_query($conn, "DELETE FROM tblstudent_classarms WHERE studentId='$Id'");
      
      // Then, add new class arm assignments
      if(isset($_POST['classArmsId']) && is_array($_POST['classArmsId'])){
        foreach($_POST['classArmsId'] as $classArmId){
          $insertStudentClassArmQuery = mysqli_query($conn, "INSERT INTO tblstudent_classarms (studentId, classArmId) VALUES ('$Id', '$classArmId')");
          if(!$insertStudentClassArmQuery){
            $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error occurred while updating class arms!</div>";
            break;
          }
        }
      }
      
      echo "<script type = \"text/javascript\">
      window.location = (\"createStudents.php\")
      </script>"; 
    }
    else {
      $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
    }
  }
}

//--------------------------------DELETE------------------------------------------------------------------

 /* if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "delete")
	{
        $Id= $_GET['Id'];
        $classArmId= $_GET['classArmId'];

        $query = mysqli_query($conn,"DELETE FROM tblstudents WHERE Id='$Id'");

        if ($query == TRUE) {

            echo "<script type = \"text/javascript\">
            window.location = (\"createStudents.php\")
            </script>";
        }
        else{

            $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>"; 
         }
      
  } */

        if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "delete") {
          $Id = $_GET['Id'];
        
          // First, delete the student's class arm assignments
          $deleteClassArmsQuery = mysqli_query($conn, "DELETE FROM tblstudent_classarms WHERE studentId='$Id'");
        
          // Then, delete the student
          $query = mysqli_query($conn, "DELETE FROM tblstudents WHERE Id='$Id'");
        
          if ($query == TRUE) {
            echo "<script type = \"text/javascript\">
            window.location = (\"createStudents.php\")
            </script>";
          }
          else {
            $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>"; 
          }
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
<?php include 'includes/title.php';?>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
  <link href="css/delete.css" rel="stylesheet">




   <script>
    function classArmDropdown(classId) {
    if (classId === "") {
        $("#txtHint").html("");
        return;
    }
    var studentId = <?php echo isset($Id) ? $Id : 'null'; ?>;
    $.ajax({
        url: "ajaxStudentClassArms.php",
        type: "GET",
        data: { classId: classId, studentId: studentId },
        success: function(response) {
            $("#txtHint").html(response);
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", status, error);
            $("#txtHint").html("An error occurred while fetching class arms.");
        }
    });
}
</script>
</head>

<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
      <?php include "Includes/sidebar.php";?>
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
       <?php include "Includes/topbar.php";?>
        <!-- Topbar -->

        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Create Students</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create Students</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Create Students</h6>
                    <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                <form method="post">
                  <div class="form-group row mb-3">
                    <div class="col-xl-6">
                      <label class="form-control-label">First Name<span class="text-danger ml-2">*</span></label>
                      <input type="text" class="form-control" name="firstName" value="<?php echo $row['firstName'];?>" required>
                    </div>
                    <div class="col-xl-6">
                      <label class="form-control-label">Last Name<span class="text-danger ml-2">*</span></label>
                      <input type="text" class="form-control" name="lastName" value="<?php echo $row['lastName'];?>" required>
                    </div>
                  </div>
                  <div class="form-group row mb-3">
                    <div class="col-xl-6">
                      <label class="form-control-label">Select Class<span class="text-danger ml-2">*</span></label>
                      <select required name="classId" onchange="classArmDropdown(this.value)" class="form-control mb-3">
                        <option value="">--Select Class--</option>
                        <?php
                        $qry= "SELECT * FROM tblclass ORDER BY className ASC";
                        $result = $conn->query($qry);
                        $num = $result->num_rows;		
                        if ($num > 0){
                          while ($rows = $result->fetch_assoc()){
                            echo '<option value="'.$rows['Id'].'" '.($row['classId'] == $rows['Id'] ? 'selected' : '').'>'.$rows['className'].'</option>';
                          }
                        }
                        ?>
                      </select>
                    </div>
                    <div class="col-xl-6">
                      <label class="form-control-label">Class Arms<span class="text-danger ml-2">*</span></label>
                      <div id="txtHint"></div>
                    </div>
                  </div>
                  <?php
                  if (isset($Id)){
                    echo '<button type="submit" name="update" class="btn btn-warning">Update</button>';
                  } else {           
                    echo '<button type="submit" name="save" class="btn btn-primary">Save</button>';
                  }
                  ?>
                </form>
                </div>
              </div>

              <!-- Input Group -->
                 <div class="row">
              <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">All Student</h6>
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                    <thead class="thead-light">
                      <tr>
                        <th>#</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Class</th>
                        <th>Class Arm</th>
                        <th>Date Created</th>
                         <th>Edit</th>
                        <th>Delete</th>
                      </tr>
                    </thead>
                
                    <tbody>

                  <?php
                      $query = "SELECT DISTINCT s.Id, s.firstName, s.lastName, s.dateCreated,
                      c.className,
                      GROUP_CONCAT(ca.classArmName SEPARATOR ', ') as classArms
                      FROM tblstudents s
                      LEFT JOIN tblclass c ON c.Id = s.classId
                      LEFT JOIN tblstudent_classarms sca ON sca.studentId = s.Id
                      LEFT JOIN tblclassarms ca ON ca.Id = sca.classArmId
                      GROUP BY s.Id
                      ORDER BY s.dateCreated DESC";
          
                      $rs = $conn->query($query);
                      $num = $rs->num_rows;
                      $sn=0;
                      $status="";
                      if($num > 0)
                      { 
                        while ($rows = $rs->fetch_assoc())
                          {
                             $sn = $sn + 1;
                            echo"
                              <tr>
                                <td>".$sn."</td>
                                <td>".$rows['firstName']."</td>
                                <td>".$rows['lastName']."</td>
                                <td>".$rows['className']."</td>
                                <td>".$rows['classArms']."</td>
                                 <td>".$rows['dateCreated']."</td>
                                <td><a href='?action=edit&Id=".$rows['Id']."'><img src='img/new logo initial version/Edit.png' style='width:33px;'></a></td>
                                <td><a href='#' onclick='openDeletePopup(\"{$rows['firstName']}\",\"{$rows['lastName']}\",\"{$rows['Id']}\")'><img src='img/new logo initial version/delete.png' style='width:20px; padding-top:5px;'></a></td>
                              </tr>";
                          }
                      }
                      else
                      {
                           echo   
                           "<div class='alert alert-danger' role='alert'>
                            No Record Found!
                            </div>";
                      }
                      
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            </div>
          </div>
          <!--Row-->

          <!-- Documentation Link -->
          <!-- <div class="row">
            <div class="col-lg-12 text-center">
              <p>For more documentations you can visit<a href="https://getbootstrap.com/docs/4.3/components/forms/"
                  target="_blank">
                  bootstrap forms documentations.</a> and <a
                  href="https://getbootstrap.com/docs/4.3/components/input-group/" target="_blank">bootstrap input
                  groups documentations</a></p>
            </div>
          </div> -->

        </div>
        <!---Container Fluid-->
      </div>
      <!-- Footer -->
       <?php include "Includes/footer.php";?>
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
   <!-- Page level plugins -->
  <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>




  <div id='deleteConfirmationPopup' class='popup-overlay' style='display:none'>
        <div class='popup'>
            <div class='popup-header' >
                Delete Confirmation
            </div>
            <div class='popup-body'>
                <text>Are you sure you would like to delete the teacher <b id="itemType"></b>?<br> The action cannot be undone. </text>
            </div>
            <div class='popup-footer'>
            
                <button class='btn-delete' id="confirmDeleteButton" >Delete</button>
                <button class='btn-cancel' onclick='closePopup()'>Cancel</button>
            </div>
        </div>
</div>

  <!-- Page level custom scripts -->
  <script>
    $(document).ready(function () {
      $('#dataTable').DataTable(); // ID From dataTable 
      $('#dataTableHover').DataTable(); // ID From dataTable with Hover
    });

    function closePopup() {
        document.getElementById('deleteConfirmationPopup').style.display = 'none';
    }

    function openDeletePopup(firstName, lastName, classId) {
        document.getElementById('itemType').textContent = firstName+' ' + lastName;
        document.getElementById('confirmDeleteButton').setAttribute('data-item-id', classId);
        document.getElementById('deleteConfirmationPopup').style.display = 'block';
    }

    document.getElementById('confirmDeleteButton').addEventListener('click', function() {
        let itemId = this.getAttribute('data-item-id');
        window.location.href = "?action=delete&Id=" + itemId;
    });

    

  </script>
</body>

</html>