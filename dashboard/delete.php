<?php
// Start session if needed
session_start();

// Firebase connection
require __DIR__.'/dbcon.php'; // make sure this path ikwiriye

// Check if 'id' or 'delete' parameter exists
$msgId = $_GET['delete'] ?? null;

if($msgId){
    try {
        // Remove message from Firebase Realtime Database
        $realtimeDatabase->getReference("messages/$msgId")->remove();

        // Redirect back to messages.php after deletion
        header("Location: messages.php?msg=deleted");
        exit;
    } catch(Exception $e){
        // If deletion fails
        echo "Error deleting message: " . $e->getMessage();
    }
} else {
    // No ID provided
    echo "No message ID provided to delete.";
}
