<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';


//--------------------------------DELETE------------------------------------------------------------------


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="delete.css">
    <title>Delete Confirmation Popup</title>
</head>
<body>
    <?php

$query = "SELECT * FROM tblclass";
$rs = $conn->query($query);
$num = $rs->num_rows;
$sn=0;


if($num >0){
   
        while($rows = $rs->fetch_assoc()){


      echo "<div class='popup-overlay'>
        <div class='popup'>
            <div class='popup-header'>
                Delete Confirmation
            </div>
            <div class='popup-body'>
                Are you sure you would like to delete <b>". $rows['className']." </b> class ?"." <br> The action cannot be undone.
            </div>
            <div class='popup-footer'>
            
                <button class='btn-delete' href='?action=delete&Id=".$rows['Id']."'>Delete</button>
                <button class='btn-cancel' onclick='closePopup()'>Cancel</button>
            </div>
        </div>
    </div>";

    } } 



     
  if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "delete")
  {
      $Id= $_GET['Id'];

      $query = mysqli_query($conn,"DELETE FROM tblclass WHERE Id='$Id'");

      if ($query == TRUE) {

              echo "<script type = \"text/javascript\">
              window.location = (\"createClass.php\")
              </script>";  
      }
      else{

          $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>"; 
       }
    
}

   
?>

    <script>

function closePopup() {
        document.getElementById('deleteConfirmationPopup').style.display = 'none';
    }

    function openDeletePopup(className, classId) {
        document.getElementById('itemType').textContent = className;
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
