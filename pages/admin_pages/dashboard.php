<?php
  require '../../config/dbcon.php';
	
    if(!empty($_SESSION['user_role'])){
        $admin_id = $_SESSION['user_id'] ?? 0;
        $excludedValues = ["admin"];
        $filteredArray = array_diff($allroles, $excludedValues);

        if (in_array($_SESSION['user_role'], $filteredArray)) {
            header('location: ../../config/redirect.php');
        }
    }else{
        header("location: ../logout.php");
    }

    // Ensure database connection is established by dbcon.php
    if (!isset($conn)) {
        die("Error: Database connection not available. Check dbcon.php.");
    }
    
    // Total Organizations
    $sql_orgs = "SELECT COUNT(*) AS total_orgs FROM organizations";
    $result_orgs = $conn->query($sql_orgs);
    $total_orgs = $result_orgs->fetch_assoc()['total_orgs'];

    // Total Documents
    $sql_docs = "SELECT COUNT(*) AS total_docs FROM documents";
    $result_docs = $conn->query($sql_docs);
    $total_docs = $result_docs->fetch_assoc()['total_docs'];

    // Recent Activities (Limit to 5)
    $sql_activities = "SELECT
        o.name AS organization_name,
        ura.description,
        ura.created_at
    FROM
        org_recent_activities ura
    JOIN
        organizations o ON ura.organization_id = o.organization_id
    ORDER BY
        ura.created_at DESC
    LIMIT 10";
    $result_activities = $conn->query($sql_activities);
    $recent_activities = $result_activities->fetch_all(MYSQLI_ASSOC);

    // Total Pending Approvals (Status: 'pending')
    $query_pending = "SELECT COUNT(*) AS total_pending FROM documents WHERE status = 'endorsed'";
    $result_pending = $conn->query($query_pending);
    $total_pending = $result_pending->fetch_assoc()['total_pending'] ?? 0;

    // Approved This Month (Status: 'approved' this month)
    $query_approved = "SELECT COUNT(*) AS approved_this_month FROM documents
                        WHERE NOT status = 'revision' AND NOT status = 'draft' 
                        AND MONTH(updated_at) = MONTH(CURRENT_DATE()) 
                        AND YEAR(updated_at) = YEAR(CURRENT_DATE())";
    $result_approved = $conn->query($query_approved);
    $approved_this_month = $result_approved->fetch_assoc()['approved_this_month'] ?? 0;


    // 4. NOTIFICATIONS (Last 5 notifications specific to the adviser)
    // NOTE: This uses 'o.name' from 'organizations' and aliases it as 'organization_name'.
    $query_notifications = "
        SELECT 
            n.message, 
            d.pdf_filename, 
            o.name AS organization_name,
            DATE_FORMAT(n.created_at, '%b %d %I:%i %p') AS notification_time
        FROM notifications n
        JOIN documents d ON n.document_id = d.document_id
        JOIN organizations o ON d.organization_id = o.organization_id
        WHERE n.user_id = $admin_id -- Target the current adviser
        ORDER BY n.created_at DESC
        LIMIT 5;
    ";
    $result_notifications = $conn->query($query_notifications);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BASC</title>
	<link rel="icon"  href="../../img/logo/logo_osas.png"><!-- sample icon -->
    <link rel="stylesheet" href="../../assets/externalCSS/dash.css">
    <link rel="stylesheet" href="../../assets/alertifyjs/css/alertify.min.css"> <!-- added by me -->
    <script src="../../assets/alertifyjs/alertify.min.js"></script> <!-- added by me -->
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="../../assets/datatables/bootstrap.min.css" /> <!-- added by me -->
	<link rel="stylesheet" href="../../assets/datatables/dataTables.bootstrap5.css" /> <!-- added by me -->
	<link rel="stylesheet" href="../../assets/externalCSS/style.css">
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
			max-height: calc(100vh - 130px); /* Adjust this value as needed */
			overflow-y: auto;
		}
  </style>
</head>
<body>
        <?php
        include '../Components/sidebar.php';
        ?>
        <div class="container-fluid">
            <div class="row py-4 ">

                <div class="col-lg-8">
                    <div class="row">

                        <div class="col-12 mb-3">
                            <div class="row align-items-center">
                                <div class="col-md-4 mb-3">
                                    <div class="card shadow border-left-warning h-100 py-2">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                        Total Organizations
                                                    </div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800 card-number">
                                                        <?= $total_orgs; ?>
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fas fa-people-group fa-2x text-info"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card shadow border-left-success h-100 py-2">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                        Total Documents
                                                    </div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800 card-number">
                                                        <?= $total_docs; ?>
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fas fa-file fa-2x text-warning"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card shadow border-left-danger h-100 py-2">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                        Approved Documents
                                                    </div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800 card-number">
                                                        <?= $approved_this_month; ?>
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fas fa-check-circle fa-2x text-primary"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mb-4">
                            <div class="panel">
                                <h5 class="mb-3">Recent Activity</h5>
                                
                                <ul class="list-group list-group-flush">
                                    <?php if (count($recent_activities) > 0): ?>
                                        <?php foreach ($recent_activities as $activity): ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                                <div class="ms-2 me-auto">
                                                    <div class="fw-bold"><?php echo htmlspecialchars($activity['organization_name']); ?></div>
                                                    <?php echo htmlspecialchars($activity['description']); ?>
                                                </div>
                                                <span class="badge bg-secondary rounded-pill"><?php echo date('M d, Y H:i', strtotime($activity['created_at'])); ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li class="list-group-item">No recent activity recorded.</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card eventcard shadow m-0" style="height: 100%;">
                        <div class="card-body">
                            <h4 class="card-title titlesummary text-center mb-3"><i class="fas fa-bell me-2 text-warning"></i> Notifications</h4>
                            <hr class="m-0">
                            <div class="list-group list-group-flush mt-3">
                                <?php if ($result_notifications->num_rows > 0): ?>
                                    <?php while ($notification = $result_notifications->fetch_assoc()): ?>
                                        <div class="list-group-item list-group-item-action d-flex flex-column align-items-start">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1 text-primary">
                                                    "<?= htmlspecialchars($notification['organization_name']); ?>" submitted "<?= htmlspecialchars($notification['pdf_filename']); ?>" for your approval.
                                                </h6>
                                            </div>
                                            <small class="text-muted">
                                                <?= htmlspecialchars($notification['message']); ?>
                                            </small>
                                            <small class="text-info w-100 text-end">
                                                <i class="fas fa-calendar-alt me-1"></i> <?= htmlspecialchars($notification['notification_time']); ?>
                                            </small>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <p class="text-center text-muted mt-5">No new notifications.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<!-- end of the document -->
	</div>
    <script src="../../assets/jquery/jquery-3.7.1.min.js"></script> <!-- added by me -->
    <script src="../../assets/externalJS/script.js"></script>
	<!-- <script	script src="../../assets/datatables/bootstrap.bundle.min.js"></script> -->
	<script	script src="../../assets/datatables/dataTables.min.js"></script> <!-- added by me -->
	<script	script src="../../assets/datatables/dataTables.bootstrap5.js"></script> <!-- added by me -->
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../../../assets/js/date-fns.js"></script>
	<script src="../../assets/darkmode.js" defer></script>
	<script src="../../assets/count.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="../../assets/externalJS/app.js"></script>
    
	<script>
		
        $(document).ready(function() {
            // Initialize DataTable for Pending Approvals
            $('#pendingDocumentsTable').DataTable({
                "order": [[2, "asc"]], // Order by Submitted Date ascending
                "paging": true,
                "searching": true,
                "info": false
            });
        });

    </script>
</body>
</html>
