<?php
require __DIR__.'/dbcon.php';
include("includes/header.php");

/* ================= ADD STUDENT ================= */
if(isset($_POST['add_student'])){
  $id = $_POST['student_id'];

  $photo = "";
  if(!empty($_FILES['photo']['name'])){
    if(!is_dir("uploads")) mkdir("uploads");
    $photo = "uploads/".time().$_FILES['photo']['name'];
    move_uploaded_file($_FILES['photo']['tmp_name'], $photo);
  }

  $realtimeDatabase->getReference("students/$id")->set([
    "student_id" => $id,
    "photo" => $photo,
    "names" => $_POST['names'],
    "level" => $_POST['main_level'],
    "class" => $_POST['sub_level'], // Ihuza na custom_name
    "subject" => $_POST['subject'],
    "father" => $_POST['father'],
    "mother" => $_POST['mother'],
    "nid" => $_POST['nid'],
    "phone" => $_POST['phone']
  ]);

  header("Location: students.php");
}

$students = $realtimeDatabase->getReference("students")->getValue();
$classes = $realtimeDatabase->getReference("classes")->getValue(); // Kugira ngo dukore selector ya class
?>

<div class="container mt-5 pt-5">

<h3>Add Student</h3>
<form method="POST" enctype="multipart/form-data" class="row g-2">
  <input class="form-control col-md-3" name="student_id" placeholder="ID" required>
  <input class="form-control col-md-3" type="file" name="photo">
  <input class="form-control col-md-3" name="names" placeholder="Full Names" required>

  <!-- MAIN LEVEL -->
  <select class="form-control col-md-3" id="mainLevel" name="main_level" required>
    <option value="">Select Level</option>
    <option value="Primary Lower">Primary Lower</option>
    <option value="Primary Upper">Primary Upper</option>
    <option value="Secondary Ordinary">Secondary Ordinary</option>
    <option value="Secondary Advanced">Secondary Advanced</option>
  </select>

  <!-- SUB LEVEL (CUSTOM CLASS) -->
  <select class="form-control col-md-3" id="subLevel" name="sub_level" required>
    <option value="">Select Class</option>
  </select>

  <input class="form-control col-md-3" name="subject" placeholder="Subject" required>
  <input class="form-control col-md-3" name="father" placeholder="Father Name" required>
  <input class="form-control col-md-3" name="mother" placeholder="Mother Name" required>
  <input class="form-control col-md-3" name="nid" placeholder="NID" required>
  <input class="form-control col-md-3" name="phone" placeholder="Phone">

  <button class="btn btn-primary col-12 mt-2" name="add_student">
    <i class="fas fa-user-plus"></i> Add Student
  </button>
</form>

<hr>

<h3>Students List</h3>
<input id="search" class="form-control mb-2" placeholder="Search Students...">

<div class="table-responsive">
  <table class="table table-bordered table-hover" id="table">
    <thead class="table-dark">
      <tr>
        <th>Photo</th>
        <th>ID</th>
        <th>Names</th>
        <th>Level</th>
        <th>Class</th>
        <th>Subject</th>
        <th>Father</th>
        <th>Mother</th>
        <th>NID</th>
        <th>Phone</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if($students) foreach($students as $sid=>$s): ?>
      <tr>
        <td><?php if(!empty($s['photo'])) echo "<img src='{$s['photo']}' width='40' class='rounded-circle'>"; ?></td>
        <td><?= htmlspecialchars($s['student_id']) ?></td>
        <td><?= htmlspecialchars($s['names']) ?></td>
        <td><?= htmlspecialchars($s['level']) ?></td>
        <td><?= htmlspecialchars($s['class']) ?></td>
        <td><?= htmlspecialchars($s['subject']) ?></td>
        <td><?= htmlspecialchars($s['father']) ?></td>
        <td><?= htmlspecialchars($s['mother']) ?></td>
        <td><?= htmlspecialchars($s['nid']) ?></td>
        <td><?= htmlspecialchars($s['phone']) ?></td>
        <td>
          <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#e<?=$sid?>">
            <i class="fas fa-edit"></i>
          </button>
          <a class="btn btn-sm btn-danger" onclick="return confirm('Delete?')" href="delete_student.php?id=<?=$sid?>">
            <i class="fas fa-trash"></i>
          </a>
        </td>
      </tr>

      <!-- EDIT MODAL -->
      <div class="modal fade" id="e<?=$sid?>">
        <div class="modal-dialog modal-lg"><div class="modal-content">
        <form method="POST" action="edit_student.php?id=<?=$sid?>">
          <div class="modal-header"><h5>Edit Student</h5></div>
          <div class="modal-body row g-2">
            <input class="form-control col-md-4" name="names" value="<?= htmlspecialchars($s['names']) ?>">
            <input class="form-control col-md-4" name="level" value="<?= htmlspecialchars($s['level']) ?>">
            <input class="form-control col-md-4" name="class" value="<?= htmlspecialchars($s['class']) ?>">
            <input class="form-control col-md-4" name="subject" value="<?= htmlspecialchars($s['subject']) ?>">
            <input class="form-control col-md-4" name="father" value="<?= htmlspecialchars($s['father']) ?>">
            <input class="form-control col-md-4" name="mother" value="<?= htmlspecialchars($s['mother']) ?>">
            <input class="form-control col-md-4" name="nid" value="<?= htmlspecialchars($s['nid']) ?>">
            <input class="form-control col-md-4" name="phone" value="<?= htmlspecialchars($s['phone']) ?>">
          </div>
          <div class="modal-footer">
            <button name="edit_student" class="btn btn-success">Save</button>
          </div>
        </form>
        </div></div>
      </div>

      <?php endforeach; ?>
    </tbody>
  </table>
</div>
</div>

<script>
/* ===== CLASS SELECTORS ===== */
const data = {
 "Primary Lower":["P1A","P1B","P1C","P1D","P1E","P2A","P2B","P2C","P2D","P2E","P3A","P3B","P3C","P3D","P3E"],
 "Primary Upper":["P4A","P4B","P4C","P4D","P4E","P5A","P5B","P5C","P5D","P5E","P6A","P6B","P6C","P6D","P6E"],
 "Secondary Ordinary":["S1A","S1B","S1C","S1D","S1E","S2A","S2B","S2C","S2D","S2E","S3A","S3B","S3C","S3D","S3E"],
 "Secondary Advanced":["S4A","S4B","S4C","S4D","S4E","S5A","S5B","S5C","S5D","S5E","S6A","S6B","S6C","S6D","S6E"]
};

document.getElementById("mainLevel").addEventListener("change", function(){
 let s = document.getElementById("subLevel");
 s.innerHTML = "<option value=''>Select Class</option>";
 if(data[this.value]){
   data[this.value].forEach(v => {
     let o = document.createElement("option");
     o.value = v;
     o.text = v;
     s.appendChild(o);
   });
 }
});

/* ===== SEARCH FUNCTIONALITY ===== */
document.getElementById("search").onkeyup = function(){
 let v = this.value.toLowerCase();
 document.querySelectorAll("#table tbody tr").forEach(r=>{
   r.style.display = r.innerText.toLowerCase().includes(v)?"":"none";
 });
};
</script>

<?php include("includes/footer.php"); ?>
