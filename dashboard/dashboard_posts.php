<?php
require __DIR__.'/dbcon.php';

// ================= ADD POST =================
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['add_post'])){
    $id = uniqid();
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';

    $imageName = '';
    if(isset($_FILES['image']) && $_FILES['image']['error']===0){
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageName = "uploads/{$id}.{$ext}";
        move_uploaded_file($_FILES['image']['tmp_name'],$imageName);
    }

    $realtimeDatabase->getReference("posts/$id")->set([
        "id"=>$id,
        "title"=>$title,
        "content"=>$content,
        "image"=>$imageName,
        "created_at"=>date("Y-m-d H:i:s")
    ]);

    header("Location: dashboard_posts.php");
    exit;
}

// ================= DELETE POST =================
if(isset($_GET['delete'])){
    $del_id = $_GET['delete'];
    $realtimeDatabase->getReference("posts/$del_id")->remove();
    header("Location: dashboard_posts.php");
    exit;
}

// ================= FETCH POSTS =================
$posts = $realtimeDatabase->getReference("posts")->getValue();

include("includes/header.php");
?>

<style>
/* ===== Container & spacing ===== */
.container{
    max-width:1200px;
    margin:140px auto 60px;
    padding:20px;
}

/* ===== POST FORM ===== */
.post-form{
    background:#fff;
    padding:25px;
    border-radius:12px;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
    margin-bottom:50px;
}
.post-form h2{ color:#2563eb; margin-bottom:15px; }
.post-form input[type="text"], .post-form textarea{
    width:100%; padding:12px 15px; margin-bottom:12px;
    border-radius:8px; border:1px solid #ccc; font-size:14px;
}
.post-form input[type="file"]{ margin-bottom:12px; }
.post-form button{
    background:#2563eb; color:white;
    padding:10px 20px; border:none; border-radius:8px; cursor:pointer;
    transition:0.3s;
}
.post-form button:hover{ background:#1d4ed8; }

/* ===== TOOLBAR ===== */
/* ===== MODALS ===== */
.modal{
    display:none;
    position:fixed;
    z-index:10000;
    left:0; top:0;
    width:100%; height:100%;
    background:rgba(0,0,0,0.6);
    overflow:auto;
    justify-content:center;
    align-items:center;
    padding:20px;
}

.modal-content{
    background:#fff;
    padding:25px;
    border-radius:12px;
    max-width:700px;
    width:100%;
    position:relative;
    box-shadow:0 10px 25px rgba(0,0,0,0.2);
}

.close-modal{
    position:absolute;
    top:15px;
    right:20px;
    font-size:24px;
    font-weight:bold;
    color:#333;
    cursor:pointer;
    transition:0.3s;
}
.close-modal:hover{
    color:#2563eb;
}

/* Modal Title */
.modal-content h3{
    margin-bottom:15px;
    color:#2563eb;
}

/* Modal Image */
.modal-content img{
    width:100%;
    max-height:300px;
    object-fit:cover;
    border-radius:8px;
    margin-bottom:15px;
}

/* Modal Content Text */
#modal-content, #comments-content{
    max-height:400px;
    overflow-y:auto;
    line-height:1.6;
    font-size:14px;
    color:#333;
    padding:10px;
    border:1px solid #ddd;
    border-radius:8px;
    background:#f9fafb;
}

/* Edit Form in Modal */
#edit-form input[type="text"], #edit-form textarea{
    width:100%;
    padding:10px 12px;
    margin-bottom:12px;
    border-radius:6px;
    border:1px solid #ccc;
    font-size:14px;
}
#edit-form input[type="file"]{
    margin-bottom:12px;
}
#edit-form button{
    background:#2563eb;
    color:#fff;
    padding:10px 18px;
    border:none;
    border-radius:6px;
    cursor:pointer;
    transition:0.3s;
}
#edit-form button:hover{ background:#1d4ed8; }

/* ===== TOOLBAR ===== */
#toolbar button{
    margin-right:5px;
    padding:6px 8px;
    border:none;
    background:#f3f4f6;
    cursor:pointer;
    border-radius:4px;
    transition:0.3s;
    color:#333;
}
#toolbar button:hover{
    background:#e0f2fe;
    color:#2563eb;
}

/* Scrollable comments */
#comments-content{
    max-height:300px;
    overflow-y:auto;
}

/* ===== Responsive ===== */
@media(max-width:768px){
    .modal-content{padding:15px;}
    #modal-content, #comments-content{max-height:250px;}
    .posts-table img{width:60px;height:40px;}
    .action-btn{font-size:12px;padding:4px 6px;}
    .post-form h2{font-size:20px;}
}

#toolbar button{
    margin-right:5px; padding:6px 8px;
    border:none; background:#f3f4f6; cursor:pointer;
    border-radius:4px; transition:0.3s; color:#333;
}
#toolbar button:hover{background:#e0f2fe; color:#2563eb;}

/* ===== POSTS TABLE ===== */
.table-responsive{overflow-x:auto;}
.posts-table{width:100%;border-collapse:collapse;}
.posts-table th, .posts-table td{padding:12px;border:1px solid #ddd;text-align:left;vertical-align: middle;}
.posts-table th{background:#2563eb;color:#fff;}
.posts-table img{width:80px;height:50px;object-fit:cover;border-radius:6px;}

/* ===== ACTION BUTTONS ===== */
.action-btn{
    padding:5px 10px; border:none; border-radius:6px;
    margin:2px; cursor:pointer; color:white;
    font-size:14px; display:inline-flex; align-items:center; gap:4px; transition:0.3s;
}
.edit-btn{background:#f59e0b;}
.delete-btn{background:#ef4444;}
.read-btn{background:#10b981;}
.comment-btn{background:#6366f1;}
.action-btn:hover{opacity:0.85;}

/* ===== MODALS ===== */
.modal{
    display:none; position:fixed; z-index:10000;
    left:0; top:0; width:100%; height:100%;
    background:rgba(0,0,0,0.5); overflow:auto;
    justify-content:center; align-items:center;
}
.modal-content{
    background:#fff; padding:20px; border-radius:12px;
    max-width:600px; width:90%; position:relative;
}
.close-modal{ position:absolute; top:10px; right:15px; font-size:20px; cursor:pointer; }

/* ===== RESPONSIVE ===== */
@media(max-width:768px){
    .posts-table img{width:60px;height:40px;}
    .action-btn{font-size:12px;padding:4px 6px;}
    .post-form h2{font-size:20px;}
}
</style>

<div class="container">

<!-- ADD POST FORM -->
<div class="post-form">
<h2>📝 Add New Post</h2>
<form method="POST" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Post Title" required>

    <!-- Rich text toolbar -->
    <div id="toolbar" class="mb-2">
        <button type="button" data-command="bold"><i class="fas fa-bold"></i></button>
        <button type="button" data-command="italic"><i class="fas fa-italic"></i></button>
        <button type="button" data-command="underline"><i class="fas fa-underline"></i></button>
        <button type="button" data-command="insertUnorderedList"><i class="fas fa-list-ul"></i></button>
        <button type="button" data-command="insertOrderedList"><i class="fas fa-list-ol"></i></button>
        <button type="button" data-command="createLink"><i class="fas fa-link"></i></button>
    </div>

    <div contenteditable="true" id="content" style="border:1px solid #ccc; border-radius:8px; padding:10px; min-height:150px; margin-bottom:10px;"></div>
    <input type="hidden" name="content" id="content-hidden">

    <input type="file" name="image">

    <button type="submit" name="add_post">Add Post</button>
</form>
</div>

<!-- POSTS TABLE -->
<div class="table-responsive">
<table class="posts-table">
    <thead>
        <tr>
            <th>Title</th>
            <th>Content</th>
            <th>Image</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php if($posts): foreach($posts as $id=>$post): ?>
        <tr>
            <td><?= htmlspecialchars($post['title']) ?></td>
            <td><?= htmlspecialchars(substr(strip_tags($post['content']),0,100)) ?>...</td>
            <td>
              <?php if(!empty($post['image']) && file_exists($post['image'])): ?>
                <img src="<?= htmlspecialchars($post['image']) ?>" alt="Post Image">
              <?php endif; ?>
            </td>
            <td><?= $post['created_at'] ?></td>
            <td>
                <button class="action-btn edit-btn" data-id="<?= $id ?>"><i class="fas fa-edit"></i> Edit</button>
                <button class="action-btn delete-btn" onclick="if(confirm('Delete this post?')) window.location='dashboard_posts.php?delete=<?= $id ?>';"><i class="fas fa-trash"></i> Delete</button>
                <button class="action-btn read-btn" data-id="<?= $id ?>"><i class="fas fa-eye"></i> Read</button>
                <button class="action-btn comment-btn" data-id="<?= $id ?>"><i class="fas fa-comments"></i> Comments</button>
            </td>
        </tr>
    <?php endforeach; endif; ?>
    </tbody>
</table>
</div>

</div>

<!-- MODALS -->
<div class="modal" id="modal-view">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h3 id="modal-title"></h3>
        <img id="modal-image" style="width:100%; max-height:300px; object-fit:cover; margin-bottom:10px;">
        <div id="modal-content"></div>
    </div>
</div>

<div class="modal" id="modal-edit">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h3>Edit Post</h3>
        <form id="edit-form" enctype="multipart/form-data">
            <input type="text" name="title" id="edit-title" required>
            <div contenteditable="true" id="edit-content" style="border:1px solid #ccc; border-radius:8px; padding:10px; min-height:120px; margin-bottom:10px;"></div>
            <input type="hidden" name="content" id="edit-content-hidden">
            <input type="file" name="image" id="edit-image">
            <button type="submit">Save Changes</button>
        </form>
    </div>
</div>

<div class="modal" id="modal-comments">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h3>Comments</h3>
        <div id="comments-content">No comments yet.</div>
    </div>
</div>

<script>
// ===== Toolbar
document.querySelectorAll('#toolbar button').forEach(btn=>{
    btn.addEventListener('click',()=>{
        const cmd = btn.dataset.command;
        if(cmd==='createLink'){
            let url = prompt("Enter link:");
            if(url) document.execCommand(cmd,false,url);
        } else { document.execCommand(cmd,false,null); }
    });
});
document.querySelector('.post-form form').addEventListener('submit',()=>{
    document.getElementById('content-hidden').value = document.getElementById('content').innerHTML;
});

// ===== Modals
function openModal(modal){ modal.style.display='flex'; }
function closeModal(modal){ modal.style.display='none'; }
document.querySelectorAll('.close-modal').forEach(btn=>{ btn.addEventListener('click',()=>{ closeModal(btn.closest('.modal')); }); });
window.addEventListener('click',e=>{ document.querySelectorAll('.modal').forEach(m=>{ if(e.target==m) closeModal(m); }); });

// ===== Read Post
document.querySelectorAll('.read-btn').forEach(btn=>{
    btn.addEventListener('click',()=>{
        const id = btn.dataset.id;
        fetch('get_post.php?id='+id).then(res=>res.json()).then(data=>{
            document.getElementById('modal-title').innerText=data.title;
            document.getElementById('modal-content').innerHTML=data.content;
            document.getElementById('modal-image').src=data.image ?? '';
            openModal(document.getElementById('modal-view'));
        });
    });
});

// ===== Edit Post
document.querySelectorAll('.edit-btn').forEach(btn=>{
    btn.addEventListener('click',()=>{
        const id = btn.dataset.id;
        fetch('get_post.php?id='+id).then(res=>res.json()).then(data=>{
            document.getElementById('edit-title').value = data.title;
            document.getElementById('edit-content').innerHTML = data.content;
            openModal(document.getElementById('modal-edit'));
            document.getElementById('edit-form').onsubmit = function(e){
                e.preventDefault();
                const updatedTitle = document.getElementById('edit-title').value;
                const updatedContent = document.getElementById('edit-content').innerHTML;
                const formData = new FormData();
                formData.append('title',updatedTitle);
                formData.append('content',updatedContent);
                if(document.getElementById('edit-image').files[0])
                    formData.append('image',document.getElementById('edit-image').files[0]);
                formData.append('update_id',id);

                fetch('update_post.php',{method:'POST',body:formData})
                .then(res=>res.text()).then(r=>{ location.reload(); });
            };
        });
    });
});

// ===== Comments
document.querySelectorAll('.comment-btn').forEach(btn=>{
    btn.addEventListener('click',()=>{
        document.getElementById('comments-content').innerHTML = "This is placeholder comments.";
        openModal(document.getElementById('modal-comments'));
    });
});
</script>

<?php include("includes/footer.php"); ?>
