<?php
// Include the database connection and session management file.
require '../../config/dbcon.php';

if(!empty($_SESSION['user_role'])){
    $adviser_id = $_SESSION['user_id'] ?? 0;
    $excludedValues = ["adviser"];
    $filteredArray = array_diff($allroles, $excludedValues);

    if (in_array($_SESSION['user_role'], $filteredArray)) {
        header('location: ../../config/redirect.php');
    }
}else{
    header("location: ../logout.php");
}

$adviser_organization_id = $_SESSION['organization_id'];

// Fetch all students for the adviser's organization (QUERY 1)
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

// Fetch all available ranks (QUERY 2)
$ranks_query = $conn->prepare("SELECT rank_id, rank_name FROM organization_ranks ORDER BY rank_id");
$ranks_query->execute();
$ranks_result = $ranks_query->get_result();
$ranks = $ranks_result->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adviser | Manage Ranks</title>
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
        .card-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #4768e1;
        }
        .card-title-sm {
            font-size: 1rem;
            font-weight: 500;
            color: #6c757d;
        }
        .text-pending { color: #ffc107 !important; }
        .text-approved { color: #198754 !important; }
        .text-revision { color: #dc3545 !important; }
        .bg-pending { background-color: #ffc107 !important; }
        .bg-approved { background-color: #198754 !important; }
        .bg-revision { background-color: #dc3545 !important; }

		/* NEW CSS: Ensure the modal body is contained and scrollable */
		.modal-dialog-scrollable .modal-body {
			max-height: calc(100vh - 200px); /* Adjust this value as needed */
			overflow-y: auto;
		}
  </style>
</head>

<body>
    <?php include '../Components/sidebar.php' ?>

        <div class="container-fluid">
            <h2 class="mt-4 mb-2">Manage Student Ranks</h2>
            <ul class="breadcrumb mb-4">
                <li><a href="#">Adviser</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a class="active" href="#">Student Ranks</a></li>
            </ul>
				<div class="container-fluid rounded-3 border border-secondary-subtle p-3 my-3">
					<!-- <div class="d-flex justify-content-between align-items-center px-2">
						<h4>Update logo</h4>
						<button data-bs-toggle="modal" data-bs-target="#uploadSignatureModal" type="button" class="basc-green-button btn btn-success d-flex justify-content-center align-items-center">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
								<path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
							</svg>&nbsp;Upload E-Signature
						</button>
					</div> -->
                    
                    <div class="head mb-3">
                        <h4 class="text-primary">Update logo</h4>
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
    
    <script src="../../assets/jquery/jquery-3.7.1.min.js"></script> 
    <script src="../../assets/externalJS/script.js"></script>
	<script	script src="../../assets/datatables/dataTables.min.js"></script> 
    <script	script src="../../assets/datatables/dataTables.bootstrap5.js"></script> 
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>

    <script src="../../assets/externalJS/app.js"></script>
    
    
    <script>
        $(document).ready(function() {
            // Initialize datatables
            new DataTable('#example', {
                responsive: true
            });
            
            $(document).ready( function () {
                $('#myTable').DataTable();
            } );

            // Handle rank change
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
        });
    </script>
</body>
</html>