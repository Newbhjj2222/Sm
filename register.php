<?php
session_start();
require __DIR__.'/dashboard/dbcon.php'; // Firebase Realtime DB connection

$error = "";
$success = "";

// Handle form submission
if($_SERVER['REQUEST_METHOD'] == "POST"){
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role     = $_POST['role'] ?? "student";

    if(!$username || !$email || !$password){
        $error = "All fields are required.";
    }else{
        try {
            // Firebase Auth REST API signUp
            $firebaseUrl = "https://identitytoolkit.googleapis.com/v1/accounts:signUp?key=AIzaSyDvsgQFc0fbLtyJgVC3uAjgIMLhqadJTnE";

            $data = [
                "email" => $email,
                "password" => $password,
                "returnSecureToken" => true
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $firebaseUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            $response = curl_exec($ch);
            curl_close($ch);

            $resData = json_decode($response, true);

            if(isset($resData['error'])){
                $error = $resData['error']['message'];
            }else{
                $uid = $resData['localId'];

                // Save extra user info in Firebase Realtime DB
                $realtimeDatabase->getReference("users/$uid")->set([
                    "username" => $username,
                    "email" => $email,
                    "role" => $role,
                    "createdAt" => date("Y-m-d H:i:s")
                ]);

                setcookie("username", $username, time()+604800, "/");
                setcookie("role", $role, time()+604800, "/");

                $success = "Registration successful!";
                header("Location: login.php");
                exit;
            }

        } catch(Exception $e){
            $error = "Registration failed. Try again.";
        }
    }
}

include("header.php");
?>

<style>
*{box-sizing:border-box;}
body{background:#f3f4f6;font-family:system-ui;}
.register-container{
    max-width:400px;
    margin:120px auto;
    background:#fff;
    padding:25px;
    border-radius:15px;
    box-shadow:0 10px 25px rgba(0,0,0,.1);
}
.register-container h2{
    text-align:center;
    color:#2563eb;
    margin-bottom:20px;
}
.register-container .error{color:red; text-align:center; margin-bottom:10px;}
.register-container .success{color:green; text-align:center; margin-bottom:10px;}
.input-group{margin-bottom:15px;}
.input-group input, .input-group select{
    width:100%;
    padding:12px 14px;
    border-radius:8px;
    border:1px solid #ddd;
    font-size:14px;
}
button{
    width:100%;
    padding:12px;
    background:#2563eb;
    color:#fff;
    border:none;
    border-radius:10px;
    font-size:16px;
    cursor:pointer;
    margin-bottom:10px;
}
@media(max-width:480px){
    .register-container{margin:80px 10px;padding:20px;}
}
</style>

<div class="register-container">
    <h2>Register</h2>
    <?php if($error): ?><div class="error"><?= $error ?></div><?php endif; ?>
    <?php if($success): ?><div class="success"><?= $success ?></div><?php endif; ?>

    <form method="POST">
        <div class="input-group">
            <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="input-group">
            <input type="email" name="email" placeholder="Email" required>
        </div>
        <div class="input-group">
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <div class="input-group">
            <select name="role" required>
                <option value="">Select role</option>
                <option value="student">Student</option>
                <option value="teacher">Teacher</option>
            </select>
        </div>
        <button type="submit">Register</button>
    </form>

    <p style="text-align:center;">Already have an account? <a href="login.php" style="color:#2563eb;">Login</a></p>
</div>

<?php include("footer.php"); ?>
