<?php
namespace app\models;

use app\core\DbModel;

class RegisterModel extends DbModel {

  public string $firstname = '';
  public string $lastname = '';
  public string $login = '';
  public string $email = '';
  public string $password = '';
  public int $status = 0; 
  public string $password_confirm = '';

  public static function tableName(): string
  {
    return 'users';
  }


  public function rules() : array {
    return [
      'firstname' => [self::RULE_REQUIRED],
      'lastname' => [self::RULE_REQUIRED],
      'login' => [self::RULE_REQUIRED],
      'email' => [self::RULE_REQUIRED, self::RULE_EMAIL, [self::RULE_UNIQUE, 'class' => self::class]],
      'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => '6'], [self::RULE_MAX, 'max' => '50']],
      'password_confirm' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'password']],
    ];
  }

  public function attributes() : array {
    return ['firstname', 'lastname', 'login', 'email', 'password', 'status'];
  }

  public function save() : bool {
    $this->password = password_hash($this->password, PASSWORD_DEFAULT);
    return parent::save();
  }

}