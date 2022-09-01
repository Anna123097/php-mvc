<?php $this->title = 'SignUp Page'; ?>

<div class="flex_container">

  <div class="form_begin">
    <h1 class="form_title">Sign Up</h1>

    <?php $form = app\core\form\Form::begin() ?>

    <?= $form->field($model, "firstname", "Firstname", "John") ?>
    <?= $form->field($model, "lastname", "Lastname", "Smith") ?>
    <?= $form->field($model, "login", "Login", "john_smith228") ?>
    <?= $form->field($model, "email", "Email", "johnsmith@gmail.com") ?>
    <?= $form->field($model, "password", "Password", "*******")->passwordField() ?>
    <?= $form->field($model, "password_confirm", "Repeat password", "*******")->passwordField() ?>

    <input type="submit" value="Create" class="btn-submit">
    <?= app\core\form\Form::end() ?>


  </div>


</div>