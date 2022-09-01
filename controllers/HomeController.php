<?php
namespace app\controllers;

use app\core\Controller;
use app\core\Request;

class HomeController extends Controller {
  public function home(Request $request) { //  рендерим главную страницу
    $this->render('index');
  }
}