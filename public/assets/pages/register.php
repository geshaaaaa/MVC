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
    <title>Реєстрація</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/assets/css/register.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>

<div class="login-container">
    <a href="/auth" class="back-link">
  <span class="arrow-wrapper">
    <span class="arrow">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20z" fill="currentColor"/>
      </svg>
    </span>
  </span>
       <b>Повернутись до входу</b>
    </a>
    <h2>Реєстрація</h2>
    <?php if (!empty($errors)): ?>
        <div class="error-messages">
            <?php foreach ($errors as $error): ?>
                <p class="error-message"><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="/api/register" method="post">
        <input name="email" class="input-field" placeholder="Email" >
        <input type="password" name="password" class="input-field" placeholder="Пароль" >
        <button type="submit" class="register-btn">Зареєструватись</button>
    </form>
</div>
</body>
</html>
