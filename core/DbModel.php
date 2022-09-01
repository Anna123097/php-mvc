<?php

namespace app\core;

// класс для связи всех моделей с БД
abstract class DbModel extends Model {

  abstract public static function tableName() : string; // здесь будет указано название таблицы с которой модель будет работать
  abstract public function attributes() : array; // поля с которыми мы будем работать
  
  public static function primaryKey() : string { // уникальный ключ табоицы(пока айди)
    return 'id';
  }

  public function save() : bool { // функция для сохранения записи в БД
    $tableName = $this->tableName(); // берем название таблицы
    $attributes = $this->attributes(); // берем аттрибуты
    $params = array_map(fn($param) => ":$param", $attributes); // соединяем 

    // готовим запрос к БД
    $statement = self::prepare("INSERT INTO $tableName (".implode(', ', $attributes).") VALUES (".implode(', ', $params).")");

    foreach($attributes as $attribute) {
      $statement->bindValue(":$attribute", $this->{$attribute}); // биндим значения(для защиты от аттак на БД)
    }

    $statement->execute(); // выполняем сам запрос

    return true;
  }

  public static function findOne($where) { // [email => 'asd@asd.asd', login => 'asd']
    $tableName = static::tableName();
    $attribute = array_keys($where); // ['email', 'login']
    $sql = implode("AND ", array_map(fn($attr) => "$attr = :$attr", $attribute)); // 'email = :email AND login = :login'
    $statement = self::prepare("SELECT * FROM $tableName WHERE $sql");
    foreach($where as $key => $value) {
      $statement->bindValue(":$key", $value);
    }
    $statement->execute();
    return $statement->fetchObject(static::class);



    // SELECT * FROM $tablename WHERE email = :email AND login = :login


  } 

  public static function prepare(string $sql) { // функция для подготовки запроса
    return Application::$app->database->pdo->prepare($sql);
  }
}