<?php
require __DIR__.'/dbcon.php';
$id=$_GET['id'];
$realtimeDatabase->getReference("classes/$id")->remove();
header("Location: classes.php");
