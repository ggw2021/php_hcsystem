<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>注册</title>
</head>
<body>
  <h1>注册</h1>

  <form method="post">
    <div>
      <label>用户名：</label>
      <input type="text" name="username" required>
    </div>
    <div>
      <label>密码：</label>
      <input type="password" name="password" required>
    </div>
    <div>
      <label>用户类型：</label>
      <select name="role" required>
        <option value="student">学生</option>
        <option value="teacher">教师</option>
      </select>
    </div>
    <div>
      <label>班级（仅限学生）：</label>
      <input type="text" name="class">
    </div>
    <div>
      <label>学院（仅限教师）：</label>
      <input type="text" name="college">
    </div>
    <div>
      <button type="submit">注册</button>
    </div>
  </form>

  <?php
  // 如果表单已经提交，处理注册请求
  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 引入数据库连接文件
    require_once "db.php";

    // 获取表单提交的数据
    $username = $_POST["username"];
    $password = $_POST["password"];
    $role = $_POST["role"];

    // 插入用户信息到 users 表
    $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username, $password, $role]);

    // 获取插入的用户ID
    $user_id = $pdo->lastInsertId();

    // 根据用户类型，插入相应的表中
    if ($role === "student") {
      // 如果是学生，插入到 students 表
      $class = $_POST["class"];
      $sql = "INSERT INTO students (id, name, class) VALUES (?, ?, ?)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$user_id, $username, $class]);
    } else if ($role === "teacher") {
      // 如果是教师，插入到 teachers 表
      $college = $_POST["college"];
      $sql = "INSERT INTO teachers (id, name, college) VALUES (?, ?, ?)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$user_id, $username, $college]);
    }

    // 注册成功，跳转到登录页面
    header("Location: login.php");
    exit;
  }
  ?>
</body>
</html>
