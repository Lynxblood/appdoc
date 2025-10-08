// Ensure you have date-fns imported in your environment

class DatePicker {
    /**
     * @param {string} inputSelector - CSS selector for the date input field (e.g., '#dateInput1').
     * @param {string} containerSelector - CSS selector for the calendar container (e.g., '#calendarContainer').
     * @param {object} dateFns - The date-fns library object.
     */
    constructor(inputSelector, containerSelector, dateFns) {
        this.inputElement = document.querySelector(inputSelector);
        this.container = document.querySelector(containerSelector);
        this.dateFns = dateFns;

        if (!this.inputElement || !this.container) {
            console.error('DatePicker: Input or container element not found.');
            return;
        }

        // Elements inside the container (only retrieved for rendering/state tracking)
        this.monthLabel = this.container.querySelector("#monthLabel");
        this.calendarBody = this.container.querySelector("#calendarBody");
        this.calendarHeader = this.container.querySelector("#calendarHeader");
        // Navigation/Action buttons are referenced but their listeners are set globally (see below)

        this.currentDate = new Date(); // Date to display the month/year for
        this.selectedDate = this.getInitialDate(); // The date selected by the user
        this.currentView = "days"; // days | months | years

        this.attachInputListener(); // ONLY attaches listener to the input element
        this.renderCalendar(this.currentDate); // Initial render (hidden)
    }

    // --- State and Initialization ---

    getInitialDate() {
        if (this.inputElement.value) {
            const parsedDate = this.dateFns.parse(this.inputElement.value, "yyyy-MM-dd", new Date());
            if (this.dateFns.isValid(parsedDate)) {
                return parsedDate;
            }
        }
        return null;
    }

    // ONLY attach listener to the unique input element and the document
    attachInputListener() {
        // Event listener for the input field to show the calendar
        this.inputElement.addEventListener("click", (e) => this.toggleCalendar(e));
        
        // Prevent container clicks from propagating to the document listener
        this.container.addEventListener("click", (e) => e.stopPropagation());
    }

    // --- Core Logic ---

    toggleCalendar(e) {
        e.stopPropagation();
        
        // 🔑 FIX: Set this instance as the ACTIVE ONE so global listeners can access it
        DatePicker.activeInstance = this; 
        
        const rect = e.target.getBoundingClientRect();
        this.container.style.top = rect.bottom + window.scrollY + "px";
        this.container.style.left = rect.left + window.scrollX + "px";
        this.container.style.zIndex = "2000"; 
        this.container.style.display = "block";

        // Re-initialize state based on input value when opening
        this.selectedDate = this.getInitialDate();
        this.currentDate = this.selectedDate || new Date();
        this.renderCalendar(this.currentDate);
    }

    handleOutsideClick(e) {
        // Close if the click is not on the input, and not inside the calendar container
        if (e.target !== this.inputElement && !this.container.contains(e.target)) {
            this.container.style.display = "none";
            DatePicker.activeInstance = null; // Clear active instance when closing
        }
    }

    // --- New Action Methods for Global Listeners to Call ---

    clearDate() {
        this.inputElement.value = "";
        this.selectedDate = null;
        this.renderCalendar(this.currentDate);
        this.container.style.display = "none"; // Close after clearing
        DatePicker.activeInstance = null;
    }
    
    selectToday() {
        const today = new Date();
        this.currentDate = today;
        this.selectedDate = today;
        this.inputElement.value = this.dateFns.format(today, "yyyy-MM-dd");
        this.renderCalendar(this.currentDate);
        this.container.style.display = "none"; // Close on selection
        DatePicker.activeInstance = null;
    }
    
    // --- Navigation (No change needed here) ---
    
    navigate(direction) {
        if (this.currentView === "days") {
            this.currentDate = this.dateFns.addMonths(this.currentDate, direction);
            this.renderCalendar(this.currentDate);
        } else if (this.currentView === "months") {
            this.currentDate = this.dateFns.addYears(this.currentDate, direction);
            this.renderMonths(this.currentDate);
        } else if (this.currentView === "years") {
            this.currentDate = this.dateFns.addYears(this.currentDate, direction * 12);
            this.renderYears(this.currentDate);
        }
    }

    selectDate(date) {
        this.selectedDate = date;
        this.inputElement.value = this.dateFns.format(date, "yyyy-MM-dd");
        this.container.style.display = "none"; // Close calendar on selection
        DatePicker.activeInstance = null;
    }

    // --- Render Views (Your updated class names are preserved) ---

    renderCalendar(date) {
        this.currentView = "days";
        this.calendarHeader.style.display = "table-header-group";

        const startOfMonth = this.dateFns.startOfMonth(date);
        const endOfMonth = this.dateFns.endOfMonth(date);
        const startDate = this.dateFns.startOfWeek(startOfMonth, { weekStartsOn: 0 }); // Sunday start
        const endDate = this.dateFns.endOfWeek(endOfMonth, { weekStartsOn: 0 });

        this.monthLabel.textContent = this.dateFns.format(date, "MMMM yyyy");
        this.calendarBody.innerHTML = "";

        let day = startDate;
        while (day <= endDate) {
            let row = document.createElement("tr");
            for (let i = 0; i < 7; i++) {
                const currentCellDate = day;
                const cell = document.createElement("td");
                cell.classList.add("pickercalendar-day"); // 👈 Updated class name
                cell.textContent = currentCellDate.getDate();

                if (!this.dateFns.isSameMonth(currentCellDate, date)) {
                    cell.classList.add("pickerother-month"); // 👈 Updated class name
                }
                if (this.dateFns.isToday(currentCellDate)) {
                    cell.classList.add("pickertoday"); // 👈 Updated class name
                }
                if (this.selectedDate && this.dateFns.isSameDay(currentCellDate, this.selectedDate)) {
                    cell.classList.add("selected");
                }

                cell.addEventListener("click", (e) => {
                    e.stopPropagation();
                    this.selectDate(currentCellDate);
                });

                row.appendChild(cell);
                day = this.dateFns.addDays(day, 1);
            }
            this.calendarBody.appendChild(row);
        }
    }

    renderMonths(date) {
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

    renderYears(date) {
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
}

// 2. Add the static property to the class
DatePicker.activeInstance = null;

// function fetchNotifications() {
// 	// Change the URL here
// 	$.getJSON('../../pages/Components/fetch_notifications.php', function(data) {
// 		const countElement = $('#notificationCount');
// 		const listElement = $('#notificationList');

// 		if (data.unread_count > 0) {
// 			countElement.removeClass('d-none').text(data.unread_count);
// 		} else {
// 			countElement.addClass('d-none');
// 		}

// 		listElement.empty();
// 		if (data.notifications.length > 0) {
// 			data.notifications.forEach(notif => {
// 				const isReadClass = notif.is_read == 1 ? 'text-muted' : 'fw-bold';
// 				listElement.append(`
// 					<li>
// 						<a class="dropdown-item ${isReadClass}" href="#" data-id="${notif.notification_id}">
// 							${notif.message}<br>
// 							<small class="text-secondary">${notif.created_at}</small>
// 						</a>
// 					</li>
// 				`);
// 			});
// 		} else {
// 			listElement.append('<li><a class="dropdown-item text-center" href="#">No new notifications</a></li>');
// 		}
// 	});
// }

// // Mark notification as read when clicked
// $(document).on('click', '#notificationList a', function() {
// 	const notifId = $(this).data('id');
// 	if (notifId) {
// 		// Change the URL here
// 		$.post('../components/fetch_notifications.php', { mark_as_read: notifId }, function() {
// 			fetchNotifications();
// 		});
// 	}
// });

// // Fetch notifications every 30 seconds
// setInterval(fetchNotifications, 30000);

// // Initial fetch on page load
// $(document).ready(function() {
// 	fetchNotifications();
// });
    function fetchNotifications() {
        $.getJSON('../../pages/Components/fetch_notifications.php', function(data) {
            const countElement = $('#notificationCount');
            const listElement = $('#notificationList');

            if (data.unread_count > 0) {
                countElement.removeClass('d-none').text(data.unread_count);
            } else {
                countElement.addClass('d-none');
            }

            listElement.empty();
            if (data.notifications.length > 0) {
                data.notifications.forEach(notif => {
                    const isReadClass = notif.is_read == 1 ? 'text-muted' : 'fw-bold';
                    // Added a data-message attribute to hold the full notification text and the new CSS class for truncation
                    listElement.append(`
                        <li>
                            <a class="dropdown-item text-truncate-custom ${isReadClass}" href="#" data-id="${notif.notification_id}" data-message="${notif.message}">
                                ${notif.message}<br>
                                <small class="text-secondary">${formatDateTime(notif.created_at)}</small>
                            </a>
                        </li>
                    `);
                });
            } else {
                listElement.append('<li><a class="dropdown-item text-center" href="#">No new notifications</a></li>');
            }
        });
    }

    // Handle notification click to open modal
    $(document).on('click', '#notificationList a', function(e) {
        e.preventDefault(); // Prevent default link behavior

        const notifId = $(this).data('id');
        const message = $(this).data('message');
        
        if (notifId) {
            // Populate the modal with the notification message
            $('#notificationMessage').text(message);
            
            // Show the modal
            $('#notificationModal').modal('show');
            
            // Store the notification ID in a data attribute on the modal for later use
            $('#notificationModal').data('notif-id', notifId);
        }
    });

    // Handle modal close event to mark notification as read
    $('#notificationModal').on('hidden.bs.modal', function() {
        const notifId = $(this).data('notif-id');
        if (notifId) {
            $.post('../../pages/Components/fetch_notifications.php', { mark_as_read: notifId }, function() {
                // Refresh notifications after marking as read
                fetchNotifications();
            });
        }
    });

    // Fetch notifications every 30 seconds
    setInterval(fetchNotifications, 30000);

    // Initial fetch on page load
    $(document).ready(function() {
        fetchNotifications();
    });