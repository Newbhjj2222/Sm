<?php
require __DIR__.'/dbcon.php';
include("includes/header.php");

/* ================= FETCH DATA ================= */
$students = $realtimeDatabase->getReference("students")->getValue();
$teachers = $realtimeDatabase->getReference("teachers")->getValue();
$classes = $realtimeDatabase->getReference("classes")->getValue();
$attendance = $realtimeDatabase->getReference("attendance")->getValue();

/* Totals */
$totalStudents = $students ? count($students) : 0;
$totalTeachers = $teachers ? count($teachers) : 0;
$totalClasses = $classes ? count($classes) : 0;

/* Attendance counts (today) */
$today = date("Y-m-d");
$totalPresent = 0;
$totalAbsent = 0;

if($attendance){
    foreach($attendance as $classId => $dates){
        if(isset($dates[$today])){
            foreach($dates[$today] as $studentId => $status){
                if($status === "present") $totalPresent++;
                else if($status === "absent") $totalAbsent++;
            }
        }
    }
}

/* Prepare data for charts */
$lineDates = []; // last 7 days
$linePresent = [];
$lineAbsent = [];

for($i=6;$i>=0;$i--){
    $d = date("Y-m-d", strtotime("-$i days"));
    $lineDates[] = $d;
    $p=0;$a=0;
    if($attendance){
        foreach($attendance as $classId => $dates){
            if(isset($dates[$d])){
                foreach($dates[$d] as $sId => $status){
                    if($status==="present") $p++;
                    else if($status==="absent") $a++;
                }
            }
        }
    }
    $linePresent[] = $p;
    $lineAbsent[] = $a;
}

?>

<div class="container mt-5 pt-5">

<h2 class="mb-4">Dashboard</h2>

<!-- CARDS -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Total Students</h5>
                <p class="card-text fs-3"><?= $totalStudents ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Total Teachers</h5>
                <p class="card-text fs-3"><?= $totalTeachers ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title">Total Classes</h5>
                <p class="card-text fs-3"><?= $totalClasses ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">Today Attendance</h5>
                <p class="card-text fs-6">Present: <?= $totalPresent ?> | Absent: <?= $totalAbsent ?></p>
            </div>
        </div>
    </div>
</div>

<!-- CHARTS -->
<div class="row">
    <div class="col-md-8 mb-4">
        <div class="card p-3">
            <h5>Attendance Trends (Last 7 days)</h5>
            <canvas id="lineChart"></canvas>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card p-3">
            <h5>Today's Attendance</h5>
            <canvas id="pieChart"></canvas>
        </div>
    </div>
</div>

</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const lineCtx = document.getElementById('lineChart').getContext('2d');
const lineChart = new Chart(lineCtx, {
    type: 'line',
    data: {
        labels: <?= json_encode($lineDates) ?>,
        datasets: [
            {
                label: 'Present',
                data: <?= json_encode($linePresent) ?>,
                borderColor: 'green',
                backgroundColor: 'rgba(0,255,0,0.2)',
                tension: 0.4
            },
            {
                label: 'Absent',
                data: <?= json_encode($lineAbsent) ?>,
                borderColor: 'red',
                backgroundColor: 'rgba(255,0,0,0.2)',
                tension: 0.4
            }
        ]
    },
    options: {
        responsive:true,
        plugins:{
            legend:{position:'top'}
        }
    }
});

const pieCtx = document.getElementById('pieChart').getContext('2d');
const pieChart = new Chart(pieCtx, {
    type:'pie',
    data:{
        labels:['Present','Absent'],
        datasets:[{
            data:[<?= $totalPresent ?>, <?= $totalAbsent ?>],
            backgroundColor:['green','red']
        }]
    },
    options:{
        responsive:true
    }
});
</script>

<?php include("includes/footer.php"); ?>
