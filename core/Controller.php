<?php 
namespace app\core;

use app\core\middlewares\BaseMiddleware;

class Controller {
  public string $layout = 'main'; // лейаут по дефолту(можно менять в методах)
  public string $action = ''; // метод, который сейчас используется
  public array $middlewares = []; // массив из миддлвееров

  public function setLayout($layout) { // функция для смены лейаута
    $this->layout = $layout;
  }


  public function render($view, $params = []){ // функция рендера
    return Application::$app->router->renderView($view, $params);
  }

  public function registerMiddleware(BaseMiddleware $middleware) { // функция для добавления нового миддлвеера
    $this->middlewares[] = $middleware;
  }

  public function getMiddlewares() { // геттер для свойства мидлвееров
    return $this->middlewares;
  }
}