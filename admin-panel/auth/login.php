<?php

session_start();

require_once __DIR__ . '/../../config/Database.php';

$db = (new Database())->connect();
$errors = [];

if (isset($_SESSION['admin_id'])) {
    header("Location: ../dashboard/index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email)) {
        $errors['email'] = 'Email is required.';
    } 
    if (empty($password)) {
        $errors['password'] = 'Password is required.';
    }

    if (empty($errors)) {
        $stmt = $db->prepare("SELECT * FROM admins WHERE email = ?");
        $stmt->execute([$email]);

        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && $password === $admin['password']) {
            $_SESSION['admin_id']    = $admin['id'];
            $_SESSION['admin_name']  = $admin['name'];
            $_SESSION['admin_email'] = $admin['email'];

            header("Location: ../dashboard/index.php");
            exit;
        } else {
            $errors['general'] = 'Invalid email or password.';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body class="login-body">

<div class="login-box">
    <h2>Admin Login</h2>

    <?php if (!empty($errors['general'])): ?>
        <div class="form-error">
            <?php echo htmlspecialchars($errors['general']); ?>
        </div>
    <?php endif; ?>
    
    <form action="" method="POST">
        <div class="form-group">
            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                placeholder="Enter your email"
                required
            >
            <?php if (isset($errors['email'])): ?>
                <div class="error-message"><?php echo htmlspecialchars($errors['email']); ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Enter your password"
                required
            >
            <?php if (isset($errors['password'])): ?>
                <div class="error-message"><?php echo htmlspecialchars($errors['password']); ?></div>
            <?php endif; ?>
        </div>

        <button type="submit" name="login" class="btn-login">
            Login
        </button>
    </form>
</div>

</body>
</html>
