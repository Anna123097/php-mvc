<?php

namespace app\core\form;

use app\core\Model;

class Field
{
  public Model $model;
  public string $attribute;
  public string $type;
  public string $title;
  public string $placeholder;


  public function __construct($model, $attribute, $title, $placeholder)
  {
    $this->attribute = $attribute;
    $this->model = $model;
    $this->title = $title;
    $this->placeholder = $placeholder;
    $this->type = 'text';
  }

  public function __toString()
  {
    return sprintf(
      "
      <div class='form-group'>
        <label>%s</label>
        <input type='%s' name='%s' value='%s' class='input_field status-%s' placeholder='%s'>
        <div class='field-error'>
          %s
        </div>
      </div>
      ",
      $this->title,
      $this->type,
      $this->attribute,
      $this->model->{$this->attribute},
      $this->model->hasError($this->attribute) ? 'error' : 'success',
      $this->placeholder,
      $this->model->hasError($this->attribute) ? $this->model->errors[$this->attribute][0] : ''
    );
  }
  public function passwordField(){
    $this->type = "password";
    return $this; 
  }
}
