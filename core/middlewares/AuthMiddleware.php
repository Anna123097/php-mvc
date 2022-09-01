<?php

namespace app\core\middlewares;

use app\core\Application;
use app\core\exceptions\ForbiddenExceptions;

class AuthMiddleware extends BaseMiddleware {
  public function __construct(array $actions = []) {
    $this->actions = $actions;
  }

  public function execute() {
    if(Application::isGuest()) { // если пользователь гость
      if(empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)) { // и страница под защитой
        throw new ForbiddenExceptions(); // выбрасываем ошибку
      }
    }
  }
}