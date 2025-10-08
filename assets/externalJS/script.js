alertify.set('notifier','position', 'top-right');
// function usable around all pages to format raw date
function formatDate(rawDate) {
    try {
      const date = new Date(rawDate);
  
      if (isNaN(date.getTime())) {
        return "Invalid date";
      }
  
      return date.toLocaleDateString("en-US", {
        year: "numeric",
        month: "long",
        day: "numeric"
      });
    } catch (e) {
      return "Invalid date";
    }
  }
  
  // function usable around all pages to format raw datetime
  function formatDateTime(rawDate) {
    try {
      const date = new Date(rawDate);
  
      if (isNaN(date.getTime())) {
        return "Invalid date";
      }
  
      return date.toLocaleDateString("en-US", {
        year: "numeric",
        month: "long",
        day: "numeric"
      }) + ", " + date.toLocaleTimeString("en-US", {
        hour: "numeric",
        minute: "2-digit",
        hour12: true
      });
    } catch (e) {
      return "Invalid date";
    }
  }
  
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('collapsed');
}
const allSidebtns = document.querySelectorAll('.sidebar-link');
const windowPath = window.location.pathname;

allSidebtns.forEach(allSidebtn => {
    const navlinkpath = new URL(allSidebtn.href).pathname;

    if(navlinkpath === windowPath){
        allSidebtn.classList.add('active');
    }else{
        allSidebtn.classList.remove('active');
    }
});

function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const osasText = document.querySelector('.role h4'); 
    const adminName = document.querySelector('.profile-info h6');
    const adminRole = document.querySelector('.profile-info i');
    const profileDropup = document.querySelector('.profile-dropup i');
    const nameContainer = document.querySelector('.nameContainer');
    
    sidebar.classList.toggle('collapsed');


    const shouldShow = !sidebar.classList.contains('collapsed');

    if (osasText) osasText.style.display = shouldShow ? 'block' : 'none';
    if (adminName) adminName.style.display = shouldShow ? 'block' : 'none';
    if (adminRole) adminRole.style.display = shouldShow ? 'block' : 'none';
    if (profileDropup) profileDropup.style.display = shouldShow ? 'block' : 'none';
    if (nameContainer) nameContainer.style.display = shouldShow ? 'block' : 'none';
}

if (localStorage.getItem('darkMode') === 'enabled') {
    document.documentElement.classList.add('dark-mode');
}

function updateDateTime() {
    const now = new Date();
    const options = {
        weekday: 'long', year: 'numeric', month: 'long',
        day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit'
    };
    const formattedDate = now.toLocaleDateString('en-US', options);
    document.getElementById('currentDateTime').textContent = formattedDate;
}
    const userManagement = document.getElementById('settings');
    const userSubMenu = document.getElementById('subsettings');

    userManagement.addEventListener('click', () => {
        userSubMenu.classList.toggle('show');
    }
);
// Initial call
updateDateTime();

// Update every second
setInterval(updateDateTime, 1000);

