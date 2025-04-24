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
    <title>Реєстрація</title>
    <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
<div class="overlay">
    <form action="/api/register" method="post">
        <div class="con">
            <header class="head-form">
                <h2>Реєстрація</h2>
                <p>Заповніть форму для реєстрації</p>
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
                    <i class="fa fa-envelope"></i>
                </span>
                <input class="form-input" type="text" placeholder="Email" name="email" >

                <br>

                <span class="input-item">
                    <i class="fa fa-key"></i>
                </span>
                <input class="form-input" type="password" placeholder="Пароль" name="password" id="pwd" >

                <br>

                <button class="log-in" type="submit" onclick="window.location.href='/auth'" >Зареєструватись</button>
            </div>

            <div class="other">
                <button class="btn submits home-button" type="button" onclick="window.location.href='/'">На головну сторінку</button>
                <button class="btn submits back-to-auth" type="button" onclick="window.location.href='/auth'">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Логін
                </button>
            </div>
        </div>
    </form>
</div>
</body>
</html>
