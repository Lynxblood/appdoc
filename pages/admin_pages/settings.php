<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Settings - BASC</title>
	<link rel="icon"  href="../../img/logo/logo_osas.png"><link rel="stylesheet" href="../../assets/externalCSS/dash.css">
    <link rel="stylesheet" href="../../assets/alertifyjs/css/alertify.min.css"> <script src="../../assets/alertifyjs/alertify.min.js"></script> <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="../../assets/externalCSS/style.css">
   	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Special+Gothic+Expanded+One&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
  
	<style>
		body {
		font-family: 'Poppins', sans-serif;
		background: #f0f4f8;
		color: #1a202c;
		}
		.panel {
			background: #fff;
			padding: 1.5rem;
			border-radius: 1rem;
			border: 1px solid #e2e8f0;
			box-shadow: 0 4px 6px rgba(0,0,0,0.1);
		}
		/* Styles for the E-Signature Dropzone */
		.dropzone-area {
			transition: all 0.3s ease;
		}
		.dropzone-area:hover, .dropzone-area.border-primary {
			border-color: #4768e1 !important; /* BASC blue */
		}
		.dropzone-content {
			color: #6c757d;
		}
		#previewWrapper {
			height: 200px !important; /* Adjusted for better visibility */
			overflow: hidden;
		}
		#previewImage {
			max-height: 100%;
			width: auto;
			object-fit: contain !important;
		}

        /* Settings card style */
        .settings-card {
            margin-bottom: 2rem;
        }

  </style>
</head>
<?php
  require '../../config/dbcon.php';
	
    // EXISTING AUTHENTICATION LOGIC - DO NOT CHANGE
    if(!empty($_SESSION['user_role'])){
        $excludedValues = ["admin"];
        $filteredArray = array_diff($allroles, $excludedValues);

        if (in_array($_SESSION['user_role'], $filteredArray)) {
            header('location: ../../config/redirect.php');
        }
    }else{
        header("location: ../logout.php");
    }

    // =======================================================
    // DATABASE INTEGRATION FOR USER PROFILE AND E-SIGNATURE
    // =======================================================
    $user_id = $_SESSION['user_id'] ?? 0;
    $user_data = [
        'full_name' => 'N/A',
        'email' => 'N/A',
        'role' => $_SESSION['user_role'] ?? 'Guest',
        'e_signature_path' => null
    ];
    $signature_status = "Not Uploaded";

    if ($user_id > 0 && isset($conn)) {
        // !!! REPLACE 'users' AND COLUMN NAMES ('first_name', 'last_name', 'email', 'e_signature_path') 
        //     WITH ACTUAL NAMES FROM stud_org_gemini(2).sql
        $sql = "SELECT first_name, last_name, email, e_signature_path 
                FROM users 
                WHERE user_id = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
			
            $user_data['full_name'] = htmlspecialchars($row['first_name'] . ' ' . $row['last_name']);
            $user_data['email'] = htmlspecialchars($row['email']);
            $user_data['e_signature_path'] = $row['e_signature_path'];
            
            if (!empty($user_data['e_signature_path'])) {
                $signature_status = "Uploaded: " . basename($user_data['e_signature_path']);
            }
        }
        $stmt->close();
    }
    // =======================================================
?>
<body>
    <?php
    include '../Components/sidebar.php';
    ?>
    <div class="container-fluid">
        <div class="container-fluid rounded-3 p-3 my-3">
            <h2 class="mb-4">⚙️ User Settings</h2>
            
            <div class="row">
                <div class="col-lg-6 settings-card">
                    <div class="panel">
                        <h5 class="mb-3 d-flex align-items-center"><i class='bx bxs-user-detail me-2'></i> Profile Information</h5>
                        <form>
                            <div class="mb-3">
                                <label for="fullName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="fullName" value="<?php echo $user_data['full_name']; ?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" value="<?php echo $user_data['email']; ?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <input type="text" class="form-control" id="role" value="<?php echo htmlspecialchars(ucfirst($user_data['role'])); ?>" disabled>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-lg-6 settings-card">
                    <div class="panel">
                        <h5 class="mb-3 d-flex align-items-center"><i class='bx bxs-lock-alt me-2'></i> Account Security</h5>
                        <form id="changePasswordForm" method="POST" action="../function/dean/change_password.php">
                            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>"> 
                            <div class="mb-3">
                                <label for="currentPassword" class="form-label">Current Password</label>
                                <input type="password" class="form-control" id="currentPassword" name="current_password" required>
                            </div>
                            <div class="mb-3">
                                <label for="newPassword" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="newPassword" name="new_password" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirmNewPassword" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirmNewPassword" name="confirm_new_password" required>
                            </div>
                            <button type="submit" class="btn btn-primary basc-green-button">Change Password</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 settings-card">
                    <div class="panel">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="d-flex align-items-center"><i class='bx bxs-signature me-2'></i> E-Signature Management</h5>
                            <button data-bs-toggle="modal" data-bs-target="#uploadSignatureModal" type="button" class="basc-green-button btn btn-success btn-sm">
                                <i class='bx bx-upload me-1'></i> Upload/Change E-Signature
                            </button>
                        </div>
                        <p class="text-muted small mt-2">Current Status: <strong><?php echo htmlspecialchars($signature_status); ?></strong></p>
                        <?php if (!empty($user_data['e_signature_path'])): ?>
                            <p class="text-muted small">Your current signature will be used for document endorsement.</p>
                            <div class="mt-3 p-2 border rounded text-center">
                                <img src="<?php echo htmlspecialchars($user_data['e_signature_path']); ?>" alt="Current E-Signature" style="max-width: 100%; max-height: 100px; object-fit: contain;">
                            </div>
                        <?php else: ?>
                            <p class="text-danger small">No E-Signature uploaded. You cannot endorse documents without one.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-lg-6 settings-card">
                    <div class="panel">
                        <h5 class="d-flex align-items-center"><i class='bx bxs-cog me-2'></i> Application Preferences</h5>
                        <p class="text-muted">No additional application settings available at this time.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
    </main>

    <div class="modal fade" id="uploadSignatureModal" tabindex="-1" aria-labelledby="uploadSignatureModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadSignatureModalLabel">Upload your e-signature</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="uploadSignatureForm" method="POST" action="../function/dean/e_signature_upload.php" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="d-flex justify-content-center w-100">
                        <label for="signatureImage" 
                                class="w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4 border border-2 border-secondary rounded bg-light text-center dropzone-area"
                                style="height: 250px; cursor: pointer; position: relative;">
                            
                            <div class="dropzone-content">
                            <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-cloud-arrow-up" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M7.646 5.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 6.707V10.5a.5.5 0 0 1-1 0V6.707L6.354 7.854a.5.5 0 1 1-.708-.708z"/>
                                <path d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383m.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z"/>
                            </svg>
                            <p class="mb-1"><strong>Click to upload</strong> or drag & drop</p>
                            <p class="small text-muted">SVG, PNG, JPG, GIF (MAX. 800x400px)</p>
                            </div>

                            <div id="previewWrapper" class="d-none w-100 h-100 position-relative">
                            <img id="previewImage" class="img-fluid rounded" style="height: 100px; object-fit: contain;" />
                            <div class="position-absolute top-0 end-0 m-2">
                                <button type="button" id="removeImageBtn" class="btn btn-sm btn-danger me-1">Remove</button>
                                <button type="button" id="changeImageBtn" class="btn btn-sm btn-primary">Change</button>
                            </div>
                            </div>

                            <input id="signatureImage" name="signatureImage" type="file" class="d-none" accept="image/*" required/>
                        </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success basc-green-button">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script src="../../assets/jquery/jquery-3.7.1.min.js"></script>
    <script src="../../assets/externalJS/script.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/darkmode.js" defer></script>
    <script src="../../assets/count.js" defer></script>
    <script src="../../assets/externalJS/app.js"></script>
    
    
    
	<script>
        // RETAINED E-SIGNATURE UPLOAD JAVASCRIPT
		document.addEventListener("DOMContentLoaded", function () {
			const dropzone = document.querySelector(".dropzone-area");
			const fileInput = document.getElementById("signatureImage");
			const previewImage = document.getElementById("previewImage");
			const previewWrapper = document.getElementById("previewWrapper");
			const dropzoneContent = document.querySelector(".dropzone-content");
			const removeImageBtn = document.getElementById("removeImageBtn");
			const changeImageBtn = document.getElementById("changeImageBtn");

			// Highlight dropzone on drag
			["dragenter", "dragover"].forEach(evt => {
			dropzone.addEventListener(evt, e => {
				e.preventDefault();
				e.stopPropagation();
				dropzone.classList.add("border-primary", "bg-white");
			});
			});

			["dragleave", "drop"].forEach(evt => {
			dropzone.addEventListener(evt, e => {
				e.preventDefault();
				e.stopPropagation();
				dropzone.classList.remove("border-primary", "bg-white");
			});
			});

			// Handle drop
			dropzone.addEventListener("drop", e => {
			fileInput.files = e.dataTransfer.files;
			showPreview(fileInput.files[0]);
			});

			// Handle file selection
			fileInput.addEventListener("change", () => {
			if (fileInput.files.length > 0) {
				showPreview(fileInput.files[0]);
			}
			});

			function showPreview(file) {
			if (!file.type.startsWith("image/")) return;

			const reader = new FileReader();
			reader.onload = function (e) {
				previewImage.src = e.target.result;
				previewWrapper.classList.remove("d-none");
				dropzoneContent.classList.add("d-none"); // Hide default content
			};
			reader.readAsDataURL(file);
			}

			// Remove image
			removeImageBtn.addEventListener("click", () => {
			fileInput.value = ""; // Clear input
			previewImage.src = "";
			previewWrapper.classList.add("d-none");
			dropzoneContent.classList.remove("d-none");
			});

			// Change image (trigger file input again)
			changeImageBtn.addEventListener("click", () => {
			fileInput.click();
			});
		});

        // Placeholder for Change Password form submission
        $("#changePasswordForm").on("submit", function(e) {
            e.preventDefault();
            
            // Basic client-side validation for new password match
            const newPass = $("#newPassword").val();
            const confirmPass = $("#confirmNewPassword").val();

            if (newPass !== confirmPass) {
                alertify.error("New passwords do not match!");
                return;
            }

            // AJAX call to change_password.php
            $.post($(this).attr("action"), $(this).serialize(), function(data) {
                if (data.success) {
                    alertify.success(data.message || "Password changed successfully!");
                    $("#changePasswordForm")[0].reset(); // Clear the form
                } else {
                    alertify.error(data.message || "Failed to change password. Check your current password.");
                }
            }, "json").fail(function() {
                alertify.error("A server error occurred during password change.");
            });
        });
	</script>
</body>
</html>