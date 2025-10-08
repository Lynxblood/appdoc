<?php
  // organization_dashboard.php
  require '../../config/dbcon.php';
  
  // Assuming formatDateTime function is available in a required file or defined here
  if (!function_exists('formatDateTime')) {
    function formatDateTime($date) {
        return date("M d, Y h:i A", strtotime($date));
    }
  }

  // --- ACCESS CONTROL AND USER DATA FETCH ---
  if(!empty($_SESSION['user_role'])){
      // Allow only 'academic_organization' to access this page
      if ($_SESSION['user_role'] !== "academic_organization") {
          header('location: ../../config/redirect.php');
          exit();
      }
  }else{
      header("location: ../logout.php");
      exit();
  }
    
  $user_id = $_SESSION['user_id'];
  
  // 1. Fetch the organization_id of the current user
  $organization_id_query = $conn->prepare("SELECT organization_id FROM users WHERE user_id = ?");
  $organization_id_query->bind_param("i", $user_id);
  $organization_id_query->execute();
  $organization_id_result = $organization_id_query->get_result();
  $organization_id = $organization_id_result->fetch_assoc()['organization_id'];
  $organization_id_query->close();

  // 2. Fetch the organization's details using CORRECT columns
  $org_details = [];
  if ($organization_id) {
      // CORRECTED QUERY: Using only 'name' and 'type' from 'organizations' table
      $org_query = $conn->prepare("SELECT o.name, o.type, r.rank_name FROM organizations o JOIN organization_ranks r ON o.rank_id = r.rank_id WHERE organization_id = ?");
      $org_query->bind_param("i", $organization_id);
      $org_query->execute();
      $org_result = $org_query->get_result();
      $org_details = $org_result->fetch_assoc();
      $org_query->close();
  }

  // 3. Fetch all members/users of this organization
  $members_query = $conn->prepare("SELECT user_id, first_name, last_name, email, user_role FROM users WHERE organization_id = ? ORDER BY last_name ASC");
  $members_query->bind_param("i", $organization_id);
  $members_query->execute();
  $members_result = $members_query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organization Dashboard | BASC</title>
	<link rel="icon"  href="../../img/logo/logo_osas.png"><link rel="stylesheet" href="../../assets/externalCSS/dash.css">
    <link rel="stylesheet" href="../../assets/alertifyjs/css/alertify.min.css"> <script src="../../assets/alertifyjs/alertify.min.js"></script> <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="../../assets/datatables/bootstrap.min.css" /> <link rel="stylesheet" href="../../assets/datatables/dataTables.bootstrap5.css" /> <link rel="stylesheet" href="../../assets/externalCSS/style.css">
   	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Special+Gothic+Expanded+One&display=swap" rel="stylesheet">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css' rel='stylesheet' />
	<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js'></script>
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
	</style>
</head>
<body>
        <?php include '../Components/sidebar.php'; ?>
        <main>
        <div class="container-fluid">
            <div class="container-fluid rounded-3 border border-secondary-subtle p-3 my-3 panel">
                <div class="d-flex justify-content-between align-items-center px-2">
                    <h4>Organization Profile</h4>
                    <button data-bs-toggle="modal" data-bs-target="#editOrganizationModal" type="button" class="btn btn-primary d-flex justify-content-center align-items-center">
                        <i class='bx bxs-edit-alt' ></i>&nbsp;Edit Profile
                    </button>
                </div>
                <?php if ($org_details): ?>
                <div class="row pt-3">
                    <div class="col-md-6"><strong>Name:</strong> <?= htmlspecialchars($org_details['name']) ?></div>
                    <div class="col-md-6"><strong>Type:</strong> <?= ucfirst(htmlspecialchars($org_details['type'])) ?></div>
                    <div class="col-md-6"><strong>Rank:</strong> <?= htmlspecialchars($org_details['rank_name']) ?></div>
                    </div>
                <?php else: ?>
                <p class="text-danger mt-3">Organization details not found.</p>
                <?php endif; ?>
            </div>
            
            <div class="container-fluid rounded-3 border border-secondary-subtle p-3 my-3 panel">
                <div class="d-flex justify-content-between align-items-center px-2 mb-3">
                    <h4>Current Members</h4>
                    <button data-bs-toggle="modal" data-bs-target="#addMemberModal" type="button" class="basc-green-button btn btn-success d-flex justify-content-center align-items-center">
                        <i class='bx bx-user-plus'></i>&nbsp;Add Member
                    </button>
                </div>
                <table id="myTable" class="table table-striped text-start">
                    <thead>
                        <tr>
                            <th>Member Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody >
                        <?php
                        if ($members_result->num_rows > 0) {
                            while ($row = $members_result->fetch_assoc()) {
                                $name = htmlspecialchars($row["first_name"]) . " " . htmlspecialchars($row["last_name"]);
                                $is_current_user = ($row["user_id"] == $user_id);
                                echo "<tr>";
                                echo "<td>" . $name . "</td>";
                                echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["user_role"]) . "</td>";
                                echo '<td>';
                                if (!$is_current_user) {
                                    echo '<button class="btn btn-sm btn-outline-danger leave-org-btn" data-id="'. $row['user_id'] .'" data-name="'. $name .'">Remove Member</button>';
                                } else {
                                    echo '<span class="badge text-bg-secondary">Current User</span>';
                                }
                                echo '</td>';
                                echo "</tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        </main>
        
        <div class="modal fade" id="leaveOrganizationModal" tabindex="-1" aria-labelledby="leaveOrganizationModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="leaveOrganizationModalLabel">Remove Member</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
                    <p>Are you sure you want to remove <strong id="memberNamePlaceholder"></strong> from the organization? This action will remove their organization affiliation but keep their user account.</p>
                    <input type="hidden" id="memberIdToRemove">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
					<button type="button" id="confirmLeaveOrganization" class="btn btn-danger">Yes, Remove Member</button>
				</div>
				</div>
			</div>
		</div>
        
        <div class="modal fade" id="editOrganizationModal" tabindex="-1" aria-labelledby="editOrganizationModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="editOrganizationModalLabel">Edit Organization Details</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<form id="editOrganizationForm" action="../function/org/update_org.php" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="orgName" class="form-label">Organization Name</label>
                            <input type="text" class="form-control" id="orgName" name="name" value="<?= htmlspecialchars($org_details['name'] ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="orgType" class="form-label">Organization Type</label>
                            <select class="form-control" id="orgType" name="type" required>
                                <option value="academic" <?= ($org_details['type'] ?? '') == 'academic' ? 'selected' : '' ?>>Academic</option>
                                <option value="non_academic" <?= ($org_details['type'] ?? '') == 'non_academic' ? 'selected' : '' ?>>Non-Academic</option>
                            </select>
                        </div>
                        <input type="hidden" name="organization_id" value="<?= $organization_id ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
				</form>
				</div>
			</div>
		</div>

        <div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="addMemberModalLabel">Add New Member</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
                    <p>You can add members by inviting them to register and select your organization during signup, or by updating their user account in the staff/admin panel.</p>
                    <p class="text-muted small">For a functional implementation, you would need an AJAX endpoint to search for existing users by email and assign them to this organization ID.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				</div>
				</div>
			</div>
		</div>


    <script src="../../assets/jquery/jquery-3.7.1.min.js"></script> <script src="../../assets/externalJS/script.js"></script>
	<script	script src="../../assets/datatables/dataTables.min.js"></script> <script	script src="../../assets/datatables/dataTables.bootstrap5.js"></script> <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../../../assets/js/date-fns.js"></script>
	<script src="../../assets/darkmode.js" defer></script>
	<script src="../../assets/count.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="../../assets/externalJS/app.js"></script>
    
    
    
	<script>
        
        $(document).ready(function() {
            // Initialize DataTable for Members List
            $('#myTable').DataTable({
                "order": [[0, "asc"]], // Order by Name ascending
                "paging": true,
                "searching": true,
                "info": false
            });
        });

        // --- Leave Organization (Remove Member) Logic ---

        // 1. Show the confirmation modal when "Remove Member" button is clicked
		$(document).on("click", ".leave-org-btn", function() {
			let memberId = $(this).data("id");
            let memberName = $(this).data("name");
            
            $("#memberIdToRemove").val(memberId);
            $("#memberNamePlaceholder").text(memberName);
            $("#leaveOrganizationModal").modal("show");
		});

        // 2. Handle the final confirmation button click
        $("#confirmLeaveOrganization").on("click", function() {
            const memberId = $("#memberIdToRemove").val();
            $("#leaveOrganizationModal").modal("hide");

			// Send POST request to the function to set organization_id to NULL
			const xhr = new XMLHttpRequest();
			xhr.open("POST", "../function/org/remove_member.php", true); 
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

			xhr.onreadystatechange = function () {
				if (xhr.readyState === 4 && xhr.status === 200) {
				    let response;
                    try {
                        response = JSON.parse(xhr.responseText);
                    } catch (e) {
                        alertify.error("Server error: Invalid JSON response.");
                        console.error(xhr.responseText);
                        return;
                    }
                    
                    if (response.success) {
                        alertify.success(response.message);
                        window.location.reload();
                    } else {
                        alertify.error(response.message || "Failed to remove member.");
                    }
				}
			};

			xhr.send("user_id=" + encodeURIComponent(memberId) + "&action=remove_from_org"); 
        });

        // --- Edit Organization Form Submission (Requires a new PHP file) ---
        $("#editOrganizationForm").on("submit", function(e) {
            e.preventDefault();
            
            let formData = $(this).serialize();

            $.ajax({
                url: $(this).attr("action"), // Expects ../function/org/update_org.php
                type: "POST",
                data: formData,
                dataType: "json",
                success: function(data) {
                    if (data.success) {
                        alertify.success(data.message);
                        $("#editOrganizationModal").modal("hide");
                        window.location.reload();
                    } else {
                        alertify.error(data.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", error);
                    alertify.error("Something went wrong while saving organization details.");
                }
            });
        });

    </script>
</body>
</html>