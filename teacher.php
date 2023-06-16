<?php
// 启用 session
session_start();

// 如果没有登录，跳转到登录页面
if (!isset($_SESSION["teacher_id"])) {
  header("Location: login.php");
  exit;
}

// 引入数据库连接文件
require_once "db.php";

// 获取当前登录老师的ID
$teacher_id = $_SESSION["teacher_id"];

// 查询老师信息
$sql = "SELECT * FROM teachers WHERE id=?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$teacher_id]);
$teacher = $stmt->fetch();

// 获取老师姓名和学院信息
$name = $teacher["name"];
$college = $teacher["college"];

// 查询老师已上传课件信息
$sql = "SELECT * FROM courses WHERE uploader_id=?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$teacher_id]);
$courses = $stmt->fetchAll();

// 查询学生列表
$sql = "SELECT * FROM students";
$stmt = $pdo->query($sql);
$students = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>老师主页</title>
</head>
<body>
  <!-- 添加退出登录的按钮 -->
  <a href="logout.php">退出登录</a>

  <h1>老师主页</h1>

  <h2>欢迎，<?php echo $name ?>！</h2>

  <h3><?php echo $college ?></h3>

  <h2>已上传的课件</h2>
  <?php if (count($courses) > 0) { ?>
    
    <ul>
      <?php foreach ($courses as $course) { ?>
        <li>
          <a href="<?php echo $course["file_path"] ?>"><?php echo $course["title"] ?></a>
          （提交时间：<?php echo $course["upload_time"] ?>）
        </li>
      <?php } ?>
    </ul>
  <?php } else { ?>
    <p>暂无已上传的课件</p>
  <?php } ?>

  <a href="teacher_upload.php">提交新文件</a>

  <h2>学生列表</h2>
  <ul>
    <?php foreach ($students as $student) { ?>
      <li>
        <a href="teacher_download.php?student_id=<?php echo $student["id"] ?>"><?php echo $student["name"] ?></a>
      </li>
    <?php } ?>
  </ul>
</body>
</html>
