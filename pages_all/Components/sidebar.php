	<!-- SIDEBAR -->
	<div class="d-flex">
		<nav class="sidebar d-flex flex-column flex-shrink-0 position-fixed">
			<div class=" logo py-3 px-1" >
				<div class="container">
					<div class="row">
						<div class="col-12  text-center">
							<img src="../../img/logo/templogo.png" alt="">
							
						</div>
						<div class="col-12 role pt-2 col-8 align-content-center text-center">
							<h4 class="fw-bold mb-0 ms-1 text-white text-shadow">
								<p class="p-text m-0" style="font-size: 15px;">
									<span class="span-text" style="color: white;">
										<?php echo ($_SESSION['username']);?>
									</span>
								</p>
							</h4>			
						</div>
					</div>
					
				</div>
			</div>
			<div class="scroll">
				<div class="nav flex-column ">
					<?php if($_SESSION['user_role'] == 'academic_organization') {
					?>
						<a href="dash-org.php" class="sidebar-link text-decoration-none px-3 py-1">
							<i class="fas fa-home me-2 "></i>
							<span class="hide-on-collapse">Dashboard</span>
						</a>
						<hr class="hr p-0 m-0 ">
						<a href="org-org.php" class="sidebar-link text-decoration-none px-3 py-1">
							<i class="fas fa-users me-2"></i>
							<span class="hide-on-collapse">Organization</span>
						</a>
						
						<!-- <hr class="hr p-0 m-0 ">
						<a href="files-org.php" class="sidebar-link text-decoration-none px-3 py-1">
							<i class="fa-solid fa-folder-open me-3"></i>
							<span class="hide-on-collapse">Files</span>
						</a> -->
						<hr class="hr p-0 m-0 ">
						<a href="document-org.php" class="sidebar-link active text-decoration-none px-3 py-1">
							<i class="fa-solid fa-file-lines me-3"></i>
							<span class="hide-on-collapse">Documents</span>
						</a>
						
						<hr class="hr p-0 m-0 ">
						
						
						<a href="create_events-org.php" class="sidebar-link text-decoration-none px-3 py-1">
							<i class="fas fa-calendar-alt me-3"></i>
							<span class="hide-on-collapse">Events</span>
						</a>
						
						<hr class="hr p-0 m-0 ">
						<div class="menu-item sidebar-link text-decoration-none px-3 py-1" id="settings">
							<i class="fas fa-gear me-3"></i>
							<span class="hide-on-collapse">Settings</span>
						
						</div>
						<div class="submenu hide-on-collapse" id="subsettings">
							<a href="profile-org.php">Organization Profile</a>
							<a href="officers-org.php">Organization Officers</a>
							<a href="manage-acc-org.php">Manage Account</a>
						
						</div>
					<?php	
					}elseif($_SESSION['user_role'] == 'non_academic_organization') {
					?>

					<?php
					}elseif ($_SESSION['user_role'] == 'adviser') {
					?>
						<a href="dashboard.php" class="sidebar-link text-decoration-none px-3 py-1">
							<i class="fas fa-home me-2 "></i>
							<span class="hide-on-collapse">Dashboard</span>

						</a>
						<hr class="hr p-0 m-0 ">

						<a href="profile-adviser.php" class="sidebar-link  text-decoration-none px-3 py-1">
						<i class="fas fa-users me-2"></i>
							<span class="hide-on-collapse">Organization</span>

						</a>
						
						<hr class="hr p-0 m-0 ">
					
				
						<a href="templates-adviser.php" class="sidebar-link text-decoration-none px-3 py-1">
							<i class="fa-solid fa-file-lines me-3"></i>
							<span class="hide-on-collapse">Templates</span>
						</a>
						
						<hr class="hr p-0 m-0 ">
						
						
						<a href="pending_events-adviser.php" class="sidebar-link text-decoration-none px-3 py-1">
						<i class="fas fa-calendar-alt me-3"></i>
							<span class="hide-on-collapse">Events</span>
						</a>
						
						<hr class="hr p-0 m-0 ">
						<a href="settings.php" class="sidebar-link  text-decoration-none px-3 py-1">
							<i class="fas fa-gear me-3"></i>
							<span class="hide-on-collapse">Settings</span>
						</a>
					<?php
					}elseif ($_SESSION['user_role'] == 'dean') {
					?>

					<?php
					}?>
					
				</div>
			</div>
			<div class=" userimg py-1 px-1 " >
				<div class="container">
					<div class=" row profile-section px-1 py-1 ">
						<div class="col-3 p-0 text-center">
							<img src="../../img/logo/basc_logo.png" height="50 " alt="Profile">
						</div>
						<div class="col-7  profile-info p-0 align-content-center text-start ps-1">
							<h6 class="text-white adname mb-0 m-0 p-0">BULACAN AGRICULTURAL STATE COLLEGE</h6>
							<!-- <p class="text-white admin m-0 p-0" style="font-size:12px;">Admin</p> -->
						</div>
						<div class="col-2 p-0 profile-info align-content-center text-end pe-2">
							<a style="text-decoration: none;" href="../logout.php" onclick="return confirmLogout();">
								<i class="fa-solid fa-right-from-bracket admin text-danger" style="font-size: 20px;"></i>
							</a>
						</div>
					</div>
				</div>
			</div>
    	</nav>
	<!-- Navigation -->
		<main class="main-content p-0">
			<nav class="navbar navbar-light  p-0">
				<button class="toggle-btn" onclick="toggleSidebar()">
					<i class="fas fa-bars"></i>
				</button>
				<div class=" dash container-fluid ms-4 py-1">
					<p class="m-0">DASHBOARD</p>
					
                    <div class="btn-group dropstart">
                        <svg class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="me-3" viewBox="0 0 24 24" style="fill: rgb(0, 0, 0);">
                            <path d="M12 22a2.98 2.98 0 0 0 2.818-2H9.182A2.98 2.98 0 0 0 12 22zm7-7.414V10c0-3.217-2.185-5.927-5.145-6.742C13.562 2.52 12.846 2 12 2s-1.562.52-1.855 1.258C7.185 4.074 5 6.783 5 10v4.586l-1.707 1.707A.996.996 0 0 0 3 17v1a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-1a.996.996 0 0 0-.293-.707L19 14.586z"></path>
                        </svg>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Menu item</a></li>
                            <li><a class="dropdown-item" href="#">Menu item</a></li>
                            <li><a class="dropdown-item" href="#">Menu item</a></li>
                        </ul>
                    </div>
				</div>
				<!-- <div class="date">
				<p id="currentDateTime" class="text-muted mb-0" style="font-size: 18px; margin-left: 15px;"></p>
				</div> -->
			</nav>
            
			
	