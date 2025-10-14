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
  
// function toggleSidebar() {
//     const sidebar = document.querySelector('.sidebar');
//     sidebar.classList.toggle('collapsed');
// }
const allSidebtns = document.querySelectorAll('.sidebar-link');
const windowPath = window.location.pathname;
const pageTitleNav = document.querySelector('#pageTitleNav');

allSidebtns.forEach(allSidebtn => {
    const navlinkpath = new URL(allSidebtn.href).pathname;

    if(navlinkpath === windowPath){
        allSidebtn.classList.add('active');
        pageTitleNav.textContent = allSidebtn.textContent;
    }else{
        allSidebtn.classList.remove('active');
    }
});

document.addEventListener('DOMContentLoaded', function() {
  const sidebar = document.querySelector('.sidebar');
  const osasText = document.querySelector('.role h4'); 
  const adminName = document.querySelector('.profile-info h6');
  const adminRole = document.querySelector('.profile-info i');
  const profileDropup = document.querySelector('.profile-dropup i');
  const nameContainer = document.querySelector('.nameContainer');
  const pageTitleNavmargin = document.querySelector('#pageTitleNav');

  function updateSidebarState() {
      const width = window.innerWidth;

      // 💡 Apply responsive logic based on breakpoints
      if (width < 576) { 
          // screen less than sm
          sidebar.classList.add('collapsed');
          pageTitleNavmargin.classList.add('ms-3');
      } 
      else if (width >= 576 && width < 768) { 
          // sm screen
          sidebar.classList.add('collapsed');
      } 
      else if (width >= 768 && width < 992) { 
          // md screen
          sidebar.classList.remove('collapsed');
      } 
      else if (width >= 992 && width < 1200) { 
          // lg screen
          sidebar.classList.remove('collapsed');
      } 
      else if (width >= 1200 && width < 1400) { 
          // xl screen
          sidebar.classList.remove('collapsed');
      } 
      else { 
          // xxl screen
          sidebar.classList.remove('collapsed');
      }

      // Toggle visibility of text elements based on collapse state
      const shouldShow = !sidebar.classList.contains('collapsed');
      if (osasText) osasText.style.display = shouldShow ? 'block' : 'none';
      if (adminName) adminName.style.display = shouldShow ? 'block' : 'none';
      if (adminRole) adminRole.style.display = shouldShow ? 'block' : 'none';
      if (profileDropup) profileDropup.style.display = shouldShow ? 'block' : 'none';
      if (nameContainer) nameContainer.style.display = shouldShow ? 'block' : 'none';
  }

  // ✅ Run once on load
  updateSidebarState();

  // ✅ Run on resize (for dynamic responsiveness)
  window.addEventListener('resize', updateSidebarState);
});


// Manual toggle (for hamburger button etc.)
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


// Helper function to get the appropriate Boxicon based on MIME type or file extension
function getFileIcon(fileNameOrMimeType) {
  const nameLower = fileNameOrMimeType.toLowerCase();
  
  if (nameLower.includes('pdf')) {
      return { icon: 'bxs-file-pdf', color: 'text-danger' };
  }
  if (nameLower.includes('image') || nameLower.endsWith('.png') || nameLower.endsWith('.jpg') || nameLower.endsWith('.jpeg')) {
      return { icon: 'bxs-file-image', color: 'text-primary' };
  }
  if (nameLower.endsWith('.doc') || nameLower.endsWith('.docx')) {
      return { icon: 'bxs-file-doc', color: 'text-info' };
  }
  if (nameLower.endsWith('.xls') || nameLower.endsWith('.xlsx')) {
      return { icon: 'bxs-spreadsheet', color: 'text-success' };
  }
  // Default for other file types
  return { icon: 'bxs-file', color: 'text-secondary' };
}