<?php
// Include the database connection and session management file.
require '../../config/dbcon.php';

if(!empty($_SESSION['user_role'])){
    $excludedValues = ["admin"];
    $filteredArray = array_diff($allroles, $excludedValues);

    if (in_array($_SESSION['user_role'], $filteredArray)) {
        header('location: ../../config/redirect.php');
    }
}else{
    header("location: ../logout.php");
}



// Fetch all organizations
$sql_orgs = "SELECT organization_id, name, type FROM organizations ORDER BY name ASC";
$result_orgs = $conn->query($sql_orgs);
$organizations = $result_orgs->fetch_all(MYSQLI_ASSOC);

// Document counts (Example: Approved Documents)
$sql_doc_counts = "SELECT organization_id, status, COUNT(*) as count FROM documents WHERE is_archived = 0 GROUP BY organization_id, status";
$result_doc_counts = $conn->query($sql_doc_counts);

$doc_summary = [];
while ($row = $result_doc_counts->fetch_assoc()) {
    $org_id = $row['organization_id'];
    if (!isset($doc_summary[$org_id])) {
        $doc_summary[$org_id] = [
            'total' => 0,
            'approved' => 0,
            'pending' => 0
        ];
    }
    $doc_summary[$org_id]['total'] += $row['count'];
    if ($row['status'] == 'approved') {
        $doc_summary[$org_id]['approved'] = $row['count'];
    } elseif ($row['status'] == 'pending') {
        $doc_summary[$org_id]['pending'] = $row['count'];
    }
}
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
                <li><a href="#">Admin</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a class="active" href="#">Organization Settings</a></li>
            </ul>
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow border-success">
                        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                            Registered Organizations
                            <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#createOrgModal">
                                + Create New Organization
                            </button>
                        </div>
                        <ul class="list-group list-group-flush">
                            <?php if (count($organizations) > 0): ?>
                                <?php foreach ($organizations as $org): ?>
                                    <?php
                                    $id = $org['organization_id'];
                                    $summary = $doc_summary[$id] ?? ['total' => 0, 'approved' => 0, 'pending' => 0];
                                    $type_badge = $org['type'] == 'academic' ? 'primary' : 'info';
                                    ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="ms-2 me-auto">
                                            <div class="fw-bold text-success"><?php echo htmlspecialchars($org['name']); ?> 
                                                <span class="badge bg-<?php echo $type_badge; ?> ms-2"><?php echo ucfirst(htmlspecialchars($org['type'])); ?></span>
                                            </div>
                                            Total Documents: "<?php echo $summary['total']; ?>"
                                        </div>
                                        <div>
                                            <span class="badge bg-success me-2">Approved: "<?php echo $summary['approved']; ?>"</span>
                                            <span class="badge bg-warning text-dark me-2">Pending: "<?php echo $summary['pending']; ?>"</span>
                                            
                                            <a href="org_documents.php?org_id=<?php echo $id; ?>" class="btn btn-sm btn-outline-success">View Documents</a>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li class="list-group-item">No organizations registered.</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="modal fade" id="createOrgModal" tabindex="-1" aria-labelledby="createOrgModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title" id="createOrgModalLabel">Create New Organization</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="organizations.php" method="POST">
              <div class="modal-body">
                  <div class="mb-3">
                      <label for="orgName" class="form-label">Organization Name</label>
                      <input type="text" class="form-control" id="orgName" name="org_name" required>
                  </div>
                  <div class="mb-3">
                      <label for="orgType" class="form-label">Organization Type</label>
                      <select class="form-select" id="orgType" name="org_type" required>
                          <option value="academic">Academic</option>
                          <option value="non_academic">Non-Academic</option>
                      </select>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="create_org" class="btn btn-success">Create Organization</button>
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
        });
    </script>
</body>
</html>