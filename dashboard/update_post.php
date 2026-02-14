<?php
require __DIR__.'/dbcon.php'; // Firebase connection

if($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['update_id'])){
    echo "Invalid request"; exit;
}

$id = $_POST['update_id'] ?? '';
$title = $_POST['title'] ?? '';
$content = $_POST['content'] ?? '';

$postRef = $realtimeDatabase->getReference("posts/$id")->getValue();

if(!$postRef){
    echo "Post not found"; exit;
}

// Handle image upload if new image provided
$imageName = $postRef['image'] ?? '';
if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $imageName = "uploads/{$id}.{$ext}";
    move_uploaded_file($_FILES['image']['tmp_name'], $imageName);
}

// Update post in Firebase
$realtimeDatabase->getReference("posts/$id")->update([
    "title" => $title,
    "content" => $content,
    "image" => $imageName
]);

echo "success";
