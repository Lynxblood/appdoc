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
        $excludedValues = ["dean"];
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
			</div>
		</main>
        <!-- Document Modal -->
        <div class="modal fade" id="documentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Document</h5>
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

let events = [
    
]; // Array to hold fetched events

async function loadEvents() {
    try {
        // NOTE: The PHP function is expected to return JSON data 
        // with fields: id, title, description, start_date, end_date, location, total_expenses, imageUrl
        const res = await fetch("../function/dean/get_events.php");
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


        // --- Months view and Years View functions remain UNCHANGED ---
        function renderMonths(date) { /* ... unchanged ... */ }
        function renderYears(date) { /* ... unchanged ... */ }


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


    </script>
</body>
</html>