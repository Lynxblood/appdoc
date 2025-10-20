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
    if ($row['status'] == 'approved_fssc') {
        $doc_summary[$org_id]['approved'] = $row['count'];
    } elseif (($row['status'] != 'draft') && ($row['status'] != 'revision') && ($row['status'] != 'approved_fssc')) {
        $doc_summary[$org_id]['pending'] = $row['count'];
    }
}
// PHP for handling form submission (inside organizations.php or a separate handler)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_org'])) {
    $org_name = $conn->real_escape_string($_POST['org_name']);
    $org_type = $conn->real_escape_string($_POST['org_type']);

    // INSERT query
    $sql_insert = "INSERT INTO organizations (name, type) VALUES ('$org_name', '$org_type')";

    if ($conn->query($sql_insert) === TRUE) {
        // Success: Redirect to refresh the page and see the new organization
        header("Location: manage_organization.php?status=success");
        exit();
    } else {
        // Error: You would typically show a user-friendly error message here
        $error = "Error: " . $sql_insert . "<br>" . $conn->error;
        // In a real app, you'd log this and show a generic error to the user.
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
        <div class="container-fluid rounded-3 border border-secondary-subtle p-3 my-3 overflow-x-scroll">
            <div>
                <div class="d-flex justify-content-between align-items-center px-2">
                    <h4>Registered Organizations</h4>
                    <button id="newOrgButton" data-bs-toggle="modal" data-bs-target="#createOrgModal" type="button" class="basc-green-button btn btn-success d-flex justify-content-center align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                        </svg>&nbsp;New organization
                    </button>
                </div>
                <table id="myTable" class="table table-striped text-start">
                    <thead>
                        <tr>
                            <th>Organization ID</th>
                            <th>Organization</th>
                            <th>Document summary</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                            <?php if (count($organizations) > 0): ?>
                                <?php foreach ($organizations as $org): ?>
                                    <?php
                                    $id = $org['organization_id'];
                                    $summary = $doc_summary[$id] ?? ['total' => 0, 'approved' => 0, 'pending' => 0];
                                    $type_badge = $org['type'] == 'academic' ? 'primary' : 'info';
                                    ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($id); ?></td>
                                            <td>
                                                <div class="ms-2 me-auto">
                                                    <div class="fw-bold text-success"><?php echo htmlspecialchars($org['name']); ?> 
                                                        <span class="badge bg-<?php echo $type_badge; ?> ms-2"><?php echo ucfirst(htmlspecialchars($org['type'])); ?></span>
                                                    </div>
                                                    Total Documents: "<?php echo $summary['total']; ?>"
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <span class="badge bg-success me-2">Approved: "<?php echo $summary['approved']; ?>"</span>
                                                    <span class="badge bg-warning text-dark me-2">Pending: "<?php echo $summary['pending']; ?>"</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group dropstart">
                                                    <button class="btn btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                                            <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                                                        </svg>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item view-pdf" href="#" data-id="<?= $id; ?>">View</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                </table>
            </div>
        </div>
    </main>

    <div class="modal fade" id="createOrgModal" tabindex="-1" aria-labelledby="createOrgModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="createOrgModalLabel">Create New Organization</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="manage_organization.php" method="POST">
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
                <button type="submit" name="create_org" class="btn btn-success basc-green-button">Create Organization</button>
              </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="orgDocumentsModal" tabindex="-1" aria-labelledby="orgDocumentsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="orgDocumentsModalLabel">Organization Documents</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="orgDocumentsContent" class="p-3 text-center">
            <div class="spinner-border text-success" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            </div>
        </div>
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
            // Initialize DataTable for Pending Approvals
            $('#myTable').DataTable({
                "order": [[3, "desc"]], // Order by Submitted Date ascending
                "paging": true,
                "searching": true,
                "info": false
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const modal = new bootstrap.Modal(document.getElementById('orgDocumentsModal'));
            const modalBody = document.getElementById('orgDocumentsContent');

            document.querySelectorAll('.view-pdf').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const orgId = this.getAttribute('data-id');

                    // Show loading spinner
                    modalBody.innerHTML = `
                        <div class="text-center p-5">
                            <div class="spinner-border text-success" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-3 text-muted">Loading documents...</p>
                        </div>
                    `;

                    // Fetch the content
                    fetch('org_documents.php?org_id=' + orgId)
                        .then(response => response.text())
                        .then(data => {
                            // Extract only the container part from the response
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(data, 'text/html');
                            const container = doc.querySelector('.container');
                            modalBody.innerHTML = container ? container.innerHTML : '<p class="text-danger">Failed to load content.</p>';
                        })
                        .catch(err => {
                            modalBody.innerHTML = `<div class="alert alert-danger">Error loading content: ${err}</div>`;
                        });

                    modal.show();
                });
            });
        });
</script>

</body>
</html>