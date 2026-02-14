<?php
require __DIR__."/dashboard/dbcon.php";

$id = $_GET['id'] ?? '';
$book = $realtimeDatabase->getReference("library/$id")->getValue();

include("header.php");
?>

<style>
.reader-container{
  width:900px;
  margin:110px auto 40px;
  padding:15px;
}

.book-header{
  display:flex;
  flex-wrap:wrap;
  justify-content:space-between;
  align-items:center;
  margin-bottom:15px;
  gap:15px;
}

.book-title h2{
  font-size:22px;
}
.book-title p{
  color:#555;
  font-size:14px;
}

/* Download button */
.download-btn{
  background:#2563eb;
  color:white;
  padding:10px 18px;
  border-radius:6px;
  text-decoration:none;
  font-size:14px;
  transition:0.3s;
}
.download-btn:hover{
  background:#1d4ed8;
}

/* PDF box */
.pdf-box{
  width:100%;
  height:80vh;
  border-radius:10px;
  overflow:hidden;
  border:1px solid #ddd;
  background:#f9fafb;
}

.pdf-box iframe{
  width:100%;
  height:100%;
  border:none;
}

/* Mobile */
@media(max-width:768px){
  .book-header{
    flex-direction:column;
    align-items:flex-start;
  }
  .reader-container{
    width: 500PX;
  }
  .download-btn{
    width:100%;
    text-align:center;
  }
  .pdf-box{
    height:70vh;
  }
}
</style>

<?php if($book): ?>
<div class="reader-container">

  <div class="book-header">
    <div class="book-title">
      <h2><?= htmlspecialchars($book['name']) ?></h2>
      <p><?= htmlspecialchars($book['level']) ?> | <?= htmlspecialchars($book['class']) ?></p>
    </div>

    <!-- Download Button -->
    <a class="download-btn" href="dashboard/<?= htmlspecialchars($book['file']) ?>" download>
      📥 Download Book
    </a>
  </div>

  <!-- PDF Viewer -->
  <div class="pdf-box">
    <iframe 
      src="dashboard/<?= htmlspecialchars($book['file']) ?>#toolbar=1&navpanes=0&scrollbar=1">
    </iframe>
  </div>

</div>
<?php else: ?>
<p style="margin:150px;text-align:center;">Book not found</p>
<?php endif; ?>

<?php include("footer.php"); ?>
