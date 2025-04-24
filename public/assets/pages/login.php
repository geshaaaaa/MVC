<?php

use App\Session\Session;

$session = new Session();
$errors = $session->getFlash('errors', []);
$errors = array_unique($errors);
?>

<!DOCTYPE html>
<html >
<head>
    <meta charset="UTF-8">
    <title>Вхід</title>
    <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>
<div class="overlay">
    <form action="/api/auth" method="post">
        <div class="con">
            <header class="head-form">
                <h2>Вхід</h2>
                <p>Введіть ваш email та пароль</p>
            </header>
            <?php if (!empty($errors)): ?>
                <div class="error-messages">
                    <?php foreach ($errors as $error): ?>
                        <p class="error-message"><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <br>

            <div class="field-set">
          <span class="input-item">
            <i class="fa fa-user-circle"></i>
          </span>
                <input class="form-input" type="text" placeholder="Email" name="email" >

                <br>

                <span class="input-item">
            <i class="fa fa-key"></i>
          </span>
                <input class="form-input" type="password" placeholder="Пароль" name="password" id="pwd" >

                <span>
            <i class="fa fa-eye" aria-hidden="true" type="button" id="eye"></i>
          </span>

                <br>

                <button class="log-in" type="submit">Увійти</button>
            </div>

            <div class="other">
                <button class="btn submits sign-up" type="button" onclick="window.location.href='/register'">Реєстрація <i class="fa fa-user-plus" aria-hidden="true"></i></button>
            </div>
        </div>
    </form>
</div>

<script src="/assets/js/index.js"></script>
</body>
</html>
