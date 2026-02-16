<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ONLINE CLASSES</title>

<style>
:root{
  --main:#2563eb;
  --dark:#0f172a;
  --light:#ffffff;
}

/* ============ RESET ============ */
*{
  margin:0;
  padding:0;
  box-sizing:border-box;
  font-family: Arial, Helvetica, sans-serif;
}

/* ============ MOBILE HEADER ============ */
.mobile-header{
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:15px 20px;
  background:var(--dark);
  color:white;
  position:fixed;
  width:100%;
  top:0;
  left:0;
  z-index:1000;
}

/* Logo hagati kuri mobile */
.logo{
  font-size:22px;
  font-weight:bold;
}
.logo span:first-child{color:var(--main);}
.logo span:last-child{color:white;}

.mobile-header .logo{
  position:absolute;
  left:50%;
  transform:translateX(-50%);
}

.menu-btn{
  font-size:26px;
  cursor:pointer;
}

/* ============ SIDEBAR ============ */
.sidebar{
  position:fixed;
  top:0;
  left:-260px;
  width:260px;
  height:100vh;
  background:var(--dark);
  color:white;
  transition:0.3s ease;
  padding-top:80px;
  z-index:999;
}

.sidebar.show{
  left:0;
}

.sidebar .logo{
  text-align:center;
  margin-bottom:30px;
}

.nav a{
  display:flex;
  align-items:center;
  gap:12px;
  padding:15px 25px;
  text-decoration:none;
  color:white;
  transition:0.3s;
}

.nav a:hover{
  background:var(--main);
}

/* ============ DESKTOP MODE ============ */
@media(min-width:768px){
  .mobile-header{
    position:relative;
    justify-content:space-between;
  }

  .mobile-header .logo{
    position:relative;
    left:0;
    transform:none;
  }

  .menu-btn{
    display:none;
  }

  .sidebar{
    position:relative;
    left:0;
    height:auto;
    width:100%;
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:15px 40px;
  }

  .nav{
    display:flex;
    gap:20px;
  }

  .nav a{
    padding:10px;
  }
}
</style>
</head>

<body>

<!-- ============ MOBILE HEADER ============ -->
<div class="mobile-header">
  <div class="menu-btn" onclick="toggleMenu()">☰</div>

  <div class="logo">
    <span>Your</span><span>Logo</span>
  </div>
</div>

<!-- ============ SIDEBAR / NAV ============ -->
<div class="sidebar" id="sidebar">

 

 <div class="nav">
  <a href="index.php">🏠 Home</a>
  <a href="store.php">📚 Library of books</a>
  <a href="about.php">ℹ️ About</a>
  <a href="contact.php">✉️ Contact</a>
  <a href="login.php">🔐 Login</a>
  <a href="poly.php">👤 Quiz Portal</a>
  <a href="profile.php">👤 Profile</a>
   <a href="dashboard">🏠 Dashboard</a>

</div>


</div>

<script>
function toggleMenu(){
  document.getElementById("sidebar").classList.toggle("show");
}
</script>

</body>
</html>
