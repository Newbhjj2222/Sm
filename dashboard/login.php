<?php
require __DIR__.'/dbcon.php';
session_start();

$errorMessage = "";

if(isset($_POST['login'])){
    $id = $_POST['user_id'];
    $password = $_POST['password'];

    $user = $realtimeDatabase->getReference("users/$id")->getValue();

    if($user && password_verify($password, $user['password'])){
        setcookie("user_id",$user['user_id'],time()+3600,"/");
        setcookie("user_name",$user['names'],time()+3600,"/");
        setcookie("user_role",$user['role'],time()+3600,"/");
        setcookie("user_photo",$user['photo'],time()+3600,"/");

        $_SESSION['user_id']=$user['user_id'];
        header("Location: index.php");
        exit;
    }else{
        $errorMessage="Invalid ID or Password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:#f8f9fa;}
.container{max-width:500px;margin-top:80px;}
</style>
</head>
<body>

<div class="container bg-white p-4 shadow rounded">
<h3 class="mb-4">Login</h3>

<?php if($errorMessage): ?>
<div class="alert alert-danger"><?= $errorMessage ?></div>
<?php endif; ?>

<form method="POST" class="row g-3">
<div class="col-12">
<input type="text" name="user_id" class="form-control" placeholder="Your ID" required>
</div>
<div class="col-12">
<input type="password" name="password" class="form-control" placeholder="Password" required>
</div>
<div class="col-12">
<button type="submit" name="login" class="btn btn-success w-100">
<i class="fas fa-sign-in-alt"></i> Login
</button>
</div>
</form>

<a href="register.php" class="d-block mt-3 text-center">Don't have an account? Register</a>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
