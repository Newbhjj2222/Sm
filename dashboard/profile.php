<?php
session_start(); // Mbere ya include
require __DIR__.'/dbcon.php';

// Reba niba user yinjiyemo
if(!isset($_COOKIE['user_id'])){
    header("Location: login.php");
    exit;
}

$user_id = $_COOKIE['user_id'];
$user = $realtimeDatabase->getReference("users/$user_id")->getValue();

if(!$user){
    echo "<div class='container mt-5'><div class='alert alert-danger'>User not found!</div></div>";
    exit;
}

include("includes/header.php"); // Navbar na header
?>

<!-- HTML ya profile card -->
<div class="container mt-5 pt-5 mb-5">
  <div class="card mx-auto shadow profile-card p-4" style="max-width:700px;">
    <div class="text-center">
        <img src="<?= !empty($user['photo']) ? $user['photo'] : 'https://via.placeholder.com/120' ?>" 
        class="rounded-circle border border-primary" style="width:120px;height:120px;object-fit:cover;" alt="Profile Photo">

        <h3 class="mt-3"><?= htmlspecialchars($user['names']) ?></h3>
        <p class="text-muted"><?= htmlspecialchars($user['role']) ?></p>
    </div>

    <hr>

    <div class="row mt-3 text-start">
        <div class="col-md-6 mb-2"><strong>User ID:</strong> <?= htmlspecialchars($user['user_id']) ?></div>
        <div class="col-md-6 mb-2"><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></div>
        <div class="col-md-6 mb-2"><strong>Role:</strong> <?= htmlspecialchars($user['role']) ?></div>
        <div class="col-md-6 mb-2"><strong>Password:</strong> ********</div>
    </div>

    <div class="text-center mt-4">
        <a href="logout.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
  </div>
</div>

<style>
body{background:#f8f9fa;}
.profile-card{border-radius:15px;}
</style>

<?php include("includes/footer.php"); ?>
