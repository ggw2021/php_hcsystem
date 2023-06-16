<?php
// 引入数据库连接文件
require_once "db.php";

// 获取传递过来的老师ID参数
$teacher_id = $_GET["teacher_id"];

// 查询该老师上传的课件
$sql = "SELECT * FROM courses WHERE uploader_id=?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$teacher_id]);
$courses = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>下载课件</title>
</head>
<body>
  <a href="./student.php">返回</a>
  <h1>下载课件</h1>

  <?php foreach ($courses as $course) { ?>
    <?php
        $download_path = './data/courses/' . $course["file_path"];
    ?>
    <div>
      <h4><?php echo $course["title"] ?></h4>
      <p>上传时间：<?php echo $course["upload_time"] ?></p>
      <a href="<?php echo $download_path ?>" download>下载文件</a>
    </div>
  <?php } ?>
</body>
</html>
