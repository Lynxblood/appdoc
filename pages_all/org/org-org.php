
<?php
  require '../../config/dbcon.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BASC</title>
	<link rel="icon"  href="../../img/logo/logo_osas.png"><!-- sample icon -->
    <link rel="stylesheet" href="../../assets/externalCSS/org.css">
	<link rel="stylesheet" href="../../assets/externalCSS/style.css">
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
   	<!-- Font Link -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Special+Gothic+Expanded+One&display=swap" rel="stylesheet">


	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- FullCalendar CSS -->
	<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css' rel='stylesheet' />

	<!-- FullCalendar JS -->
	<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js'></script>


	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
	
<style>
	.dropdown ul li a:hover{
		background-color: #a8cd89;
	}



</style>
</head>
<body>
	<!-- SIDEBAR -->
	<div class="d-flex">
		<nav class="sidebar d-flex flex-column flex-shrink-0 position-fixed">
			<div class=" logo py-3 px-1" >
				<div class="container">
					<div class="row">
						<div class="col-4 align-content-center">
							<img src="../../img/logo/bits.png" alt="">
						</div>
						<div class=" role col-8 mb-1">
							<h4 class="fw-bold mb-0 ms-1 me-5 w-100 w-md-auto text-white text-shadow">
								<p class="p-text m-0" style="font-size: 20px;"><span class="span-text" style="color: yellow; font-size: 25px;">B</span>uilders of </p>
								<p class="p-text m-0" style="font-size: 20px;"><span class="span-text" style="color: yellow; font-size: 25px;">I</span>nformation</p>
								<p class="p-text m-0" style="font-size: 20px;"><span class="span-text" style="color: yellow; font-size: 25px;">T</span>echnology</p>
								<p class="p-text m-0" style="font-size: 20px;"><span class="span-text" style="color: yellow; font-size: 25px;">S</span>ociety</p>
							</h4>				
<!-- 
							<p class="tw-bold  small hide-on-collapse ms-1" style="font-weight: bold; color: white;">Admin</p> -->
						</div>
					</div>
				</div>
			</div>
			<div class="scroll">
				<div class="nav flex-column ">
					<a href="dash-org.php" class="sidebar-link text-decoration-none px-3 py-1">
						<i class="fas fa-home me-2 "></i>
						<span class="hide-on-collapse">Dashboard</span>

					</a>
					<hr class="hr p-0 m-0 ">
					<a href="org-org.php" class="sidebar-link active text-decoration-none px-3 py-1">
						<i class="fas fa-users me-2"></i>
						<span class="hide-on-collapse">Organization</span>
					</a>
					
					<hr class="hr p-0 m-0 ">
					<a href="files-org.php" class="sidebar-link text-decoration-none px-3 py-1">
						<i class="fa-solid fa-folder-open me-3"></i>
						<span class="hide-on-collapse">Files</span>
					</a>
					<hr class="hr p-0 m-0 ">
					<a href="templates-org.php" class="sidebar-link text-decoration-none px-3 py-1">
						<i class="fa-solid fa-file-lines me-3"></i>
						<span class="hide-on-collapse">Templates</span>
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
							<a style="text-decoration: none;" href="../../index.php" onclick="return confirmLogout();">
								<i class="fa-solid fa-right-from-bracket admin text-danger" style="font-size: 20px;"></i>
							</a>
						</div>
					</div>
				</div>
			</div>
    	</nav>
	<!-- Navigation -->
		<main class="main-content p-0">
			<nav class="navbar navbar-light p-0">
				<button class="toggle-btn" onclick="toggleSidebar()">
					<i class="fas fa-bars"></i>
				</button>
				<div class=" dash container-fluid ms-4 py-1">
					<p  class="m-0">ORGANIZATION PROFILE</p>
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="me-3"  viewBox="0 0 24 24" style="fill: rgb(0, 0, 0);"><path d="M12 22a2.98 2.98 0 0 0 2.818-2H9.182A2.98 2.98 0 0 0 12 22zm7-7.414V10c0-3.217-2.185-5.927-5.145-6.742C13.562 2.52 12.846 2 12 2s-1.562.52-1.855 1.258C7.185 4.074 5 6.783 5 10v4.586l-1.707 1.707A.996.996 0 0 0 3 17v1a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-1a.996.996 0 0 0-.293-.707L19 14.586z"></path></svg>
				</div>
				<!-- <div class="date">
				<p id="currentDateTime" class="text-muted mb-0" style="font-size: 18px; margin-left: 15px;"></p>
				</div> -->
			</nav>
                
            <div class="container-fluid mt-3 ">
                <div class="row">
                    <div class="col-8">
                        <div class="card shadow" style="height: 87vh;">
                            <div class="card-body">
                                <div class="row">
									<h1>ORGANIZATION NAME</h1>
									<p>Date Founded</p>
									<p>Founder</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="card shadow" style="height: 87vh;">
                            <div class="card-body">
                                <div class="row px-3">
									<div class="card align-items-center shadow-sm">
										<div class="card-body">
											<img src="../../img/profile/default.jpg" alt=""  height="100px" width="100px">
										
											<h6>ADVISER NAME</h6>
											<p>postion/ranking</p>
										</div>
									</div>
                                </div>
								<div class="row px-3 mt-2">
									<div class="card p-0  align-items-center shadow-sm">
										<div class="card-body p-0  ">
											<div class="row">
												<div class="col"> 
													<div class="row">
														<div class="col-4">
															<img src="../../img/profile/default.jpg" alt=""  height="100px" width="100px">
														
														</div>
														<div class="col-8 align-content-center">
															<h6 class=" m-0">OFFICER NAME</h6>
															<p class="m-0">postion/ranking</p>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

		</main>

	</div>
<script>
	function confirmLogout() {
    return confirm("Are you sure you want to log out?");
  }
	 document.addEventListener("DOMContentLoaded", function () {
      

      const events = document.getElementById("events");
      const subevents = document.getElementById("subevents");

      if (events && subevents) {
        events.addEventListener("click", function () {
          subevents.classList.toggle("show");
        });
      }
    });
</script>

	<script src="../../assets/externalJS/script.js" ></script>
	<script src="../../assets/darkmode.js" ></script>
	<script src="../../assets/count.js" ></script>
</body>
</html>