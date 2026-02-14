<?php
require __DIR__.'/dbcon.php';
session_start();

$successMessage = "";
$errorMessage = "";

if(isset($_POST['register'])){
    // Generate automatic ID
    $users = $realtimeDatabase->getReference("users")->getValue();
    $lastNum = 0;
    if($users){
        $ids = array_keys($users);
        sort($ids);
        $lastId = end($ids);
        $lastNum = intval(substr($lastId,4));
    }
    $newNum = $lastNum + 1;
    $userId = "GSEX".str_pad($newNum,4,"0",STR_PAD_LEFT);

    // Handle photo
    $photo = "";
    if(!empty($_FILES['photo']['name'])){
        if(!is_dir("uploads")) mkdir("uploads");
        $photo = "uploads/".time().$_FILES['photo']['name'];
        move_uploaded_file($_FILES['photo']['tmp_name'],$photo);
    }

    // Password hash
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Save to Firebase
    $realtimeDatabase->getReference("users/$userId")->set([
        "user_id"=>$userId,
        "email"=>$_POST['email'],
        "names"=>$_POST['names'],
        "role"=>$_POST['role'],
        "photo"=>$photo,
        "password"=>$password
    ]);

    $successMessage = "Registered successfully! Your ID: <strong>$userId</strong>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:#f8f9fa;}
.container{max-width:700px;margin-top:50px;}
img.rounded-photo{width:50px;height:50px;object-fit:cover;border-radius:50%;}
</style>
</head>
<body>

<div class="container bg-white p-4 shadow rounded">
<h3 class="mb-4">Register</h3>

<?php if($successMessage): ?>
<div class="alert alert-success"><?= $successMessage ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="row g-3">
<div class="col-md-6">
<input type="email" name="email" class="form-control" placeholder="Email" required>
</div>
<div class="col-md-6">
<input type="text" name="names" class="form-control" placeholder="Full Names" required>
</div>
<div class="col-md-6">
<select name="role" class="form-select" required>
<option value="">Select Role</option>
<option value="Admin">Administration</option>


</select>
</div>
<div class="col-md-6">
<input type="password" name="password" class="form-control" placeholder="Password" required>
</div>
<div class="col-md-6">
<input type="file" name="photo" class="form-control" accept="image/*">
</div>
<div class="col-12">
<button type="submit" name="register" class="btn btn-primary w-100">
<i class="fas fa-user-plus"></i> Register
</button>
</div>
</form>
<a href="login.php" class="d-block mt-3 text-center">Already have an account? Login</a>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
