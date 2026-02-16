<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__.'/dbcon.php';

/* ================= ADD BOOK ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_book'])) {

    $id = uniqid();

    $name  = $_POST['name']  ?? '';
    $level = $_POST['level'] ?? '';
    $class = $_POST['class'] ?? '';

    /* ===== ENSURE UPLOADS DIR ===== */
    if (!is_dir("uploads")) {
        mkdir("uploads", 0777, true);
    }

    /* ===== FILE UPLOAD ===== */
    $fileName = '';
    if (!empty($_FILES['file']['tmp_name'])) {
        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $fileName = "uploads/{$id}.".$ext;
        move_uploaded_file($_FILES['file']['tmp_name'], $fileName);
    }

    /* ===== IMAGE UPLOAD ===== */
    $imageName = '';
    if (!empty($_FILES['image']['tmp_name'])) {
        $imgExt = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageName = "uploads/{$id}_img.".$imgExt;
        move_uploaded_file($_FILES['image']['tmp_name'], $imageName);
    }

    /* ===== SAVE TO FIREBASE ===== */
    $realtimeDatabase->getReference("library/$id")->set([
        "id"    => $id,
        "name"  => $name,
        "level" => $level,
        "class" => $class,
        "file"  => $fileName,
        "image" => $imageName
    ]);

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

<?php if ($books): foreach ($books as $bid => $b): ?>
<tr>
    <td><?= htmlspecialchars($b['name'] ?? '') ?></td>
    <td><?= htmlspecialchars($b['level'] ?? '') ?></td>
    <td><?= htmlspecialchars($b['class'] ?? '') ?></td>
    <td>
        <?php if (!empty($b['image']) && file_exists($b['image'])): ?>
            <img src="<?= htmlspecialchars($b['image']) ?>" style="height:50px;">
        <?php endif; ?>
    </td>
    <td>
        <a class="btn btn-sm btn-info" href="view_book.php?id=<?= $bid ?>">
            View
        </a>
        <a class="btn btn-sm btn-danger"
           onclick="return confirm('Delete this book?')"
           href="delete_book.php?id=<?= $bid ?>">
           Delete
        </a>
    </td>
</tr>
<?php endforeach; endif; ?>

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

/* ===== LEVEL / CLASS ===== */
const data = {
    "Primary Lower":["P1","P2","P3"],
    "Primary Upper":["P4","P5","P6"],
    "Secondary Ordinary":["S1","S2","S3"],
    "Secondary Advanced":["S4","S5","S6A","S6"]
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
