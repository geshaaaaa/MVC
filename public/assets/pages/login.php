<?php

use App\Session\Session;

$session = new Session();
$errors = $session->getFlash('errors', []);
$errors = array_unique($errors);
?>


<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вхід</title>
    <link rel="stylesheet" href="/assets/css/login.css">
</head>
<body>

<div class="login-container">
    <h2>Вхід</h2>

    <?php if (!empty($errors)): ?>
        <div class="error-messages">
            <?php foreach ($errors as $error): ?>
                <p class="error-message"><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form action="/api/auth" method="post">
        <input  name="email" class="input-field" placeholder="Email">
        <input  type="password" name="password" class="input-field" placeholder="Пароль">
        <button type="submit" class="login-btn">Увійти</button>
    </form>

    <button type="button" class="register-btn" onclick="window.location.href='/register'">Реєстрація</button>


    <p class="forgot-password">
        <a href="#">Забули пароль?</a> • <a href="#">Служба підтримки</a>
    </p>
</div>
</body>
</html>
