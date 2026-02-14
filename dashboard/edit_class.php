<?php
require __DIR__.'/dbcon.php';
$id=$_GET['id'];
if(isset($_POST['edit_class'])){
$realtimeDatabase->getReference("classes/$id")->update($_POST);
header("Location: classes.php");
}
