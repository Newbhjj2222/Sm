<?php
session_start();
require __DIR__.'/dashboard/dbcon.php'; // Firebase connection

$username = $_COOKIE['username'] ?? "Guest";
$success = "";
$error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $email   = trim($_POST["email"]);
    $message = trim($_POST["message"]);

    if($email && $message){
        try{
            // Firebase Realtime Database
            $realtimeDatabase->getReference("messages")->push([
                "username" => $username,
                "email" => $email,
                "message" => $message,
                "createdAt" => date("Y-m-d H:i:s")
            ]);
            $success = "Message sent successfully!";
        }catch(Exception $e){
            $error = "Failed to send. Try again.";
        }
    }else{
        $error = "All fields are required.";
    }
}

include("header.php");
?>

<style>
*{box-sizing:border-box;}
body{background:#f3f4f6;font-family:system-ui;}

.contact-container{
    max-width:500px;
    margin:120px auto;
    background:#fff;
    padding:25px;
    border-radius:15px;
    box-shadow:0 10px 25px rgba(0,0,0,.1);
}

.contact-container h1{
    text-align:center;
    color:#2563eb;
    margin-bottom:10px;
}

.contact-container p{
    text-align:center;
    color:#555;
    margin-bottom:25px;
}

.form-group{
    margin-bottom:15px;
}

.form-group label{
    display:flex;
    align-items:center;
    gap:8px;
    font-size:14px;
    margin-bottom:5px;
    color:#333;
}

.form-group input,
.form-group textarea{
    width:100%;
    padding:12px;
    border-radius:8px;
    border:1px solid #ddd;
    font-size:14px;
}

.form-group textarea{
    resize:none;
    height:120px;
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
}

button:disabled{
    background:#aaa;
}

.success{
    color:green;
    text-align:center;
    margin-top:15px;
}
.error{
    color:red;
    text-align:center;
    margin-top:15px;
}

/* Mobile */
@media(max-width:480px){
    .contact-container{
        margin:80px 10px;
        padding:20px;
    }
}
</style>

<div class="contact-container">
    <h1>Contact Us</h1>
    <p>Feel free to reach out for help, suggestions or any information.</p>

    <form method="POST">
        <div class="form-group">
            <label>👤 Username</label>
            <input type="text" value="<?= htmlspecialchars($username) ?>" disabled>
        </div>

        <div class="form-group">
            <label>📧 Email</label>
            <input type="email" name="email" required placeholder="Enter your email...">
        </div>

        <div class="form-group">
            <label>💬 Message</label>
            <textarea name="message" required placeholder="Write your message..."></textarea>
        </div>

        <button type="submit">Send Message</button>

        <?php if($success): ?>
            <div class="success"><?= $success ?></div>
        <?php endif; ?>

        <?php if($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
    </form>
</div>

<?php include("footer.php"); ?>
