<?php

namespace app\core\exceptions;

class ForbiddenExceptions extends \Exception {
  protected $message = 'You dont have permission';
  protected $code = 403;
  
}