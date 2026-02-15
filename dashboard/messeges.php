<?php
require __DIR__.'/dbcon.php'; // Firebase connection
include("includes/header.php");

// FETCH MESSAGES
$messages = $realtimeDatabase->getReference("messages")->getValue();
?>

<div class="container mt-5 pt-5">

<h3 class="mb-3">Messages</h3>

<!-- Search -->
<input id="search" class="form-control mb-3" placeholder="Search messages...">

<?php if(isset($_GET['msg']) && $_GET['msg']=='deleted'): ?>
    <div class="alert alert-success">Message deleted successfully!</div>
<?php endif; ?>

<div class="table-responsive">
<table class="table table-bordered table-hover" id="messagesTable">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Username</th>
            <th>Email</th>
            <th>Message</th>
            <th>Sent At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
<?php
if($messages){
    $i = 1;
    foreach($messages as $id=>$msg):
        $username = $msg['username'] ?? 'Guest';
        $email    = $msg['email'] ?? '';
        $message  = $msg['message'] ?? '';
        $sentAt   = $msg['createdAt'] ?? '';
?>
<tr>
    <td><?= $i++ ?></td>
    <td><?= htmlspecialchars($username) ?></td>
    <td><?= htmlspecialchars($email) ?></td>
    <td><?= htmlspecialchars($message) ?></td>
    <td><?= htmlspecialchars($sentAt) ?></td>
    <td>
        <!-- Reply button -->
        <a class="btn btn-sm btn-success" 
           href="mailto:<?= urlencode($email) ?>?subject=Reply from Admin" 
           title="Reply">
           <i class="fas fa-reply"></i>
        </a>

        <!-- Delete button -->
        <a class="btn btn-sm btn-danger" 
           onclick="return confirm('Delete this message?')" 
           href="delete.php?delete=<?= $id ?>" 
           title="Delete">
           <i class="fas fa-trash"></i>
        </a>
    </td>
</tr>
<?php
    endforeach;
} else {
    echo '<tr><td colspan="6" class="text-center">No messages found.</td></tr>';
}
?>
    </tbody>
</table>
</div>
</div>

<script>
// SEARCH FUNCTIONALITY
document.getElementById("search").onkeyup = function(){
    let v = this.value.toLowerCase();
    document.querySelectorAll("#messagesTable tbody tr").forEach(r=>{
        r.style.display = r.innerText.toLowerCase().includes(v) ? "" : "none";
    });
};
</script>

<?php include("includes/footer.php"); ?>

