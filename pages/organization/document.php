<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BASC</title>
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
		/* NEW CSS: Ensure the modal body is contained and scrollable */
		.modal-dialog-scrollable .modal-body {
			max-height: calc(100vh - 130px); /* Adjust this value as needed */
			overflow-y: auto;
		}
		.editor-container {
			display: flex;
			flex-direction: column;
			height: 100%;
		}
		#editor {
			min-height: 500px;
			border: 2px dashed #e2e8f0;
			padding: 1.5rem;
			border-radius: .75rem;
			background: #fff;
			line-height: 1.6;
			outline: none;
		}
		#editor:focus {
			border-color: #5a7dff;
		}
		#editor img {
		max-width: 100%;
		display: block;
		margin: .5rem 0;
		cursor: grab;
		}
		#editor img:hover {
		outline: 2px solid #5a7dff;
		outline-offset: 2px;
		}
		.filename-input {
		font-size: 1rem;
		font-weight: 600;
		color: #4768e1;
		border: none;
		background: transparent;
		flex: 1;
		outline: none;
		}
		.filename-input:focus {
		border-bottom: 1px dashed #5a7dff;
		}
        
		.comments-panel {
			display: flex;
			flex-direction: column;
			border: 1px solid #e2e8f0;
			border-radius: .75rem;
			padding: 1rem;
			background: #fff;
			height: 500px;
			overflow-y: auto;
		}

		.comment-item {
			background-color: #f7f9fc;
			border-radius: .75rem;
			padding: .75rem;
			margin-bottom: .75rem;
		}

		.comment-item strong {
			display: block;
			color: #4768e1;
			font-size: 0.9rem;
		}

		.comment-item p {
			margin: 0;
			font-size: 0.85rem;
			color: #333;
		}

		.comment-form {
			display: flex;
			margin-top: 1rem;
		}

		.comment-form textarea {
			flex: 1;
			border-radius: .75rem;
			padding: .75rem;
			border: 1px solid #e2e8f0;
			resize: vertical;
		}

		.comment-form button {
			margin-left: .5rem;
		}
  </style>
</head>
<?php
  require '../../config/dbcon.php';
  
  
    if(!empty($_SESSION['user_role'])){
        $excludedValues = ["academic_organization", "non_academic_organization"];
        $filteredArray = array_diff($allroles, $excludedValues);

        if (in_array($_SESSION['user_role'], $filteredArray)) {
            header('location: ../../config/redirect.php');
        }
    }else{
        header("location: ../logout.php");
    }
    
    $user_id = $_SESSION['user_id'];
    $organization_id_query = $conn->prepare("SELECT organization_id FROM users WHERE user_id = ?");
    $organization_id_query->bind_param("i", $user_id);
    $organization_id_query->execute();
    $organization_id_result = $organization_id_query->get_result();
    $organization_id = $organization_id_result->fetch_assoc()['organization_id'];
    $organization_id_query->close();

    $documents_query = $conn->prepare("SELECT document_id, status, document_type, pdf_filename, created_at FROM documents WHERE organization_id = ? AND user_id= ? ORDER BY created_at DESC");
    $documents_query->bind_param("ii", $organization_id, $user_id);
    $documents_query->execute();
    $documents_result = $documents_query->get_result();
?>
<body>
        <?php
        include '../Components/sidebar.php';
        // echo '<img src="' . $useURL . "img/logo/logo_osas.png" . '" alt="">';
        ?>
        <div class="container-fluid">
				<div class="container-fluid rounded-3 border border-secondary-subtle p-3 my-3 overflow-x-scroll">
					<div class="d-flex justify-content-between align-items-center px-2">
						<h4>Letter request</h4>
						<button id="newApplicationButton" data-bs-toggle="modal" data-bs-target="#newApplication" type="button" class="basc-green-button btn btn-success d-flex justify-content-center align-items-center">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
								<path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
							</svg>&nbsp;New application
						</button>
					</div>
					<table id="myTable" class="table table-striped text-start">
						<thead>
							<tr>
								<th>Application </th>
								<th>Filename</th>
								<th>Status</th>
								<th>Date created</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody >
                        <?php
                            $sql = "SELECT * FROM documents WHERE user_id = '" . $_SESSION['user_id'] . "'";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row["document_id"] . "</td>";
                                    echo "<td>" . $row["pdf_filename"] . "</td>";
                                    echo "<td>" . $row["status"] . "</td>";
                                    echo "<td>" . formatDateTime($row["created_at"]) . "</td>";
                                    echo '<td>
                                            <div class="btn-group dropstart">
                                                <button class="btn btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                                        <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                                                    </svg>
                                                </button>
                                                <ul class="dropdown-menu">';
                                                
                                                // Show submit only if not revision or draft
                                                if ($row['status'] == 'revision' || $row['status'] == 'draft') {
                                                    echo '<li><a class="dropdown-item submit-pdf" href="#" data-id="'. $row['document_id'] .'">Submit</a></li>';
                                                }

                                                echo '
                                                    <li><a class="dropdown-item view-pdf" href="#" data-id="'. $row['document_id'] .'" data-bs-toggle="modal" data-bs-target="#viewPdfApplication">View</a></li>';
                                                
                                                    if ($row['status'] == 'revision' || $row['status'] == 'draft') {
                                                        echo '
                                                        <li><a class="dropdown-item edit-pdf" href="#" data-id="'. $row['document_id'] .'">Edit</a></li>
                                                        <li><a class="dropdown-item delete-pdf" data-id="'. $row['document_id'] .'" href="#">Delete</a></li>';
                                                    }
                                                echo'
                                                </ul>	
                                            </div>
                                        </td>';
                                    echo "</tr>";
                                }
                            }
                            ?>

						</tbody>
					</table>
				</div>
			</div>
		</main>

		<div class="modal fade" id="newApplication" tabindex="-1" aria-labelledby="newApplicationLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-scrollable modal-fullscreen">
				<div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="newApplicationLabel"></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editorForm" method="POST" action="../function/org/save.php">
                        <input type="hidden" name="id" id="documentId" >
                        <input type="hidden" name="organization_id" value="<?= $_SESSION['organization_id']; ?>">
                        <div class="modal-body">
                            <div class="container-fluid py-4">
                                <div class="row g-4">
                                    <div id="loadCallout" class="px-3 py-0"></div>
                                    <div class="col-lg-4">
                                        <a href="#" id="viewCommentBtn" class="ms-2">View comments</a>
                                        <div id="controlPanel" class=" panel">
                                        <h6 class="mt-3">Supporting Documents (PDF only)</h6>
                                        <div class="mb-2">
                                            <label for="supportingDocInput" class="form-label small">Upload PDF(s)</label>
                                            <input id="supportingDocInput" name="supporting_document[]" type="file" accept="application/pdf" multiple class="form-control">
                                        </div>

                                            <div id="existingDocumentsList" class="mt-3">
                                            </div>

                                            <div class="mb-3">
                                                <label for="templateSelect" class="form-label">Use a Template</label>
                                                <select class="form-select" id="templateSelect">
                                                    <?php
                                                    $templates_query = $conn->prepare("SELECT template_id, template_name FROM templates ORDER BY template_name ASC");
                                                    $templates_query->execute();
                                                    $templates_result = $templates_query->get_result();
                                                    if($templates_result->num_rows == 0){
                                                        echo '<option value="">-- No Template --</option>';
                                                    }else{
                                                        echo '<option value="">-- Select Template --</option>';
                                                        while ($template = $templates_result->fetch_assoc()) {
                                                            echo '<option value="' . htmlspecialchars($template['template_id']) . '">' . htmlspecialchars($template['template_name']) . '</option>';
                                                        }
                                                    }
                                                    $templates_query->close();
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="documentHistory" class="form-label">Document History</label>
                                                <input type="hidden" name="docHistoryId" id="docHistoryId">
                                                <select class="form-select" id="documentHistory">
                                                    <option value="">-- Select document --</option>
                                                </select>
                                            </div>

                                            <h2 class="h5 mb-3">Controls</h2>

                                            <div class="btn-group mb-2 w-100" role="group">
                                                <button class="btn btn-outline-secondary" type="button" data-cmd="bold"><i class="fas fa-bold"></i></button>
                                                <button class="btn btn-outline-secondary" type="button" data-cmd="italic"><i class="fas fa-italic"></i></button>
                                                <button class="btn btn-outline-secondary" type="button" data-cmd="underline"><i class="fas fa-underline"></i></button>
                                                <button class="btn btn-outline-secondary" type="button" data-cmd="strikethrough"><i class="fas fa-strikethrough"></i></button>
                                            </div>

                                            <div class="btn-group mb-2 w-100" role="group">
                                                <button id="h1Btn" class="btn btn-outline-secondary">H1</button>
                                                <button id="h2Btn" class="btn btn-outline-secondary">H2</button>
                                                <button id="pBtn" class="btn btn-outline-secondary">P</button>
                                            </div>

                                            <div class="btn-group mb-2 w-100" role="group">
                                                <button class="btn btn-outline-secondary" type="button" data-cmd="subscript"><i class="fas fa-subscript"></i></button>
                                                <button class="btn btn-outline-secondary" type="button" data-cmd="superscript"><i class="fas fa-superscript"></i></button>
                                                <button class="btn btn-outline-secondary" type="button" data-cmd="removeFormat"><i class="fas fa-eraser"></i></button>
                                            </div>

                                            <h2 class="h6 mt-3">Text Formatting</h2>
                                            <div class="btn-group mb-2 w-100" role="group">
                                                <button class="btn btn-outline-secondary" type="button" data-cmd="justifyLeft"><i class="fas fa-align-left"></i></button>
                                                <button class="btn btn-outline-secondary" type="button" data-cmd="justifyCenter"><i class="fas fa-align-center"></i></button>
                                                <button class="btn btn-outline-secondary" type="button" data-cmd="justifyRight"><i class="fas fa-align-right"></i></button>
                                                <button class="btn btn-outline-secondary" type="button" data-cmd="justifyFull"><i class="fas fa-align-justify"></i></button>
                                            </div>

                                            <div class="mb-2 d-flex gap-3">
                                                <div class="child">
                                                    <label class="form-label small">Text Color</label>
                                                    <input type="color" id="textColor" value="#1a202c" class="form-control form-control-color">
                                                </div>
                                                <div class="child">
                                                    <label class="form-label small">Highlight</label>
                                                    <input type="color" id="highlightColor" value="#ffc107" class="form-control form-control-color">
                                                </div>
                                            </div>

                                            <div class="btn-group mb-2 w-100" role="group">
                                                <button class="btn btn-outline-secondary" type="button" data-cmd="indent"><i class="fas fa-indent"></i></button>
                                                <button class="btn btn-outline-secondary" type="button" data-cmd="outdent"><i class="fas fa-outdent"></i></button>
                                                <button class="btn btn-outline-secondary" type="button" data-cmd="insertUnorderedList"><i class="fas fa-list-ul"></i></button>
                                                <button class="btn btn-outline-secondary" type="button" data-cmd="insertOrderedList"><i class="fas fa-list-ol"></i></button>
                                            </div>

                                            <div class="mb-2">
                                                <label class="form-label small">Font</label>
                                                <select id="fontSelect" class="form-select">
                                                    <option value="arial">Arial</option>
                                                    <option value="timesnewroman">Times New Roman</option>
                                                    <option value="couriernew">Courier New</option>
                                                    <option value="georgia">Georgia</option>
                                                    <option value="verdana">Verdana</option>
                                                </select>
                                            </div>

                                            <div class="mb-2">
                                                <label class="form-label small">Letter Spacing</label>
                                                <select id="letterSpacingSelect" class="form-select">
                                                    <option value="0px" selected>Normal</option>
                                                    <option value="-0.5px">Slightly Tighter</option>
                                                    <option value="0.5px">Slightly Wider</option>
                                                    <option value="1px">Wide</option>
                                                </select>
                                            </div>


                                            <div class="mb-2">
                                                <label class="form-label small">Font Size</label>
                                                <select id="fontSizeSelect" class="form-select">
                                                    <option value="10pt">10 pt</option>
                                                    <option value="11pt" selected>11 pt</option>
                                                    <option value="12pt">12 pt</option>
                                                    <option value="14pt">14 pt</option>
                                                    <option value="16pt">16 pt</option>
                                                </select>
                                            </div>


                                            <div class="mb-2">
                                                <label class="form-label small">Line Spacing</label>
                                                <select id="lineHeightSelect" class="form-select">
                                                <option value="1.0">Single</option>
                                                <option value="1.5">1.5 Lines</option>
                                                <option value="1.6" selected>Normal</option>
                                                <option value="2.0">Double</option>
                                                </select>
                                            </div>

                                            <h2 class="h6 mt-3">Insert</h2>
                                            <button id="insertTableBtn" class="btn btn-outline-secondary w-100 mb-2">
                                                <i class="fas fa-table"></i> Insert Table
                                            </button>

                                            <div class="mb-2">
                                                <label for="imgInput" class="form-label small">Image</label>
                                                <input id="imgInput" type="file" accept="image/*" class="form-control">
                                            </div>

                                            <div class="input-group mb-2">
                                                <input id="linkInput" type="text" class="form-control" placeholder="https://example.com">
                                                <button id="insertLinkBtn" class="btn btn-outline-primary"><i class="fas fa-link"></i></button>
                                                <button id="unlinkBtn" class="btn btn-outline-danger"><i class="fas fa-unlink"></i></button>
                                            </div>

                                            <h2 class="h6 mt-3">Export</h2>
                                            <div class="mb-2">
                                                <label class="form-label small">PDF Size</label>
                                                <select id="pageSizeSelect" class="form-select">
                                                <option value="A4">A4</option>
                                                <option value="Letter">Letter</option>
                                                <option value="Legal" selected>Legal</option>
                                                </select>
                                            </div>

                                            <div class="d-flex gap-2">
                                                <button id="clearBtn" type="button" class="btn btn-danger flex-fill"><i class="fas fa-trash"></i> Clear</button>
                                                <button id="exportPdfBtn" type="button" class="btn btn-success flex-fill"><i class="fas fa-file-pdf"></i> Export</button>
                                            </div>
                                        </div>
                                        <div class=" panel comments-panel d-flex flex-column justify-content-between d-none" id="commentsPanel">
                                            <div id="commentsContainer">
                                            </div>
                                            <!-- <div class="comment-form">
                                                <input type="hidden" id="commentDocumentId">
                                                <textarea id="commentText" placeholder="Write a comment..." rows="3"></textarea>
                                                <div class="d-flex align-items-center flex-column justify-content-between">
                                                    <input type="color" id="highlightColor" value="#ffc107" class="form-control form-control-color">
                                                    <button class="btn btn-primary" id="submitCommentBtn">Send</button>
                                                </div>
                                            </div> -->
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-8">
                                        <div class="panel d-flex flex-column">
                                                <h4 class="fw-bold">Event Details</h4>
                                            <div class="row g-3 border mx-1 my-3 p-3 rounded-4">
                                                <div class="col-md-12">
                                                    <label for="eventTitle" class="form-label">Title</label>
                                                    <input type="text" class="form-control" id="eventTitle" name="eventTitle">
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="eventDescription" class="form-label">Description</label>
                                                    <input type="text" class="form-control" id="eventDescription" name="eventDescription">
                                                </div>
                                                <div class="col-md-8">
                                                    <label for="eventLocation" class="form-label">Venue</label>
                                                    <input type="text" class="form-control" id="eventLocation" placeholder="Farmers Training Center">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="eventExpenses" class="form-label">Possible Expenses</label>
                                                    <input type="number" class="form-control" id="eventExpenses" name="eventExpenses">
                                                </div>
                                                <div class="col-md-7">
                                                    <label for="startDateInput" class="form-label">From Date</label>
                                                    <input type="text" class="form-control" id="startDateInput" name="eventfromDate">
                                                </div>
                                                <div class="col-md-5">
                                                    <label for="fromTime" class="form-label">From Time</label>
                                                    <input type="time" class="form-control" id="fromTime" name="eventfromTime" value="09:00">
                                                </div>
                                                <div class="col-md-7">
                                                    <label for="endDateInput" class="form-label">To Date</label>
                                                    <input type="text" class="form-control" id="endDateInput" name="eventtoDate">
                                                </div>
                                                <div class="col-md-5">
                                                    <label for="toTime" class="form-label">To Time</label>
                                                    <input type="time" class="form-control" id="toTime" name="eventtoTime" value="17:00">
                                                </div>
                                            </div>
                                            
                                            
                                            <h4 class="fw-bold">Document</h4>
                                            <textarea name="editorContent" id="editorContent" hidden></textarea>
                                            <div id="editor" contenteditable="true">

                                            </div>
                                            <div class="d-flex align-items-center border-bottom mt-3 pt-2">
                                                <label class="me-2 fw-semibold">File Name:</label>
                                                <input id="filenameInput" name="filename" value="page-export.pdf" class="filename-input">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success basc-green-button">Save changes</button>
                        </div>
                    </form>
				</div>
			</div>
		</div>
		
		<div class="modal fade" id="viewPdfApplication" tabindex="-1" aria-labelledby="viewPdfApplicationLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-scrollable modal-fullscreen">
				<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="viewPdfApplicationLabel">Create new application</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<iframe id="pdfFrame" src="" style="width:100%;height:80vh;border:none;"></iframe>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-success basc-green-button">Save changes</button>
				</div>
				</div>
			</div>
		</div>

		<div id="calendarContainer" class="pickercalendar-container" style="display: none; position: absolute; z-index: 1000; background-color: white; border: 1px solid #ccc;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <button id="prevMonth" class="btn btn-sm btn-outline-success pickerprevnextMonthbtn">&lt;</button>
                <h6 id="monthLabel" class="mb-0 pickercalendar-header"></h6>
                <button id="nextMonth" class="btn btn-sm btn-outline-success pickerprevnextMonthbtn">&gt;</button>
            </div>
            <table class="table table-bordered text-center">
                <thead id="calendarHeader" class="table-light">
                    <tr>
                        <th>Su</th><th>Mo</th><th>Tu</th><th>We</th>
                        <th>Th</th><th>Fr</th><th>Sa</th>
                    </tr>
                </thead>
                <tbody id="calendarBody"></tbody>
            </table>
            <div class="d-flex justify-content-between mt-2">
                <button id="todayBtn" class="btn btn-sm btn-success basc-green-button flex-fill me-1">Today</button>
                <button id="clearBtn" class="btn btn-sm btn-outline-secondary flex-fill">Clear</button>
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
            // Initialize DataTable for Pending Approvals
            $('#myTable').DataTable({
                "order": [[3, "desc"]], // Order by Submitted Date ascending
                "paging": true,
                "searching": true,
                "info": false
            });
        });

        const editor = document.getElementById('editor');

        // Text formatting
        document.querySelectorAll('[data-cmd]').forEach(btn => {
            btn.addEventListener('click', () => {
                const cmd = btn.getAttribute('data-cmd');
                document.execCommand(cmd, false, null);
                editor.focus();
            });
        });
        document.getElementById('h1Btn').addEventListener('click', () => document.execCommand('formatBlock', false, 'h1'));
        document.getElementById('h2Btn').addEventListener('click', () => document.execCommand('formatBlock', false, 'h2'));
        document.getElementById('pBtn').addEventListener('click', () => document.execCommand('formatBlock', false, 'p'));

        // Color & Highlight
        document.getElementById('textColor').addEventListener('input', (e) => {
            document.execCommand('foreColor', false, e.target.value);
            editor.focus();
        });
        document.getElementById('highlightColor').addEventListener('input', (e) => {
            document.execCommand('backColor', false, e.target.value);
            editor.focus();
        });

        // Font & Size
        document.getElementById('fontSelect').addEventListener('change', (e) => {
            document.execCommand('fontName', false, e.target.value);
            editor.focus();
        });
		document.getElementById('fontSizeSelect').addEventListener('change', (e) => {
			const size = e.target.value; // e.g. "11pt"
			
			// Step 1: apply a dummy fontSize so browser wraps selection in <font>
			document.execCommand('fontSize', false, 7);

			// Step 2: replace <font size="7"> with inline CSS font-size: 11pt
			const fonts = editor.getElementsByTagName('font');
			for (let i = 0; i < fonts.length; i++) {
				if (fonts[i].size === "7") {
					fonts[i].removeAttribute("size");
					fonts[i].style.fontSize = size;
            		fonts[i].style.letterSpacing = "0px";
				}
			}

			editor.focus();
		});
		document.getElementById('letterSpacingSelect').addEventListener('change', (e) => {
			const spacing = e.target.value;

			// Apply inline style to selected text
			document.execCommand('fontSize', false, 7); // force a wrapper
			const fonts = editor.getElementsByTagName('font');
			for (let i = 0; i < fonts.length; i++) {
				if (fonts[i].size === "7") {
					fonts[i].removeAttribute("size");
					fonts[i].style.letterSpacing = spacing;
				}
			}

			editor.focus();
		});



        // Get the new select element
        const lineHeightSelect = document.getElementById('lineHeightSelect');

        // *** THIS IS THE CORRECTED FUNCTION ***
        function setLineHeight(value) {
            const selection = window.getSelection();
            if (selection.rangeCount > 0) {
                const range = selection.getRangeAt(0);
                let targetElement = range.commonAncestorContainer;

                if (targetElement.nodeType === Node.TEXT_NODE) {
                    targetElement = targetElement.parentNode;
                }

                while (targetElement && targetElement !== editor && !['P', 'H1', 'H2'].includes(targetElement.tagName)) {
                    targetElement = targetElement.parentElement;
                }

                if (targetElement && ['P', 'H1', 'H2'].includes(targetElement.tagName)) {
                    targetElement.style.lineHeight = value;
                } else {
                    editor.style.lineHeight = value;
                }
            }
        }

        // Add event listener for the new select element
        lineHeightSelect.addEventListener('change', (e) => {
            setLineHeight(e.target.value);
            editor.focus();
        });

        // Insert Image
        document.getElementById('imgInput').addEventListener('change', e => {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = () => {
                const img = document.createElement('img');
                img.src = reader.result;
                img.style.maxWidth = '100%';
                img.setAttribute('draggable', 'true');
                editor.appendChild(img);
                editor.focus();
            };
            reader.readAsDataURL(file);
        });

        // Insert Link
        document.getElementById('insertLinkBtn').addEventListener('click', () => {
            const url = document.getElementById('linkInput').value.trim();
            if (!url) return;
            const selection = window.getSelection();
            if (selection.toString().length > 0) {
                document.execCommand('createLink', false, url);
            } else {
                const text = prompt('Text for the link', url) || url;
                document.execCommand('insertHTML', false, `<a href="${url}" target="_blank">${text}</a>`);
            }
            editor.focus();
        });
        // Add these to your existing script block

        // Insert Table
        document.getElementById('insertTableBtn').addEventListener('click', () => {
            const tableHtml = `
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="border: 1px solid #ccc; padding: 8px;">Header 1</th>
                        <th style="border: 1px solid #ccc; padding: 8px;">Header 2</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="border: 1px solid #ccc; padding: 8px;">Row 1, Cell 1</td>
                        <td style="border: 1px solid #ccc; padding: 8px;">Row 1, Cell 2</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ccc; padding: 8px;">Row 2, Cell 1</td>
                        <td style="border: 1px solid #ccc; padding: 8px;">Row 2, Cell 2</td>
                    </tr>
                </tbody>
            </table>
            `;
            document.execCommand('insertHTML', false, tableHtml);
            editor.focus();
        });

        // Remove Link
        document.getElementById('unlinkBtn').addEventListener('click', () => {
            document.execCommand('unlink', false, null);
            editor.focus();
        });

        // Drag & Drop for images
        let draggedItem = null;
        editor.addEventListener('dragstart', (e) => {
            if (e.target.tagName === 'IMG') {
                draggedItem = e.target;
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/plain', null);
                e.target.style.opacity = '0.5';
            }
        });
        editor.addEventListener('dragover', (e) => {
            e.preventDefault();
            const target = e.target;
            if (draggedItem && (target.tagName === 'IMG' || target.tagName === 'P')) {
                const rect = target.getBoundingClientRect();
                const isAfter = e.clientY > rect.top + rect.height / 2;
                if (isAfter) {
                    target.parentNode.insertBefore(draggedItem, target.nextSibling);
                } else {
                    target.parentNode.insertBefore(draggedItem, target);
                }
            }
        });
        editor.addEventListener('drop', (e) => {
            e.preventDefault();
            if (draggedItem) {
                draggedItem.style.opacity = '1';
                draggedItem = null;
            }
        });
        editor.addEventListener('dragend', () => {
            if (draggedItem) {
                draggedItem.style.opacity = '1';
                draggedItem = null;
            }
        });


		document.getElementById('newApplicationButton').addEventListener('click', () => {
            // Reset the modal for a new application
            $("#documentId").val(""); // Clear the hidden ID field
            $("#filenameInput").val(""); // Clear the filename
            document.getElementById('newApplicationLabel').innerHTML = "Create new document";
            document.getElementById('viewCommentBtn').classList.add('d-none');
			editor.innerHTML = '<h1>Your page title</h1><p>Start writing your page here.</p>';
			document.getElementById('loadCallout').innerHTML = '<div class="alert alert-info alert-dismissible fade show border-5 border-top-0 border-bottom-0 border-end-0 rounded-0" role="alert"><h5>Instructions:</h5><p>Create your document below. To include an e-signature for the approver, type <strong>[ADVISER_SIGNATURE], [DEAN_SIGNATURE], and [FSSC_SIGNATURE]</strong> exactly where you want it to appear.</p><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		});
        document.getElementById('clearBtn').addEventListener('click', () => {
            if (confirm('Clear the editor?')) editor.innerHTML = '<h1>Your page title</h1><p>Start writing your page here.</p>';
        });
        async function renderHtmlToBlob(html, filename = 'file.jpg') {
            if (!html || !html.trim()) return null;
        
            // create offscreen container
            const container = document.createElement('div');
            container.style.position = 'absolute';
            container.style.left = '-9999px';
            container.style.top = '0';
            container.style.minWidth = '595px'; // width you want for header/footer capture
            container.innerHTML = html;
            document.body.appendChild(container);
        
            // give fonts/images time to load (adjust if needed)
            try {
                if (document.fonts && document.fonts.ready) await document.fonts.ready;
            } catch (e) { /* ignore */ }
            await new Promise(r => setTimeout(r, 200));
        
            // Render — use JPEG at quality 0.9 to reduce size (less chance of post limits)
            const canvas = await html2canvas(container, {
                scale: 1,            // reduce scale for smaller size; bump to 2 if you need higher DPI
                useCORS: true,
                backgroundColor: '#ffffff',
            });
        
            const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg', 0.9));
            document.body.removeChild(container);
            return blob; // can be appended to FormData
        }
        
        // document.getElementById('exportPdfBtn').addEventListener('click', async () => {
        //     try {
        //         const content = document.getElementById('editor').innerHTML;
        //         const filenameInput = document.getElementById('filenameInput').value || 'page-export.pdf';
        //         const filename = filenameInput.replace(/\.docx$/i, '.pdf');
        //         const pageSize = document.getElementById('pageSizeSelect').value || 'A4';
        
        
        //         const form = new FormData();
        //         form.append('html_content', content);
        //         form.append('filename', filename);
        //         form.append('page_size', pageSize);
        
        //         // Send as multipart/form-data (no manual content-type header)
        //         const resp = await fetch('export.php', { method: 'POST', body: form });
        
        //         if (!resp.ok) throw new Error('Server returned ' + resp.status);
        //         const blob = await resp.blob();
        //         saveAs(blob, filename); // FileSaver.js already loaded in your page
        //     } catch (err) {
        //         console.error('Export error:', err);
        //         alert('PDF export failed. See console for details.');
        //     }
        // });
        document.getElementById('exportPdfBtn').addEventListener('click', async () => {
            try {
                // Get the document ID from the hidden input field.
                // It might be null or empty if this is a new document.
                const documentIdElement = document.getElementById('documentId');
                const documentId = documentIdElement ? documentIdElement.value : null;

                const content = document.getElementById('editor').innerHTML;
                const filenameInput = document.getElementById('filenameInput').value || 'page-export.pdf';
                const filename = filenameInput.replace(/\.docx$/i, '.pdf');
                const pageSize = document.getElementById('pageSizeSelect').value || 'A4';

                const form = new FormData();
                form.append('html_content', content);
                form.append('filename', filename);
                form.append('page_size', pageSize);

                // Only append the document_id if it exists.
                if (documentId) {
                    form.append('document_id', documentId);
                }

                const resp = await fetch('export.php', { method: 'POST', body: form });

                if (!resp.ok) {
                    throw new Error('Server returned ' + resp.status);
                }
                
                const blob = await resp.blob();
                saveAs(blob, filename);
            } catch (err) {
                console.error('Export error:', err);
                alert('PDF export failed. See console for details.');
            }
        });

		const editorEditable = document.getElementById("editor"); // your contenteditable div

		document.getElementById("editorForm").addEventListener("submit", function() {
			document.getElementById("editorContent").value = editorEditable.innerHTML;
		});

		document.addEventListener("DOMContentLoaded", () => {
		  document.querySelectorAll(".view-pdf").forEach(btn => {
			btn.addEventListener("click", () => {
			  const id = btn.getAttribute("data-id");
			  document.getElementById("pdfFrame").src = "../function/org/view.php?id=" + id;
			});
		  });
		});
		
		
		document.addEventListener("DOMContentLoaded", () => {
        
        document.getElementById('viewCommentBtn').addEventListener('click', () => {
            const controlPanel = document.getElementById('controlPanel');
            const commentPanel = document.getElementById('commentsPanel');

            if(controlPanel.classList.contains('d-none')){
                document.getElementById('viewCommentBtn').innerHTML = "View comments";
                controlPanel.classList.remove('d-none');
                commentPanel.classList.add('d-none');
            }else{
                document.getElementById('viewCommentBtn').innerHTML = "Hide comments";
                controlPanel.classList.add('d-none');
                commentPanel.classList.remove('d-none');
            }
        })
		document.querySelectorAll(".submit-pdf").forEach(btn => {
			btn.addEventListener("click", () => {
			const id = btn.getAttribute("data-id");

			// Send POST request
			const xhr = new XMLHttpRequest();
			xhr.open("POST", "../function/org/submitpdf.php", true);
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

			xhr.onreadystatechange = function () {
				if (xhr.readyState === 4 && xhr.status === 200) {
				alertify.success(xhr.responseText);
				// if(xhr.responseText == "Record updated successfully"){
					window.location.reload();
				// }
				}
			};

			xhr.send("id=" + encodeURIComponent(id) + "&status=submitted"); 
			});
		});
		});
		// sample
		$(document).on("click", ".edit-pdf", function(e) {
    e.preventDefault();
    document.getElementById('newApplicationLabel').innerHTML = "Edit Document";
    document.getElementById('viewCommentBtn').classList.remove('d-none');
    let docId = $(this).data("id");

    $.post("../function/org/get_document.php", { id: docId }, function(data) {
        if (data.success) {
            // Load document content
            $("#documentViewer").html(data.content_html);
            $("#filenameInput").val(data.filename);
            $("#editor").html(data.content_html);
            $("#documentId").val(docId);

            // Load event fields if event exists
            if (data.event) {
                $("#eventTitle").val(data.event.title);
                $("#eventDescription").val(data.event.description);
                $("#eventLocation").val(data.event.location);
                $("#eventExpenses").val(data.event.total_expenses);

                // Handle start_date (split into date + time)
                if (data.event.start_date) {
                    let start = new Date(data.event.start_date);
                    $("#startDateInput").val(start.toISOString().split("T")[0]);
                    $("#fromTime").val(start.toTimeString().slice(0,5));
                }

                // Handle end_date (split into date + time)
                if (data.event.end_date) {
                    let end = new Date(data.event.end_date);
                    $("#endDateInput").val(end.toISOString().split("T")[0]);
                    $("#toTime").val(end.toTimeString().slice(0,5));
                }
            } else {
                // Clear event fields if no event found
                $("#eventTitle").val("");
                $("#eventDescription").val("");
                $("#eventLocation").val("");
                $("#eventExpenses").val("");
                $("#startDateInput").val("");
                $("#fromTime").val("09:00");
                $("#endDateInput").val("");
                $("#toTime").val("17:00");
            }

            // Load comments
            $("#commentsContainer").empty();
            if (data.comments.length > 0) {
                data.comments.forEach(comment => {
                    $("#commentsContainer").append(`
                        <div class="comment-item">
                            <strong>${comment.first_name} ${comment.last_name}</strong>
                            <p>${comment.comment_text}</p>
                            <small class="text-muted">${formatDateTime(comment.created_at)}</small>
                        </div>
                    `);
                });
            } else {
                $("#commentsContainer").append('<p class="text-center text-muted mt-4">No comments yet.</p>');
            }
            // Clear old supporting docs
            $("#existingDocumentsList").empty();

            if (data.supporting_docs && data.supporting_docs.length > 0) {
                data.supporting_docs.forEach(doc => {
                    $("#existingDocumentsList").append(`
                        <div class="d-flex align-items-center mb-2 p-2 border rounded">
                            <i class='bx bxs-file-pdf text-danger fs-4 me-2'></i>
                            <span class="flex-grow-1 text-truncate">${doc.file_name}</span>
                            <a href="${doc.file_path}" target="_blank" class="btn btn-sm btn-primary ms-2">View</a>
                            <button type="button" class="btn btn-sm btn-danger ms-2 delete-supporting-doc" data-id="${doc.id}">
                                Delete
                            </button>
                        </div>
                    `);
                });
            } else {
                $("#existingDocumentsList").append('<p class="text-muted">No supporting documents uploaded.</p>');
            }


            $("#commentDocumentId").val(docId);
            $("#newApplication").modal("show");
        } else {
            alertify.error(data.message || "Failed to load document");
        }
    }, "json");

    // Clear and set loading for history dropdown
    $('#documentHistory').html('<option>Loading...</option>');

    // Load document history
    $.post('../function/org/get_doc_history_option.php', { document_id: docId }, function (data) {
        $('#documentHistory').html(data);
    });
});


$("#editorForm").on("submit", function(e) {
    e.preventDefault();

    let formData = new FormData();

    // Main document fields
    formData.append("id", $("#documentId").val());
    formData.append("filename", $("#filenameInput").val());
    formData.append("content_html", $("#editor").html());
    formData.append("document_type", $("#templateSelect option:selected").text());

    // Event details
    formData.append("event_title", $("#eventTitle").val());
    formData.append("event_description", $("#eventDescription").val());
    formData.append("event_location", $("#eventLocation").val());
    formData.append("event_expenses", $("#eventExpenses").val());
    formData.append("event_from_date", $("#startDateInput").val());
    formData.append("event_from_time", $("#fromTime").val());
    formData.append("event_to_date", $("#endDateInput").val());
    formData.append("event_to_time", $("#toTime").val());

    // Supporting documents (multiple PDFs)
    let files = $("#supportingDocInput")[0].files;
    for (let i = 0; i < files.length; i++) {
        formData.append("supporting_document[]", files[i]);
    }

    // Validation
    if ($("#filenameInput").val().trim() === "") {
        alertify.error("Please enter a filename. (sample.pdf)");
        return;
    }

    $.ajax({
        url: "../function/org/save.php",
        type: "POST",
        data: formData,
        processData: false,  // ⛔ prevents jQuery from converting FormData into a string
        contentType: false,  // ⛔ lets browser set correct content type (multipart/form-data)
        dataType: "json",
        success: function(data) {
            if (data.success) {
                alertify.success(data.message);
                $("#newApplication").modal("hide");
                window.location.reload();
            } else {
                alertify.error(data.message);
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", error);
            console.log(xhr.responseText);
            alertify.error("Something went wrong while saving.");
        }
    });
});



		$(document).on("click", ".delete-pdf", function(e) {
			e.preventDefault();

			let docId = $(this).data("id");

			alertify.confirm("Delete Document", "Are you sure you want to delete this document? This action cannot be undone.", 
				function() { // OK button callback
					$.post("../function/org/delete_document.php", { id: docId }, function(data) {
						if (data.success) {
							alertify.success(data.message);
							window.location.reload(); // Reload the page to show the updated table
						} else {
							alertify.error(data.message);
						}
					}, "json").fail(function(jqXHR, textStatus, errorThrown) {
						alertify.error("Request failed: " + textStatus);
					});
				},
				function() { // Cancel button callback
					alertify.error('Delete cancelled');
				}
			);
		});
        // Add this new function to handle the template selection
        $('#templateSelect').on('change', function() {
            let templateId = $(this).val();
            if (templateId) {
                $.post('../function/org/get_template.php', { id: templateId }, function(data) {
                    if (data.success) {
                        $('#editor').html(data.content_html);
                        alertify.success("Template loaded successfully.");
                    } else {
                        alertify.error(data.message);
                    }
                }, 'json');
            } else {
                // Clear the editor if no template is selected
                $('#editor').html('');
            }
        });
        $('#documentHistory').on('change', function() {
            let selectedVal = $(this).val();

            if (selectedVal) {
                let postData = {};

                if (selectedVal.startsWith("Current_")) {
                    // Handle current document
                    let docId = selectedVal.replace("Current_", "");
                    postData = { document_id: docId, type: "current" };
                } else {
                    // Handle history
                    postData = { id: selectedVal, type: "history" };
                }

                $.post('../function/org/get_document_history.php', postData, function(data) {
                    if (data.success) {
                        $('#editor').html(data.content_html);
                        alertify.success("Document loaded successfully.");
                    } else {
                        alertify.error(data.message);
                    }
                }, 'json');
            } else {
                $('#editor').html('');
            }
        });

        

    </script>
    <script>// Ensure you have date-fns imported in your environment
        // This assumes dateFns is available globally (e.g., from a <script> tag)
        // If you are using modules, you would import them.
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById("calendarContainer");
            if (!container) return; 
            
            // --- Attach Global Listeners to Shared Buttons ---

            // Clear Button FIX: Calls the clearDate method of the active instance
            container.querySelector("#clearBtn").addEventListener("click", (e) => {
                e.stopPropagation();
                if (DatePicker.activeInstance) {
                    DatePicker.activeInstance.clearDate();
                }
            });

            // Today Button FIX: Calls the selectToday method of the active instance
            container.querySelector("#todayBtn").addEventListener("click", (e) => {
                e.stopPropagation();
                if (DatePicker.activeInstance) {
                    DatePicker.activeInstance.selectToday();
                }
            });
            
            // Navigation Buttons (Prev/Next)
            container.querySelector("#prevMonth").addEventListener("click", (e) => {
                e.stopPropagation();
                if (DatePicker.activeInstance) {
                    DatePicker.activeInstance.navigate(-1);
                }
            });

            container.querySelector("#nextMonth").addEventListener("click", (e) => {
                e.stopPropagation();
                if (DatePicker.activeInstance) {
                    DatePicker.activeInstance.navigate(1);
                }
            });
            
            // Month Label (View Switcher)
            container.querySelector("#monthLabel").addEventListener("click", (e) => {
                e.stopPropagation();
                if (DatePicker.activeInstance) {
                    if (DatePicker.activeInstance.currentView === "days") {
                        DatePicker.activeInstance.renderMonths(DatePicker.activeInstance.currentDate);
                    } else if (DatePicker.activeInstance.currentView === "months") {
                        DatePicker.activeInstance.renderYears(DatePicker.activeInstance.currentDate);
                    }
                }
            });

            // Outside Click Handler (Replaces the one previously in attachEventListeners)
            document.addEventListener("click", (e) => {
                if (DatePicker.activeInstance) {
                    DatePicker.activeInstance.handleOutsideClick(e);
                }
            });


            // --- Instantiate your Pickers ---
            // Example usage: Ensure you have your input fields in your HTML with these IDs.
            const containerSelector = "#calendarContainer";
            
            // Assuming dateFns is available globally
            new DatePicker("#startDateInput", containerSelector, dateFns);
            new DatePicker("#endDateInput", containerSelector, dateFns);
        });
        </script>
        <script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('supportingDocInput');
    const documentsList = document.getElementById('existingDocumentsList');

    if (fileInput && documentsList) {
        fileInput.addEventListener('change', function(event) {
            const files = event.target.files;

            if (files.length > 0) {
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];

                    if (file.type === 'application/pdf') {
                        // Create preview wrapper
                        const filePreviewDiv = document.createElement('div');
                        filePreviewDiv.className = 'd-flex align-items-center mb-2 p-2 border rounded temp-doc';

                        filePreviewDiv.innerHTML += `
                            <i class='bx bxs-file-pdf text-danger fs-4 me-2'></i>
                            <span class="flex-grow-1 text-truncate">${file.name}</span>
                            <button type="button" class="btn btn-sm btn-primary ms-2" disabled title="Will be viewable after saving">View</button>
                            <button type="button" class="btn btn-sm btn-danger ms-2 remove-temp-doc">Remove</button>
                        `;

                        // Append to existing list (don’t clear!)
                        documentsList.appendChild(filePreviewDiv);
                    }
                }
            }
        });

        // Remove temporary previewed file
        documentsList.addEventListener("click", function(e) {
            if (e.target.classList.contains("remove-temp-doc")) {
                e.target.closest(".temp-doc").remove();
            }
        });
    }

    // Delegate because items are dynamic
documentsList.addEventListener("click", function(e) {
    if (e.target.classList.contains("remove-temp-doc")) {
        e.target.closest("div").remove(); 
    }
});
$(document).on("click", ".delete-supporting-doc", function() {
    let btn = $(this);
    let id = btn.data("id");

    alertify.confirm(
        "Delete Confirmation",
        "Are you sure you want to delete this supporting document?",
        function () {
            // ✅ User clicked OK
            $.post("../function/org/delete_supporting_doc.php", { id: id }, function(data) {
                if (data.success) {
                    alertify.success(data.message);
                    btn.closest("div").remove(); // Remove from DOM
                } else {
                    alertify.error(data.message);
                }
            }, "json");
        },
        function () {
            // ❌ User clicked Cancel
            alertify.error("Delete cancelled");
        }
    ).set('labels', {ok:'Yes', cancel:'No'});
});



});
</script>
</body>
</html>