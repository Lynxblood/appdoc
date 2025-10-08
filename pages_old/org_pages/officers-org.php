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
          <a href="org-org.php" class="sidebar-link text-decoration-none px-3 py-1">
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
          <div class="menu-item sidebar-link active text-decoration-none px-3 py-1" id="settings">
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
					<p  class="m-0">ORGANIZATION OFFICERS</p>
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
                <div class="row">
                  <div class="col">
                  </div>
                </div>
                <div class="row">
                  <?php
                    $sql = "SELECT * FROM officers WHERE user_ID = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $user_ID);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while ($row = $result->fetch_assoc()):
                  ?>

                  <!-- Edit Officer Modal -->
                  <!-- <div class="modal fade" id="editOfficerModal<?= $row['officer_ID'] ?>" tabindex="-1" aria-labelledby="editOfficerModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <form class="modal-content" method="POST" enctype="multipart/form-data" action="../../function/function.php?id=<?= $row['officer_ID'] ?>">
                        <div class="modal-header">
                          <h5 class="modal-title" id="editOfficerModalLabel<?= $row['officer_ID'] ?>">Edit Officer</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <input class="form-control mb-2" name="name" value="<?= $row['f_name'] ?>" required>
                          <input class="form-control mb-2" name="role" value="<?= $row['m_name'] ?>" >
                          <input class="form-control mb-2" name="course" value="<?= $row['l_name'] ?>" required>
                          <label>Update Photo (optional):</label>
                          <input class="form-control mb-2" type="file" name="photo" value="<?= $row['photo'] ?>">
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                          <button type="submit" class="btn btn-success">Update</button>
                        </div>
                      </form>
                    </div>
                  </div> -->

                  <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <div class="card text-center p-2" style="border-radius: 1rem; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);">
                      <img src="../../img/profile/<?= $row['photo'] ?>" 
                          class="rounded-circle mx-auto d-block mt-3" 
                          style="width: 70px; height: 70px; object-fit: cover; border: 2px solid #fff; box-shadow: 0 0 10px rgba(0,0,0,0.2);">
                      <div class="card-body p-2">
                        <h6 class="card-title mb-1 small"><?= $row['f_name'] ." " . $row['m_name'] ." " . $row['l_name']?></h6>
                        <p class="card-text mb-1 text-muted small"><?= $row['role'] ?></p>
                        <div class="d-flex justify-content-center gap-2">
                        
                          <button 
                            class="btn btn-link text-warning p-0" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editOfficerModal<?= $row['officer_ID'] ?>" 
                            title="Edit">
                            <i class="fas fa-pencil-alt fa-lg"></i>
                          </button>

                          <button 
                            class="btn btn-link text-danger p-0" 
                            onclick="if(confirm('Delete this officer?')){ window.location='delete_officer.php?id=<?= $row['officer_ID'] ?>'; }" 
                            title="Delete">
                            <i class="fas fa-trash-alt fa-lg"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>


                  <?php endwhile; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="position-fixed" style="bottom:40px; right:40px; z-index:1000;" data-bs-toggle="modal" data-bs-target="#addOfficerModal">
        <button id="fabMainBtn" class="btn btn-dark d-flex align-items-center justify-content-center"
          style="top: 500px; left: 1000px; background-color: black; color: white; border: none;
            border-radius: 50%; width: 50px; height: 50px; font-size: 28px; z-index: 500;">
          <i id="fabIcon" class="fa-solid fa-plus"></i>
        </button>
			</div>

      <!-- Add Officer Modal -->
      <div class="modal fade" id="addOfficerModal" tabindex="-1" aria-labelledby="addOfficerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <form class="modal-content" action="../../function/function.php" method="POST" enctype="multipart/form-data">
            
            <input type="text" name="user_ID" value="<?= $user_ID ?>">
            <div class="modal-header">
              <h5 class="modal-title" id="addOfficerModalLabel">Add Officer</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingFolder" name="f_name" placeholder="First Name" required>
                <label for="floatingFolder" class="form-label">First Name</label>
              </div>
              <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingFolder" name="m_name" placeholder="Middle Initial">
                <label for="floatingFolder" class="form-label">Middle Name</label>
              </div>
              <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingFolder" name="l_name" placeholder="Last Name" required>
                <label for="floatingFolder" class="form-label">Last Name</label>
              </div>
              <!-- Preview Container -->
              <div class="row">
                <div class="col-3">
                  <img id="photoPreview" 
                      src="../../img/profile/default.jpg" 
                      class="rounded-circle" 
                      style="width: 100px; height: 100px; object-fit: cover; border: 1px solid #ddd;">
                </div>
                <div class="col-9">
                  <div class="input-group mb-3">
                    <label class="input-group-text" for="inputGroupSelect01">Position</label>
                    <select class="form-select" id="inputGroupSelect01" name="role">
                      <option selected disabled>Choose...</option>
                      <option value="1">President</option>
                      <option value="2">Vice President</option>
                      <option value="3">Secretary</option>
                      <option value="3">Treasurer</option>
                      <option value="3">Auditor</option>
                      <option value="3">Public Information Officer</option>
                    </select>
                  </div>
                  <input class="form-control mb-2" type="file" name="image" id="photoInput" accept="image/*" required>
                </div>
              </div>
              <div class="mb-3 text-center">
              </div>

            </div>
            <div class="modal-footer">
              <input type="hidden" name="add_officer" value="1">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary" name="add-officer">Add Officer</button>
            </div>
          </form>
        </div>
      </div>


		</main>
	</div>
<script>
  document.getElementById('photoInput').addEventListener('change', function(event) {
  const preview = document.getElementById('photoPreview');
  const file = event.target.files[0];
    if (!file) {
      preview.src = '../../img/profile/default.jpg';
      return;
    }
    preview.src = URL.createObjectURL(file);
  });
  
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


  <script src="../../assets/externalJS/script.js"></script>
  <script src="../../assets/js/bootstrap.bundle.min.js"></script>
	<script src="../../assets/darkmode.js" defer></script>
	<script src="../../assets/count.js" defer></script>
</body>
</html>
