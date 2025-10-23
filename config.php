<?php
// config.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * الاتصال بقاعدة البيانات عبر PDO
 * شرح: نستخدم DSN مع utf8mb4، ونفعل ERRMODE_EXCEPTION لرفع الأخطاء على هيئة استثناءات (سهل تتبعها)
 */
$host = 'localhost';
$db   = 'shop_db';
$user = 'root';
$pass = ''; // في XAMPP غالبًا فاضي. لو مختلف غيّره

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // مهم لتشخيص الأخطاء
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // يجلب النتائج كمصفوفة ترابطية
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

/**
 * دالة مساعده: تهريب النص لمنع XSS عند الطباعة في HTML
 */
function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * دوال مصادقة بسيطة
 */
function isLoggedIn() {
    return isset($_SESSION['user']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}
