<?php
require 'config.php';

$cart = $_SESSION['cart'] ?? [];
$productIds = array_keys($cart);

$items = [];
$total = 0;

if ($productIds) {
    // جلب تفاصيل المنتجات الموجودة في السلة
    $in  = implode(',', array_fill(0, count($productIds), '?'));
    $stmt = $pdo->prepare("SELECT id, name, price, image FROM products WHERE id IN ($in)");
    $stmt->execute($productIds);
    $rows = $stmt->fetchAll();

    // جهّز صفوف العرض واحسب الإجمالي
    foreach ($rows as $row) {
        $pid = (int)$row['id'];
        $qty = (int)($cart[$pid] ?? 0);
        $line = $row['price'] * $qty;
        $total += $line;
        $items[] = [
            'id'    => $pid,
            'name'  => $row['name'],
            'price' => $row['price'],
            'image' => $row['image'],
            'qty'   => $qty,
            'line'  => $line,
        ];
    }
}

include 'header.php';
?>
<h1>سلة المشتريات</h1>

<?php if (!$items): ?>
  <p>السلة فارغة.</p>
<?php else: ?>
  <form action="update_cart.php" method="post">
    <table class="cart-table">
      <thead>
        <tr>
          <th>المنتج</th><th>السعر</th><th>الكمية</th><th>الإجمالي</th><th>حذف</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $it): ?>
          <tr>
            <td>
              <img src="<?= e($it['image']) ?>" class="thumb" alt="">
              <?= e($it['name']) ?>
            </td>
            <td><?= number_format($it['price'], 2) ?> ر.س</td>
            <td>
              <input type="number" name="qty[<?= (int)$it['id'] ?>]" min="1" value="<?= (int)$it['qty'] ?>">
            </td>
            <td><?= number_format($it['line'], 2) ?> ر.س</td>
            <td>
              <label><input type="checkbox" name="remove[]" value="<?= (int)$it['id'] ?>"> حذف</label>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="cart-total">
      <strong>المجموع: <?= number_format($total, 2) ?> ر.س</strong>
    </div>

    <div class="cart-actions">
      <button type="submit">حدّث السلة</button>
      <a class="btn" href="checkout.php">إتمام الشراء</a>
    </div>
  </form>
<?php endif; ?>

<?php include 'footer.php'; ?>
