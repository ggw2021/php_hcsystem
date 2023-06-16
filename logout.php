<?php
// 启用 session
session_start();

// 清空 session 和 cookie
session_unset();
session_destroy();
setcookie(session_name(), '', time() - 86400, '/');

// 跳转到主界面
header("Location: index.php");
exit;
?>