<?php
require __DIR__.'/dbcon.php';
$id=$_GET['id'];
$realtimeDatabase->getReference("teachers/$id")->remove();
header("Location: teachers.php");
