<?php
require __DIR__.'/dbcon.php';
$id=$_GET['id'];
if(isset($_POST['edit_teacher'])){
$realtimeDatabase->getReference("teachers/$id")->update($_POST);
header("Location: teachers.php");
}
