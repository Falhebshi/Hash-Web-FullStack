<?php
require 'config.php';

$q = trim($_GET['q'] ?? '');

// SQL بـ LIKE (بحث بالاسم والوصف)
if ($q !== '') {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ? ORDER BY id DESC");
    $like = "%$q%";
    $stmt->execute([$like, $like]);
} else {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
}
$products = $stmt->fetchAll();

include 'header.php';
?>
<section class="hero">
  <img src="assets/uploads/hero.jpg" alt="عرض ترويجي" />
  <div class="hero-text">
    <h2>أفضل العروض على معدات الرياضة</h2>
    <p>تسوق الآن</p>
  </div>
</section>

<h1>المنتجات <?= $q ? '— نتيجة البحث عن: '.e($q) : '' ?></h1>

<div class="grid">
<?php foreach ($products as $p): ?>
  <div class="card">
    <img src="<?= e($p['image']) ?>" alt="<?= e($p['name']) ?>">
    <h3><?= e($p['name']) ?></h3>
    <p class="desc"><?= e($p['description']) ?></p>
    <div class="price"><?= number_format($p['price'], 2) ?> ر.س</div>

    <form action="add_to_cart.php" method="post" class="inline">
      <input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>">
      <input type="number" name="quantity" value="1" min="1" class="qty">
      <button type="submit">أضف للسلة</button>
    </form>
  </div>
<?php endforeach; ?>
</div>
<?php include 'footer.php'; ?>
