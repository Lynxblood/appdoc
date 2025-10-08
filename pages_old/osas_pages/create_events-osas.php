<?php
  session_start();
  require '../../config/dbcon.php';
  $user_ID = $_SESSION['user_ID']; // Get the logged-in user's ID
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BASC</title>
	<link rel="icon"  href="../../img/logo/logo_osas.png"><!-- sample icon -->
    <link rel="stylesheet" href="../../assets/externalCSS/dash.css">
	<link rel="stylesheet" href="../../assets/externalCSS/style.css">
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
	
    <script src="../../assets/jquery/jquery-3.7.1.min.js"></script>
    <script src="../../assets/DATA_TABLES/datatables.js"></script>
    <link href="../../assets/DATA_TABLES/datatables.css" rel="stylesheet">
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
	
</head>
<body>
	<!-- SIDEBAR -->
	<div class="d-flex">
		<nav class="sidebar d-flex flex-column flex-shrink-0 position-fixed">
			<div class=" logo py-3 px-1" >
				<div class="container">
					<div class="row">
						<div class="col-4 align-content-center">
							<img src="../../img/logo/logo_osas.png" alt="">
						</div>
						<div class=" role col-8 mb-1">
							<h4 class="fw-bold mb-0 ms-1 me-5 w-100 w-md-auto text-white text-shadow">
								<p class="p-text m-0" style="font-size: 20px;"><span class="span-text" style="color: yellow; font-size: 25px;">O</span>ffice of </p>
								<p class="p-text m-0" style="font-size: 20px;"><span class="span-text" style="color: yellow; font-size: 25px;">S</span>tudent</p>
								<p class="p-text m-0" style="font-size: 20px;"><span class="span-text" style="color: yellow; font-size: 25px;">A</span>ffairs and</p>
								<p class="p-text m-0" style="font-size: 20px;"><span class="span-text" style="color: yellow; font-size: 25px;">S</span>ervices</p>
							</h4>				
<!-- 
							<p class="tw-bold  small hide-on-collapse ms-1" style="font-weight: bold; color: white;">Admin</p> -->
						</div>
					</div>
				</div>
			</div>
			<div class="scroll">
				<div class="nav flex-column ">
					<a href="dash-osas.php" class="sidebar-link active text-decoration-none px-3 py-1">
						<i class="fas fa-home me-2 "></i>
						<span class="hide-on-collapse">Dashboard</span>

					</a>
					<hr class="hr p-0 m-0 ">
					<a href="org-osas.php" class="sidebar-link text-decoration-none px-3 py-1">
						<i class="fas fa-users me-2"></i>
						<span class="hide-on-collapse">Organization</span>
					</a>
					
					<hr class="hr p-0 m-0 ">
					<a href="templates-osas.php" class="sidebar-link text-decoration-none px-3 py-1">
						<i class="fa-solid fa-file-lines me-3"></i>
						<span class="hide-on-collapse">Templates</span>
					</a>
					
					<hr class="hr p-0 m-0 ">
					<div class="menu-item sidebar-link text-decoration-none px-3 py-1" id="settings">
						<i class="fas fa-gear me-3"></i>
						<span class="hide-on-collapse">Settings</span>
					
					</div>
					<div class="submenu hide-on-collapse" id="subsettings">
						<a href="profile-osas.php">Organization Profile</a>
						<a href="manage-acc-osas.php">Manage Account</a>
					
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
					<p class="m-0">EVENTS</p>
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="me-3"  viewBox="0 0 24 24" style="fill: rgb(0, 0, 0);"><path d="M12 22a2.98 2.98 0 0 0 2.818-2H9.182A2.98 2.98 0 0 0 12 22zm7-7.414V10c0-3.217-2.185-5.927-5.145-6.742C13.562 2.52 12.846 2 12 2s-1.562.52-1.855 1.258C7.185 4.074 5 6.783 5 10v4.586l-1.707 1.707A.996.996 0 0 0 3 17v1a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-1a.996.996 0 0 0-.293-.707L19 14.586z"></path></svg>
				</div>
				<!-- <div class="date">
				<p id="currentDateTime" class="text-muted mb-0" style="font-size: 18px; margin-left: 15px;"></p>
				</div> -->

			</nav>

			<div class="container-fluid mt-3">
				<div class="row">
					<div class="col">
						<div class="card shadow " style="height: 87vh;">
							<div class="card-body"> 	
								<table id="myTable" class="display ">
                                    <thead class="pt-3">
                                        <tr class="pt-3 bg-secondary-subtle">
                                            <th>Event Title</th>
                                            <th >Venue</th>
                                            <th >Date</th>
                                            <th >Status</th>
                                            <th style="width:100px;">File</th>
                                            <!-- <th style="width:100px;">Operation</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Charter Day</td>
                                            <td>BASC Main Campus</td>
                                            <td>May 6, 2025</td>
                                            <td>Ongoing</td>
                                            <td  class="">
                                                <div class="row  p-0 m-0">
                                                    <div class="col " >
                                                        <a href="edit.php" class="">
														<button type="submit" name="editcourse" id="editcourse" class="btn bg-primary text-light fw-bold py-0 px-2" style="height: 30px; width:100px;">  
                                                            &nbsp;View File</button>
                                                        </a>
                                                    </div>
												</div>

											</td>
                                            <!-- <td  class="d-flex justify-content-evenly ">
                                                <div class="row  p-0 m-0">
                                                    <div class="col-6" >
                                                        <a href="edit.php" class="">
                                                            <button type="submit" name="editcourse" id="editcourse" class="btn " style="height: 30px; width:60px;">  
                                                          	</button>
															<i class="fa-solid fa-square-check text-success" style="font-size: 30px;"></i>
                                                        </a>
                                                    </div>
                                                    <div class="col-6 ">
                                                        <a href="delete.php" class="">
                                                            <button type="submit" name="editcourse" id="editcourse" class="btn" style="height: 30px; width:70px;">  
                                                            </button>
															<i class="fa-solid fa-square-xmark text-danger" style="font-size: 30px;"></i>
                                                        </a>
                                                    </div>
                                                </div>

                                            </td> -->
                                        </tr>
                                    
                                    </tbody>
                                </table>
						

                            </div>
                        </div>
				
						<!-- FAB Container -->
						<div class="position-fixed" style="bottom:40px; right:40px; z-index:1000;" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
							<button id="fabMainBtn" class="btn btn-dark d-flex align-items-center justify-content-center"
								style="top: 500px; left: 1000px; background-color: black; color: white; border: none;
									border-radius: 50%; width: 50px; height: 50px; font-size: 28px; z-index: 500;">
								<i id="fabIcon" class="fa-solid fa-plus"></i>
							</button>
						</div>
						<!-- Upload Event Modal -->
						<div class="modal fade" id="staticBackdrop" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="staticBackdropLabel">Create Event Proposal</h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
									</div>
									<div class="modal-body">
										<form action="" method="post">
											<div class="form-floating mb-3">
												<input type="text" class="form-control" id="floatingFolder" name="folder_name" placeholder="Folder Name" required>
												<label for="floatingFolder" class="form-label">Event Title</label>
											</div>
											<div class="input-group mb-3">
												<label class="input-group-text" for="inputGroupSelect01">Venue</label>
												<select class="form-select" id="inputGroupSelect01">
													<option selected>Choose...</option>
													<option value="1">One</option>
													<option value="2">Two</option>
													<option value="3">Three</option>
												</select>
											</div>
											<div class="row">
												<div class="col-6">
													<div class="form-floating mb-3">
														<input type="text" class="form-control" id="floatingFolder" name="folder_name" placeholder="Folder Name" required>
														<label for="floatingFolder" class="form-label">Date</label>
													</div>
												</div>
												<div class="col-6">
													<div class="form-floating mb-3">
														<input type="text" class="form-control" id="floatingFolder" name="folder_name" placeholder="Folder Name" required>
														<label for="floatingFolder" class="form-label">Time</label>
													</div>		
												</div>

											</div>
																				
											<div class="input-group mb-3">
												<input type="file" class="form-control" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" aria-label="Upload">
												<button class="btn btn-outline-secondary" type="button" id="inputGroupFileAddon04">Button</button>
											</div>
											<div class="text-end">
												<button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
												<button type="submit" class="btn btn-success" id="new_folder" name="add-folder">Upload</button>
											</div>
										</form>
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
        $(document).ready(function(){
            $('#myTable').DataTable();
        }
        )

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

    <script src="../../assets/externalJS/script.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
	<script src="../../assets/darkmode.js" defer></script>
	<script src="../../assets/count.js" defer></script>
</body>
</html>