<?php
require __DIR__."/dashboard/dbcon.php";
$books = $realtimeDatabase->getReference("library")->getValue();

include("header.php");
?>

<style>
.store-container{
  max-width:1300px;
  margin:110px auto 60px;
  padding:15px;
}

/* ===== TOP BAR ===== */
.store-top{
  display:flex;
  justify-content:space-between;
  align-items:center;
  flex-wrap:wrap;
  margin-bottom:25px;
  gap:15px;
}
.store-top h2{
  font-size:24px;
}
.search-box{
  max-width:300px;
  width:100%;
}
.search-box input{
  width:100%;
  padding:10px 15px;
  border-radius:8px;
  border:1px solid #ccc;
  outline:none;
}

/* ===== GRID ===== */
.store-grid{
  display:grid;
  grid-template-columns:repeat(auto-fill,minmax(180px,1fr));
  gap:20px;
}

/* ===== CARD ===== */
.book-card{
  background:#fff;
  border-radius:12px;
  overflow:hidden;
  box-shadow:0 10px 25px rgba(0,0,0,0.08);
  display:flex;
  flex-direction:column;
  transition:0.3s;
}
.book-card:hover{
  transform:translateY(-8px);
}

.book-img{
  width:100%;
  height:230px;
  overflow:hidden;
}
.book-img img{
  width:100%;
  height:100%;
  object-fit:cover;
  transition:0.4s;
}
.book-card:hover img{
  transform:scale(1.05);
}

.book-info{
  padding:15px;
  flex:1;
}
.book-info h3{
  font-size:16px;
  margin-bottom:5px;
}
.book-info p{
  font-size:13px;
  color:#666;
}

/* ===== BUTTON ===== */
.book-card a{
  display:block;
  text-align:center;
  padding:12px;
  background:#2563eb;
  color:white;
  text-decoration:none;
  font-size:14px;
  transition:0.3s;
}
.book-card a:hover{
  background:#1d4ed8;
}

/* ===== MOBILE ===== */
@media(max-width:600px){
  .store-top h2{
    font-size:20px;
  }
  .book-img{
    height:200px;
  }
}
</style>

<div class="store-container">

  <div class="store-top">
    <h2>📚 Books Store</h2>
    <div class="search-box">
      <input id="search" placeholder="Search books...">
    </div>
  </div>

  <div class="store-grid" id="grid">
    <?php if($books): foreach($books as $id=>$b): ?>
      <div class="book-card">
        <div class="book-img">
          <img src="dashboard/<?= htmlspecialchars($b['image']) ?>" alt="<?= htmlspecialchars($b['name']) ?>">
        </div>
        <div class="book-info">
          <h3><?= htmlspecialchars($b['name']) ?></h3>
          <p><?= htmlspecialchars($b['level']) ?> | <?= htmlspecialchars($b['class']) ?></p>
        </div>
        <a href="view.php?id=<?= $id ?>">read Book</a>
      </div>
    <?php endforeach; endif; ?>
  </div>

</div>

<script>
document.getElementById("search").addEventListener("keyup", function(){
  let v = this.value.toLowerCase();
  document.querySelectorAll(".book-card").forEach(card=>{
    card.style.display = card.innerText.toLowerCase().includes(v) ? "flex" : "none";
  });
});
</script>

<?php include("footer.php"); ?>
