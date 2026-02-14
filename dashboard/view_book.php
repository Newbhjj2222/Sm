<?php
require __DIR__.'/dbcon.php';
include("includes/header.php");

if(!isset($_GET['id'])) die("No book ID");

$id = $_GET['id'];
$book = $realtimeDatabase->getReference("library/$id")->getValue();
if(!$book) die("Book not found");

$fileExists = !empty($book['file']) && file_exists($book['file']);
?>

<style>
/* ===== Responsive PDF Viewer ===== */
.pdf-container {
    max-width: 900px;
    margin: 30px auto;
    padding: 20px;
    background-color: #f8f9fa;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0,0,0,0.2);
}

.pdf-container iframe {
    width: 100%;
    height: 80vh;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.download-btn {
    margin-top: 15px;
}

@media (max-width: 768px) {
    .pdf-container {
        padding: 10px;
    }

    .pdf-container iframe {
        height: 60vh;
    }
}
</style>

<div class="container pdf-container text-center">
    <h2 class="mb-3"><?= htmlspecialchars($book['name']) ?></h2>
    <p>Level: <?= htmlspecialchars($book['level']) ?> | Class: <?= htmlspecialchars($book['class']) ?></p>
    
    <?php if(!empty($book['image']) && file_exists($book['image'])): ?>
        <img src="<?= htmlspecialchars($book['image']) ?>" alt="Book Image" style="max-height:200px; margin-bottom:20px;">
    <?php endif; ?>

    <?php if($fileExists): ?>
        <iframe src="<?= htmlspecialchars($book['file']) ?>" frameborder="0"></iframe>
        <a href="<?= htmlspecialchars($book['file']) ?>" download class="btn btn-success download-btn">
            <i class="fas fa-download"></i> Download PDF
        </a>
    <?php else: ?>
        <p class="text-danger">PDF file not found.</p>
    <?php endif; ?>
</div>

<?php include("includes/footer.php"); ?>
