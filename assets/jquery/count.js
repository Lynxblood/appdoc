document.addEventListener('DOMContentLoaded', () => {
    const orgCount = document.querySelectorAll('.logo-container').length;
    document.getElementById('org-count').innerText = orgCount;

    localStorage.setItem('orgCount', orgCount); // optional
});