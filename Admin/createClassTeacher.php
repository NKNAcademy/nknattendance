
<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

//------------------------SAVE--------------------------------------------------

if (isset($_POST['save'])) {
  $firstName = $_POST['firstName'];
  $lastName = $_POST['lastName'];
  $emailAddress = $_POST['emailAddress'];
  $phoneNo = $_POST['phoneNo'];
  $classId = $_POST['classId'];
  $dateCreated = date("d-m-Y");
  $pass = $_POST['passwordd'];

  // Check if email already exists
  $checkEmail = mysqli_query($conn, "SELECT * FROM tblclassteacher WHERE emailAddress = '$emailAddress'");
  if(mysqli_num_rows($checkEmail) > 0) {
      $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>This Email Address Already Exists!</div>";
  } else {
      // Insert into tblclassteacher
      $insertTeacherQuery = "INSERT INTO tblclassteacher (firstName, lastName, emailAddress, passwordd, phoneNo, classId, dateCreated) 
                           VALUES ('$firstName', '$lastName', '$emailAddress', '$pass', '$phoneNo', '$classId', DATE_FORMAT(NOW(), '%d/%m/%Y '))";

      if (mysqli_query($conn, $insertTeacherQuery)) {
          $teacherId = mysqli_insert_id($conn);

          if (isset($_POST['classArmsId']) && is_array($_POST['classArmsId'])) {
              foreach ($_POST['classArmsId'] as $classArmId) {
                  $insertTeacherClassArmQuery = "INSERT INTO tblteacherclassarms (teacherId, classArmId) VALUES ('$teacherId', '$classArmId')";
                  mysqli_query($conn, $insertTeacherClassArmQuery);

                  // Update tblsessionterm to assign teacherId to the classArm
                  $updateSessionTermQuery = "UPDATE tblsessionterm SET teacher_id = '$teacherId' WHERE classArmId = '$classArmId'";
                  mysqli_query($conn, $updateSessionTermQuery);

                  $updateClassArmQuery = "UPDATE tblclassarms SET isAssigned = '1' WHERE Id = '$classArmId'";
                  mysqli_query($conn, $updateClassArmQuery);
              }
          }

          $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Created Successfully!</div>";
      } else {
          $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error occurred: " . mysqli_error($conn) . "</div>";
      }
  }
}


//--------------------EDIT------------------------------------------------------------
if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
  $Id = $_GET['Id'];

  $query = mysqli_query($conn, "SELECT * FROM tblclassteacher WHERE Id ='$Id'");
  $row = mysqli_fetch_array($query);

  if (isset($_POST['update'])) {
      $firstName = $_POST['firstName'];
      $lastName = $_POST['lastName'];
      $emailAddress = $_POST['emailAddress'];
      $phoneNo = $_POST['phoneNo'];
      $classId = $_POST['classId'];

      $query = mysqli_query($conn, "UPDATE tblclassteacher SET firstName='$firstName', lastName='$lastName',
          emailAddress='$emailAddress', phoneNo='$phoneNo', classId='$classId'
          WHERE Id='$Id'");

      if ($query) {
          // Unassign all previous class arms
          $unassignQuery = mysqli_query($conn, "DELETE FROM tblteacherclassarms WHERE teacherId='$Id'");
          if (!$unassignQuery) {
              $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error occurred while unassigning class arms: " . mysqli_error($conn) . "</div>";
          } else {
              // Assign the selected class arms
              if (!empty($_POST['classArmsId'])) {
                  foreach ($_POST['classArmsId'] as $classArmId) {
                      $queryArm = mysqli_query($conn, "INSERT INTO tblteacherclassarms (teacherId, classArmId) VALUES ('$Id', '$classArmId')");
                      if (!$queryArm) {
                          $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error occurred while reassigning class arms: " . mysqli_error($conn) . "</div>";
                          break;
                      }

                      // Update tblsessionterm with the new teacherId for class arms
                      $updateSessionTermQuery = mysqli_query($conn, "UPDATE tblsessionterm SET teacher_id = '$Id' WHERE classArmId = '$classArmId'");
                      if (!$updateSessionTermQuery) {
                          $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error occurred while updating session term: " . mysqli_error($conn) . "</div>";
                          break;
                      }
                  }
              }

              // Update isAssigned status in tblclassarms
              $updateAssignedQuery = mysqli_query($conn, "UPDATE tblclassarms SET isAssigned = CASE WHEN Id IN (SELECT classArmId FROM tblteacherclassarms WHERE teacherId='$Id') THEN '1' ELSE '0' END WHERE classId='$classId'");

              echo "<script type='text/javascript'>window.location = 'createClassTeacher.php'</script>";
          }
      } else {
          $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error occurred while updating the teacher: " . mysqli_error($conn) . "</div>";
      }
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
    var teacherId = <?php echo isset($Id) ? $Id : 'null'; ?>;
    $.ajax({
        url: "ajaxClassArms.php",
        type: "GET",
        data: { classId: classId, teacherId: teacherId },
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

<style>
        /* Include the CSS provided in the previous response here */
        .form-check {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            border: #333;
        }

        .form-check-input {
            width: 20px;
            height: 20px;
            margin-right: 10px;
            cursor: pointer;
            accent-color: #4e73df; 
            border-radius: 3px;
            border: 2px solid #ddd;
            transition: background-color 0.3s ease;
        }

        .form-check-input:checked {
            background-color: #4e73df;
            border-color: #4e73df;
        }

        .form-check-label {
            font-size: 16px;
            color: #333;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .form-check-label:hover {
            color: #4e73df;
        }

        .form-check-input:hover {
            border-color: #4e73df;
        }
    </style>

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
            <h1 class="h3 mb-0 text-gray-800">Create Class Teachers</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create Class Teachers</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Create Class Teachers</h6>
                    <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
        <form method="post">
                   <div class="form-group row mb-3">
                        <div class="col-xl-6">
                        <label class="form-control-label">Firstname<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" required name="firstName" value="<?php echo $row['firstName'];?>" id="exampleInputFirstName">
                        </div>
                        <div class="col-xl-6">
                        <label class="form-control-label">Lastname<span class="text-danger ml-2">*</span></label>
                      <input type="text" class="form-control" required name="lastName" value="<?php echo $row['lastName'];?>" id="exampleInputFirstName" >
                        </div>
                    

                    <div class="col-xl-6">
                        <label class="form-control-label">Phone No<span class="text-danger ml-2">*</span></label>
                      <input type="text" class="form-control" name="phoneNo" value="<?php echo $row['phoneNo'];?>" id="exampleInputFirstName" >
                    </div>

                    <div class="col-xl-6">
                      <label for="gender" class="form-label">Gender</label>
                      <select name="gender" id="gender" class="form-control mb-3" required>
                          <option value="">--Select Gender--</option>
                          <option value="Male">Male</option>
                          <option value="Female">Female</option>
                          <option value="Other">Other</option>
                      </select>
                  </div>
              </div>   

                    <div class="form-group row mb-3">
                        <div class="col-xl-6">
                        <label class="form-control-label">Email Address<span class="text-danger ml-2">*</span></label>
                        <input type="email" class="form-control" required name="emailAddress" value="<?php echo $row['emailAddress'];?>" id="exampleInputFirstName" >
                        </div>

                        <div class="col-xl-6">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="passwordd" id="password" value="<?php echo $row['passwordd'] ?>" class="form-control mb-3" placeholder="Enter password" required>
                        </div>

                     
                    </div>
                   <!-- <div class="form-group row mb-3">
                        <div class="col-xl-6">
                        <label class="form-control-label">Select Class<span class="text-danger ml-2">*</span></label>
                       
                       <?php /*
                        $qry= "SELECT * FROM tblclass ORDER BY className ASC";
                        $result = $conn->query($qry);
                        $num = $result->num_rows;		
                        if ($num > 0){
                          echo ' <select required name="classId" onchange="classArmDropdown(this.value)" class="form-control mb-3">';
                          echo'<option value="">--Select Class--</option>';
                          while ($rows = $result->fetch_assoc()){
                          echo'<option value="'.$rows['Id'].'" >'.$rows['className'].'</option>';
                              }
                                  echo '</select>';
                              }
                           */ ?>  
                        </div>
                        <div class="col-xl-6">
                        <label class="form-control-label">Class Arm<span class="text-danger ml-2">*</span></label>
                            <?php
                              //  echo"<div id='txtHint'></div>";
                            ?>
                        </div>
                    </div> 
                            -->

                       <div class="form-group row mb-3">
                      <div class="col-xl-6">
                     <label class="form-control-label">Select Class<span class="text-danger ml-2">*</span></label>
                       <select required name="classId" onchange="classArmDropdown(this.value)" class="form-control mb-3">
                      <option value="">--Select Class--</option>
                      <?php
                      $qry = "SELECT * FROM tblclass ORDER BY className ASC";
                      $result = $conn->query($qry);
                      if ($result->num_rows > 0) {
                          while ($row = $result->fetch_assoc()) {
                              echo "<option value='".$row['Id']."'>".$row['className']."</option>";
                          }
                      }
                      ?>
                          </select>
                        </div>
                        <div class="col-xl-6">
                            <label class="form-control-label">Class Arm<span class="text-danger ml-2">*</span></label>
                            <div id="txtHint" ></div> <!-- This will be populated with checkboxes -->
                            
                      </div>
                      </div>

                      <?php
                    if (isset($Id))
                    {
                      $classArmQuery = mysqli_query($conn, "SELECT ca.Id, ca.classArmName, CASE WHEN tca.teacherId IS NOT NULL THEN 1 ELSE 0 END as isAssigned 
                      FROM tblclassarms ca
                      LEFT JOIN tblteacherclassarms tca ON ca.Id = tca.classArmId AND tca.teacherId = '$Id'
                      WHERE ca.classId = '{$row['classId']}'");
                      while($armRow = mysqli_fetch_array($classArmQuery)) {
                      echo '<div class="form-check">';
                      echo '<input class="form-check-input" type="checkbox" name="classArmsId[]" value="'.$armRow['Id'].'" '.($armRow['isAssigned'] ? 'checked' : '').'>';       
                      echo '<label class="form-check-label">'.$armRow['classArmName'].'</label>';
                      echo '</div>';
                      }
                    }         
                    ?>

                    <?php
                      if (isset($Id)) {
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
                  <h6 class="m-0 font-weight-bold text-primary">All Class Teachers</h6>
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                    <thead class="thead-light">
                      <tr>
                        <th>#</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email Address</th>
                        <th>Phone No</th>
                        <th>Class</th>
                        <th>Class Arm</th>
                        <th>Date Created</th>
                        <th>Edit</th>
                        <th>Delete</th>
                      </tr>
                    </thead>
                   
                    <tbody>

                  <?php
                  

                  $query = "SELECT DISTINCT tblclassteacher.Id, tblclassteacher.firstName,tblclassteacher.lastName, tblclassteacher.emailAddress, tblclassteacher.phoneNo, tblclassteacher.dateCreated,
                      tblclass.className,
                       GROUP_CONCAT(tblclassarms.classArmName SEPARATOR ', ') as classArms
                      FROM tblclassteacher 
                      LEFT JOIN tblclass  ON tblclass.Id = tblclassteacher.classId
                      LEFT JOIN tblteacherclassarms ON tblteacherclassarms.teacherId = tblclassteacher.Id
                      LEFT JOIN tblclassarms  ON tblclassarms.Id = tblteacherclassarms.classArmId
                      GROUP BY tblclassteacher.Id
                      ORDER BY tblclassteacher.dateCreated DESC ";
                      
                      $rs = $conn->query($query);
                      $num = $rs->num_rows;
                      $sn = 0;
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
                                  <td>".$rows['emailAddress']."</td>
                                  <td>".$rows['phoneNo']."</td>
                                  <td>".$rows['className']."</td>
                                  <td>".$rows['classArms']."</td>
                                  <td>".$rows['dateCreated']."</td>
                                  <td><a href='?action=edit&Id=".$rows['Id']."'><img src='img/new logo initial version/Edit.png' style='width:33px;'></a></td>
                                  <td><a href='#' onclick='openDeletePopup(\"{$rows['firstName']}\",\"{$rows['lastName']}\",\"{$rows['Id']}\")' ><img src='img/new logo initial version/delete.png' style='width:20px; padding-top:5px;'></a></td>
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
  
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
   <!-- Page level plugins -->
  <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script>
    $(document).ready(function () {
      $('#dataTable').DataTable(); // ID From dataTable 
      $('#dataTableHover').DataTable(); // ID From dataTable with Hover
    });
  </script>
</body>


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


    <script>

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



    function classArmDropdown(classId) {
    if (classId === "") {
        $("#txtHint").html("");
        return;
    }
    $.ajax({
        url: "ajaxClassArms.php",
        type: "GET",
        data: { classId: classId },
        success: function(response) {
            $("#txtHint").html(response);
        }
    });
}


document.getElementById('select').addEventListener('change', function() {
    if (this.value === "3") {
        alert("Option 3 cannot be selected.");
        this.value = ""; // Revert to the default selection
    }
});



    </script> 


</html>