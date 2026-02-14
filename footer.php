<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
:root{
  --main:#2563eb;
  --dark:#0f172a;
  --gray:#94a3b8;
}

/* ===== RESET ===== */
*{
  margin:0;
  padding:0;
  box-sizing:border-box;
  font-family:Arial, Helvetica, sans-serif;
}

/* ===== PAGE LAYOUT ===== */
html, body{
  height:100%;
}

body{
  display:flex;
  flex-direction:column;
  min-height:100vh;
}

/* ===== FOOTER ===== */
.footer{
  margin-top:auto; /* THIS pushes footer to bottom */
  background:var(--dark);
  color:white;
  padding:50px 20px 20px;
}

/* ===== GRID ===== */
.footer .top{
  max-width:1200px;
  margin:auto;
  display:grid;
  grid-template-columns:1fr;
  gap:40px;
}

/* ===== BRAND ===== */
.brand .logo{
  font-size:24px;
  font-weight:bold;
  margin-bottom:10px;
}
.brand .logo span:first-child{color:var(--main);}
.brand .logo span:last-child{color:white;}

.brand p{
  color:var(--gray);
  line-height:1.6;
}

/* ===== LINKS ===== */
.links h4,
.social h4{
  margin-bottom:15px;
  font-size:18px;
}

.links a{
  display:block;
  color:var(--gray);
  text-decoration:none;
  margin-bottom:10px;
  transition:.3s;
}
.links a:hover{
  color:var(--main);
}

/* ===== SOCIAL ===== */
.icons{
  display:flex;
  gap:15px;
}
.icons a{
  width:40px;
  height:40px;
  background:#1e293b;
  display:flex;
  align-items:center;
  justify-content:center;
  border-radius:50%;
  color:white;
  font-size:18px;
  text-decoration:none;
  transition:.3s;
}
.icons a:hover{
  background:var(--main);
}

/* ===== BOTTOM ===== */
.bottom{
  margin-top:40px;
  padding-top:20px;
  text-align:center;
  border-top:1px solid #1e293b;
  color:var(--gray);
}

.heart{
  color:red;
}

/* ===== DESKTOP ===== */
@media(min-width:768px){
  .footer .top{
    grid-template-columns:2fr 1fr 1fr;
  }
}
</style>
</head>

<body>

<footer class="footer">

  <div class="top">

    <!-- BRAND -->
    <div class="brand">
      <div class="logo">
        <span>Your</span><span>Logo</span>
      </div>
      <p>A modern platform for stories, books, and meaningful conversations.</p>
    </div>

    <!-- LINKS -->
    <div class="links">
  <h4>Quick Links</h4>
  <a href="index.php">Home</a>
  <a href="store.php">Store</a>
  <a href="about.php">About</a>
  <a href="contact.php">Contact</a>
  <a href="poly.php">Quick</a>
</div>


    <!-- SOCIAL -->
    <div class="social">
      <h4>Follow Us</h4>
      <div class="icons">
        <a href="#"><i class="fab fa-facebook-f"></i></a>
        <a href="#"><i class="fab fa-twitter"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fab fa-youtube"></i></a>
      </div>
    </div>

  </div>

  <div class="bottom">
    © <?php echo date("Y"); ?> YourLogo. Made with <span class="heart">❤</span> in Netweb
  </div>

</footer>

</body>
</html>
