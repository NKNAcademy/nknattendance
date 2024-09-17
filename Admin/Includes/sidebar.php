<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

 
 <ul class="navbar-nav sidebar sidebar-light accordion " id="accordionSidebar">
     <a class="sidebar-brand d-flex align-items-center bg-gradient-primary  justify-content-center" href="index.php">
         <div class="sidebar-brand-icon">
             <img src="img\new logo initial version\NKN.png">
         </div>
         <div class="sidebar-brand-text mx-3">NKN Academy </div>
     </a>
     <hr class="sidebar-divider my-0">
     <li class="nav-item active">
         <a class="nav-link" href="index.php">
             <!-- <i class="fas fa-fw fa-tachometer-alt">  </i> -->
             <img src="img/new logo initial version/dashboard.png" width="30px" style="margin-right:10px;">
             <span style="font-size:16px;">Dashboard</span></a>
     </li>
     <hr class="sidebar-divider">
     <div class="sidebar-heading">
         Class and Class Arms
     </div>
     <li class="nav-item">
         <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrap" aria-expanded="true" aria-controls="collapseBootstrap">
             <img src="img/new logo initial version/class.png" width="25px" style="margin-right:5px;">
             <span>Manage Classes</span> 
         </a> 
         <div id="collapseBootstrap" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
             <div class="bg-white py-2 collapse-inner rounded">                
                 <a class="collapse-item" href="createClass.php">Create Class</a>
                 <!-- <a class="collapse-item" href="#">Member List</a> -->
             </div>
         </div>
     </li>
     <li class="nav-item">
         <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrapusers" aria-expanded="true" aria-controls="collapseBootstrapusers">
             <img src="img/new logo initial version/class Arms.png" width="25px" style="margin-right:5px;"> 
             <span>Manage Class Arms</span>
         </a>
         <div id="collapseBootstrapusers" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
             <div class="bg-white py-2 collapse-inner rounded">
                 <a class="collapse-item" href="createClassArms.php">Create Class Arms</a>
                 <!-- <a class="collapse-item" href="usersList.php">User List</a> -->
             </div>
         </div>
     </li>
     <hr class="sidebar-divider">
     <div class="sidebar-heading">
         Teachers
     </div>
     <li class="nav-item">
         <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrapassests" aria-expanded="true" aria-controls="collapseBootstrapassests">
         <img src="img/new logo initial version/teacher.png" width="25px" style="margin-right:5px;">    
         <span>Manage Teachers</span>
         </a>
         <div id="collapseBootstrapassests" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
             <div class="bg-white py-2 collapse-inner rounded">
                 <a class="collapse-item" href="createClassTeacher.php">Create Class Teachers</a>
                 <!-- <a class="collapse-item" href="assetsCategoryList.php">Assets Category List</a>
             <a class="collapse-item" href="createAssets.php">Create Assets</a> -->
             </div>
         </div>
     </li>
     <!-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrapschemes"
          aria-expanded="true" aria-controls="collapseBootstrapschemes">
          <i class="fas fa-home"></i>
          <span>Manage Schemes</span>
        </a>
        <div id="collapseBootstrapschemes" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Manage Schemes</h6>
             <a class="collapse-item" href="createSchemes.php">Create Scheme</a>
            <a class="collapse-item" href="schemeList.php">Scheme List</a>
          </div>
        </div>
      </li> -->

     <hr class="sidebar-divider">
     <div class="sidebar-heading">
         Students
     </div>
     </li>
     <li class="nav-item">
         <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrap2" aria-expanded="true" aria-controls="collapseBootstrap2">
         <img src="img/new logo initial version/students.png" width="25px" style="margin-right:5px;">    
         <span>Manage Students</span>
         </a>
         <div id="collapseBootstrap2" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
             <div class="bg-white py-2 collapse-inner rounded" href="createStudents.php">
                 <a class="collapse-item" href="createStudents.php">Create Students</a>
                 <!-- <a class="collapse-item" href="#">Assets Type</a> -->
             </div>
         </div>
     </li>

     <hr class="sidebar-divider">
     <div class="sidebar-heading">
         Session 
     </div>
     </li>
     <li class="nav-item">
         <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrapcon" aria-expanded="true" aria-controls="collapseBootstrapcon">
         <img src="img/new logo initial version/calendar.png" width="25px" style="margin-right:5px;">    
         <span>Manage Sessions </span>
         </a>
         <div id="collapseBootstrapcon" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
             <div class="bg-white py-2 collapse-inner rounded">
                 <a class="collapse-item" href="createSessionTerm.php">View Sessions</a>
                 <!-- <a class="collapse-item" href="addMemberToContLevel.php ">Add Member to Level</a> -->
             </div>
         </div>
     </li>
     
    
     <hr class="sidebar-divider">
     <div class="sidebar-heading">
      Attendance    
     </div>
     </li>
     <li class="nav-item">
         <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstraptest" aria-expanded="true" aria-controls="collapseBootstrapcon">
         <img src="img/new logo initial version/data settings.png" width="25px" style="margin-right:5px;">    
         <span>Manage Attendance</span>
         </a>
         <div id="collapseBootstraptest" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
             <div class="bg-white py-2 collapse-inner rounded">
             <a class="collapse-item" href="takeAttendance.php">Take Attendance</a>
            <a class="collapse-item" href="viewAttendance.php">View by Date</a>
            <a class="collapse-item" href="viewClassAttendance.php">View By Class</a>
            <a class="collapse-item" href="viewStudentAttendance.php">View By Student</a>
            <a class="collapse-item" href="downloadRecord.php">Export Report (xls)</a>
                             <!-- <a class="collapse-item" href="addMemberToContLevel.php ">Add Member to Level</a> -->
             </div>
         </div>
     </li>

     <!-- <li class="nav-item">
        <a class="nav-link" href="forms.html">
          <i class="fab fa-fw fa-wpforms"></i>
          <span>Forms</span> 
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTable" aria-expanded="true"
          aria-controls="collapseTable">
          <i class="fas fa-fw fa-table"></i>
          <span>Tables</span>
        </a>
        <div id="collapseTable" class="collapse" aria-labelledby="headingTable" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Tables</h6>
            <a class="collapse-item" href="simple-tables.html">Simple Tables</a>
            <a class="collapse-item" href="datatables.html">DataTables</a>
          </div>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="ui-colors.html">
          <i class="fas fa-fw fa-palette"></i>
          <span>UI Colors</span>
        </a>
      </li>
      <hr class="sidebar-divider">
      <div class="sidebar-heading">
        Examples
      </div>
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePage" aria-expanded="true"
          aria-controls="collapsePage">
          <i class="fas fa-fw fa-columns"></i>
          <span>Pages</span>
        </a>
        <div id="collapsePage" class="collapse" aria-labelledby="headingPage" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Example Pages</h6>
            <a class="collapse-item" href="login.html">Login</a>
            <a class="collapse-item" href="register.html">Register</a>
            <a class="collapse-item" href="404.html">404 Page</a>
            <a class="collapse-item" href="blank.html">Blank Page</a>
          </div>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="charts.html">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Charts</span>
        </a>
      </li> -->
     <hr class="sidebar-divider">

 </ul>