<?php

namespace app\core;

class Request {
     public function getPath() { // берем путь пользователя на странице
          $path = $_SERVER['REQUEST_URI'] ?? '/';
          $position = strpos($path, '?');
          if($position !== false) {
               return substr($path, 0, $position);
          }
          return $path;
     }

     public function getMethod() { // достаем метод запроса
          return strtoupper($_SERVER['REQUEST_METHOD']) ?? false;
     }

     public function isPost() {
          return $this->getMethod() === 'POST';
     }

     public function isGet() {
          return $this->getMethod() === 'GET';
     }


     public function getBody(){ // достает тело запроса
          $body = [];
          if($this->isGet()){
               foreach($_GET as $key => $value) {
                    $body[$key] = $value;
               }
          } else if($this->isPost()) {
               foreach($_POST as $key => $value) {
                    $body[$key] = $value;
               }
          }

          return $body;
     }
}