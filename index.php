<?php
require __DIR__.'/dashboard/dbcon.php'; 
require __DIR__ . '/vendor/autoload.php';

$posts = $realtimeDatabase->getReference("posts")->getValue();

if($posts){
    uasort($posts, function($a,$b){
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });
}

include("header.php");
?>

<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{background:#f3f4f6;font-family:system-ui,-apple-system,sans-serif;}

.container{
    max-width:100%;
    margin:100px auto 60px;
    padding:10px;
}

/* ===== SLIDER ===== */
.slider-wrapper{
    overflow:hidden;
    width:100%;
    margin-bottom:20px;
}
.slider{
    display:flex;
    gap:12px;
    transition:transform 0.6s ease;
}
.slider-item{
    min-width:260px;
    background:#fff;
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 6px 18px rgba(0,0,0,.08);
    cursor:pointer;
}
.slider-item img{
    width:100%;
    height:150px;
    object-fit:cover;
}
.slider-item .content{
    padding:10px;
}
.slider-item h3{
    font-size:15px;
    color:#2563eb;
}
.slider-item p{
    font-size:13px;
    color:#444;
}

/* ===== SEARCH ===== */
.search-box{
    width:100%;
    padding:0 8px;
    margin:20px 0;
}
.search-box input{
    width:100%;
    padding:12px 14px;
    border-radius:25px;
    border:1px solid #ddd;
    font-size:15px;
}

/* ===== POSTS ===== */
.posts-list{
    display:flex;
    flex-direction:column;
    gap:15px;
}


.post-item{
    background:#fff;
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 4px 15px rgba(0,0,0,.08);
    display:flex;
    flex-direction:column;
}
.post-item img{
    width:auto;
    height:680px;
    margin: 30px;
    object-fit:cover;
}
.post-item .details{
    padding:12px;
}
.post-item h3{
    font-size:16px;
    color:#2563eb;
}
.post-item p{
    font-size:13px;
    color:#333;
    margin:6px 0;
}
.read-more{
    display:inline-block;
    background:#2563eb;
    color:#fff;
    padding:7px 12px;
    border-radius:8px;
    text-decoration:none;
    font-size:13px;
}

/* ===== MOBILE FIX ===== */
@media(max-width:780px){
      .posts-list{
        display:flex;
        flex-direction:column;
        gap:15px;
}
    .slider-item{min-width:220px;}
    .post-item img{height:140px;}
    .search-box input{font-size:14px;}
}
.post-item img{
    width: 100%;
    height: auto;
    margin: 0;
}
.post-item{
    width: 100%;
}
.search-box{
    width: 100%;
}
</style>

<div class="container">

<!-- SLIDER -->
<?php if($posts): ?>
<div class="slider-wrapper">
    <div class="slider" id="slider">
        <?php foreach($posts as $post): ?>
        <div class="slider-item" onclick="window.location='single_post.php?id=<?= $post['id'] ?>'">
            <?php if(!empty($post['image'])): ?>
            <img src="dashboard/<?= $post['image'] ?>">
            <?php endif; ?>
            <div class="content">
                <h3><?= htmlspecialchars($post['title']) ?></h3>
                <p><?= substr(strip_tags($post['content']),0,60) ?>...</p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- SEARCH -->
<div class="search-box">
    <input type="text" id="search" placeholder="Search feeds...">
</div>

<!-- POSTS -->
<div class="posts-list" id="postList">
<?php foreach($posts as $post): ?>
<div class="post-item" data-title="<?= strtolower($post['title']) ?>">
    <?php if(!empty($post['image'])): ?>
    <img src="dashboard/<?= $post['image'] ?>">
    <?php endif; ?>
    <div class="details">
        <h3><?= htmlspecialchars($post['title']) ?></h3>
        <p><?= substr(strip_tags($post['content']),0,100) ?>...</p>
        <a href="single_post.php?id=<?= $post['id'] ?>" class="read-more">Read More</a>
    </div>
</div>
<?php endforeach; ?>
</div>

</div>

<script>
let slider = document.getElementById("slider");
let index = 0;

setInterval(()=>{
    let items = document.querySelectorAll(".slider-item");
    if(!items.length) return;
    index++;
    if(index >= items.length) index = 0;
    slider.style.transform = `translateX(-${index * (items[0].offsetWidth + 12)}px)`;
},5000);

/* SEARCH FILTER */
document.getElementById("search").addEventListener("input", function(){
    let v = this.value.toLowerCase();
    document.querySelectorAll(".post-item").forEach(post=>{
        post.style.display = post.dataset.title.includes(v) ? "flex" : "none";
    });
});
</script>

<?php include("footer.php"); ?>
