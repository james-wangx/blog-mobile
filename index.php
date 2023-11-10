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
    <nav>
      <div class="nav-left">博客</div>
      <div class="nav-right">
          <?php
          if ($login) {
              $sql = "SELECT `username` FROM `user` WHERE `id` = ?";
              $username = query_one_str($conn, $sql, $_SESSION['user_id'], "username");
              echo "<span>$username</span>";
              echo "<button class='logout'>退出登录</button>";
          } else {
              echo "<button class='login'>登录</button>";
          }
          ?>
      </div>
    </nav>
    <main>
      <table>
        <tbody>
            <?php
            $result = $conn->query("SELECT `id`, `title`, `author_id`, `type_id`, `update_time` FROM `article`");
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // 标题行
                    echo "<tr>";
                    echo "<td>" . $row["title"] . "</td>";
                    echo "</tr>";
                    // 信息行
                    echo "<tr>";
                    $sql = "SELECT `username` FROM `user` WHERE `id` = ?";
                    $username = query_one_str($conn, $sql, $row["author_id"], "username");
                    $sql = "SELECT `name` FROM `type` WHERE `id` = ?";
                    $typename = query_one_str($conn, $sql, $row["type_id"], "name");
                    echo "<td>作者：$username</td>";
                    echo "<td>分类：$typename</td>";
                    echo "<td>时间：" . $row["update_time"] . "</td>";
                    echo "</tr>";
                }
            }
            ?>
        </tbody>
      </table>
    </main>
    <script src="static/js/index.js"></script>
  </body>
  </html>

<?php
$conn->close();
?>