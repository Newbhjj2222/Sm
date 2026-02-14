<?php include("header.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
.about-container{
  max-width:1000px;
  margin:120px auto 60px;
  padding:20px;
}

.about-card{
  background:#fff;
  border-radius:15px;
  box-shadow:0 10px 30px rgba(0,0,0,0.08);
  padding:30px;
}

.about-card h1{
  font-size:28px;
  margin-bottom:20px;
  display:flex;
  align-items:center;
  gap:12px;
  color:#0f172a;
}

.about-card h2{
  font-size:22px;
  margin:25px 0 10px;
  display:flex;
  align-items:center;
  gap:10px;
}

.icon{
  color:#2563eb;
  font-size:28px;
}

.icon-small{
  color:#2563eb;
  font-size:20px;
}

.about-text{
  color:#555;
  line-height:1.7;
  margin-bottom:15px;
  font-size:15px;
}

/* ===== Mobile ===== */
@media(max-width:768px){
  .about-card{
    padding:20px;
  }
  .about-card h1{
    font-size:22px;
  }
  .about-card h2{
    font-size:18px;
  }
}
</style>
</head>

<body>

<div class="about-container">

  <div class="about-card">

    <h1>
      <i class="fas fa-book-open icon"></i>
      About Our E-Library System
    </h1>

    <p class="about-text">
      Welcome to our E-Library System — a secure and modern digital platform designed to provide
      controlled, reliable, and educational access to digital reading materials within a correctional
      environment. The system supports users inside the facility, including juveniles, inmates,
      correctional officers, institutional staff, and administration.
    </p>

    <p class="about-text">
      Our E-Library enables users to explore a carefully curated collection of books, academic
      resources, vocational training materials, and rehabilitation-focused content. With organized
      categories and an easy-to-use interface, users can access the information they need in a safe
      and structured manner.
    </p>

    <p class="about-text">
      This system was developed to promote digital learning, support personal development, and offer
      meaningful educational opportunities even within restricted environments. It also assists
      correctional officers and staff in managing digital resources efficiently and securely.
    </p>

    <!-- Mission -->
    <h2>
      <i class="fas fa-bullseye icon-small"></i>
      Mission
    </h2>
    <p class="about-text">
      To provide secure, meaningful, and accessible digital learning resources that support
      rehabilitation, education, and personal development within the correctional facility.
    </p>

    <!-- Vision -->
    <h2>
      <i class="fas fa-eye icon-small"></i>
      Vision
    </h2>
    <p class="about-text">
      To become a trusted digital library solution that empowers individuals in correctional
      institutions through education, knowledge, and positive transformation.
    </p>

  </div>

</div>

<?php include("footer.php"); ?>

</body>
</html>
