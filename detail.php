<?php
require_once "utils/mysql.php";

session_start();

$login = isset($_SESSION['user_id']);
$conn = connect_mysql();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <title>文章详情</title>
  <link rel="stylesheet" href="static/css/normalize.css">
  <link rel="stylesheet" href="static/css/style.css">
</head>
<body>
  <nav class="space-between" style="font-size: 20px">
    <div class="nav-left">博客</div>
    <div class="nav-right">
        <?php
        if ($login) {
            $sql = "SELECT `username` FROM `user` WHERE `id` = ?";
            $username = query_one_str($conn, $sql, $_SESSION['user_id'], "username");
            echo "<span>你好👋 $username</span>";
            echo "&nbsp;&nbsp;&nbsp;&nbsp;";
            echo "<button id='logout'>退出登录</button>";
        } else {
            echo "<button id='login'>登录</button>";
        }
        ?>
    </div>
  </nav>
  <script type="module" src="static/js/nav.js"></script>
  <hr>
  <main>
      <?php
      $article_id = $_GET["id"];
      $sql = "SELECT `title`, `content`, `author_id`, `type_id`, `update_time` FROM `article` WHERE `id` = '$article_id'";
      $result = $conn->query($sql);

      if ($result->num_rows === 1) {
          $row = $result->fetch_assoc();
          $title = $row["title"];
          $content = $row["content"];
          $author_id = $row["author_id"];
          $type_id = $row["type_id"];
          $update_time = $row["update_time"];
          // 标题行
          echo "<div class='margin-top-large margin-bottom-small'>";
          echo "<h2>$title</h2>";
          echo "</div>";
          // 信息行
          echo "<div class='space-between padding-bottom-small border-bottom'>";
          $sql = "SELECT `username` FROM `user` WHERE `id` = ?";
          $username = query_one_str($conn, $sql, $author_id, "username");
          $sql = "SELECT `name` FROM `type` WHERE `id` = ?";
          $typename = query_one_str($conn, $sql, $type_id, "name");
          echo "<span>作者:$username&nbsp;&nbsp;分类:$typename</span>";
          echo "<span>时间:$update_time</span>";
          echo "</div>";
          // 内容区域
          echo "<div class='font-normal margin-top-large'>$content</div>";
      }
      ?>
  </main>
</body>
</html>

<?php
$conn->close();
?>

