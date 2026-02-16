<!-- NAVBAR.PHP -->
<nav class="dashboard-navbar">
  <div class="container">
    <!-- Logo -->
    <a href="index.php" class="brand"><i class="fas fa-school"></i> GS Example Admin</a>

    <!-- Mobile toggle button -->
    <button class="navbar-toggler" type="button"><i class="fas fa-bars"></i></button>

    <!-- Navbar links -->
    <ul class="nav-links">
      <li><a href="./index.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
      <li><a href="./dashboard/students.php"><i class="fas fa-user-graduate"></i> Students</a></li>
      <li><a href="./teachers.php"><i class="fas fa-chalkboard-teacher"></i> Teachers</a></li>
      <li><a href="./classes.php"><i class="fas fa-school"></i> Classes</a></li>
      <li>
    <a href="./dashboard_posts.php" class="menu-item <?= basename($_SERVER['PHP_SELF']) == 'dashboard_posts.php' ? 'active' : '' ?>">
        <i class="fas fa-newspaper"></i>
        <span>Posts</span>
    </a>
</li>

<li>
    <a href="./library.php" class="menu-item <?= basename($_SERVER['PHP_SELF']) == 'library.php' ? 'active' : '' ?>">
        <i class="fas fa-book"></i>
        <span>Library</span>
    </a>
</li>

      <li><a href="./messeges.php"><i class="fas fa-comments"></i> Messeges</a></li>
      <li><a href="./attendance.php"><i class="fas fa-calendar-check"></i> Attendance</a></li>
      <li><a href="./attendancemanager.php"><i class="fas fa-calendar-check"></i> Attendance Manager</a></li>
      <li class="dropdown">
        <a href="#" class="dropdown-toggle"><i class="fas fa-user-cog"></i> Admin</a>
        <ul class="dropdown-menu">
          <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
          <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
          <li><hr></li>
          <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
      </li>
    </ul>
  </div>
</nav>

<style>
/* ===== Reset ===== */
* { box-sizing:border-box; margin:0; padding:0; font-family: Arial,sans-serif; }
a { text-decoration:none; }

/* ===== Navbar ===== */
.dashboard-navbar {
    position: fixed;
    top:0;
    width:100%;
    background:#343a40;
    color:#fff;
    z-index:1000;
    padding:10px 20px;
    box-shadow:0 2px 6px rgba(0,0,0,0.2);
}

/* Container */
.dashboard-navbar .container {
    max-width:1200px;
    margin:auto;
    display:flex;
    align-items:center;
    justify-content:space-between;
    flex-wrap:wrap;
}

/* Brand */
.brand {
    font-size:1.5rem;
    font-weight:bold;
    color:#fff;
    display:flex;
    align-items:center;
    gap:5px;
}

/* Toggle button */
.navbar-toggler {
    font-size:1.5rem;
    color:#fff;
    background:none;
    border:none;
    cursor:pointer;
    display:none; /* hidden on desktop */
}

/* Nav links */
.nav-links {
    list-style:none;
    display:flex;
    gap:10px;
    align-items:center;
}

.nav-links li {
    position:relative;
}

.nav-links a {
    color:#fff;
    padding:8px 12px;
    display:flex;
    align-items:center;
    gap:5px;
    border-radius:5px;
    transition:0.3s;
}

.nav-links a:hover,
.nav-links a.active {
    background:#ffc107;
    color:#000;
}

/* Dropdown */
.dropdown:hover .dropdown-menu {
    display:block;
}
.dropdown-menu {
    display:none;
    position:absolute;
    top:100%;
    left:0;
    background:#f8f9fa;
    min-width:160px;
    border-radius:5px;
    box-shadow:0 4px 8px rgba(0,0,0,0.1);
    z-index:1000;
}
.dropdown-menu li a {
    color:#000;
    padding:8px 12px;
    display:flex;
    align-items:center;
    gap:5px;
}
.dropdown-menu li a:hover {
    background:#e2e6ea;
}

/* ===== Responsive ===== */
@media(max-width:991px){
    .navbar-toggler { display:block; }
    .nav-links {
        flex-direction:column;
        width:100%;
        display:none; /* hidden by default */
        margin-top:10px;
        gap:0;
        background:#343a40;
        border-radius:5px;
        padding:10px 0;
    }
    .nav-links li { width:100%; }
    .nav-links li a { padding:10px; }
}
</style>

<script>
// Toggle mobile menu
const toggler = document.querySelector(".navbar-toggler");
const navLinks = document.querySelector(".nav-links");

toggler.addEventListener("click", ()=>{
    if(navLinks.style.display === "flex"){
        navLinks.style.display = "none";
    } else {
        navLinks.style.display = "flex";
        navLinks.style.flexDirection = "column";
    }
});
</script>



