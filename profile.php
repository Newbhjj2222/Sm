<?php
session_start();

// Check if user is logged in
if(!isset($_COOKIE['username']) || !isset($_COOKIE['role'])){
    header("Location: login.php");
    exit;
}

// Handle logout
if(isset($_POST['logout'])){
    setcookie("username", "", time() - 3600, "/");
    setcookie("role", "", time() - 3600, "/");
    header("Location: login.php");
    exit;
}

$username = $_COOKIE['username'];
$role     = $_COOKIE['role'];

// Optional: you can also fetch email from Firebase Realtime DB if needed
require __DIR__.'/dashboard/dbcon.php';
$userData = $realtimeDatabase->getReference("users")->getValue();
$email = "";
foreach($userData as $uid => $user){
    if($user['username'] == $username){
        $email = $user['email'] ?? "";
        break;
    }
}

include("header.php");
?>

<style>
*{box-sizing:border-box;}
body{background:#f3f4f6;font-family:system-ui;}
.profile-container{
    max-width:500px;
    margin:100px auto;
    background:#fff;
    padding:30px;
    border-radius:15px;
    box-shadow:0 10px 25px rgba(0,0,0,.1);
}
.profile-container h2{
    text-align:center;
    color:#2563eb;
    margin-bottom:25px;
}
.profile-info{
    margin-bottom:20px;
}
.profile-info p{
    font-size:16px;
    color:#333;
    margin-bottom:10px;
}
.logout-btn{
    width:100%;
    padding:12px;
    background:#ef4444;
    color:#fff;
    border:none;
    border-radius:10px;
    font-size:16px;
    cursor:pointer;
    transition:0.3s;
}
.logout-btn:hover{background:#dc2626;}
@media(max-width:480px){
    .profile-container{margin:50px 10px;padding:20px;}
    .profile-info p{font-size:15px;}
}
</style>

<div class="profile-container">
    <h2>My Profile</h2>
    <div class="profile-info">
        <p><strong>Username:</strong> <?= htmlspecialchars($username) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
        <p><strong>Role:</strong> <?= htmlspecialchars($role) ?></p>
    </div>

    <form method="POST">
        <button type="submit" name="logout" class="logout-btn">Logout</button>
    </form>
</div>

<?php include("footer.php"); ?>
