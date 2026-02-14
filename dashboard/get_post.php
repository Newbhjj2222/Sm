<?php
require __DIR__.'/dbcon.php'; // Firebase connection

header('Content-Type: application/json');

if(!isset($_GET['id'])){
    echo json_encode(['error'=>'No ID provided']);
    exit;
}

$id = $_GET['id'];

// Fetch post from Firebase
$postRef = $realtimeDatabase->getReference("posts/$id")->getValue();

if(!$postRef){
    echo json_encode(['error'=>'Post not found']);
    exit;
}

// Send JSON
echo json_encode($postRef);
