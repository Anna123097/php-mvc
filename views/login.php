<?php $this->title = 'Login Page'; ?>
<div class="flex_container">

  <div class="form_begin">
    <h1 class="form_title">Login</h1>

    <?php $form = app\core\form\Form::begin() ?>

    <?= $form->field($model, "login", "Login", "john_smith228") ?>
    <?= $form->field($model, "password", "Password", "*******")->passwordField() ?>

    <input type="submit" value="Log in" class="btn-submit">
    <?= app\core\form\Form::end() ?>


  </div>


</div>