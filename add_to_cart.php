<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pid = (int)($_POST['product_id'] ?? 0);
    $qty = max(1, (int)($_POST['quantity'] ?? 1));

    // تحقق أن المنتج موجود
    $stmt = $pdo->prepare("SELECT id FROM products WHERE id = ?");
    $stmt->execute([$pid]);
    $exists = $stmt->fetch();

    if ($exists) {
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
        if (!isset($_SESSION['cart'][$pid])) $_SESSION['cart'][$pid] = 0;
        $_SESSION['cart'][$pid] += $qty;
    }
}

header('Location: cart.php');
exit;
