<?php
// Include the database connection and session management file.
require '../../config/dbcon.php';

// --- Security and Session Check ---
if(!empty($_SESSION['user_role'])){
    $excludedValues = ["adviser"];
    $filteredArray = array_diff($allroles, $excludedValues);

    if (in_array($_SESSION['user_role'], $filteredArray)) {
        header('location: ../../config/redirect.php');
    }
}else{
    header("location: ../logout.php");
}

$adviser_organization_id = $_SESSION['organization_id'] ?? null;

if (!$adviser_organization_id) {
    die("Error: Organization ID not found in session.");
}
// ----------------------------------


// --- Fetch Organization Data (QUERY 3 - for Logo) ---
$org_data_query = $conn->prepare("SELECT name, logo FROM organizations WHERE organization_id = ?");
$org_data_query->bind_param("i", $adviser_organization_id);
$org_data_query->execute();
$org_data_result = $org_data_query->get_result();
$organization = $org_data_result->fetch_assoc();
$org_data_query->close();


// --- Fetch Students for Rank Management (QUERY 1) ---
$students_query = $conn->prepare("
    SELECT u.user_id, u.first_name, u.last_name, u.email, r.rank_name, r.rank_id AS current_rank_id
    FROM users u
    LEFT JOIN organization_ranks r ON u.rank_id = r.rank_id
    WHERE u.organization_id = ? AND u.user_role IN ('academic_organization', 'non_academic_organization')
    ORDER BY u.last_name, u.first_name
");
$students_query->bind_param("i", $adviser_organization_id);
$students_query->execute();
$students_result = $students_query->get_result();
$students_query->close();


// --- Fetch all available ranks (QUERY 2) ---
$ranks_query = $conn->prepare("SELECT rank_id, rank_name FROM organization_ranks ORDER BY rank_id");
$ranks_query->execute();
$ranks_result = $ranks_query->get_result();
$ranks = $ranks_result->fetch_all(MYSQLI_ASSOC);
$ranks_query->close();

// $conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adviser | Organization Management</title>
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
        /* ... existing styles from manage_ranks.php ... */

		/* NEW CSS: Ensure the modal body is contained and scrollable */
		.modal-dialog-scrollable .modal-body {
			max-height: calc(100vh - 130px);  /* Adjust this value as needed */
			overflow-y: auto;
		}
  </style>
</head>

<body>
    <?php include '../Components/sidebar.php' ?>

        <div class="container-fluid">
            <h2 class="mt-4 mb-2">Organization Management</h2>
            <ul class="breadcrumb mb-4">
                <li><a href="#">Adviser</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a class="active" href="#">Organization Settings</a></li>
            </ul>
            
            <div class="container-fluid rounded-3 border border-secondary-subtle p-3 my-3">
                
                <div class="row mb-5">
                    <div class="col-md-12">
                        <div class="panel">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="text-primary">Manage Organization Logo</h4>
                                <button type="button" class="basc-green-button btn btn-success d-flex justify-content-center align-items-center" data-bs-toggle="modal" data-bs-target="#updateLogoModal">
                                    <i class='bx bx-upload'></i>&nbsp;Update Logo
                                </button>
                            </div>
                            <p class="text-muted">Current logo for "<?php echo htmlspecialchars($organization['name'] ?? 'Your Organization'); ?>".</p>
                            <div class="text-center mt-4">
                                <?php if (!empty($organization['logo'])): ?>
                                    <img src="../../<?php echo htmlspecialchars($organization['logo']); ?>" alt="Current Organization Logo" style="max-width: 150px; height: auto; border: 1px solid #ccc; padding: 5px; border-radius: 50%;">
                                <?php else: ?>
                                    <p>No logo currently set.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="head mb-3 mt-4">
                    <h4 class="text-primary">Manage Student Ranks</h4>
                    <p class="text-muted">Set or change the ranks for members of your organization.</p>
                </div>
                <?php if ($students_result->num_rows > 0): ?>
                <table id="myTable" class="table table-striped text-start">
                    <thead>
                        <tr>
                            <th>Student Email (ID)</th>
                            <th>Full Name</th>
                            <th>Current Rank</th>
                            <th>Assign Rank</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($student = $students_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($student['email']); ?></td>
                            <td><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($student['rank_name'] ?? 'N/A'); ?></td>
                            <td>
                                
                                <select class="form-select rank-select" data-user-id="<?php echo $student['user_id']; ?>">
                                    <?php if (empty($student['current_rank_id'])): ?>
                                        <option value="" selected disabled>Select Rank</option>
                                    <?php endif; ?>

                                    <?php foreach ($ranks as $rank): ?>
                                        <option value="<?php echo $rank['rank_id']; ?>" <?php if ($student['current_rank_id'] == $rank['rank_id']) echo 'selected'; ?>>
                                            <?php echo htmlspecialchars($rank['rank_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p class="text-center text-muted mt-3">No students found for your organization.</p>
                <?php endif; ?>
                </div>
        </div>
    </main>

    <div class="modal fade" id="updateLogoModal" tabindex="-1" aria-labelledby="updateLogoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateLogoModalLabel">Upload New Logo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="logoUpdateForm" action="../function/adviser/process_organization_logo_update.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="logoFile" class="form-label">Select New Logo (JPG, PNG, GIF)</label>
                            <input type="file" class="form-control" id="logoFile" name="logoFile" accept="image/jpeg,image/png,image/gif" required>
                            <small class="text-muted">This will replace the current logo for "<?php echo htmlspecialchars($organization['name'] ?? 'Your Organization'); ?>".</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success basc-green-button">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="../../assets/jquery/jquery-3.7.1.min.js"></script> 
    <script src="../../assets/externalJS/script.js"></script>
	<script	script src="../../assets/datatables/dataTables.min.js"></script> 
    <script	script src="../../assets/datatables/dataTables.bootstrap5.js"></script> 
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>

    <script src="../../assets/externalJS/app.js"></script>
    
    
    <script>
        $(document).ready(function() {
            // Initialize datatables
            // new DataTable('#example', { responsive: true }); // Commented out unused example table init
            
            $('#myTable').DataTable();
            
            // --- Rank Update AJAX (Existing from manage_ranks.php) ---
            $(document).on('change', '.rank-select', function() {
                const userId = $(this).data('user-id');
                const rankId = $(this).val();

                $.post('../function/adviser/update_rank.php', {
                    user_id: userId,
                    rank_id: rankId
                }, function(response) {
                    if (response.success) {
                        alertify.success('Rank updated successfully.');
                    } else {
                        alertify.error('Failed to update rank: ' + response.message);
                    }
                }, 'json').fail(function() {
                    alertify.error('Server error. Could not update rank.');
                });
            });

            // --- Logo Update AJAX (New, adapted from update_logo.php) ---
            $('#logoUpdateForm').on('submit', function(e) {
                e.preventDefault();
                
                // Use FormData to handle file uploads via AJAX
                const formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false, // Prevents jQuery from processing the data
                    contentType: false, // Prevents jQuery from setting the content type
                    dataType: 'json', // Expecting JSON response
                    success: function(response) {
                        if (response.success) {
                            alertify.success(response.message);
                            $('#updateLogoModal').modal('hide'); // Close modal on success
                            // Reload the page to show the new logo
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            alertify.error(response.message);
                        }
                    },
                    error: function() {
                        alertify.error("A server error occurred. Could not update logo.");
                    }
                });
            });
        });
    </script>
</body>
</html>