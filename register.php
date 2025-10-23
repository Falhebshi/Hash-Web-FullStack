<?php
require 'config.php';

$errors = [];
$name = $email = $phone = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm']  ?? '';

    // التحقق من المدخلات
    if ($name === '')   $errors[] = 'الاسم مطلوب';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'بريد إلكتروني غير صالح';
    if (strlen($password) < 6) $errors[] = 'كلمة المرور يجب ألا تقل عن 6 أحرف';
    if ($password !== $confirm) $errors[] = 'كلمتا المرور غير متطابقتين';

    if (!$errors) {
        // تشفير كلمة المرور
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // إدراج المستخدم - استخدام Prepared Statement لمنع الحقن
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, phone) VALUES (?, ?, ?, ?)");
        try {
            $stmt->execute([$name, $email, $hash, $phone ?: null]);
            // تسجيله تلقائيًا
            $_SESSION['user'] = [
                'id'    => $pdo->lastInsertId(),
                'name'  => $name,
                'email' => $email
            ];
            header('Location: products.php');
            exit;
        } catch (PDOException $e) {
            // في حال البريد مكرر (UNIQUE)
            $errors[] = 'البريد الإلكتروني مستخدم مسبقًا';
        }
    }
}

include 'header.php';
?>
<h1>إنشاء حساب</h1>
<?php if ($errors): ?>
  <div class="alert"><?php foreach ($errors as $er) echo "<p>".e($er)."</p>"; ?></div>
<?php endif; ?>

<form method="post" class="form">
  <label>الاسم</label>
  <input type="text" name="name" value="<?= e($name) ?>" required>

  <label>البريد الإلكتروني</label>
  <input type="email" name="email" value="<?= e($email) ?>" required>

  <label>الجوال (اختياري)</label>
  <input type="text" name="phone" value="<?= e($phone) ?>">

  <label>كلمة المرور</label>
  <input type="password" name="password" required>

  <label>تأكيد كلمة المرور</label>
  <input type="password" name="confirm" required>

  <button type="submit">تسجيل</button>
</form>
<?php include 'footer.php'; ?>
