<?php
// header.php
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>متجر بسيط</title>
  <link rel="stylesheet" href="assets/style.css" />
  <!-- ممكن تضيف Bootstrap من CDN لو حاب -->
</head>
<body>
<header class="navbar">
  <div class="brand"><a href="products.php">متجري</a></div>

  <form class="search" action="products.php" method="get">
    <input type="text" name="q" placeholder="ابحث عن منتج..." value="<?= e($_GET['q'] ?? '') ?>">
    <button type="submit">بحث</button>
  </form>

  <nav class="links">
    <?php if (isLoggedIn()): ?>
      <span>مرحبًا، <?= e($_SESSION['user']['name']) ?></span>
      <a href="cart.php">السلة</a>
      <a href="logout.php">تسجيل الخروج</a>
    <?php else: ?>
      <a href="login.php">تسجيل الدخول</a>
      <a href="register.php">إنشاء حساب</a>
      <a href="cart.php">السلة</a>
    <?php endif; ?>
    <a href="contact.php">تواصل</a>
  </nav>
</header>

<main class="container">
