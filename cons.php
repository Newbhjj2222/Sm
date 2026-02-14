<?php
include("header.php");
?>

<style>
*{box-sizing:border-box;}
body{background:#f3f4f6;font-family:system-ui;}

.cons-container{
    min-height:80vh;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    text-align:center;
    padding:20px;
}

.cons-container h1{
    font-size:36px;
    color:#2563eb;
    margin-bottom:20px;
}

.cons-container p{
    font-size:18px;
    color:#555;
    margin-bottom:30px;
}

.cons-icons{
    display:flex;
    gap:25px;
    flex-wrap:wrap;
    justify-content:center;
}

.cons-icons i{
    font-size:50px;
    color:#2563eb;
    animation: bounce 1.5s infinite;
}

/* Bounce animation */
@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
    40% {transform: translateY(-15px);}
    60% {transform: translateY(-7px);}
}

/* Mobile */
@media(max-width:480px){
    .cons-container h1{font-size:28px;}
    .cons-container p{font-size:16px;}
    .cons-icons i{font-size:40px;}
}
</style>

<div class="cons-container">
    <h1>🚧 Page Under Construction 🚧</h1>
    <p>We are working hard to bring this page online. Stay tuned!</p>

    <div class="cons-icons">
        <i class="fas fa-tools"></i>
        <i class="fas fa-hard-hat"></i>
        <i class="fas fa-wrench"></i>
        <i class="fas fa-cogs"></i>
    </div>
</div>

<!-- Font Awesome CDN for icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<?php
include("footer.php");
?>
