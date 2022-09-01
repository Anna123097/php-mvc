<?php 

namespace app\core;

// прослойка между БД и выводом на страницу
class User extends DbModel {
  public function attributes() : array { return []; }
  public function rules() : array { return []; }
  
  public static function tableName(): string
  {
    return 'users';
  }

  public function getName() {
    return Application::$app->user->firstname;
  }

}