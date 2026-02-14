<?php
require __DIR__.'/dbcon.php';
include("includes/header.php");

/* ===== FETCH TEACHER CLASSES ===== */
$teacherId = $_GET['teacher'] ?? '';
$classSelected = $_GET['class'] ?? '';
$classes = [];
$students = [];

if($teacherId){
    // fetch classes akuriye
    $allClasses = $realtimeDatabase->getReference("classes")->getValue();
    if($allClasses){
        foreach($allClasses as $c){
            if($c['teacher_id'] === $teacherId){
                $classes[] = $c;
            }
        }
    }
}

// fetch students only if class selected
if($classSelected){
    $allStudents = $realtimeDatabase->getReference("students")->getValue();
    if($allStudents){
        foreach($allStudents as $s){
            if(($s['class'] ?? '') === $classSelected){
                $students[] = $s;
            }
        }
    }
}

/* ===== SAVE ATTENDANCE ===== */
if(isset($_POST['save_attendance'])){
    $date = date('Y-m-d'); // today's date
    $classId = $_POST['class'];
    $attendance = $_POST['attendance'] ?? [];

    foreach($attendance as $studentId => $status){
        $realtimeDatabase->getReference("attendance/$classId/$date/$studentId")
            ->set($status); // 'present' or 'absent'
    }

    $successMessage = "Attendance saved successfully!";
}
?>

<div class="container mt-5 pt-5">
<h3>Attendance</h3>

<?php if(isset($successMessage)): ?>
<div class="alert alert-success"><?= $successMessage ?></div>
<?php endif; ?>

<form method="GET" class="row g-2 mb-3">
    <input class="form-control col-md-4" name="teacher" placeholder="Enter your Teacher ID" value="<?= htmlspecialchars($teacherId) ?>" required>
    <button class="btn btn-primary col-md-2">Load Classes</button>
</form>

<?php if($classes): ?>
<form method="GET" class="row g-2 mb-3">
    <input type="hidden" name="teacher" value="<?= htmlspecialchars($teacherId) ?>">
    <select class="form-control col-md-4" name="class" onchange="this.form.submit()" required>
        <option value="">Select Class</option>
        <?php foreach($classes as $c): 
            $name = $c['custom_name'] ?: $c['sub_level'];
        ?>
        <option value="<?= htmlspecialchars($name) ?>" <?= ($name==$classSelected)?'selected':'' ?>>
            <?= htmlspecialchars($name) ?> (<?= htmlspecialchars($c['main_level']) ?>)
        </option>
        <?php endforeach; ?>
    </select>
</form>
<?php endif; ?>

<?php if($classSelected && $students): ?>
<form method="POST">
<input type="hidden" name="class" value="<?= htmlspecialchars($classSelected) ?>">
<div class="table-responsive">
<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>Photo</th>
            <th>ID</th>
            <th>Names</th>
            <th>Level</th>
            <th>Class</th>
            <th>Present</th>
            <th>Absent</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($students as $s): ?>
        <tr>
            <td><?php if(!empty($s['photo'])) echo "<img src='{$s['photo']}' width='40' class='rounded-circle'>"; ?></td>
            <td><?= htmlspecialchars($s['student_id']) ?></td>
            <td><?= htmlspecialchars($s['names']) ?></td>
            <td><?= htmlspecialchars($s['level']) ?></td>
            <td><?= htmlspecialchars($s['class']) ?></td>
            <td>
                <input type="radio" name="attendance[<?= $s['student_id'] ?>]" value="present" required>
            </td>
            <td>
                <input type="radio" name="attendance[<?= $s['student_id'] ?>]" value="absent" required>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
<button class="btn btn-success mt-2" name="save_attendance">Save Attendance</button>
</form>
<?php elseif($classSelected): ?>
<div class="alert alert-warning">No students found in this class.</div>
<?php endif; ?>

</div>

<?php include("includes/footer.php"); ?>
