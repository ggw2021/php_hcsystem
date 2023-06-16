<?php
// 引入数据库连接文件
require_once "db.php";

// 获取传递过来的老师ID参数
$student_id = $_GET["student_id"];

// 查询该学生上传的作业
$sql = "SELECT * FROM homeworks WHERE submitter_id=?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$student_id]);
$homeworks = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>下载作业</title>
</head>
<body>
  <a href="./teacher.php">返回</a>
  <h1>下载作业</h1>

  <?php foreach ($homeworks as $homework) { ?>
    <?php
        $download_path = './data/homeworks/' . $homework["file_path"];
    ?>
    <div>
      <h4><?php echo $homework["title"] ?></h4>
      <p>上传时间：<?php echo $homework["upload_time"] ?></p>
      <a href="<?php echo $download_path ?>" download>下载文件</a>
    </div>
  <?php } ?>
</body>
</html>