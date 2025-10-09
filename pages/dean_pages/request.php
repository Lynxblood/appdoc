
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
		$excludedValues = ["dean"];
		$filteredArray = array_diff($allroles, $excludedValues);

		if (in_array($_SESSION['user_role'], $filteredArray)) {
			header('location: ../../config/redirect.php');
		}
	}else{
		header("location: ../logout.php");
	}
?>
<body>
        <?php
        include '../Components/sidebar.php';
        // echo '<img src="' . $useURL . "img/logo/logo_osas.png" . '" alt="">';
        ?>
        <!-- MAIN CONTENT -->
			<div class="container-fluid">
				<div class="container-fluid rounded-3 border border-secondary-subtle p-3 my-3">
					<div class="d-flex justify-content-between align-items-center px-2">
						<h4>Applicant Table</h4>
						<!-- <button data-bs-toggle="modal" data-bs-target="#returnApplication" type="button" class="basc-green-button btn btn-success d-flex justify-content-center align-items-center">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
								<path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
							</svg>&nbsp;New application
						</button> -->
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
						<tbody>
							<?php
							$sql = "SELECT * FROM documents WHERE status = 'endorsed'";
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
													<ul class="dropdown-menu">
														<li><a class="dropdown-item approve-pdf" href="#" data-id="'. $row['document_id'] .'" data-bs-toggle="modal" >Approve</a></li>
														<li><a class="dropdown-item view-pdf" href="#" data-id="'. $row['document_id'] .'" data-bs-toggle="modal" data-bs-target="#viewPdfApplication">View as pdf</a></li>
														<li><a class="dropdown-item return-pdf" data-id="'. $row['document_id'] .'" href="#" data-bs-toggle="modal" data-bs-target="#returnApplication">Comment/Return</a></li>
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

		<div class="d-none">
			<h5 class="">Choose a date</h5>
			<div class="input-group ">
				<input type="text" id="dateInput" class="form-control" placeholder="Select date" readonly>
				<button id="openCalendarBtn" class="btn btn-outline-success" type="button">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar" viewBox="0 0 16 16">
						<path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
					</svg>
				</button>
			</div>
		</div>
		<!-- Modal -->
		<div class="modal fade" id="returnApplication" tabindex="-1" aria-labelledby="returnApplicationLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-scrollable modal-fullscreen">
				<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="returnApplicationLabel">Comment / Return Application</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
					<form id="editorForm" method="POST" action="../function/dean/return.php">
						<input type="hidden" name="id" id="documentId">
						<div class="modal-body">
							<div class="container-fluid py-4">
								<div class="row g-4">
									<div class="col-lg-4">
										<div class="panel comments-panel d-flex flex-column justify-content-between" id="commentsPanel">
											<div id="commentsContainer"></div>
										</div>
											<div class="comment-form">
												<input type="hidden" id="commentDocumentId">
												<textarea id="commentText" placeholder="Write a comment..." rows="3" style="resize: none;"></textarea>
												<div class="d-flex align-items-center flex-column justify-content-between">
													<!-- Hidden color input -->
													<input type="color" id="highlightColor" value="#ffc107" class="d-none">

													<!-- Button with icon -->
													<button type="button" id="highlightBtn" class="btn btn-outline-secondary">
														<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-highlighter" viewBox="0 0 16 16">
															<path fill-rule="evenodd" d="M11.096.644a2 2 0 0 1 2.791.036l1.433 1.433a2 2 0 0 1 .035 2.791l-.413.435-8.07 8.995a.5.5 0 0 1-.372.166h-3a.5.5 0 0 1-.234-.058l-.412.412A.5.5 0 0 1 2.5 15h-2a.5.5 0 0 1-.354-.854l1.412-1.412A.5.5 0 0 1 1.5 12.5v-3a.5.5 0 0 1 .166-.372l8.995-8.07zm-.115 1.47L2.727 9.52l3.753 3.753 7.406-8.254zm3.585 2.17.064-.068a1 1 0 0 0-.017-1.396L13.18 1.387a1 1 0 0 0-1.396-.018l-.068.065zM5.293 13.5 2.5 10.707v1.586L3.707 13.5z"/>
														</svg>
													</button>
													<button class="btn btn-primary" id="submitCommentBtn" type="button">Send</button>
												</div>
											</div>
									</div>
									
									<div class="col-lg-8">
										<div class="panel d-flex flex-column">
											
											<textarea name="editorContent" id="editorContent" hidden></textarea>
											<div id="editor" contenteditable="true">

											</div>
											
											<div id="existingDocumentsList" class="mt-3">
                                        	</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-success basc-green-button">Return</button>
						</div>
					</form>
				</div>
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

		<!-- calendar mini components -->
		<div id="calendarContainer" class="calendar-container">
			<div class="d-flex justify-content-between align-items-center mb-2">
			<button id="prevMonth" class="btn btn-sm btn-outline-success prevnextMonthbtn"><</button>
			<h6 id="monthLabel" class="mb-0 calendar-header"></h6>
			<button id="nextMonth" class="btn btn-sm btn-outline-success prevnextMonthbtn">></button>
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
		$(document).ready( function () {
			$('#myTable').DataTable();
		} );
	</script>
	<script>
        const editor = document.getElementById('editor');

        document.getElementById('highlightColor').addEventListener('input', (e) => {
            document.execCommand('backColor', false, e.target.value);
            editor.focus();
        });
		const editorEditable = document.getElementById("editor"); // your contenteditable div

		document.getElementById("editorForm").addEventListener("submit", function() {
			document.getElementById("editorContent").value = editorEditable.innerHTML;
		});

		document.addEventListener("DOMContentLoaded", () => {
		  document.querySelectorAll(".view-pdf").forEach(btn => {
			btn.addEventListener("click", () => {
			  const id = btn.getAttribute("data-id");
			  document.getElementById("pdfFrame").src = "../function/dean/view.php?id=" + id;
			});
		  });
		});
		
		
		document.addEventListener("DOMContentLoaded", () => {
		document.querySelectorAll(".approve-pdf").forEach(btn => {
			btn.addEventListener("click", () => {
			const id = btn.getAttribute("data-id");

			// Send POST request
			const xhr = new XMLHttpRequest();
			xhr.open("POST", "../function/dean/submitpdf.php", true);
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

			xhr.onreadystatechange = function () {
				if (xhr.readyState === 4 && xhr.status === 200) {
				alertify.success(xhr.responseText);
				// if(xhr.responseText == "Record updated successfully"){
					window.location.reload();
				// }
				}
			};

			xhr.send("id=" + encodeURIComponent(id) + "&status=pending"); 
			});
		});
		});
		
		$(document).on("click", ".return-pdf", function(e) {
			e.preventDefault();

			let docId = $(this).data("id");

			$.post("../function/dean/get_document.php", { id: docId }, function(data) {
				if (data.success) {
					$("#editor").html(data.content_html);
					$("#documentId").val(docId);
					$("#returnApplication").modal("show");
					
					// Clear old supporting docs
					$("#existingDocumentsList").empty();

					if (data.supporting_docs && data.supporting_docs.length > 0) {
						data.supporting_docs.forEach(doc => {
							$("#existingDocumentsList").append(`
								<div class="d-flex align-items-center mb-2 p-2 border rounded">
									<i class='bx bxs-file-pdf text-danger fs-4 me-2'></i>
									<span class="flex-grow-1 text-truncate">${doc.file_name}</span>
									<a href="${doc.file_path}" target="_blank" class="btn btn-sm btn-primary ms-2">View</a>
								</div>
							`);
						});
					} else {
						$("#existingDocumentsList").append('<p class="text-muted">No supporting documents uploaded.</p>');
					}
				} else {
					alertify.error(data.message || "Failed to load document");
				}
			}, "json");
		});

		$("#editorForm").on("submit", function(e) {
			e.preventDefault();

			let docId = $("#documentId").val();
			let content = $("#editor").html();

			// send content to update_document.php (not re-fetch!)
			$.post("../function/dean/return.php", {
				id: docId,
				content_html: content,
			}, function(data) {
				if (data.success) {
					alertify.success(data.message);
					$("#returnApplication").modal("hide");
					window.location.reload();
				} else {
					alertify.error(data.message);
				}
			}, "json");
		});

		$(document).on("click", ".delete-pdf", function(e) {
			e.preventDefault();

			let docId = $(this).data("id");

			alertify.confirm("Delete Document", "Are you sure you want to delete this document? This action cannot be undone.", 
				function() { // OK button callback
					$.post("../function/dean/delete_document.php", { id: docId }, function(data) {
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


		//new trying
		
		$(document).on("click", ".return-pdf", function(e) {
			e.preventDefault();
			let docId = $(this).data("id");

			$.post("../function/dean/get_document.php", { id: docId }, function(data) {
				if (data.success) {
					// Load document content and history
					$("#documentViewer").html(data.content_html);
					
					// Clear and load comments
					$("#commentsContainer").empty();
					if (data.comments.length > 0) {
                        data.comments.forEach(comment => {
							let deleteButton = '';
							// Check if the comment belongs to the current user
							if (comment.user_id == data.current_user_id) {
								deleteButton = `<button type="button" class="btn rounded-3 btn-sm btn-outline-danger float-end delete-comment-btn" data-comment-id="${comment.comment_id}">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
										<path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
									</svg>
								</button>`;
							}

                            $("#commentsContainer").append(`
                                <div class="comment-item">
                                	${deleteButton}
                                    <strong>${comment.first_name} ${comment.last_name}</strong>
                                    <p>${comment.comment_text}</p>
                                    <small class="text-muted">${formatDateTime(comment.created_at)}</small>
                                </div>
                            `);
                        });
                    } else {
                        $("#commentsContainer").append('<p class="text-center text-muted mt-4">No comments yet.</p>');
                    }
					
					$("#commentDocumentId").val(docId);
					$("#returnApplication").modal("show");
				} else {
					alertify.error(data.message || "Failed to load document.");
				}
			}, "json");
		});
    
		// Handle comment submission
		$("#submitCommentBtn").on("click", function() {
			let docId = $("#commentDocumentId").val();
			let commentText = $("#commentText").val();

			if (commentText.trim() === "") {
				alertify.warning("Comment cannot be empty.");
				return;
			}

			$.post("../function/dean/save_comment.php", {
				document_id: docId,
				comment_text: commentText
			}, function(data) {
				if (data.success) {
					alertify.success("Comment added!");
					$("#commentText").val(""); // Clear the textarea
					// Clear and load comments
					$("#commentsContainer").empty();
					if (data.comments.length > 0) {
                        data.comments.forEach(comment => {
							let deleteButton = '';
							// Check if the comment belongs to the current user
							if (comment.user_id == data.current_user_id) {
								deleteButton = `<button type="button" class="btn rounded-3 btn-sm btn-outline-danger float-end delete-comment-btn" data-comment-id="${comment.comment_id}">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
										<path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
									</svg>
								</button>`;
							}

                            $("#commentsContainer").append(`
                                <div class="comment-item">
                                	${deleteButton}
                                    <strong>${comment.first_name} ${comment.last_name}</strong>
                                    <p>${comment.comment_text}</p>
                                    <small class="text-muted">${formatDateTime(comment.created_at)}</small>
                                </div>
                            `);
                        });
                    } else {
                        $("#commentsContainer").append('<p class="text-center text-muted mt-4">No comments yet.</p>');
                    }
					// Re-fetch comments to show the new one
					// You can call the same function that loads the modal content
				} else {
					alertify.error(data.message || "Failed to add comment.");
				}
			}, "json");
		});
		// Handle comment deletion
		$(document).on("click", ".delete-comment-btn", function() {
        let commentId = $(this).data("comment-id");
        let docId = $("#commentDocumentId").val();

        alertify.confirm("Delete Comment", "Are you sure you want to delete this comment?", 
            function() { // OK button callback
                $.post("../function/dean/delete_comment.php", { comment_id: commentId }, function(data) {
                    if (data.success) {
                        alertify.success(data.message);
                        // Re-fetch comments to show the updated list
                        $(".return-pdf[data-id='" + docId + "']").click();
                    } else {
                        alertify.error(data.message);
                    }
                }, "json").fail(function() {
                    alertify.error("Server error. Could not delete comment.");
                });
            }, 
            function() { // Cancel button callback
                alertify.error('Deletion canceled.');
            }
        );
    });
		const colorInput = document.getElementById("highlightColor");
		const highlightBtn = document.getElementById("highlightBtn");

		// When button is clicked, open color picker
		highlightBtn.addEventListener("click", () => {
			colorInput.click();
		});

		// Update button color preview when a color is chosen
		colorInput.addEventListener("input", () => {
			highlightBtn.style.backgroundColor = colorInput.value;
		});

    </script>
</body>
</html>
