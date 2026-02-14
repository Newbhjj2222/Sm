<?php
require __DIR__.'/dbcon.php';
include("includes/header.php");

/* ================= ADD CLASS ================= */
if(isset($_POST['add_class'])){
    $id = $_POST['class_id'];
    $teacherId = $_POST['teacher_id'];

    $teacher = $realtimeDatabase->getReference("teachers/$teacherId")->getValue();
    $teacherName = $teacher ? $teacher['names'] : "Unknown";

    $realtimeDatabase->getReference("classes/$id")->set([
        "class_id" => $id,
        "main_level" => $_POST['main_level'],
        "sub_level" => $_POST['sub_level'],
        "custom_name" => $_POST['custom_name'] ?: $_POST['sub_level'],
        "teacher_id" => $teacherId,
        "teacher_name" => $teacherName
    ]);

    header("Location: classes.php");
}

/* ================= FETCH ================= */
$classes = $realtimeDatabase->getReference("classes")->getValue();
$students = $realtimeDatabase->getReference("students")->getValue();
?>

<div class="container mt-5 pt-5">

<h3>Add Class</h3>
<form method="POST" class="row g-2">

    <input class="form-control col-md-3" name="class_id" placeholder="Class ID" required>

    <!-- MAIN LEVEL -->
    <select class="form-control col-md-3" name="main_level" id="mainLevel" required>
        <option value="">Select Level</option>
        <option value="Primary Lower">Primary Lower</option>
        <option value="Primary Upper">Primary Upper</option>
        <option value="Secondary Ordinary">Secondary Ordinary</option>
        <option value="Secondary Advanced">Secondary Advanced</option>
    </select>

    <!-- SUB LEVEL -->
    <select class="form-control col-md-3" name="sub_level" id="subLevel" required>
        <option value="">Select Class</option>
    </select>

    <input class="form-control col-md-3" name="custom_name" placeholder="Custom Name (P1A, S3B)">

    <input class="form-control col-md-3" name="teacher_id" id="teacherId" placeholder="Teacher ID" required>
    <input class="form-control col-md-3" id="teacherName" placeholder="Teacher Name" readonly>

    <button class="btn btn-primary col-12 mt-2" name="add_class">
        <i class="fas fa-plus"></i> Add Class
    </button>
</form>

<hr>

<h3>Classes</h3>
<input id="search" class="form-control mb-2" placeholder="Search class...">

<div class="table-responsive">
<table class="table table-bordered table-hover" id="table">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Level</th>
            <th>Class</th>
            <th>Custom</th>
            <th>Teacher</th>
            <th>Students</th>
            <th>Attendance</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>

<?php if($classes) foreach($classes as $cid=>$c):

    /* ===== COUNT STUDENTS ===== */
    $count = 0;
    $className = $c['custom_name'] ?: $c['sub_level'];
    if($students){
        foreach($students as $s){
            $studentClass = $s['class'] ?? '';
            if($studentClass === $className){
                $count++;
            }
        }
    }
?>
<tr>
    <td><?= htmlspecialchars($c['class_id']) ?></td>
    <td><?= htmlspecialchars($c['main_level']) ?></td>
    <td><?= htmlspecialchars($c['sub_level']) ?></td>
    <td><?= htmlspecialchars($className) ?></td>
    <td><?= htmlspecialchars($c['teacher_name']) ?></td>
    <td><span class="badge bg-primary fs-6"><?= $count ?></span></td>
    <td>
        <a class="btn btn-sm btn-info" 
           href="attendance.php?class=<?= urlencode($className) ?>">
           <i class="fas fa-calendar-check"></i>
        </a>
    </td>
    <td>
        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#e<?= $cid ?>">
            <i class="fas fa-edit"></i>
        </button>
        <a class="btn btn-sm btn-danger" onclick="return confirm('Delete?')" 
           href="delete_class.php?id=<?= $cid ?>">
           <i class="fas fa-trash"></i>
        </a>
    </td>
</tr>

<!-- EDIT MODAL -->
<div class="modal fade" id="e<?= $cid ?>">
<div class="modal-dialog">
<div class="modal-content">
<form method="POST" action="edit_class.php?id=<?= $cid ?>">
    <div class="modal-header"><h5>Edit Class</h5></div>
    <div class="modal-body row g-2">
        <input class="form-control col-12" name="custom_name" value="<?= htmlspecialchars($className) ?>">
        <input class="form-control col-12" name="teacher_id" value="<?= htmlspecialchars($c['teacher_id']) ?>">
    </div>
    <div class="modal-footer">
        <button name="edit_class" class="btn btn-success">Save</button>
    </div>
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
const mainLevel = document.getElementById("mainLevel");
const subLevel = document.getElementById("subLevel");

/* ===== SUB LEVEL DATA ===== */
const data = {
    "Primary Lower":["P1A","P1B","P1C","P1D","P1E","P2A","P2B","P2C","P2D","P2E","P3A","P3B","P3C","P3D","P3E"],
    "Primary Upper":["P4A","P4B","P4C","P4D","P4E","P5A","P5B","P5C","P5D","P5E","P6A","P6B","P6C","P6D","P6E"],
    "Secondary Ordinary":["S1A","S1B","S1C","S1D","S1E","S2A","S2B","S2C","S2D","S2E","S3A","S3B","S3C","S3D","S3E"],
    "Secondary Advanced":["S4A","S4B","S4C","S4D","S4E","S5A","S5B","S5C","S5D","S5E","S6A","S6B","S6C","S6D","S6E"]
};

mainLevel.addEventListener("change", () => {
    subLevel.innerHTML = "<option value=''>Select</option>";
    if(data[mainLevel.value]){
        data[mainLevel.value].forEach(v => {
            let o = document.createElement("option");
            o.value = v;
            o.text = v;
            subLevel.appendChild(o);
        });
    }
});

/* ===== TEACHER LOOKUP ===== */
document.getElementById("teacherId").addEventListener("keyup", function(){
    fetch("teacher_lookup.php?id="+this.value)
    .then(r=>r.text()).then(d=>{
        document.getElementById("teacherName").value = d;
    });
});

/* ===== SEARCH ===== */
document.getElementById("search").onkeyup = function(){
    let v = this.value.toLowerCase();
    document.querySelectorAll("#table tbody tr").forEach(r=>{
        r.style.display = r.innerText.toLowerCase().includes(v) ? "" : "none";
    });
};
</script>

<?php include("includes/footer.php"); ?>
