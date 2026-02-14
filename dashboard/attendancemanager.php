<?php
require __DIR__.'/dbcon.php';
include("includes/header.php");

/* ===== FETCH CLASS STUDENTS ===== */
$classId = $_GET['class'] ?? '';
$students = [];
$attendanceData = [];

if($classId){
    // Fetch all students in that class
    $allStudents = $realtimeDatabase->getReference("students")->getValue();
    if($allStudents){
        foreach($allStudents as $s){
            if(($s['class'] ?? '') === $classId){
                $students[$s['student_id']] = [
                    'names' => $s['names'],
                    'photo' => $s['photo'] ?? ''
                ];
            }
        }
    }

    // Fetch attendance data
    $allAttendance = $realtimeDatabase->getReference("attendance/$classId")->getValue();
    if($allAttendance){
        foreach($allAttendance as $date => $records){
            foreach($records as $studentId => $status){
                if(!isset($attendanceData[$studentId])){
                    $attendanceData[$studentId] = ['present'=>0,'absent'=>0];
                }
                if($status === 'present') $attendanceData[$studentId]['present']++;
                if($status === 'absent') $attendanceData[$studentId]['absent']++;
            }
        }
    }
}
?>

<div class="container mt-5 pt-5">
<h3>Attendance Manager</h3>

<form method="GET" class="row g-2 mb-3">
    <input class="form-control col-md-4" name="class" placeholder="Enter Class ID" value="<?= htmlspecialchars($classId) ?>" required>
    <button class="btn btn-primary col-md-2">Load Students</button>
</form>

<?php if($classId && $students): ?>
<div class="table-responsive">
<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>Photo</th>
            <th>ID</th>
            <th>Names</th>
            <th>Present Count</th>
            <th>Absent Count</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($students as $sid => $s): 
            $present = $attendanceData[$sid]['present'] ?? 0;
            $absent  = $attendanceData[$sid]['absent'] ?? 0;
        ?>
        <tr>
            <td><?php if(!empty($s['photo'])) echo "<img src='{$s['photo']}' width='40' class='rounded-circle'>"; ?></td>
            <td><?= htmlspecialchars($sid) ?></td>
            <td><?= htmlspecialchars($s['names']) ?></td>
            <td><span class="badge bg-success"><?= $present ?></span></td>
            <td><span class="badge bg-danger"><?= $absent ?></span></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php elseif($classId): ?>
<div class="alert alert-warning">No students found in this class.</div>
<?php endif; ?>
</div>

<?php include("includes/footer.php"); ?>
