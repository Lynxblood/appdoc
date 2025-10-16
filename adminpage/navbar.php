<nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-house-door-fill me-2" viewBox="0 0 16 16">
          <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.505c0 .25.25.495.5.495h2.5a.5.5 0 0 0 .5-.5v-7.258l-.22-1.252a.5.5 0 0 0-.5-.353H9V4.5A1.5 1.5 0 0 0 7.5 3h-1A1.5 1.5 0 0 0 5 4.5v.793l-.265.265a.5.5 0 0 0-.176.324L4.045 7.75A.5.5 0 0 0 4.5 8.25h.5V14a.5.5 0 0 0 .5.5h1.5z"/>
        </svg>
        StudOrg
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link **<?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>**" href="index.php">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link **<?php echo (basename($_SERVER['PHP_SELF']) == 'events.php') ? 'active' : ''; ?>**" href="events.php">Events</a>
        </li>
        <li class="nav-item">
          <a class="nav-link **<?php echo (basename($_SERVER['PHP_SELF']) == 'organizations.php') ? 'active' : ''; ?>**" href="organizations.php">Organizations & Docs</a>
        </li>
      </ul>
      <form class="d-flex">
        <input class="form-control me-2" type="search" placeholder="Search..." aria-label="Search">
        <button class="btn btn-outline-light" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>