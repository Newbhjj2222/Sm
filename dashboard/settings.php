<?php
session_start();

// Reba niba user yinjiyemo
if(!isset($_COOKIE['role'])){
    header("Location: login.php");
    exit;
}

// Firebase connection
require __DIR__.'/dbcon.php'; // dbcon.php iri muri dashboard/

include("includes/header.php");
?>

<style>
* { box-sizing: border-box; margin: 0; padding: 0; font-family: system-ui, sans-serif; }

body { background: #f3f4f6; }

/* Container ya under construction */
.settings-container {
    min-height: 80vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    padding: 30px 20px;
}

.settings-container h1 {
    font-size: 36px;
    color: #2563eb;
    margin-bottom: 20px;
}

.settings-container p {
    font-size: 18px;
    color: #555;
    margin-bottom: 30px;
}

/* Icons */
.settings-icons {
    display: flex;
    gap: 25px;
    flex-wrap: wrap;
    justify-content: center;
}

.settings-icons i {
    font-size: 50px;
    color: #2563eb;
    animation: bounce 1.5s infinite;
}

/* Bounce animation */
@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-15px); }
    60% { transform: translateY(-7px); }
}

/* Responsive Mobile */
@media (max-width: 480px) {
    .settings-container h1 { font-size: 28px; }
    .settings-container p { font-size: 16px; }
    .settings-icons i { font-size: 40px; }
}
</style>

<div class="settings-container">
    <h1>🚧 Settings Under Construction 🚧</h1>
    <p>We are working hard to bring this page online. Stay tuned!</p>

    <div class="settings-icons">
        <i class="fas fa-tools"></i>
        <i class="fas fa-cog"></i>
        <i class="fas fa-wrench"></i>
        <i class="fas fa-hard-hat"></i>
    </div>
</div>

<!-- Font Awesome CDN for icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<?php
include("includes/footer.php");
?>
