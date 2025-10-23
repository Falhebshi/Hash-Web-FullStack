<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // تحديث الكميات
    foreach (($_POST['qty'] ?? []) as $pid => $qty) {
        $pid = (int)$pid;
        $qty = max(1, (int)$qty);
        if (isset($_SESSION['cart'][$pid])) {
            $_SESSION['cart'][$pid] = $qty;
        }
    }

    // حذف العناصر
    foreach (($_POST['remove'] ?? []) as $pid) {
        $pid = (int)$pid;
        unset($_SESSION['cart'][$pid]);
    }
}

header('Location: cart.php');
exit;
