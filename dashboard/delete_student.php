<?php
require __DIR__.'/dbcon.php';
$id=$_GET['id'];
$realtimeDatabase->getReference("students/$id")->remove();
header("Location: students.php");
