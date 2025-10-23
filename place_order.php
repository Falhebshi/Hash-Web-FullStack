<?php
require 'config.php';

$cart = $_SESSION['cart'] ?? [];
if (!$cart) {
    header('Location: cart.php');
    exit;
}

// التحقق من المدخلات
$name    = trim($_POST['name'] ?? '');
$phone   = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');

if ($name === '' || $phone === '' || $address === '') {
    die('الرجاء تعبئة جميع الحقول المطلوبة.');
}

// اجلب تفاصيل المنتجات لحساب الإجمالي
$productIds = array_keys($cart);
$in  = implode(',', array_fill(0, count($productIds), '?'));
$stmt = $pdo->prepare("SELECT id, price FROM products WHERE id IN ($in)");
$stmt->execute($productIds);
$rows = $stmt->fetchAll();

$prices = [];
foreach ($rows as $r) $prices[$r['id']] = (float)$r['price'];

$total = 0;
foreach ($cart as $pid => $qty) {
    $p = $prices[$pid] ?? 0;
    $total += $p * $qty;
}

$pdo->beginTransaction();
try {
    // إدراج الطلب
    $userId = isLoggedIn() ? (int)$_SESSION['user']['id'] : null;

    $stmt = $pdo->prepare("INSERT INTO orders (user_id, customer_name, customer_phone, customer_address, total_amount)
                           VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$userId, $name, $phone, $address, $total]);
    $orderId = (int)$pdo->lastInsertId();

    // إدراج عناصر الطلب
    $itemStmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)");
    foreach ($cart as $pid => $qty) {
        $price = $prices[$pid] ?? 0;
        $itemStmt->execute([$orderId, (int)$pid, (int)$qty, $price]);
    }

    $pdo->commit();
    // إفراغ السلة
    unset($_SESSION['cart']);

    header('Location: success.php?order=' . $orderId);
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    die('حصل خطأ أثناء حفظ الطلب: ' . e($e->getMessage()));
}
