<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\middlewares\AuthMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\LoginModel;
use app\models\RegisterModel;

class FormController extends Controller {

  public function __construct() {
    $this->registerMiddleware(new AuthMiddleware(['profile'])); // регистрируем новый мидлвеер на страницу профиля
  }
  
  public function signup(Request $request, Response $response) { // методы класса
    // $this->setLayout('admin');    
    $registerModel = new RegisterModel(); // подключаем модельку регистрации (для валидации полей и добавления пользователя в БД)


    if($request->isPost()) { // если метод запроса пост(то есть если человек нажал на кнопку регистрации)
      $data = $request->getBody(); // получаем значение всех полей на странице
      $registerModel->loadData($data); // загружаем это данные в модельку
      if($registerModel->validate() && $registerModel->save()) { // валидируем все поля(по правилам из модельки) и регистрируем пользователя
        // если все успешно, добавляем флеш и делаем редирект на главную страницу
        Application::$app->session->setFlash('success', 'You are successfully registered');
        $response->redirect('/');
      }
    }

    // рендерим страницу и передаем нашу модельку для показа ошибок
    $this->render('signUp', [
      'model' => $registerModel
    ]);
    // $this->render('form');
  }


  public function login(Request $request, Response $response){
    $loginModel = new LoginModel();


    if($request->isPost()) {
      $data = $request->getBody();
      $loginModel->loadData($data);
      if($loginModel->validate() && $loginModel->login()) {
        // Application::$app->session->setFlash('success', 'You are successfully logged in');
        $response->redirect('/');
      }
    }


    $this->render("login",  [
      'model' => $loginModel
    ]);

  }

  public function logout(Request $request, Response $response) { // выходим из аккаунта и делаем редирект
    Application::$app->logout();
    $response->redirect('/');
  }


  public function profile() { // рендерим страницу профиля
    $this->render("profile");
  }
}
