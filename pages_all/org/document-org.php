
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
      min-height: 520px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
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
  </style>
</head>
<?php
  require '../../config/dbcon.php';
  
  if(!empty($_SESSION['user_role'])){
		if($_SESSION['user_role'] != "academic_organization" && (in_array($_SESSION['user_role'], $allroles))){
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
						<h4>Letter request</h4>
						<button data-bs-toggle="modal" data-bs-target="#newApplication" type="button" class="basc-green-button btn btn-success d-flex justify-content-center align-items-center">
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
										echo "<td>" . $row["id"] . "</td>";
										echo "<td>" . $row["filename"] . "</td>";
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
														<li><a class="dropdown-item submit-pdf" href="#" data-id="'. $row['id'] .'">Submit</a></li>
														<li><a class="dropdown-item view-pdf" href="#" data-id="'. $row['id'] .'" data-bs-toggle="modal" data-bs-target="#viewPdfApplication">View</a></li>
														<li><a class="dropdown-item" href="#">Edit</a></li>
														<li><a class="dropdown-item" href="#">Delete</a></li>
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
		<div class="modal fade" id="newApplication" tabindex="-1" aria-labelledby="newApplicationLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-scrollable modal-fullscreen">
				<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="newApplicationLabel">Create new application</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
				<form id="editorForm" method="POST" action="../function/org/save.php">
					<div class="container-fluid py-4">
						<div class="row g-4">
							<!-- Controls -->
							<div class="col-lg-4">
								<div class="panel">

								<div class="mb-2">
									<label class="form-label small">Templates</label>
									<select id="templateSelect" class="form-select">
										<option value="Accreditaion">Accreditaion</option>
										<option value="Off-campus Activities">Off-campus Activities</option>
										<option value="Proposed Activities">Proposed Activities</option>
										<option value="Recognition">Recognition</option>
									</select>
								</div>
								<h2 class="h5 mb-3">Controls</h2>

								<div class="btn-group mb-2 w-100" role="group">
									<button class="btn btn-outline-secondary" data-cmd="bold"><i class="fas fa-bold"></i></button>
									<button class="btn btn-outline-secondary" data-cmd="italic"><i class="fas fa-italic"></i></button>
									<button class="btn btn-outline-secondary" data-cmd="underline"><i class="fas fa-underline"></i></button>
									<button class="btn btn-outline-secondary" data-cmd="strikethrough"><i class="fas fa-strikethrough"></i></button>
								</div>

								<div class="btn-group mb-2 w-100" role="group">
									<button id="h1Btn" class="btn btn-outline-secondary">H1</button>
									<button id="h2Btn" class="btn btn-outline-secondary">H2</button>
									<button id="pBtn" class="btn btn-outline-secondary">P</button>
								</div>

								<div class="btn-group mb-2 w-100" role="group">
									<button class="btn btn-outline-secondary" data-cmd="subscript"><i class="fas fa-subscript"></i></button>
									<button class="btn btn-outline-secondary" data-cmd="superscript"><i class="fas fa-superscript"></i></button>
									<button class="btn btn-outline-secondary" data-cmd="removeFormat"><i class="fas fa-eraser"></i></button>
								</div>

								<h2 class="h6 mt-3">Text Formatting</h2>
								<div class="btn-group mb-2 w-100" role="group">
									<button class="btn btn-outline-secondary" data-cmd="justifyLeft"><i class="fas fa-align-left"></i></button>
									<button class="btn btn-outline-secondary" data-cmd="justifyCenter"><i class="fas fa-align-center"></i></button>
									<button class="btn btn-outline-secondary" data-cmd="justifyRight"><i class="fas fa-align-right"></i></button>
									<button class="btn btn-outline-secondary" data-cmd="justifyFull"><i class="fas fa-align-justify"></i></button>
								</div>

								<div class="mb-2">
									<label class="form-label small">Text Color</label>
									<input type="color" id="textColor" value="#1a202c" class="form-control form-control-color">
								</div>
								<div class="mb-2">
									<label class="form-label small">Highlight</label>
									<input type="color" id="highlightColor" value="#ffc107" class="form-control form-control-color">
								</div>

								<div class="btn-group mb-2 w-100" role="group">
									<button class="btn btn-outline-secondary" data-cmd="indent"><i class="fas fa-indent"></i></button>
									<button class="btn btn-outline-secondary" data-cmd="outdent"><i class="fas fa-outdent"></i></button>
									<button class="btn btn-outline-secondary" data-cmd="insertUnorderedList"><i class="fas fa-list-ul"></i></button>
									<button class="btn btn-outline-secondary" data-cmd="insertOrderedList"><i class="fas fa-list-ol"></i></button>
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
									<option value="A4" selected>A4</option>
									<option value="Letter">Letter</option>
									<option value="Legal">Legal</option>
									</select>
								</div>

								<div class="d-flex gap-2">
									<button id="clearBtn" class="btn btn-danger flex-fill"><i class="fas fa-trash"></i> Clear</button>
									<button id="exportPdfBtn" class="btn btn-success flex-fill"><i class="fas fa-file-pdf"></i> Export</button>
								</div>
								</div>
							</div>
							
							<!-- Editor -->
							<div class="col-lg-8">
								<div class="panel d-flex flex-column">
									<div class="d-flex align-items-center border-bottom mb-3 pb-2">
										<label class="me-2 fw-semibold">File Name:</label>
										<input id="filenameInput" name="filenameInput" value="page-export.pdf" class="filename-input">
									</div>
									
									<textarea name="editorContent" id="editorContent" hidden></textarea>
									<div id="editor" contenteditable="true">
										<div align="justify">
											<font face="Arial" style="letter-spacing: 0px"
												>28 February 2025<br /><br /><b>DR. CECILIA S. SANTIAGO</b><br />Vice
												President, Academic Affairs<br />This College<br /><br /><br />Madame,<br /><br />Warmest
												greetings! I hope this letter finds you well.<br /><br />The Builders of
												Information Technology Society formally request approval to host an event
												titled <b>"From Vision to Reality: The Capstone Adventure Begins”</b>,
												scheduled for <b>March 27, 2025</b>, from <b>7:00 AM to 12:00 PM</b> at the
												<b>Farmers Training Center</b>.<br /><br />This event is designed to provide
												2nd-year BSIT students with a comprehensive understanding of the capstone
												project development process. It aims to equip students with the fundamental
												knowledge necessary before embarking on their capstone journey. Through
												discussions on research methodologies, project design, and the development
												process, this event will serve as a valuable guide in preparing students for
												their future projects.<br /><br />Thank you for considering our request. We
												look forward to your positive response and hope to make this event a
												memorable occasion for our IT community.<br /><br />Respectfully yours:<br /><br /><b
												>ANGELO LAURENTE</b
												><br />OIC President, Builders of Information Technology Society<br /><br /><b
												>MA. MELANIE ABLAZA-CRUZ, DIT</b
												>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
												&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br />Adviser,
												Builders of Information Technology Society<br /><br />Noted by:<br /><br /><br /><b
												>MICHELLE M. CORTEZ, MIT</b
												><br />Dean, Institute of Engineering and Applied Technology<br /><br /><b
												>VLADIMIR C. SEMPIO, RN, MSN</b
												><br />Head, Student Development Programs Unit<br /><br />Recommending
												Approval:<br /><b><br />JENNIFER P. ADRIANO, Ph.D.</b><br />Director,
												Student Affairs and Services<br /><br /><br /><br /><br
											/></font>
											</div>

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-success basc-green-button">Save changes</button>
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
        
        document.getElementById('exportPdfBtn').addEventListener('click', async () => {
            try {
                const content = document.getElementById('editor').innerHTML;
                const filenameInput = document.getElementById('filenameInput').value || 'page-export.pdf';
                const filename = filenameInput.replace(/\.docx$/i, '.pdf');
                const pageSize = document.getElementById('pageSizeSelect').value || 'A4';
        
        
                const form = new FormData();
                form.append('html_content', content);
                form.append('filename', filename);
                form.append('page_size', pageSize);
        
                // Send as multipart/form-data (no manual content-type header)
                const resp = await fetch('export.php', { method: 'POST', body: form });
        
                if (!resp.ok) throw new Error('Server returned ' + resp.status);
                const blob = await resp.blob();
                saveAs(blob, filename); // FileSaver.js already loaded in your page
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
				if(xhr.responseText == "Record updated successfully"){
					window.location.reload();
				}
				}
			};

			xhr.send("id=" + encodeURIComponent(id) + "&status=submitted"); 
			});
		});
		});

    </script>
</body>
</html>
