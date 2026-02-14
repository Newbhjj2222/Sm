<?php
// Gutangira session niba ikenewe
session_start();

// Gusiba cookie ya user
if(isset($_COOKIE['user_id'])){
    // Shyiraho cookie ishaje kugira ngo isibe
    setcookie("user_id", "", time() - 3600, "/");
}

// Ushobora gusiba session niba uyikoresheje
session_unset();
session_destroy();

// Ohereza kuri login page
header("Location: login.php");
exit;
