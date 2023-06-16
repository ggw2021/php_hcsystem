<?php
$host = "localhost";
$username = "root";
$password = "123456";
$dbname = "xpl";

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "连接数据库失败：" . $e->getMessage();
}
?>