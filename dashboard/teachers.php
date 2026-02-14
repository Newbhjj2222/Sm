<?php
require __DIR__.'/dbcon.php';
include("includes/header.php");

/* ================= ADD TEACHER ================= */
if(isset($_POST['add_teacher'])){
    $id = $_POST['teacher_id'];

    $photo = "";
    if(!empty($_FILES['photo']['name'])){
        if(!is_dir("uploads")) mkdir("uploads");
        $photo = "uploads/".time().$_FILES['photo']['name'];
        move_uploaded_file($_FILES['photo']['tmp_name'],$photo);
    }

    $realtimeDatabase->getReference("teachers/$id")->set([
        "teacher_id" => $id,
        "photo" => $photo,
        "names" => $_POST['names'],
        "level" => $_POST['level'],
        "year" => $_POST['year'],
        "subject" => $_POST['subject'],
        "email" => $_POST['email'],
        "phone" => $_POST['phone'],
        "nid" => $_POST['nid']
    ]);

    header("Location: teachers.php");
}

/* ================= FETCH ================= */
$teachers = $realtimeDatabase->getReference("teachers")->getValue();
?>

<div class="container mt-5 pt-5">
<h3>Add Teacher</h3>

<form method="POST" enctype="multipart/form-data" class="row g-2">
    <input class="form-control col-md-3" name="teacher_id" placeholder="ID" required>
    <input class="form-control col-md-3" type="file" name="photo">
    <input class="form-control col-md-3" name="names" placeholder="Names" required>

    <!-- LEVEL SELECTOR -->
    <select class="form-control col-md-3" name="level" required>
        <option value="">Select Level</option>
        <option value="Primary Lower">Primary Lower</option>
        <option value="Primary Upper">Primary Upper</option>
        <option value="Secondary Ordinary">Secondary Ordinary</option>
        <option value="Secondary Advanced">Secondary Advanced</option>
    </select>

    <input class="form-control col-md-3" name="year" placeholder="Year(s) Teaching" required>
    <input class="form-control col-md-3" name="subject" placeholder="Subject" required>
    <input class="form-control col-md-3" name="email" placeholder="Email">
    <input class="form-control col-md-3" name="phone" placeholder="Phone">
    <input class="form-control col-md-3" name="nid" placeholder="NID">
    <button class="btn btn-primary col-12" name="add_teacher">Add Teacher</button>
</form>

<h3 class="mt-4">Teachers</h3>
<input id="search2" class="form-control mb-2" placeholder="Search...">

<div class="table-responsive">
<table class="table table-bordered table-hover" id="table2">
<thead class="table-dark">
<tr>
    <th>Photo</th>
    <th>ID</th>
    <th>Names</th>
    <th>Level</th>
    <th>Years</th>
    <th>Subject</th>
    <th>Email</th>
    <th>Phone</th>
    <th>NID</th>
    <th>Actions</th>
</tr>
</thead>
<tbody>

<?php if($teachers) foreach($teachers as $tid=>$t): ?>
<tr>
    <td><?php if(!empty($t['photo'])) echo "<img src='{$t['photo']}' width='40' class='rounded-circle'>"; ?></td>
    <td><?= htmlspecialchars($t['teacher_id']) ?></td>
    <td><?= htmlspecialchars($t['names']) ?></td>
    <td><?= htmlspecialchars($t['level']) ?></td>
    <td><?= htmlspecialchars($t['year']) ?></td>
    <td><?= htmlspecialchars($t['subject']) ?></td>
    <td><?= htmlspecialchars($t['email']) ?></td>
    <td><?= htmlspecialchars($t['phone']) ?></td>
    <td><?= htmlspecialchars($t['nid']) ?></td>
    <td>
        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#t<?=$tid?>">
            <i class="fas fa-edit"></i>
        </button>
        <a class="btn btn-sm btn-danger" onclick="return confirm('Delete?')" href="delete_teacher.php?id=<?=$tid?>">
            <i class="fas fa-trash"></i>
        </a>
    </td>
</tr>

<!-- EDIT MODAL -->
<div class="modal fade" id="t<?=$tid?>">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<form method="POST" action="edit_teacher.php?id=<?=$tid?>">
<div class="modal-header"><h5>Edit Teacher</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body row g-2">
<input class="form-control col-md-4" name="names" value="<?= htmlspecialchars($t['names']) ?>">
<select class="form-control col-md-4" name="level">
    <option value="Primary Lower" <?= $t['level']=="Primary Lower"?"selected":"" ?>>Primary Lower</option>
    <option value="Primary Upper" <?= $t['level']=="Primary Upper"?"selected":"" ?>>Primary Upper</option>
    <option value="Secondary Ordinary" <?= $t['level']=="Secondary Ordinary"?"selected":"" ?>>Secondary Ordinary</option>
    <option value="Secondary Advanced" <?= $t['level']=="Secondary Advanced"?"selected":"" ?>>Secondary Advanced</option>
</select>
<input class="form-control col-md-4" name="year" value="<?= htmlspecialchars($t['year']) ?>">
<input class="form-control col-md-4" name="subject" value="<?= htmlspecialchars($t['subject']) ?>">
<input class="form-control col-md-4" name="email" value="<?= htmlspecialchars($t['email']) ?>">
<input class="form-control col-md-4" name="phone" value="<?= htmlspecialchars($t['phone']) ?>">
<input class="form-control col-md-4" name="nid" value="<?= htmlspecialchars($t['nid']) ?>">
</div>
<div class="modal-footer"><button name="edit_teacher" class="btn btn-success">Save</button></div>
</form>
</div>
</div>
</div>

<?php endforeach; ?>
</tbody>
</table>
</div>
</div>

<script>
// Search functionality
document.getElementById("search2").onkeyup = function(){
    let v = this.value.toLowerCase();
    document.querySelectorAll("#table2 tbody tr").forEach(r=>{
        r.style.display = r.innerText.toLowerCase().includes(v) ? "" : "none";
    });
};
</script>

<?php include("includes/footer.php"); ?>
