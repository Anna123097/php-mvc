<?php
namespace app\core;


class Application {

     public static string $ROOT_DIR; // корневая дирректория
     public string $userClass; // указываем класс для работы с пользователем
     public Router $router;      
     public Request $request;
     public Response $response;
     public static Application $app; // делаем статиком данный класс для того, чтобы его можно было вызывать в других классах
     public Controller $controller;   
     public Database $database;   
     public Session $session;
     public View $view;
     public ?DbModel $user;

     public function __construct($rootPath, $config) {
          self::$ROOT_DIR = $rootPath;
          self::$app = $this;

          $this->session = new Session();
          $this->request = new Request();
          $this->response = new Response();
          $this->router = new Router($this->request, $this->response);
          $this->database = new Database($config['db']);
          $this->userClass = $config['userClass'];
          $this->view = new View(); 

          // при перезагрузке страницы находим пользователя если он вошел в аккаунт
          $primaryValue = $this->session->get('user');
          if($primaryValue) {
               $primaryKey = $this->userClass::primaryKey();
               $this->user = $this->userClass::findOne([$primaryKey => $primaryValue]);
          }
     }

     public function run() {
          $this->router->resolve();
     }

     // главная функция для входа пользвателя 
     public function login(DbModel $user) {
          $this->user = $user; // устанавливаем свойство класса
          $primaryKey = $user->primaryKey();
          $primaryValue = $user->{$primaryKey};
          $this->session->set('user', $primaryValue); // добавляем айди пользователя в сессию
     }

     public function logout() {
          $this->user = null; // удаляем свойство класса
          $this->session->remove('user'); // удаляем айди пользователя из сессии
     }

     // функция для проверки, является ли посетитель гостем или он вошел в аккаунт
     public static function isGuest() {
          return !isset(self::$app->user);
     }
}