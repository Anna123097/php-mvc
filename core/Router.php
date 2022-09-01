<?php 

namespace app\core;

class Router {

     public array $routes = []; // массив путей
     protected Request $request; // класс запроса(для получения метода и тела запроса)
     protected Response $response;// класс ответа для дополнительных функций

     public function __construct(Request $request, Response $response) {
          $this->response = $response;
          $this->request = $request;
     }
     

     // добавление нового пути для гет метода
     public function get($path, $callback) {
          $this->routes['GET'][$path] = $callback;
     }

     // добавление нового пути для пост метода
     public function post($path, $callback) {
          $this->routes['POST'][$path] = $callback;
     }

     // главная функция, которая все запускает
     public function resolve() {
          $path = $this->request->getPath(); // берем текцщих путь на сайте
          $method = $this->request->getMethod(); // берем метод запроса
          $callback = $this->routes[$method][$path] ?? false; // ищем запись в нашем массиве путей

          if($callback === false) { // если не нашло, ставим код 404 и завершаем исплолнение кода
               $this->response->setStatusCode(404);
               echo "404 Not found";
               exit();
          }

          if(is_string($callback)) { // если была передана, рендерим вьюшку с таким же названием
               return $this->renderView($callback);
          }

          if(is_array($callback)) { // если был передан массив(вида [FormController::class, 'signup'])
               $controller = new $callback[0](); // создаем новый экземпляр класса FormController
               Application::$app->controller = $controller; // устанавливаем контроллер  в аппку
               $controller->action = $callback[1]; // ставим экшн 
               $callback[0] = Application::$app->controller; 
               
               foreach($controller->getMiddlewares() as $middleware) { // перебираем все миддлвейры и выполняем их
                    $middleware->execute();
               }
          }

          
          return call_user_func($callback, $this->request, $this->response); // вызывается метод класса, который мы передали
     }

     public function renderView($view, $params = []) { // функция для объеденения layout и view
          return Application::$app->view->renderView($view, $params);
     }

     protected function layoutContent() { // возвращает содержимое файла лейаут
          return Application::$app->view->layoutContent();

     }
     protected function renderOnlyView($view, $params = []) {  // возвращает содержимое только вьюшка
          return Application::$app->view->renderOnlyView($view,$params);

     }
}