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
			max-height: calc(100vh - 130px);  /* Adjust this value as needed */
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
        $excludedValues = ["admin"];
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

    $current_org_id = $_SESSION['organization_id'] ?? null;

    $view_all = isset($_GET['view_all']) && $_GET['view_all'] == '1';
    $limit_clause = $view_all ? '' : 'LIMIT 5';

    if (empty($current_org_id)) {
        $sql = "SELECT DISTINCT e.event_id, e.title, e.description, e.start_date, e.end_date, e.location, e.total_expenses
                FROM events e
                INNER JOIN documents d ON e.event_id = d.event_id
                WHERE e.end_date < NOW()
                AND d.status = 'approved_fssc'
                ORDER BY e.end_date DESC
                $limit_clause";
        $stmt = $conn->prepare($sql);
    } else {
        $sql = "SELECT DISTINCT e.event_id, e.title, e.description, e.start_date, e.end_date, e.location, e.total_expenses
                FROM events e
                INNER JOIN documents d ON e.event_id = d.event_id
                WHERE e.organization_id = ?
                AND e.end_date < NOW()
                AND d.status = 'approved_fssc'
                ORDER BY e.end_date DESC
                $limit_clause";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $current_org_id);
    }


    $stmt->execute();
    $result = $stmt->get_result();

    $past_events = [];
    while ($row = $result->fetch_assoc()) {
        $past_events[] = $row;
    }

    $stmt->close();

?>
<body>
        <?php
        include '../Components/sidebar.php';
        // echo '<img src="' . $useURL . "img/logo/logo_osas.png" . '" alt="">';
        ?>
            <div class="container-fluid p-4">
                <div class="row">
                    <div class="col-md-8">
                        <div id="calendarContainerPage" class="calendar-container d-block">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                            <button id="prevMonthPage" class="btn btn-sm btn-outline-success prevnextMonthbtn"><</button>
                            <h6 id="monthLabel" class="mb-0 calendar-header"></h6>
                            <button id="nextMonthPage" class="btn btn-sm btn-outline-success prevnextMonthbtn">></button>
                            </div>
                            <table class="table table-bordered text-center">
                            <thead id="calendarHeaderPage" class="table-light">
                                <tr>
                                <th>Su</th><th>Mo</th><th>Tu</th><th>We</th>
                                <th>Th</th><th>Fr</th><th>Sa</th>
                                </tr>
                            </thead>
                            <tbody id="calendarBodyPage"></tbody>
                            </table>
                            <div class="d-flex justify-content-end mt-2">
                                <button id="todayBtnPage" class="btn btn-sm btn-success basc-green-button me-1">Today</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 bg-white rounded-3 p-3">
                        <p class="fw-semibold text-success border-bottom border-secondary pb-2 text-md-start text-lg-2xl truncate">
                            Schedule for <time id="selectedDate" datetime=""></time>
                        </p>
                        <ol id="meetingList" class="mt-4 list-unstyled text-sm-start text-secondary">
                        </ol>
                    </div>
                </div>
                

                <div class="p-2">
                    <div class="row">
                        <div class="col-12 d-flex justify-content-between align-items-center">
                            <h3 class="mb-4">
                                <i class='bx bxs-calendar-check'></i> Latest Past Events
                            </h3>
                            
                            <?php if (count($past_events) >= 5 && !isset($_GET['view_all'])): ?>
                                <div class="text-center mt-4">
                                    <button id="viewAllEventsBtn" class="btn btn-outline-success px-4">
                                        View All Events
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        <?php if (count($past_events) > 0): ?>
                            <?php foreach ($past_events as $event): ?>
                                <div class="col">
                                    <div class="card h-100 shadow-sm border-0">
                                        <div class="card-header bg-success text-white">
                                            <h5 class="card-title mb-0"><?php echo htmlspecialchars($event['title']); ?></h5>
                                            <small>Event ID: <?php echo $event['event_id']; ?></small>
                                        </div>
                                        
                                        <div class="card-body d-flex flex-column">
                                            <p class="card-text text-muted small">
                                                <?php 
                                                    $desc = htmlspecialchars($event['description']);
                                                    echo (strlen($desc) > 100) ? substr($desc, 0, 100) . '...' : $desc; 
                                                ?>
                                            </p>
                                            
                                            <ul class="list-group list-group-flush mt-auto">
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <i class='bx bxs-calendar-alt me-2 text-info'></i>
                                                        <strong>Date:</strong>
                                                    </div>
                                                    <span><?php echo date('M j, Y', strtotime($event['start_date'])); ?></span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <i class='bx bxs-map me-2 text-danger'></i>
                                                        <strong>Location:</strong>
                                                    </div>
                                                    <span><?php echo htmlspecialchars($event['location']); ?></span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <i class='bx bxs-dollar-circle me-2 text-success'></i>
                                                        <strong>Expenses:</strong>
                                                    </div>
                                                    <span>₱ <?php echo number_format($event['total_expenses'], 2); ?></span>
                                                </li>
                                            </ul>
                                        </div>
                                        
                                        <div class="card-footer bg-light border-0 d-flex gap-2">
                                        <button 
                                            type="button" 
                                            class="btn btn-sm btn-outline-success flex-fill view-event-details"
                                            data-event-id="<?php echo $event['event_id']; ?>">
                                            <i class='bx bx-show'></i> View Details
                                        </button>

                                            
                                            <button 
                                                type="button" 
                                                class="btn btn-sm btn-primary upload_proof_btn flex-fill d-none" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#uploadProofModal"
                                                data-event-id="<?php echo $event['event_id']; ?>"
                                                data-event-title="<?php echo htmlspecialchars($event['title']); ?>"
                                            >
                                                <i class='bx bx-cloud-upload'></i> Upload Proof
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12">
                                <div class="alert alert-info" role="alert">
                                    No past events found for your organization.
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
			</div>

            <div class="modal fade" id="uploadProofModal" tabindex="-1" aria-labelledby="uploadProofModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form id="uploadProofForm" action="../function/org/upload_docs_events.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" id="modalEventId" name="event_id">

                            <div class="modal-header">
                                <h5 class="modal-title" id="uploadProofModalLabel">Upload Proofs for: <span id="modalEventTitle"></span></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">

                                <div class="alert alert-info" role="alert">
                                    Upload documents, images, or files that serve as event proof.
                                </div>

                                <div class="mb-2">
                                    <label for="proof_files" class="form-label small">Upload Files (PDF, Images, DOCX)</label>
                                    <input 
                                        id="proof_files" 
                                        name="proof_files[]" 
                                        type="file" 
                                        accept="application/pdf, image/*, .doc, .docx, .xls, .xlsx" 
                                        multiple 
                                        class="form-control"
                                    >
                                </div>

                                <div id="existingProofsList" class="mt-3">
                                    </div>
                                
                                <div class="mb-3 mt-3">
                                    <label for="proof_notes" class="form-label">Notes/Summary (Optional):</label>
                                    <textarea class="form-control" id="proof_notes" name="proof_notes" rows="3" placeholder="Provide a brief summary or notes regarding the uploaded proofs."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success basc-green-button">
                                    <i class='bx bx-save'></i> Save Proofs
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- View Event Details Modal -->
                <div class="modal fade" id="viewEventDetailsModal" tabindex="-1" aria-labelledby="viewEventDetailsLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="viewEventDetailsLabel">Event Details</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="eventDetailsContent" class="p-3">
                        <p class="text-muted">Loading details...</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                    </div>
                </div>
                </div>


		</main>

        <!-- Modal -->
        <div class="modal fade" id="documentModal" tabindex="-1" aria-labelledby="documentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="documentModalLabel">Modal title</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body" id="documentContent">
                        <!-- Loaded dynamically -->
                    </div>
                </div>
            </div>
        </div>

        
		</div>
    <script src="../../assets/jquery/jquery-3.7.1.min.js"></script> 
    <script src="../../assets/externalJS/script.js"></script>
	<script	script src="../../assets/datatables/dataTables.min.js"></script> 
    <script	script src="../../assets/datatables/dataTables.bootstrap5.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../../../assets/js/date-fns.js"></script>
	<script src="../../assets/darkmode.js" defer></script>
	<script src="../../assets/count.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="../../assets/externalJS/app.js"></script>
    
    
    
	<script>
        // --- DEPENDENCIES: dateFns should be imported or globally available ---
// const { format, parseISO, isSameDay } = dateFns; 
// Assuming this is handled in your HTML setup

let events = []; // Array to hold fetched events

async function loadEvents() {
    try {
        // NOTE: The PHP function is expected to return JSON data 
        // with fields: id, title, description, start_date, end_date, location, total_expenses, imageUrl
        const res = await fetch("../function/org/get_events.php");
        events = await res.json();
    } catch (err) {
        console.error("Error loading events:", err);
        // Optionally load dummy data if fetching fails (for development)
        // events = [ ... YOUR DUMMY DATA HERE ... ]; 
    }
}

// Call this before rendering the calendar
loadEvents().then(() => {
    // Wait for DOM content to load before calling renderCalendar
    if (document.getElementById("calendarContainerPage")) {
        // Initialization code moves here after events are loaded
        // and before the first renderCalendar call
        // This ensures events are ready for the initial render.
        
        // --- CALENDAR & EVENTS SETUP ---
        const calendarContainerPage = document.getElementById("calendarContainerPage");
        const monthLabel = document.getElementById("monthLabel");
        const calendarBodyPage = document.getElementById("calendarBodyPage");
        const calendarHeaderPage = document.getElementById("calendarHeaderPage");

        let currentDate = new Date(); // The month being viewed
        let selectedDate = dateFns.startOfDay(new Date()); // The currently selected day, initialized to TODAY
        let currentView = "days"; 

        // Initial call to render the calendar and events list
        renderCalendar(currentDate); 
        renderMeetings(selectedDate); // Initial call to show today's events
        
        // --- DAYS VIEW ---
        function renderCalendar(date) {
            currentView = "days";
            calendarHeaderPage.style.display = "table-header-group";

            const startOfMonth = dateFns.startOfMonth(date);
            const endOfMonth = dateFns.endOfMonth(date);
            const startDate = dateFns.startOfWeek(startOfMonth, { weekStartsOn: 0 });
            const endDate = dateFns.endOfWeek(endOfMonth, { weekStartsOn: 0 });

            monthLabel.textContent = dateFns.format(date, "MMMM yyyy");
            calendarBodyPage.innerHTML = "";

            let day = startDate;
            while (day <= endDate) {
                let row = document.createElement("tr");
                for (let i = 0; i < 7; i++) {
                    const currentCellDate = day;
                    const cell = document.createElement("td");
                    cell.classList.add("calendar-day");
                    cell.textContent = currentCellDate.getDate();

                    // Default styles
                    if (!dateFns.isSameMonth(currentCellDate, date)) {
                        cell.classList.add("other-month");
                    }
                    if (dateFns.isToday(currentCellDate)) {
                        cell.classList.add("today");
                    }
                    if (selectedDate && dateFns.isSameDay(currentCellDate, selectedDate)) {
                        cell.classList.add("selected");
                    }

                    // 🔹 Check events
                    // Use find() to check for an event on this specific day
                    const dayEvents = events.filter(ev => dateFns.isSameDay(dateFns.parseISO(ev.start_date), currentCellDate));
                    if (dayEvents.length > 0) {
                        cell.classList.add("eventDate");

                        // Create a simple popover content string for multiple events
                        const eventTitles = dayEvents.map(ev => `<li>${ev.title}</li>`).join('');
                        
                        cell.setAttribute("data-bs-toggle", "popover");
                        cell.setAttribute("data-bs-trigger", "hover"); // Popover on hover is usually better for calendars
                        cell.setAttribute("data-bs-placement", "top");
                        cell.setAttribute("data-bs-title", `${dayEvents.length} Event${dayEvents.length > 1 ? 's' : ''}`);
                        cell.setAttribute("data-bs-content", `<ul>${eventTitles}</ul>`);
                    }

                    // Click handler
                    cell.addEventListener("click", (e) => {
                        e.stopPropagation();
                        
                        // 1. Update selection style
                        document.querySelectorAll(".calendar-day").forEach(c => c.classList.remove("selected"));
                        cell.classList.add("selected");
                        
                        // 2. Update selected date
                        selectedDate = currentCellDate;
                        
                        // 3. ⭐️ RENDER EVENTS LIST ⭐️
                        renderMeetings(selectedDate); 
                    });

                    row.appendChild(cell);
                    day = dateFns.addDays(day, 1);
                }
                calendarBodyPage.appendChild(row);
            }

            // Re-init Bootstrap popovers
            const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            // Destroy existing popovers before re-initializing to prevent issues on re-render
            popoverTriggerList.forEach(el => {
                const existingPopover = bootstrap.Popover.getInstance(el);
                if (existingPopover) existingPopover.dispose();
            });
            popoverTriggerList.map(el => new bootstrap.Popover(el, { html: true })); // html: true for list content
        }
        
        // ⭐️ INTEGRATED BOOTSTRAP EVENT RENDER FUNCTION ⭐️
        function renderMeetings(day) {
            const meetingList = document.getElementById('meetingList');
            meetingList.innerHTML = ''; 
            
            // Set the selected date header text
            document.getElementById('selectedDate').innerText = dateFns.format(day, 'MMM dd, yyyy');

            // Filter events for the selected day
            const selectedEvents = events.filter(event => 
                dateFns.isSameDay(dateFns.parseISO(event.start_date), day)
            );

            // Handle 'No Events' case
            if (selectedEvents.length === 0) {
                meetingList.innerHTML = '<p class="text-center display-6 text-muted">No events scheduled.</p>';
                return;
            }

            // Render the events list
            // Render the events list
selectedEvents.forEach(event => {
    const meetingItem = document.createElement('li');
    meetingItem.className = 'mb-3'; 
    
    // Build documents buttons
    let docsHtml = '';
    if (event.documents && event.documents.length > 0) {
        docsHtml = event.documents.map((doc, index) => `
            <button type="button" class="btn btn-sm btn-outline-primary me-2 view-doc-btn" 
                data-docid="${doc.document_id}" 
                data-dochtml="${encodeURIComponent(doc.content_html)}">
                <i class="bi bi-file-earmark-text"></i> ${doc.document_type || 'Document'} ${index + 1}
            </button>
        `).join('');
    } else {
        docsHtml = `<span class="text-muted">No approved documents</span>`;
    }

    meetingItem.innerHTML = `
        <div class="card p-3 shadow-sm bg-light">
            <div class="d-flex align-items-center">
                <img src="${event.imageUrl || 'https://via.placeholder.com/50'}" alt="Event icon" 
                     class="rounded-circle me-3" 
                     style="width: 50px; height: 50px; object-fit: cover;">
                <div class="flex-grow-1">
                    <h5 class="card-title mb-0 text-primary">${event.title}</h5>
                    <p class="card-subtitle text-muted small mt-1">
                        <i class="bi bi-clock me-1"></i>
                        <time datetime="${event.start_date}">
                            ${dateFns.format(dateFns.parseISO(event.start_date), 'h:mm a')} - ${dateFns.format(dateFns.parseISO(event.end_date), 'h:mm a')}
                        </time>
                    </p>
                </div>
            </div>
            
            <hr class="my-2">
            
            <div class="small mb-2">
                <p class="mb-1 text-secondary">
                    <i class="bi bi-geo-alt-fill me-1"></i>
                    <strong>Location:</strong> ${event.location}
                </p>
                <p class="mb-0 text-dark">
                    <strong>Description:</strong> ${event.description}
                </p>
            </div>
            
            <div class="mt-2">
                ${docsHtml}
            </div>
        </div>`;
    meetingList.appendChild(meetingItem);
});

// Attach click handler for dynamically created buttons
document.addEventListener("click", (e) => {
    if (e.target.closest(".view-doc-btn")) {
        const btn = e.target.closest(".view-doc-btn");
        const html = decodeURIComponent(btn.getAttribute("data-dochtml"));
        document.getElementById("documentContent").innerHTML = html;
        const modal = new bootstrap.Modal(document.getElementById("documentModal"));
        modal.show();
    }
});

        }
        // --- END OF INTEGRATED FUNCTION ---


        function renderMonths(date) {
        this.currentView = "months";
        this.calendarHeader.style.display = "none";
        this.monthLabel.textContent = this.dateFns.format(date, "yyyy");
        this.calendarBody.innerHTML = "";

        const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        let row;

        months.forEach((m, i) => {
            if (i % 4 === 0) {
                row = document.createElement("tr");
                this.calendarBody.appendChild(row);
            }
            const cell = document.createElement("td");
            cell.textContent = m;
            cell.classList.add("pickercalendar-month"); // 👈 Updated class name

            // Highlight the current month in the view's year
            if (this.dateFns.getYear(this.currentDate) === this.dateFns.getYear(new Date()) && this.dateFns.getMonth(new Date()) === i) {
                cell.classList.add("pickertoday-month"); // 👈 Updated class name
            }
            // Highlight the previously selected month if it's in the current view's year
            if (this.selectedDate && this.dateFns.getYear(this.selectedDate) === this.dateFns.getYear(this.currentDate) && this.dateFns.getMonth(this.selectedDate) === i) {
                cell.classList.add("selected");
            }


            cell.addEventListener("click", (e) => {
                e.stopPropagation();
                this.currentDate = this.dateFns.setMonth(this.currentDate, i);
                this.renderCalendar(this.currentDate);
            });
            row.appendChild(cell);
        });
    }

    function renderYears(date) {
        this.currentView = "years";
        this.calendarHeader.style.display = "none";

        const year = this.dateFns.getYear(date);
        const startYear = Math.floor(year / 12) * 12;
        this.monthLabel.textContent = `${startYear} - ${startYear + 11}`;
        this.calendarBody.innerHTML = "";

        let row;
        for (let i = 0; i < 12; i++) {
            const y = startYear + i;
            if (i % 4 === 0) {
                row = document.createElement("tr");
                this.calendarBody.appendChild(row);
            }
            const cell = document.createElement("td");
            cell.textContent = y;
            cell.classList.add("pickercalendar-year"); // 👈 Updated class name

            // Highlight the current year
            if (y === this.dateFns.getYear(new Date())) {
                cell.classList.add("pickertoday-year"); // 👈 Updated class name
            }

            // Highlight the previously selected year
            if (this.selectedDate && y === this.dateFns.getYear(this.selectedDate)) {
                cell.classList.add("selected");
            }

            cell.addEventListener("click", (e) => {
                e.stopPropagation();
                this.currentDate = this.dateFns.setYear(this.currentDate, y);
                this.renderMonths(this.currentDate);
            });
            row.appendChild(cell);
        }
    }


        // --- Nav buttons (UNCHANGED) ---
        document.getElementById("prevMonthPage").addEventListener("click", (e) => {
            e.stopPropagation();
            if (currentView === "days") {
                currentDate = dateFns.subMonths(currentDate, 1);
                renderCalendar(currentDate);
            } else if (currentView === "months") {
                currentDate = dateFns.subYears(currentDate, 1);
                renderMonths(currentDate);
            } else if (currentView === "years") {
                currentDate = dateFns.subYears(currentDate, 12);
                renderYears(currentDate);
            }
        });

        document.getElementById("nextMonthPage").addEventListener("click", (e) => {
            e.stopPropagation();
            if (currentView === "days") {
                currentDate = dateFns.addMonths(currentDate, 1);
                renderCalendar(currentDate);
            } else if (currentView === "months") {
                currentDate = dateFns.addYears(currentDate, 1);
                renderMonths(currentDate);
            } else if (currentView === "years") {
                currentDate = dateFns.addYears(currentDate, 12);
                renderYears(currentDate);
            }
        });

        // --- Click header to switch view (UNCHANGED) ---
        monthLabel.addEventListener("click", (e) => {
            e.stopPropagation();
            if (currentView === "days") {
                renderMonths(currentDate);
            } else if (currentView === "months") {
                renderYears(currentDate);
            }
        });


        // --- Today button (MODIFIED to also call renderMeetings) ---
        document.getElementById("todayBtnPage").addEventListener("click", (e) => {
            e.stopPropagation();
            const today = new Date();
            currentDate = today;
            selectedDate = dateFns.startOfDay(today); // Make sure selectedDate is start of day
            renderCalendar(currentDate);
            renderMeetings(selectedDate); // RENDER EVENTS
        });
    }
});

function loadExistingEventProofs(data) {
    // Clear old proofs list (using jQuery as in your original snippet)
    $("#existingProofsList").empty();
    
    const docs = data.supporting_docs || []; // Or data.proofs

    if (docs.length > 0) {
        docs.forEach(doc => {
            // Get icon based on file name extension
            const iconData = getFileIcon(doc.file_name); 

            $("#existingProofsList").append(`
                <div class="d-flex align-items-center mb-2 p-2 border rounded">
                    <i class='bx ${iconData.icon} ${iconData.color} fs-4 me-2'></i>
                    <span class="flex-grow-1 text-truncate">${doc.file_name}</span>
                    <a href="${doc.file_path}" target="_blank" class="btn btn-sm btn-primary ms-2">View</a>
                    <button type="button" class="btn btn-sm btn-danger ms-2 delete-event-proof" data-id="${doc.id}">
                        Delete
                    </button>
                </div>
            `);
        });
    } else {
        $("#existingProofsList").append('<p class="text-muted">No proofs uploaded for this event.</p>');
    }
}
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('proof_files'); // ID from modal
    const proofsList = document.getElementById('existingProofsList'); // ID from modal

    if (fileInput && proofsList) {
        fileInput.addEventListener('change', function(event) {
            const files = event.target.files;

            if (files.length > 0) {
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    
                    const iconData = getFileIcon(file.type || file.name);

                    // Create preview wrapper
                    const filePreviewDiv = document.createElement('div');
                    filePreviewDiv.className = 'd-flex align-items-center mb-2 p-2 border rounded temp-doc';

                    filePreviewDiv.innerHTML += `
                        <i class='bx ${iconData.icon} ${iconData.color} fs-4 me-2'></i>
                        <span class="flex-grow-1 text-truncate">${file.name}</span>
                        <button type="button" class="btn btn-sm btn-primary ms-2" disabled title="Will be viewable after saving">View</button>
                        <button type="button" class="btn btn-sm btn-danger ms-2 remove-temp-doc">Remove</button>
                    `;

                    // Append to list
                    proofsList.appendChild(filePreviewDiv);
                }
            }
        });

        // Remove temporary previewed file (Delegation for dynamic content)
        proofsList.addEventListener("click", function(e) {
            if (e.target.classList.contains("remove-temp-doc")) {
                // Find and remove the parent element with class 'temp-doc'
                e.target.closest(".temp-doc").remove();
            }
        });
    }
    // Note: The rest of the JS should be placed outside the DOMContentLoaded event if it relies on jQuery/other elements.
});

document.addEventListener('DOMContentLoaded', function() {
    const uploadButtons = document.querySelectorAll('.upload_proof_btn');

    uploadButtons.forEach(button => {
        button.addEventListener('click', function() {
            const eventId = this.getAttribute('data-event-id');
            const eventTitle = this.getAttribute('data-event-title');

            // Fill modal fields
            document.getElementById('modalEventId').value = eventId;
            document.getElementById('modalEventTitle').textContent = eventTitle;

            // Show a loading indicator in the existingProofsList
            $("#existingProofsList").html('<p class="text-muted">Loading existing proofs...</p>');

            // Fetch existing proofs
            $.ajax({
                url: '../function/org/get_event_proofs.php',
                type: 'GET',
                data: { event_id: eventId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        loadExistingEventProofs(response);
                    } else {
                        $("#existingProofsList").html(`<p class="text-danger">${response.message || 'No proofs found.'}</p>`);
                    }
                },
                error: function() {
                    $("#existingProofsList").html('<p class="text-danger">Failed to load proofs.</p>');
                }
            });
        });
    });
});
$(document).on("click", ".delete-event-proof", function() {
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
                    btn.closest("div.d-flex").remove(); // Remove the proof item cleanly
                } else {
                    alertify.error(data.message);
                }
            }, "json");
        },
        function () {
            // ❌ User clicked Cancel
            alertify.error("Delete cancelled");
        }
    ).set('labels', { ok: 'Yes', cancel: 'No' }).set('reverseButtons', true);
});


$(document).on("click", ".view-event-details", function() {
    const eventId = $(this).data("event-id");

    $("#eventDetailsContent").html("<p class='text-muted'>Loading details...</p>");
    $("#viewEventDetailsModal").modal("show");

    $.post("../function/org/fetch_event_details.php", { event_id: eventId }, function(response) {
        if (response.success) {
            const e = response.event;
            let html = `
                <h4>${e.title}</h4>
                <p><strong>Date:</strong> ${formatDate(e.start_date)}</p>
                <p><strong>Location:</strong> ${e.location}</p>
                <p><strong>Total Expenses:</strong> ₱${parseFloat(e.total_expenses).toFixed(2)}</p>
                <p><strong>Description:</strong><br>${e.description}</p>
                <hr>
                <h5>Supporting Documents</h5>
            `;

            if (response.proofs.length > 0) {
                response.proofs.forEach(doc => {
                    // use your helper to determine icon & color
                    const iconData = getFileIcon(doc.file_name);

                    html += `
                        <div class="d-flex align-items-center mb-2 p-2 border rounded">
                            <i class='bx ${iconData.icon} ${iconData.color} fs-4 me-2'></i>
                            <span class="flex-grow-1 text-truncate">${doc.file_name}</span>
                            <a href="${doc.file_path}" target="_blank" class="btn btn-sm btn-primary ms-2">View</a>
                        </div>
                    `;
                });
            } else {
                html += `<p class="text-muted">No proofs uploaded for this event.</p>`;
            }

            $("#eventDetailsContent").html(html);
        } else {
            $("#eventDetailsContent").html(`<p class='text-danger'>${response.message}</p>`);
        }
    }, "json");
});


document.addEventListener('DOMContentLoaded', () => {
    const viewAllBtn = document.getElementById('viewAllEventsBtn');
    if (viewAllBtn) {
        viewAllBtn.addEventListener('click', function() {
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Loading...';
            
            // Reload the same page with ?view_all=1 parameter
            fetch(window.location.pathname + '?view_all=1')
                .then(response => response.text())
                .then(html => {
                    // Extract only the card section
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newCards = doc.querySelector('.row.row-cols-1');
                    
                    if (newCards) {
                        document.querySelector('.row.row-cols-1').innerHTML = newCards.innerHTML;
                        viewAllBtn.remove(); // Remove button after showing all
                    }
                })
                .catch(err => {
                    console.error(err);
                    this.textContent = 'Failed to load events';
                });
        });
    }
});
</script>

    
    <style>
        
        .alertify .ajs-footer .ajs-buttons .ajs-ok {
                background-color: #355f2e;
                border: 1px solid #355f2e;
                color: #fff;
                padding: 6px 14px;
                border-radius: 6px;
                font-weight: 500;
                transition: background-color 0.2s, border-color 0.2s;
            }

            .alertify .ajs-footer .ajs-buttons .ajs-ok:hover {
                background-color: #198754; /* darker green hover */
                border-color: #198754;
            }

            .alertify .ajs-footer .ajs-buttons .ajs-cancel {
                background-color: #6c757d;
                border: 1px solid #6c757d;
                color: #fff;
                padding: 6px 14px;
                border-radius: 6px;
                font-weight: 500;
                transition: background-color 0.2s, border-color 0.2s;
            }

            .alertify .ajs-footer .ajs-buttons .ajs-cancel:hover {
                background-color: #5c636a;
                border-color: #565e64;
            }
    </style>
</body>
</html>