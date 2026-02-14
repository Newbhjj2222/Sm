<?php
require __DIR__.'/dbcon.php';

/* ================= ADD BOOK ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_book'])) {
    $id = uniqid(); // ID yihariye

    // Avoid undefined array keys
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $level = isset($_POST['level']) ? $_POST['level'] : '';
    $class = isset($_POST['class']) ? $_POST['class'] : '';

    // File upload
    $fileName = '';
    if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
        $fileTmp = $_FILES['file']['tmp_name'];
        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $fileName = "uploads/{$id}.{$ext}";
        move_uploaded_file($fileTmp, $fileName);
    }

    // Image upload
    $imageName = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $imgTmp = $_FILES['image']['tmp_name'];
        $imgExt = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageName = "uploads/{$id}_img.{$imgExt}";
        move_uploaded_file($imgTmp, $imageName);
    }

    // Save to Firebase
    $realtimeDatabase->getReference("library/$id")->set([
        "id" => $id,
        "name" => $name,
        "level" => $level,
        "class" => $class,
        "file" => $fileName,
        "image" => $imageName
    ]);

    // Redirect before output
    header("Location: library.php");
    exit;
}

/* ================= FETCH ================= */
$books = $realtimeDatabase->getReference("library")->getValue();

include("includes/header.php");
?>

<div class="container mt-5 pt-5">

<h3>Add Book</h3>
<form method="POST" enctype="multipart/form-data" class="row g-2">

    <input class="form-control col-md-4" name="name" placeholder="Book Name" required>

    <select class="form-control col-md-2" name="level" id="level" required>
        <option value="">Select Level</option>
        <option value="Primary Lower">Primary Lower</option>
        <option value="Primary Upper">Primary Upper</option>
        <option value="Secondary Ordinary">Secondary Ordinary</option>
        <option value="Secondary Advanced">Secondary Advanced</option>
    </select>

    <select class="form-control col-md-2" name="class" id="class" required>
        <option value="">Select Class</option>
    </select>

    <input type="file" class="form-control col-md-2" name="file" required>
    <input type="file" class="form-control col-md-2" name="image">

    <button class="btn btn-primary col-12 mt-2" name="add_book">
        <i class="fas fa-plus"></i> Add Book
    </button>
</form>

<hr>

<h3>Library</h3>
<input id="search" class="form-control mb-2" placeholder="Search book...">

<div class="table-responsive">
<table class="table table-bordered table-hover" id="table">
    <thead class="table-dark">
        <tr>
            <th>Name</th>
            <th>Level</th>
            <th>Class</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>

<?php if ($books) foreach ($books as $bid => $b): ?>
<tr>
    <td><?= htmlspecialchars($b['name']) ?></td>
    <td><?= htmlspecialchars($b['level']) ?></td>
    <td><?= htmlspecialchars($b['class']) ?></td>
    <td>
        <?php if (!empty($b['image']) && file_exists($b['image'])): ?>
            <img src="<?= htmlspecialchars($b['image']) ?>" alt="<?= htmlspecialchars($b['name']) ?>" style="height:50px;">
        <?php endif; ?>
    </td>
    <td>
        <a class="btn btn-sm btn-info" href="view_book.php?id=<?= $bid ?>">
            <i class="fas fa-eye"></i> View
        </a>
        <a class="btn btn-sm btn-danger" onclick="return confirm('Delete this book?')" 
           href="delete_book.php?id=<?= $bid ?>">
           <i class="fas fa-trash"></i> Delete
        </a>
    </td>
</tr>
<?php endforeach; ?>

    </tbody>
</table>
</div>
</div>

<script>
/* ===== SEARCH ===== */
document.getElementById("search").onkeyup = function(){
    let v = this.value.toLowerCase();
    document.querySelectorAll("#table tbody tr").forEach(r=>{
        r.style.display = r.innerText.toLowerCase().includes(v) ? "" : "none";
    });
};

/* ===== LEVEL/CLASS DYNAMIC ===== */
const data = {
    "Primary Lower":["P1A","P1B","P1C","P1D","P1E","P2A","P2B","P2C","P2D","P2E","P3A","P3B","P3C","P3D","P3E"],
    "Primary Upper":["P4A","P4B","P4C","P4D","P4E","P5A","P5B","P5C","P5D","P5E","P6A","P6B","P6C","P6D","P6E"],
    "Secondary Ordinary":["S1A","S1B","S1C","S1D","S1E","S2A","S2B","S2C","S2D","S2E","S3A","S3B","S3C","S3D","S3E"],
    "Secondary Advanced":["S4A","S4B","S4C","S4D","S4E","S5A","S5B","S5C","S5D","S5E","S6A","S6B","S6C","S6D","S6E"]
};

document.getElementById("level").addEventListener("change", function(){
    const cls = document.getElementById("class");
    cls.innerHTML = "<option value=''>Select Class</option>";
    if(data[this.value]){
        data[this.value].forEach(c=>{
            let o = document.createElement("option");
            o.value = c;
            o.text = c;
            cls.appendChild(o);
        });
    }
});
</script>

<?php include("includes/footer.php"); ?>
