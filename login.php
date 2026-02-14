<?php
session_start();
require __DIR__.'/dashboard/dbcon.php'; // Firebase Realtime DB

$error = "";

// Redirect if already logged in
if(isset($_COOKIE['role'])){
    header("Location: index.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if(!$email || !$password){
        $error = "Email and password are required.";
    }else{
        try {
            // Firebase Auth REST API login
            $firebaseUrl = "https://identitytoolkit.googleapis.com/v1/accounts:signInWithPassword?key=AIzaSyDvsgQFc0fbLtyJgVC3uAjgIMLhqadJTnE";

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

                // Get user info from Realtime DB
                $user = $realtimeDatabase->getReference("users/$uid")->getValue();
                $username = $user['username'] ?? "User";
                $role     = $user['role'] ?? "student";

                setcookie("username", $username, time()+604800, "/");
                setcookie("role", $role, time()+604800, "/");

                header("Location: index.php");
                exit;
            }

        } catch(Exception $e){
            $error = "Login failed. Try again.";
        }
    }
}

include("header.php");
?>

<style>
*{box-sizing:border-box;}
body{background:#f3f4f6;font-family:system-ui;}
.login-container{
    max-width:400px;
    margin:120px auto;
    background:#fff;
    padding:25px;
    border-radius:15px;
    box-shadow:0 10px 25px rgba(0,0,0,.1);
}
.login-container h2{
    text-align:center;
    color:#2563eb;
    margin-bottom:20px;
}
.login-container .error{color:red;text-align:center;margin-bottom:10px;}
.input-group{margin-bottom:15px;position:relative;}
.input-group input{
    width:100%;
    padding:12px 14px;
    border-radius:8px;
    border:1px solid #ddd;
    font-size:14px;
}
.password-toggle{
    position:absolute;
    top:50%;
    right:12px;
    transform:translateY(-50%);
    cursor:pointer;
    font-size:18px;
    color:#666;
}
button{
    width:100%;
    padding:12px;
    border:none;
    border-radius:10px;
    font-size:16px;
    cursor:pointer;
    margin-bottom:10px;
}
.button-primary{background:#2563eb;color:#fff;}
.register-link{text-align:center;margin-top:10px;}
.register-link a{color:#2563eb;font-weight:600; text-decoration:none;}
@media(max-width:480px){
    .login-container{margin:80px 10px;padding:20px;}
}
</style>

<div class="login-container">
    <h2>Login</h2>
    <?php if($error): ?><div class="error"><?= $error ?></div><?php endif; ?>

    <form method="POST">
        <div class="input-group">
            <input type="email" name="email" placeholder="Email" required>
        </div>
        <div class="input-group">
            <input type="password" name="password" id="password" placeholder="Password" required>
            <span class="password-toggle" id="togglePass">👁️</span>
        </div>
        <button type="submit" class="button-primary">Login</button>
    </form>

    <p class="register-link">
        Not registered? <a href="register.php">Create an account</a>
    </p>
</div>

<script>
const toggle = document.getElementById("togglePass");
const passInput = document.getElementById("password");

toggle.addEventListener("click", ()=>{
    if(passInput.type === "password"){
        passInput.type = "text";
        toggle.textContent = "🙈";
    }else{
        passInput.type = "password";
        toggle.textContent = "👁️";
    }
});
</script>

<?php include("footer.php"); ?>
