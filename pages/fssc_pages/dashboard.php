<?php
  require '../../config/dbcon.php';
	
    if(!empty($_SESSION['user_role'])){
        $fssc_id = $_SESSION['user_id'] ?? 0;
        $excludedValues = ["fssc"];
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


    // Total Pending Approvals (Status: 'pending')
    $query_pending = "SELECT COUNT(*) AS total_pending FROM documents WHERE status = 'pending'";
    $result_pending = $conn->query($query_pending);
    $total_pending = $result_pending->fetch_assoc()['total_pending'] ?? 0;

    // Approved This Month (Status: 'approved' this month)
    $query_approved = "SELECT COUNT(*) AS approved_this_month FROM documents
                       WHERE status = 'approved_fssc' 
                       AND MONTH(updated_at) = MONTH(CURRENT_DATE()) 
                       AND YEAR(updated_at) = YEAR(CURRENT_DATE())";
    $result_approved = $conn->query($query_approved);
    $approved_this_month = $result_approved->fetch_assoc()['approved_this_month'] ?? 0;

    // Revisions Requested (Status: 'revision')
    $query_revision = "SELECT COUNT(*) AS revisions_requested FROM documents WHERE status = 'revision'";
    $result_revision = $conn->query($query_revision);
    $revisions_requested = $result_revision->fetch_assoc()['revisions_requested'] ?? 0;


    // 2. PENDING APPROVALS TABLE (Top 10 documents awaiting approval)
    $query_pending_table = "
        SELECT 
            d.document_id, 
            d.pdf_filename, 
            o.name AS organization_name, 
            DATE_FORMAT(d.created_at, '%M %d, %Y') AS submitted_date
        FROM documents d
        JOIN organizations o ON d.organization_id = o.organization_id
        WHERE d.status = 'pending'
        ORDER BY d.created_at ASC
        LIMIT 10;
    ";
    $result_pending_table = $conn->query($query_pending_table);

    // 3. RECENT ACTIVITY (Last 5 documents acted on - using document_history)
    $query_recent_activity = "
        SELECT 
            d.document_id, 
            d.pdf_filename, 
            dh.to_status AS action, 
            u.first_name, 
            u.last_name,
            DATE_FORMAT(dh.timestamp, '%b %d %I:%i %p') AS action_time
        FROM document_history dh
        JOIN documents d ON dh.document_id = d.document_id
        JOIN users u ON dh.modified_by_user_id = u.user_id
        WHERE u.user_id = $fssc_id -- Filter for actions taken by the current adviser
        ORDER BY dh.timestamp DESC
        LIMIT 5;
    ";
    $result_recent_activity = $conn->query($query_recent_activity);


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
        WHERE n.user_id = $fssc_id -- Target the current adviser
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
			max-height: calc(100vh - 200px); /* Adjust this value as needed */
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
                                                    <div class="text-xs font-weight-bold text-pending text-uppercase mb-1">
                                                        Total Pending
                                                    </div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800 card-number">
                                                        <?= $total_pending; ?>
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fas fa-hourglass-half fa-2x text-pending"></i>
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
                                                    <div class="text-xs font-weight-bold text-approved text-uppercase mb-1">
                                                        Approved This Month
                                                    </div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800 card-number">
                                                        <?= $approved_this_month; ?>
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fas fa-check-circle fa-2x text-approved"></i>
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
                                                    <div class="text-xs font-weight-bold text-revision text-uppercase mb-1">
                                                        Revisions Requested
                                                    </div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800 card-number">
                                                        <?= $revisions_requested; ?>
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fas fa-exclamation-triangle fa-2x text-revision"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mb-4">
                            <div class="panel">
                                <h5 class="mb-3 text-pending">Pending Approvals</h5>
                                <p class="text-muted">Table of documents awaiting adviser approval.</p>
                                <div class="table-responsive">
                                    <table id="pendingDocumentsTable" class="table table-striped table-hover" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Document Title</th>
                                                <th>Organization</th>
                                                <th>Submitted Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($result_pending_table->num_rows > 0): ?>
                                                <?php while ($doc = $result_pending_table->fetch_assoc()): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($doc['pdf_filename']); ?></td>
                                                        <td><?= htmlspecialchars($doc['organization_name']); ?></td>
                                                        <td><?= htmlspecialchars($doc['submitted_date']); ?></td>
                                                        <td>
                                                            <a href="../function/fssc/view.php?id=<?= $doc['document_id']; ?>" class="btn btn-sm btn-primary">Review</a>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mb-4">
                            <div class="panel">
                                <h5 class="mb-3">Recent Activity (Last 5 Actions)</h5>
                                <div class="list-group list-group-flush">
                                    <?php if ($result_recent_activity->num_rows > 0): ?>
                                        <?php while ($activity = $result_recent_activity->fetch_assoc()): ?>
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <div class="flex-grow-1">
                                                    <p class="mb-1">
                                                        You "<?= htmlspecialchars($activity['action']); ?>" the document: "<?= htmlspecialchars($activity['pdf_filename']); ?>"
                                                    </p>
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock me-1"></i> <?= htmlspecialchars($activity['action_time']); ?>
                                                    </small>
                                                </div>
                                                <i class="fas fa-history text-info"></i>
                                            </div>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <p class="text-center text-muted mt-3">No recent actions recorded.</p>
                                    <?php endif; ?>
                                </div>
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
