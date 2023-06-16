# 项目设计

1. 注册、登录
   + 学生
   + 老师
2. 学生功能：
   + 上传作业（word）
   + 下载老师的课件（PPT）
3. 老师功能：
   + 下载学生的作业
   + 上传课件

**完全由chatGPT实现代码编写**



# 实现

## 数据库设计

我们可以设计以下四个表：

1. `users` 表：用于存储用户信息，包括用户ID、用户名、密码和用户权限等字段。

   ```mysql
   CREATE TABLE users (
     id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
     username VARCHAR(255) NOT NULL UNIQUE,
     password VARCHAR(255) NOT NULL,
     role ENUM('student', 'teacher') NOT NULL
   );
   ```

2. `students` 表：用于存储学生的信息，包括学生ID、姓名和所属班级等字段。

   ```mysql
   CREATE TABLE students (
     id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
     name VARCHAR(255) NOT NULL,
     class VARCHAR(255) NOT NULL
   );
   ```

3. `teachers` 表：用于存储老师的信息，包括老师ID、姓名和所属学院等字段。

   ```mysql
   CREATE TABLE teachers (
     id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
     name VARCHAR(255) NOT NULL,
     college VARCHAR(255) NOT NULL
   );
   ```

4. `homeworks` 表：用于存储作业信息，包括作业ID、题目、提交时间、文件路径和提交者等字段。

   ```mysql
   CREATE TABLE homeworks (
     id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
     title VARCHAR(255) NOT NULL,
     submit_time DATETIME NOT NULL,
     file_path VARCHAR(255) NOT NULL,
     submitter_id INT UNSIGNED NOT NULL,
     FOREIGN KEY (submitter_id) REFERENCES users(id)
   );
   ```

5. `courses` 表：用来存储课件信息，包括课件ID、标题、上传时间、文件路径和上传者等字段。

   ```mysql
   CREATE TABLE courses (
     id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
     title VARCHAR(255) NOT NULL,
     upload_time DATETIME NOT NULL,
     file_path VARCHAR(255) NOT NULL,
     uploader_id INT UNSIGNED NOT NULL,
     FOREIGN KEY (uploader_id) REFERENCES users(id)
   );
   ```

其中，`users` 表是所有用户的基础表，它包含了所有用户的登录信息。`students` 和 `teachers` 表则分别记录学生和老师的具体信息。`homeworks` 表用于存储作业信息，并且有一个外键关联到 `users` 表中的用户ID字段，用于表示该作业是由哪个用户提交的。其中，`courses` 表也需要有一个外键关联到 `users` 表中的用户ID字段，用于表示该课件是由哪个用户上传的。



## PHP

**文件结构**：

```
\
│  .htaccess
│  db.php
│  index.php
│  login.php
│  logout.php
│  nginx.htaccess
│  README.md
│  register.php
│  student.php
│  student_download.php
│  student_upload.php
│  teacher.php
│  teacher_download.php
│  teacher_upload.php
│  xpl.sql
│
├─css	# 本项目没有做这块内容
├─data
│  ├─courses	# 老师上传的课件
│  └─homeworks	# 学生上传的作业
└─js	# 本项目没有做这块内容
```

`index.php`：主页

`db.php`：用于数据库连接

`register.php`：注册

`login.php`：登录

`student.php`：学生主页

+ 显示学生信息，修改学生信息
+ 学生已经提交的作业信息
+ 提交新文件按钮进入`student_upload.php`
+ 老师的列表，点击老师的名字，可以进入这个老师相关的`student_download.php`，让学生下载ppt

`logout.php`：退出登录

`student_upload.php`：上传作业，word格式

`student_download.php`：根据`student.php`点击的老师，显示该老师上传的文件

`teacher.php`：老师主页

+ 显示老师信息
+ 老师已经上传的课件信息
+ 提交新文件按钮进入`student_upload.php`
+ 学生的列表，点击学生的名字，可以进入这个学生相关的`teacher_download.php`，让老师下载学生的作业

`teacher_upload.php`：上传课件，ppt格式

`teacher_download.php`：根据`teacher.php`点击的学生，显示该学生上传的作业


