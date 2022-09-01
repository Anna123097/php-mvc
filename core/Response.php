<?php
namespace app\core;

  class Response{
    public function setStatusCode(int $code){ // установить код
      http_response_code($code);
    }

    public function redirect($path) { // функция для редиректа
      header("location: $path");
      exit();
    }
  }