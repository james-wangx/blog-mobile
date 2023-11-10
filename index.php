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
  <title>首页</title>
  <link rel="stylesheet" href="static/css/normalize.css">
  <link rel="stylesheet" href="static/css/style.css">
</head>
<body>
  <nav class="space-between">
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
  <script src="static/js/nav.js"></script>
  <hr>
  <main>
      <?php
      $result = $conn->query("SELECT `id`, `title`, `author_id`, `type_id`, `update_time` FROM `article`");
      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              $id = $row["id"];
              $title = $row["title"];
              $author_id = $row["author_id"];
              $type_id = $row["type_id"];
              $update_time = $row["update_time"];
              // 标题行
              echo "<div class='margin-top-large margin-bottom-small'>";
              echo "<a href='detail.php?id=$id'><h2>$title</h2></a>";
              echo "</div>";
              // 信息行
              echo "<div class='border-bottom space-between padding-bottom-small'>";
              $sql = "SELECT `username` FROM `user` WHERE `id` = ?";
              $username = query_one_str($conn, $sql, $author_id, "username");
              $sql = "SELECT `name` FROM `type` WHERE `id` = ?";
              $typename = query_one_str($conn, $sql, $type_id, "name");
              echo "<span>作者:$username&nbsp;&nbsp;分类:$typename</span>";
              echo "<span>时间:$update_time</span>";
              echo "</div>";
          }
      }
      ?>
  </main>
</body>
</html>

<?php
$conn->close();
?>
