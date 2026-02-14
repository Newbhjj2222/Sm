<?php
require __DIR__.'/dbcon.php';
$id=$_GET['id'];
$t=$realtimeDatabase->getReference("teachers/$id")->getValue();
echo $t ? $t['names'] : "";
