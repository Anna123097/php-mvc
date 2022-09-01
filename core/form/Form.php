<?php
namespace app\core\form;
use app\core\form\Field;

  class Form{
    public Field $field;

    public static function begin(){
      echo "<form method='POST'>";
      return new Form();
    }
    public static function end(){
      return "</form>";
    }
    public function field($model, $atribute, $title = '', $placeholder = ''){
      $field = new Field($model, $atribute, $title, $placeholder);
      return $field;
    }
  }