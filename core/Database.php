<?php 
namespace app\core;

class Database {
  public \PDO $pdo;

  public function __construct($config) {
    $this->pdo = new \PDO($config['dsn'], $config['user'], $config['password']); // подключение к БД(данные беруются из .env)
    $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION); // вывод ошибок(если будут)
  }

  // главная функция для миграций
  public function applyMigrations() {
    $this->createMigrationTable(); // создаем таблицу(если еще не существует)
    $migrations = $this->getAppliedMigrations(); // берем все миграции из таблицы

    $files = scandir(Application::$ROOT_DIR.'/migrations'); // сканируем дирректироию с миграциями(локально)
    $toAplyMigrations = array_diff($files, $migrations); // находим каких миграций не хватает в БД
    $newMigrations = []; // массив с названиями файлов миграций(будет добавляться позже)

    foreach($toAplyMigrations as $migration) { 
      if($migration === '.' || $migration === '..') { // отсеиваем ненужные названия(по дефолту)
        continue;
      }
      require_once Application::$ROOT_DIR.'/migrations/'.$migration; // подключаем файл миграции
      $className = pathinfo($migration, PATHINFO_FILENAME); // берем название клсса из названия файла
      $instance = new $className(); // создаем экземпляр класса
      $instance->up();
      $newMigrations[] = $migration;
    }

    if(!empty($newMigrations)) { // если не пустой массив с новыми миграциями
      $this->saveMigrations($newMigrations); // вызываем функцию для создания этих миграций
    } else {
      $this->log("All migrations were applied"); // выводим сообщение если все миграции уже были применены
    }


  }

  // функция создания таблицы миграций(если не существует)
  public function createMigrationTable() {
    $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
      id INT AUTO_INCREMENT PRIMARY KEY,
      migration VARCHAR(255),
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=INNODB");
  }

  // функция выборки всех существующих миграций из таблицы
  public function getAppliedMigrations() {
    $command = $this->pdo->prepare("SELECT migration FROM migrations");
    $command->execute();

    return $command->fetchAll(\PDO::FETCH_COLUMN);
  }

// сохраняем миграции в таблице
  public function saveMigrations($migrations) {
    $str = implode(", ", array_map(fn($m) => "('$m')", $migrations)); // делаем из массива строку для запроса
    $query = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $str");
    $query->execute();
  }

  // функция логгирования
  protected function log($message) {
    echo '[' . date("Y-m-d H:i:s") . '] - ' . $message; 
  }



}

