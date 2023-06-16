<?php
// 启用 session
session_start();

// 如果没有登录，跳转到登录页面
if (!isset($_SESSION["student_id"])) {
  header("Location: login.php");
  exit;
}

// 引入数据库连接文件
require_once "db.php";

// 获取当前登录学生的ID
$student_id = $_SESSION["student_id"];

// 处理上传文件请求
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // 获取上传的文件
  $file = $_FILES['homework'];

  // 检查文件是否上传成功
  if ($file['error'] !== UPLOAD_ERR_OK) {
      die('文件上传失败');
  }

  // 生成唯一的文件名
  $filename = uniqid() . '_' . $file['name'];

  // 将文件保存到指定目录
  $targetPath = './data/homeworks/' . $filename;
  if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
      die('文件保存失败');
  }

  // 获取作业标题
  $title = $_POST['title'];

  // 获取提交时间
  $submitTime = date('Y-m-d H:i:s');

  // 将文件路径、作业标题、提交时间和提交者信息保存到数据库中
  $sql = "INSERT INTO homeworks (title, submit_time, file_path, submitter_id)
          VALUES (?, ?, ?, ?)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$title, $submitTime, $filename, $student_id]);

  // 提交成功，跳转回学生主页
  header("Location: student.php");
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>提交作业</title>
</head>
<body>
  <!-- 添加退出登录的按钮 -->
  <a href="logout.php">退出登录</a>

  <h1>提交作业</h1>

  <form method="post" enctype="multipart/form-data">
    <div>
      <label>文件：</label>
      <input type="file" name="homework" accept=".doc,.docx" required>
    </div>
    <div>
      <label>标题：</label>
      <input type="text" name="title" required>
    </div>
    <div>
      <button type="submit">提交</button>
    </div>
  </form>
</body>
</html>
