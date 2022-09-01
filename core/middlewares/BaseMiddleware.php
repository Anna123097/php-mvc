<?php 

namespace app\core\middlewares;

abstract class BaseMiddleware {
  abstract public function execute(); // указываем, что должна быть реализация функции execute
}