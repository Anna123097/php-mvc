<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $this->title ?></title>
  <link rel="stylesheet" href="/css/main.css">
</head>

<body>
  <header class="header container">
    <div class="logo">
      <a href="/"><img src="/images/logo.svg" alt=""></a>
    </div>

    <ul>
      <li>
        <a href="#">blog</a>
      </li>
      <li>
        <a href="#">shop</a>
      </li>
    </ul>

    <div class="profil_buttons">
      <?php

      if (app\core\Application::isGuest()) {  // если пользователь не вошел в аккаунт, показываем кнопки для входа?>
        <a href="/login" class="login">Login</a>
        <a href="/signup" class="signup">Sign up</a>
      <?php } else {  //если все же вошел, показываем кнопки профиля и выхода ?>
        <span>Welcome, <?= app\core\Application::$app->user->getName() ?></span>
        <a href="/profile" class="login">Profile</a>
        <a href="/logout"  class="signup">Logout</a>
      <?php } ?>
    </div>

  </header>
  <div class="light1"></div>
  <div class="light2"></div>
  <div class="light3"></div>
  <?php


  if (\app\core\Application::$app->session->getFlash('success')) {  // если есть флеш сообщение, показываем ?>
    <h1><?= \app\core\Application::$app->session->getFlash('success') ?></h1>
  <?php } ?>

  {{content}}

  <footer>
    FOOTER
  </footer>
</body>

</html>