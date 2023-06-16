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

// 查询学生信息
$sql = "SELECT * FROM students WHERE id=?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$student_id]);
$student = $stmt->fetch();

// 获取学生姓名和班级信息
$name = $student["name"];
$class = $student["class"];

// 如果表单已经提交，更新学生信息
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // 获取表单提交的数据
  $new_name = $_POST["name"];
  $new_class = $_POST["class"];

  // 更新学生信息到 students 表
  $sql = "UPDATE students SET name=?, class=? WHERE id=?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$new_name, $new_class, $student_id]);

  // 更新成功，重新查询学生信息，并提示用户
  $sql = "SELECT * FROM students WHERE id=?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$student_id]);
  $student = $stmt->fetch();
  $name = $student["name"];
  $class = $student["class"];
  echo "学生信息已经更新";
}

// 查询学生已经提交的作业信息
$sql = "SELECT * FROM homeworks WHERE submitter_id=?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$student_id]);
$homeworks = $stmt->fetchAll();

// 查询老师列表
$sql = "SELECT * FROM teachers";
$stmt = $pdo->query($sql);
$teachers = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>学生主页</title>
</head>
<body>
  <!-- 添加退出登录的按钮 -->
  <a href="logout.php">退出登录</a>

  <h1>学生主页</h1>

  <h2>学生信息</h2>
  <form method="post">
    <div>
      <label>姓名：</label>
      <input type="text" name="name" value="<?php echo $name ?>" required>
    </div>
    <div>
      <label>班级：</label>
      <input type="text" name="class" value="<?php echo $class ?>">
    </div>
    <div>
      <button type="submit">更新</button>
    </div>
  </form>

  <h2>已提交的作业</h2>
  <?php if (count($homeworks) > 0) { ?>
    <ul>
      <?php foreach ($homeworks as $homework) { ?>
        <li>
          <a href="<?php echo $homework["file_path"] ?>"><?php echo $homework["title"] ?></a>
          （提交时间：<?php echo $homework["submit_time"] ?>）
        </li>
      <?php } ?>
    </ul>
  <?php } else { ?>
    <p>暂无已提交的作业</p>
  <?php } ?>

  <a href="student_upload.php">提交新文件</a>

  <h2>老师列表</h2>
  <ul>
    <?php foreach ($teachers as $teacher) { ?>
      <li>
        <a href="student_download.php?teacher_id=<?php echo $teacher["id"] ?>"><?php echo $teacher["name"] ?></a>
      </li>
    <?php } ?>
  </ul>
</body>
</html>
