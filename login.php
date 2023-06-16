<?php
// 启用 session
session_start();

// 如果已经登录，跳转到对应的主界面
if (isset($_SESSION["student_id"])) {
  header("Location: student.php");
  exit;
} else if (isset($_SESSION["teacher_id"])) {
  header("Location: teacher.php");
  exit;
}

// 引入数据库连接文件
require_once "db.php";

// 处理登录请求
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // 验证用户输入
  if (empty($username)) {
    $error = "请输入用户名";
  } else if (empty($password)) {
    $error = "请输入密码";
  } else {
    // 查询用户信息
    $sql = "SELECT * FROM users WHERE username=? AND password=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username, $password]);
    $user = $stmt->fetch();

    if ($user) {
      // 登录成功，根据用户角色跳转到相应的主界面
      if ($user["role"] == "student") {
        // 将学生ID存储在 session 中，并跳转到学生主页
        $_SESSION["student_id"] = $user["id"];
        header("Location: student.php");
        exit;
      } else if ($user["role"] == "teacher") {
        // 将老师ID存储在 session 中，并跳转到老师主页
        $_SESSION["teacher_id"] = $user["id"];
        header("Location: teacher.php");
        exit;
      }
    } else {
      // 用户名或密码错误，显示错误信息
      $error = "用户名或密码错误";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>登录</title>
</head>
<body>
  <h1>登录</h1>

  <?php if (isset($error)) { ?>
    <p style="color: red"><?php echo $error ?></p>
  <?php } ?>

  <form method="post" action="login.php">
    <label for="username">用户名：</label>
    <input type="text" id="username" name="username"><br>

    <label for="password">密码：</label>
    <input type="password" id="password" name="password"><br>

    <button type="submit" name="login">登录</button>
  </form>
</body>
</html>
