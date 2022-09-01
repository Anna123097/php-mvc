<?php

namespace app\core;

class Session {
  protected const FLASH_KEY = 'flash_messages';

  public function __construct() { 
    session_start(); // начинаем сессию

    $flashMessages = $_SESSION[self::FLASH_KEY] ?? []; 
    
    // устанавливаем всем флешам метку для удаления
    foreach($flashMessages as $key => &$value) { 
      $value['removed'] = true;
    }
    $_SESSION[self::FLASH_KEY] = $flashMessages;
  
  }

  public function setFlash(string $key, string $value) { // функция для установки флеша
    $_SESSION[self::FLASH_KEY][$key] = [
      'value' => $value,
      'removed' => false
    ];
  }

  public function getFlash(string $key) { // функция для получения флеша

    return $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;
  }

  public function set(string $key, string $value) { // функция для установки самой сессии
    $_SESSION[$key] = $value;
  }

  public function get(string $key) { // функция для получения самой сессии
    return $_SESSION[$key] ?? false;
  }

  public function remove($key) { // функция для удаления самой сессии
    unset($_SESSION[$key]);
  }

  public function __destruct() { // когда страница закрывается, удаляем все флеша
    $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
    foreach($flashMessages as $key => &$value) {
      if($value['removed']) {
        unset($flashMessages[$key]);
      }
    }
    $_SESSION[self::FLASH_KEY] = $flashMessages;
  }


}