<?php

namespace app\core;

abstract class Model
{
  // массив всех ошибок
  public array $errors = [];

  public const RULE_REQUIRED = 'required';
  public const RULE_EMAIL = 'email';
  public const RULE_MIN = 'min';
  public const RULE_MAX = 'max';
  public const RULE_MATCH = 'match';
  public const RULE_UNIQUE = 'unique';

  // загразка данных (с контроолера)
  public function loadData($data)
  {
    foreach ($data as $key => $value) {
      if (property_exists($this, $key)) {
        $this->{$key} = $value;
      }
    }
  }

  // указываем, что в дочерних классах должен быть метод rules
  abstract function rules() : array;


  // валидация данных с формы
  public function validate()
  {
    foreach ($this->rules() as $attr => $rules) {
      $value = $this->{$attr};

      foreach ($rules as $rule) {
        $ruleName = $rule;

        if (!is_string($ruleName)) {
          $ruleName = $ruleName[0];
        }

        if ($ruleName === self::RULE_REQUIRED && !$value) {
          $this->addError($attr, self::RULE_REQUIRED);
        }

        if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
          $this->addError($attr, self::RULE_EMAIL);
        }

        if ($ruleName === self::RULE_MIN && strlen($value) < $rule['min']) {
          $this->addError($attr, self::RULE_MIN, ['min' => $rule['min']]);
        }

        if ($ruleName === self::RULE_MAX && strlen($value) > $rule['max']) {
          $this->addError($attr, self::RULE_MAX, ['max' => $rule['max']]);
        }

        if ($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']}) {
          $this->addError($attr, self::RULE_MATCH);
        }


        if ($ruleName === self::RULE_UNIQUE) {

          $className = $rule['class'];
          $tableName = $className::tableName();

          $statement = Application::$app->database->pdo->prepare("SELECT * FROM $tableName WHERE $attr = :$attr");
          $statement->bindValue(":$attr", $value);
          $statement->execute();
          $record = $statement->fetchObject();
          if($record) {
            $this->addError($attr, self::RULE_UNIQUE);
          }
        }
      }
    }

    return empty($this->errors);
  }

  // добавление ошибок в массив
  protected function addError(string $attr, string $rule, array $options = [])
  {
    $message = $this->errorMessages()[$rule] ?? '';

    foreach ($options as $option => $value) {
      $message = str_replace("{{$option}}", $value, $message);
    }

    $this->errors[$attr][] = $message;
  }

  public function addCustomError(string $attr, string $errorText) {
    $this->errors[$attr][] = $errorText;
  }

  // проверить существуют ли ошибки для заданного поля
  public function hasError(string $key) : bool
  {
    return (bool) $this->errors[$key] ?? false;
  }

  // тексты всех ошибок(ключ => значение)
  public function errorMessages() : array
  {
    return [
      self::RULE_REQUIRED => 'This field is required',
      self::RULE_EMAIL => 'This field is not an email',
      self::RULE_MIN => 'min: {min}',
      self::RULE_MAX => 'max: {max}',
      self::RULE_MATCH => 'Fields doesn\'t match',
      self::RULE_UNIQUE => 'This field already exists',
    ];
  }
}
