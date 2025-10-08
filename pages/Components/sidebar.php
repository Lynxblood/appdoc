	<!-- SIDEBAR -->
	<div class="d-flex">
		<nav class="sidebar d-flex flex-column flex-shrink-0 position-fixed">
			<div class=" logo py-3 px-1" >
				<div class="container">
					<div class="row">
						<!-- <div class="col-12  text-center">
							<img src="../../img/logo/templogo.png" alt="">
							
						</div> -->
						<?php
							// Default values
							$logo_path = "../../img/logo/bits.png";
							$text_to_display = "
								<p class='p-text m-0' style='font-size: 20px;'>
									<span class='span-text' style='color: yellow; font-size: 25px;'>B</span>uilders of 
								</p>
								<p class='p-text m-0' style='font-size: 20px;'>
									<span class='span-text' style='color: yellow; font-size: 25px;'>I</span>nformation
								</p>
								<p class='p-text m-0' style='font-size: 20px;'>
									<span class='span-text' style='color: yellow; font-size: 25px;'>T</span>echnology
								</p>
								<p class='p-text m-0' style='font-size: 20px;'>
									<span class='span-text' style='color: yellow; font-size: 25px;'>S</span>ociety
								</p>
							";

							if (isset($_SESSION['user_id'])) {
								$user_id = $_SESSION['user_id'];

								$stmt = $conn->prepare("SELECT organization_id, user_role FROM users WHERE user_id = ?");
								$stmt->bind_param("i", $user_id);
								$stmt->execute();
								$result = $stmt->get_result();

								if ($sidebar_user_data = $result->fetch_assoc()) {
									$org_id = $sidebar_user_data['organization_id'];
									$user_role = $sidebar_user_data['user_role'];

									if ($org_id !== null) {
										// Fetch organization details
										$stmt_org = $conn->prepare("SELECT logo, name FROM organizations WHERE organization_id = ?");
										$stmt_org->bind_param("i", $org_id);
										$stmt_org->execute();
										$org_data = $stmt_org->get_result()->fetch_assoc();
										$stmt_org->close();

										if ($org_data) {
											$logo_path = "../../" . htmlspecialchars($org_data['logo']);

											// Split organization name into "phrases" (underscores keep words together)
											$org_name = htmlspecialchars($org_data['name']);
											$phrases = explode(' ', $org_name);
											$formatted_lines = "";

											foreach ($phrases as $phrase) {
												// Replace underscores with spaces for display
												$clean_phrase = str_replace('_', ' ', $phrase);
												$first = strtoupper(substr($clean_phrase, 0, 1));
												$rest = substr($clean_phrase, 1);

												$formatted_lines .= "
													<p class='p-text m-0' style='font-size: 20px;'>
														<span class='span-text' style='color: yellow; font-size: 25px;'>{$first}</span>{$rest}
													</p>";
											}

											$text_to_display = $formatted_lines;
										}
									} else {
										// Role-based fallback
										switch ($user_role) {
											case 'dean':
												$logo_path = "../../img/logo/ieat.png";
												$text_to_display = "
													<p class='p-text m-0' style='font-size: 20px;'>
														<span class='span-text' style='color: yellow; font-size: 25px;'>D</span>ean's 
													</p>
													<p class='p-text m-0' style='font-size: 20px;'>
														<span class='span-text' style='color: yellow; font-size: 25px;'>O</span>ffice
													</p>";
												break;

											case 'fssc':
												$logo_path = "../../img/logo/ieat.png";
												$text_to_display = "
													<p class='p-text m-0' style='font-size: 20px;'>
														<span class='span-text' style='color: yellow; font-size: 25px;'>F</span>SSC 
													</p>
													<p class='p-text m-0' style='font-size: 20px;'>
														<span class='span-text' style='color: yellow; font-size: 25px;'>O</span>ffice
													</p>";
												break;

											case 'osas':
												$logo_path = "../../img/logo/logo_osas.png";
												$text_to_display = "
													<p class='p-text m-0' style='font-size: 20px;'>
														<span class='span-text' style='color: yellow; font-size: 25px;'>O</span>SAS
													</p>";
												break;

											default:
												$logo_path = "../../img/logo/default_logo.png";
												$role_text = ucwords(str_replace('_', ' ', $user_role));
												$words = explode(' ', $role_text);
												$formatted_lines = "";

												foreach ($words as $word) {
													$first = strtoupper(substr($word, 0, 1));
													$rest = substr($word, 1);
													$formatted_lines .= "
														<p class='p-text m-0' style='font-size: 20px;'>
															<span class='span-text' style='color: yellow; font-size: 25px;'>{$first}</span>{$rest}
														</p>";
												}

												$text_to_display = $formatted_lines;
												break;
										}
									}
								}
								$stmt->close();
							}
							?>


							<div class="col-4 align-content-center">
								<img src="<?php echo $logo_path; ?>" alt="User Logo">
							</div>
							<div class=" role col-8 mb-1">
								<h4 class="fw-bold mb-0 ms-1 me-5 w-100 w-md-auto text-white text-shadow">
									<?php
									// Check if the text is a full block or just a string
									if (strpos($text_to_display, '<p') === 0) {
										echo $text_to_display;
									} else {
										echo '<p class="p-text m-0" style="font-size: 20px;">' . $text_to_display . '</p>';
									}
									?>
								</h4>
								<!-- 
								<p class="tw-bold  small hide-on-collapse ms-1" style="font-weight: bold; color: white;">Admin</p> -->
							</div>
							<div class="col-12 role pt-2 col-8 align-content-center text-center nameContainer">
								<h4 class="fw-bold mb-0 ms-1 text-white text-shadow">
									<p class="p-text m-0" style="font-size: 15px;">
										<span class="span-text" style="color: white;">
											<?php echo htmlspecialchars($_SESSION['first_name']); ?>
										</span>
									</p>
								</h4>
							</div>
					</div>
					
				</div>
			</div>
			<div class="scroll">
				<div class="nav flex-column ">
					<?php if($_SESSION['user_role'] == 'academic_organization' || $_SESSION['user_role'] == 'non_academic_organization') {
					?>
						<a href="dashboard.php" class="sidebar-link text-decoration-none px-3 py-1">
							<i class="fas fa-home me-2 "></i>
							<span class="hide-on-collapse">Dashboard</span>
						</a>
						<hr class="hr p-0 m-0 ">
						<a href="organization_dashboard.php" class="sidebar-link text-decoration-none px-3 py-1">
							<i class="fas fa-users me-2"></i>
							<span class="hide-on-collapse">Organization</span>
						</a>
						
						<!-- <hr class="hr p-0 m-0 ">
						<a href="files-org.php" class="sidebar-link text-decoration-none px-3 py-1">
							<i class="fa-solid fa-folder-open me-3"></i>
							<span class="hide-on-collapse">Files</span>
						</a> -->
						<hr class="hr p-0 m-0 ">
						<a href="document.php" class="sidebar-link active text-decoration-none px-3 py-1">
							<i class="fa-solid fa-file-lines me-3"></i>
							<span class="hide-on-collapse">Documents</span>
						</a>

						<hr class="hr p-0 m-0 ">
						<a href="templates.php" class="sidebar-link active text-decoration-none px-3 py-1">
							<i class="fa-solid fa-file-invoice me-3"></i>
							<span class="hide-on-collapse">Templates</span>
						</a>
						
						<hr class="hr p-0 m-0 ">
						<a href="calendar.php" class="sidebar-link text-decoration-none px-3 py-1">
							<i class="fas fa-calendar-alt me-3"></i>
							<span class="hide-on-collapse">Calendar</span>
						</a>
						
						<hr class="hr p-0 m-0 ">
						<a href="settings.php" class="sidebar-link  text-decoration-none px-3 py-1">
							<i class="fas fa-gear me-3"></i>
							<span class="hide-on-collapse">Settings</span>
						</a>
					<?php	
					}elseif ($_SESSION['user_role'] == 'adviser') {
					?>
						<a href="dashboard.php" class="sidebar-link text-decoration-none px-3 py-1">
							<i class="fas fa-home me-2 "></i>
							<span class="hide-on-collapse">Dashboard</span>

						</a>
						<hr class="hr p-0 m-0 ">

						<a href="manage_organization.php" class="sidebar-link  text-decoration-none px-3 py-1">
						<i class="fas fa-users me-2"></i>
							<span class="hide-on-collapse">Organization</span>

						</a>
						
						<hr class="hr p-0 m-0 ">
					
				
						<a href="request.php" class="sidebar-link text-decoration-none px-3 py-1">
							<i class="fa-solid fa-file-lines me-3"></i>
							<span class="hide-on-collapse">Request</span>
						</a>
						
						<hr class="hr p-0 m-0 ">
						
						
						<a href="calendar.php" class="sidebar-link text-decoration-none px-3 py-1">
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
						<a href="dashboard.php" class="sidebar-link text-decoration-none px-3 py-1">
							<i class="fas fa-home me-2 "></i>
							<span class="hide-on-collapse">Dashboard</span>

						</a>
						<!-- <hr class="hr p-0 m-0 ">

						<a href="profile-adviser.php" class="sidebar-link  text-decoration-none px-3 py-1">
						<i class="fas fa-users me-2"></i>
							<span class="hide-on-collapse">Organization</span>

						</a> -->
						
						<hr class="hr p-0 m-0 ">
					
				
						<a href="request.php" class="sidebar-link text-decoration-none px-3 py-1">
							<i class="fa-solid fa-file-lines me-3"></i>
							<span class="hide-on-collapse">Request</span>
						</a>
						
						<hr class="hr p-0 m-0 ">
						
						
						<a href="calendar.php" class="sidebar-link text-decoration-none px-3 py-1">
						<i class="fas fa-calendar-alt me-3"></i>
							<span class="hide-on-collapse">Events</span>
						</a>
						
						<hr class="hr p-0 m-0 ">
						<a href="settings.php" class="sidebar-link  text-decoration-none px-3 py-1">
							<i class="fas fa-gear me-3"></i>
							<span class="hide-on-collapse">Settings</span>
						</a>

					<?php
					}elseif ($_SESSION['user_role'] == 'fssc') {
						?>
							<a href="dashboard.php" class="sidebar-link text-decoration-none px-3 py-1">
								<i class="fas fa-home me-2 "></i>
								<span class="hide-on-collapse">Dashboard</span>
	
							</a>
							<!-- <hr class="hr p-0 m-0 ">
	
							<a href="profile-adviser.php" class="sidebar-link  text-decoration-none px-3 py-1">
							<i class="fas fa-users me-2"></i>
								<span class="hide-on-collapse">Organization</span>
	
							</a> -->
							
							<hr class="hr p-0 m-0 ">
						
					
							<a href="request.php" class="sidebar-link text-decoration-none px-3 py-1">
								<i class="fa-solid fa-file-lines me-3"></i>
								<span class="hide-on-collapse">Request</span>
							</a>
							
							<hr class="hr p-0 m-0 ">
							
							
							<a href="calendar.php" class="sidebar-link text-decoration-none px-3 py-1">
							<i class="fas fa-calendar-alt me-3"></i>
								<span class="hide-on-collapse">Events</span>
							</a>
							
							<hr class="hr p-0 m-0 ">
							<a href="settings.php" class="sidebar-link  text-decoration-none px-3 py-1">
								<i class="fas fa-gear me-3"></i>
								<span class="hide-on-collapse">Settings</span>
							</a>
	
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
	<div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="notificationModalLabel">Notification Details</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<p id="notificationMessage"></p>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
		</div>
		</div>
	</div>
	</div>
		<main class="main-content p-0">
			<nav class="navbar navbar-light  p-0">
				<button class="toggle-btn" onclick="toggleSidebar()">
					<i class="fas fa-bars"></i>
				</button>
				<div class=" dash container-fluid ms-4 py-1">
					<p class="m-0">DASHBOARD</p>
					<!-- NOTIFICATIONS -->
                    <!-- <div class="btn-group dropstart">
                        <svg class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="me-3" viewBox="0 0 24 24" style="fill: rgb(0, 0, 0);">
                            <path d="M12 22a2.98 2.98 0 0 0 2.818-2H9.182A2.98 2.98 0 0 0 12 22zm7-7.414V10c0-3.217-2.185-5.927-5.145-6.742C13.562 2.52 12.846 2 12 2s-1.562.52-1.855 1.258C7.185 4.074 5 6.783 5 10v4.586l-1.707 1.707A.996.996 0 0 0 3 17v1a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-1a.996.996 0 0 0-.293-.707L19 14.586z"></path>
                        </svg>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Menu item</a></li>
                            <li><a class="dropdown-item" href="#">Menu item</a></li>
                            <li><a class="dropdown-item" href="#">Menu item</a></li>
                        </ul>
                    </div> -->
					
                    <div class="notification-dropdown">
                        <div class="notification-icon-container" id="notificationBell" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell"></i>
                            <span class="notification-count d-none" id="notificationCount">0</span>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom" aria-labelledby="notificationBell" id="notificationList">
                            <li><a class="dropdown-item text-center" href="#">No new notifications</a></li>
                        </ul>
                    </div>
				</div>
				<!-- <div class="date">
				<p id="currentDateTime" class="text-muted mb-0" style="font-size: 18px; margin-left: 15px;"></p>
				</div> -->
			</nav>
            
			
	