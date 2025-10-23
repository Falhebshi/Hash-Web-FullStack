<?php
require 'config.php';

$orderId = (int)($_GET['order'] ?? 0);

include 'header.php';
?>
<h1>تم الدفع بنجاح ✅</h1>
<p>رقم طلبك: <strong>#<?= (int)$orderId ?></strong></p>
<p>شكرًا لتسوقك معنا.</p>
<a class="btn" href="products.php">عودة للمتجر</a>
<?php include 'footer.php'; ?>
