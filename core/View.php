<?php

namespace app\core;

class View
{
  protected string $title = "";
  public function renderView($view, $params = [])
  { // функция для объеденения layout и view
    $viewContent   = $this->renderOnlyView($view, $params);
    $layoutContent = $this->layoutContent();
    echo str_replace('{{content}}', $viewContent, $layoutContent);
  }

  public function layoutContent()
  { // возвращает содержимое файла лейаут
    $layout = Application::$app->controller->layout ?? 'main';
    ob_start();
    include_once Application::$ROOT_DIR . "/views/layouts/$layout.php";
    return ob_get_clean();
  }
  public function renderOnlyView($view, $params = [])
  {  // возвращает содержимое только вьюшка
    foreach ($params as $key => $value) {
      $$key = $value;
    }
    ob_start();
    include_once Application::$ROOT_DIR . "/views/$view.php";
    return ob_get_clean();
  }
}
