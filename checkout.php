<?php
require 'config.php';

$cart = $_SESSION['cart'] ?? [];
if (!$cart) {
    header('Location: cart.php');
    exit;
}

include 'header.php';
?>
<h1>معلومات الدفع</h1>
<form action="place_order.php" method="post" class="form">
  <label>الاسم الكامل</label>
  <input type="text" name="name" required>

  <label>الجوال</label>
  <input type="text" name="phone" required>

  <label>العنوان</label>
  <textarea name="address" required></textarea>

  <!-- (اختياري) حقول بطاقة/مدى — للتجربة هنا نعتبر الدفع ناجحًا -->
  <button type="submit">دفع الآن</button>
</form>
<?php include 'footer.php'; ?>
