<?php
session_start();

if (isset($_SESSION['admin_id'])) {
    header("Location: ../dashboard/index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>

    <style>
        body{
            font-family: Arial;
            background:#f5f5f5;
        }

        .login-box{
            width:350px;
            margin:100px auto;
            background:#fff;
            padding:20px;
            border-radius:5px;
        }

        input{
            width:100%;
            padding:10px;
            margin-bottom:15px;
        }

        button{
            padding:10px 20px;
            cursor:pointer;
        }

        .error{
            color:red;
            margin-bottom:10px;
        }

        .success{
            color:green;
            margin-bottom:10px;
        }
    </style>
</head>
<body>

<div class="login-box">

    <h2>Admin Login</h2>

    <?php if(isset($_SESSION['error'])) : ?>
        <div class="error">
            <?= $_SESSION['error']; ?>
        </div>
    <?php unset($_SESSION['error']); endif; ?>

    <?php if(isset($_SESSION['success'])) : ?>
        <div class="success">
            <?= $_SESSION['success']; ?>
        </div>
    <?php unset($_SESSION['success']); endif; ?>

    <form action="login-process.php" method="POST">

        <input
            type="email"
            name="email"
            placeholder="Enter Email"
            required
        >

        <input
            type="password"
            name="password"
            placeholder="Enter Password"
            required
        >

        <button type="submit" name="login">
            Login
        </button>

    </form>

</div>

</body>
</html>