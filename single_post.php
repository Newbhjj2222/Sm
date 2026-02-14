<?php
require __DIR__.'/dashboard/dbcon.php';

$id = $_GET['id'] ?? '';
$post = [];
if($id){
    $postData = $realtimeDatabase->getReference("posts/$id")->getValue();
    if($postData) $post = $postData;
}

include("header.php");
?>

<style>
.container{ max-width:800px; margin:120px auto 60px; padding:20px; }
.post-title{ font-size:24px; color:#2563eb; margin-bottom:15px; }
.post-image{ width:100%; max-height:400px; object-fit:cover; border-radius:12px; margin-bottom:15px; }
.post-content{ font-size:16px; color:#333; line-height:1.6; margin-bottom:20px; }
.comment-form input, .comment-form textarea{
    width:100%; padding:10px; margin-bottom:10px; border:1px solid #ccc; border-radius:6px;
}
.comment-form button{
    background:#2563eb; color:#fff; border:none; padding:10px 15px; border-radius:6px; cursor:pointer;
    transition:0.3s;
}
.comment-form button:hover{ background:#1d4ed8; }
.comment-item{ margin-bottom:15px; padding:10px; background:#f9fafb; border-radius:8px; }
.comment-item strong{ color:#2563eb; }
</style>

<div class="container">

    <?php if($post): ?>
        <h1 class="post-title"><?= htmlspecialchars($post['title']) ?></h1>
        <?php if(!empty($post['image'])): ?>
            <img src="dashboard/<?= htmlspecialchars($post['image']) ?>" class="post-image" alt="<?= htmlspecialchars($post['title']) ?>">
        <?php endif; ?>
        <div class="post-content"><?= $post['content'] ?></div>

        <!-- Comments -->
        <h3>Comments</h3>
        <div id="comments-list">
            <!-- Placeholder comments -->
        </div>

        <!-- Comment Form -->
        <form class="comment-form" id="comment-form">
            <input type="text" id="username" name="username" placeholder="Your name" required>
            <textarea id="comment" name="comment" placeholder="Your comment" rows="3" required></textarea>
            <button type="submit">Post Comment</button>
        </form>

    <?php else: ?>
        <p>Post not found.</p>
    <?php endif; ?>

</div>

<script>
// Save username in cookie
const usernameInput = document.getElementById('username');
if(getCookie('username')) usernameInput.value = getCookie('username');

function getCookie(name){
    let matches = document.cookie.match(new RegExp(
        "(?:^|; )"+name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g,'\\$1')+"=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}
function setCookie(name,value,days){
    let d = new Date();
    d.setTime(d.getTime() + (days*24*60*60*1000));
    document.cookie = name+"="+value+";path=/;expires="+d.toUTCString();
}

// Handle comment submit
document.getElementById('comment-form').addEventListener('submit', function(e){
    e.preventDefault();
    const username = usernameInput.value.trim();
    const comment = document.getElementById('comment').value.trim();
    if(!username || !comment) return;

    // Save username in cookie
    setCookie('username', username, 365);

    // Display comment locally
    const commentDiv = document.createElement('div');
    commentDiv.classList.add('comment-item');
    commentDiv.innerHTML = `<strong>${username}</strong><p>${comment}</p>`;
    document.getElementById('comments-list').prepend(commentDiv);

    // Clear textarea
    document.getElementById('comment').value = '';

    // Optionally, send to server via AJAX for permanent storage
});
</script>

<?php include("footer.php"); ?>
