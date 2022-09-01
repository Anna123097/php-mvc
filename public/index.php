<?php
// подключаем автозагрузку классов(чтобы не подключать везде ручками)
require_once '../vendor/autoload.php';


use app\core\Application;
use app\controllers\FormController;
use app\controllers\HomeController;

// подключаем библиотеку для работы с .env файлом
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$config = [
    'userClass' => \app\core\User::class,
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
    ]
];

// данный класс является точкой входа для всего приложения

try {

    $app = new Application(dirname(__DIR__), $config);


    // создаем новые роуты(гет или пост методы), 
    // первый аргумент это путь, второй аргумент это название класса и метод класса который будет вызываться
    $app->router->get('/', [HomeController::class, 'home']);

    $app->router->get('/contacts', 'contact');
    $app->router->get('/signup', [FormController::class, 'signup']);
    $app->router->post('/signup', [FormController::class, 'signup']);
    $app->router->get('/login', [FormController::class, 'login']);
    $app->router->post('/login', [FormController::class, 'login']);
    $app->router->get('/logout', [FormController::class, 'logout']);
    $app->router->get('/profile', [FormController::class, 'profile']);

    $app->router->get('/about_us', function () {
        echo 'Hello World';
    });


    // запускаем приложение
    $app->run();
} catch (Exception $e) {
    Application::$app->router->renderView('_error');
}
