<?php
namespace app\models;

use app\core\Application;
use app\core\DbModel;

class LoginModel extends DbModel {
  // указываем поля страницы
  public string $login = '';
  public string $password = ''; 

  public static function tableName(): string
  {
    return 'users';
  }


  public function rules() : array { // указываем правила для полей
    return [
      'login' => [self::RULE_REQUIRED],
      'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => '6'], [self::RULE_MAX, 'max' => '50']],
    ];
  }

  public function attributes() : array {
    return ['login', 'password'];
  }

  public function login() : bool {
    
    $user = parent::findOne(['login' => $this->login]); //ищем человека с таким же логином
    if(!$user) { //если человек не найден, показываем ошибку
      $this->addCustomError('login', 'User with this login was not found');
      return false;
    }
    if(!password_verify($this->password, $user->password)) { // если человек найден, но пароль не тот, показываем ошибку
      $this->addCustomError('password', 'Incorrect password');
      return false;
    }

    // если все успешно, вызываем функцию входа
    Application::$app->login($user);
    
    
    return true;
  }

}