<?php

class m0002_users {
  public function up() {
    $db = \app\core\Application::$app->database;
    $sql = "CREATE TABLE users (
      id INT AUTO_INCREMENT PRIMARY KEY,
      firstname VARCHAR(255) NOT NULL,
      lastname VARCHAR(255) NOT NULL,
      login VARCHAR(255) NOT NULL,
      email VARCHAR(255) NOT NULL,
      password VARCHAR(255) NOT NULL,
      status TINYINT NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
      ) ENGINE=INNODB";
      $db->pdo->exec($sql);

    echo "Creating users table...\n";
  }

  public function down() {
    $db = \app\core\Application::$app->database;
    $sql = "DELETE TABLE users;";
    $db->pdo->exec($sql);
    echo "Deleting users table...\n";

  }
}