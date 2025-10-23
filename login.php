<?php
require 'config.php';

$errors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'بريد إلكتروني غير صالح';
    if ($password === '') $errors[] = 'الرجاء إدخال كلمة المرور';

    if (!$errors) {
        $stmt = $pdo->prepare("SELECT id, name, email, password_hash FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // التحقق من كلمة المرور
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user'] = [
                'id'    => $user['id'],
                'name'  => $user['name'],
                'email' => $user['email'],
            ];
            header('Location: products.php');
            exit;
        } else {
            $errors[] = 'بيانات الدخول غير صحيحة';
        }
    }
}

include 'header.php';
?>
<h1>تسجيل الدخول</h1>
<?php if ($errors): ?>
  <div class="alert"><?php foreach ($errors as $er) echo "<p>".e($er)."</p>"; ?></div>
<?php endif; ?>

<form method="post" class="form">
  <label>البريد الإلكتروني</label>
  <input type="email" name="email" value="<?= e($email) ?>" required>

  <label>كلمة المرور</label>
  <input type="password" name="password" required>

  <button type="submit">دخول</button>
</form>
<?php include 'footer.php'; ?>
