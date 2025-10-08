<?php
	require '../../config/dbcon.php';
	

	// Fetch folders and files (root level)
	$folder_List = "SELECT * FROM folders WHERE parent_ID IS NULL";    
	$files_List = "SELECT * FROM files WHERE folder_ID IS NULL";;
	
?>

	

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BASC</title>
	<link rel="icon"  href="../../img/logo/logo_osas.png"><!-- sample icon -->
    <link rel="stylesheet" href="../../assets/externalCSS/files.css">
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
</head>
<body>
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
										Dean D. Dean
									</span>
								</p>
							</h4>			
						</div>
					</div>
					
				</div>
			</div>
			
			<div class="scroll">
				<div class="nav flex-column ">
					<a href="dash-sig.php" class="sidebar-link text-decoration-none px-3 py-1">
						<i class="fas fa-home me-2 "></i>
						<span class="hide-on-collapse">Dashboard</span>

					</a>
					<hr class="hr p-0 m-0 ">
					<a href="org-sig.php" class="sidebar-link text-decoration-none px-3 py-1">
						<i class="fas fa-users me-2"></i>
						<span class="hide-on-collapse">Organization</span>
					</a>
					
					<hr class="hr p-0 m-0 ">
					<a href="files-sig.php" class="sidebar-link active text-decoration-none px-3 py-1">
						<i class="fa-solid fa-folder-open me-3"></i>
						<span class="hide-on-collapse">Files</span>
					</a>
					<hr class="hr p-0 m-0 ">
					<a href="templates-sig.php" class="sidebar-link text-decoration-none px-3 py-1">
						<i class="fa-solid fa-file-lines me-3"></i>
						<span class="hide-on-collapse">Templates</span>
					</a>
					
					<hr class="hr p-0 m-0 ">
					
					<div class=" evemenu-item sidebar-link  text-decoration-none px-3 py-1"  id="events">
						<i class="fas fa-calendar-alt me-3"></i>
						<span class="hide-on-collapse">Events</span>

					</div>
					<div class="subevents hide-on-collapse" id="subevents">
						<a href="create_events-sig.php">Create Event</a>
						<a href="pending_events-sig.php">Pending Events</a>
					
					</div>
					<hr class="hr p-0 m-0 ">
					<div class="menu-item sidebar-link text-decoration-none px-3 py-1" id="settings">
						<i class="fas fa-gear me-3"></i>
						<span class="hide-on-collapse">Settings</span>
					
					</div>
					<div class="submenu hide-on-collapse" id="subsettings">
						<a href="profile-sig.php">Organization Profile</a>
						<a href="officers-sig.php">Organization Officers</a>
						<a href="manage-acc-sig.php">Manage Account</a>
					
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
					<p  class="m-0">FILES</p>
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
								<div class="d-flex flex-wrap gap-4 mb-4">
									<!-- folders -->
									 
									<?php 
										$folders_query = mysqli_query($conn, $folder_List);
										if (mysqli_num_rows($folders_query) > 0) {
											foreach ($folders_query as $folder) {
												$folder_display_name = htmlspecialchars($folder['folder_name']);
												if (strlen($folder_display_name) > 15) {
													$folder_display_name = substr($folder_display_name, 0, 10) . '...';
												}
									?>
										<div class="text-center folder-item" style="display:inline-block;">
											<a href="folder.php?folder_ID=<?= $folder['folder_ID'] ?>" class="text-decoration-none text-dark">
												<i class="fas fa-folder fa-4x text-warning"></i><br>
												<small><?= $folder_display_name ?></small>
											</a>
										</div>
									<?php
										}
									}
									?>
									<!-- files -->
									<?php
										$files_run = mysqli_query($conn, $files_List);
										if(mysqli_num_rows($files_run) > 0){
											foreach ($files_run as $show_file){
											
												$file_extension = strtolower(pathinfo($show_file['file_Name'], PATHINFO_EXTENSION));

												// Choose the icon based on the file extension
												if($file_extension == 'pdf'){
													$icon = 'fas fa-file-pdf text-danger'; // Red PDF icon
												} elseif($file_extension == 'docx'){
													$icon = 'fas fa-file-word text-primary'; // Blue Word icon
												} else {
													$icon = 'fas fa-file text-secondary'; // Default file icon
												}
												// Limit file name to 20 characters
												$file_display_name = htmlspecialchars($show_file['file_Name']);
												if(strlen($file_display_name) > 15){
													$file_display_name = substr($file_display_name, 0, 10) . '...';
												}
									?>
										<div class="text-center" style="display:inline-block;">
											<a href="../uploads/<?= htmlspecialchars($show_file['file_Name']) ?>" class="text-decoration-none text-dark" target="_blank">
												<i class="<?= $icon ?> fa-4x"></i><br>
												<small><?= $file_display_name ?></small>
											</a>
										</div>
									<?php 
											}
										} 
									?>

																		
								</div>
							</div>
						</div>
						<!-- FAB Container -->
						<div class="position-fixed"style="bottom:40px; right:40px; z-index:1000;" >
							<div id="fabMenu" class="d-none mb-3 text-end" >  <!-- Initially hidden -->
								<div class="fab-icon mb-2" style="margin-right: 5px;">
									<button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#uploadFileModal">
									<i class="fa-solid fa-upload"></i>
									</button>
								</div>
								
								<div class="fab-icon"  style="margin-right: 5px;">
									<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
									<i class="fa-solid fa-folder-plus"></i>
									</button>
								</div>
							</div>
							<button id="fabMainBtn" class="btn btn-dark d-flex align-items-center justify-content-center"
								style="top: 850px; left: 1800px; background-color: black; color: white; border: none;
									border-radius: 50%; width: 50px; height: 50px; font-size: 28px; z-index: 1000;"
									onclick="toggleFab()">
								<i id="fabIcon" class="fa-solid fa-plus"></i>
							</button>
						</div>



						<!-- Upload Folder Modal -->
						<div class="modal fade" id="staticBackdrop" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="staticBackdropLabel">Add Folder</h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
									</div>
									<div class="modal-body">
									<form action="" method="post">
										<div class="form-floating mb-3">
											<input type="text" class="form-control" id="floatingFolder" name="folder_name" placeholder="Folder Name" required>
											<label for="floatingFolder" class="form-label">Folder Name</label>
										</div>
										<div class="text-end">
											<button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
											<button type="submit" class="btn btn-primary" id="new_folder" name="add-folder">Add</button>
										</div>
									</form>
									</div>
								</div>
							</div>
						</div>

						<!-- Upload File Modal -->
						<div class="modal fade" id="uploadFileModal" tabindex="-1" aria-labelledby="uploadFileModalLabel" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="uploadFileModalLabel">Upload File</h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
									</div>
									<div class="modal-body">
									<form action="../../function/function.php" method="post" enctype="multipart/form-data">
										<input type="hidden" name="folder_id" value="<?= isset($folderID) ? $folderID : '' ?>"> <!-- Ensure the folder ID is correctly passed -->
										<div class="mb-3">
											<label for="uploadedFile" class="form-label">Choose File to Upload</label>
											<input type="file" class="form-control" name="files" id="uploadedFile" required>
										</div>
										<div class="text-end">
											<button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
											<button type="submit" class="btn btn-success" name="add-file">Upload</button>
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
		// Toggle add file/folder
		function toggleFab() {
		const menu = document.getElementById('fabMenu');
		const icon = document.getElementById('fabIcon');

		menu.classList.toggle('show');
		icon.classList.toggle('rotate');
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


    <script src="../../assets/externalJS/script.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
	<script src="../../assets/darkmode.js" defer></script>
	<script src="../../assets/count.js" defer></script>
</body>
</html>