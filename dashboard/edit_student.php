<?php
require __DIR__.'/dbcon.php';
$id=$_GET['id'];

if(isset($_POST['edit_student'])){
$realtimeDatabase->getReference("students/$id")->update($_POST);
header("Location: students.php");
}
