<?php
require __DIR__.'/dbcon.php';

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $book = $realtimeDatabase->getReference("library/$id")->getValue();

    // Delete file/image if exist
    if($book){
        if(!empty($book['file']) && file_exists($book['file'])) unlink($book['file']);
        if(!empty($book['image']) && file_exists($book['image'])) unlink($book['image']);
    }

    $realtimeDatabase->getReference("library/$id")->remove();
}

header("Location: library.php");
exit;
