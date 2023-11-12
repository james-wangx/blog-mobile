<?php
require_once "../utils/mysql.php";

session_start();

$login = isset($_SESSION['user_id']) ?? die("请先登录");
$conn = connect_mysql();

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $sql = "DELETE FROM `type` WHERE `id` = '$id'";
    $conn->query($sql);
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>类型管理</title>
  <link rel="stylesheet" href="../static/css/normalize.css">
  <link rel="stylesheet" href="../static/css/style.css">
  <style>
      table, th, td {
          border: 1px solid black;
          border-collapse: collapse;
      }

      th, td {
          padding: 5px;
      }
  </style>
</head>
<body>
  <nav class="space-between" style="font-size: 20px">
    <div class="nav-left"><a href="index.php">后台</a></div>
    <div class="nav-right">
        <?php
        $sql = "SELECT `username` FROM `user` WHERE `id` = ?";
        $username = query_one_str($conn, $sql, $_SESSION['user_id'], "username");
        echo "<span>你好👋 $username</span>";
        echo "&nbsp;&nbsp;&nbsp;&nbsp;";
        echo "<button id='logout'>退出登录</button>";
        echo "&nbsp;";
        echo "<button id='index'>首页</button>";
        ?>
    </div>
  </nav>
  <script type="module" src="../static/js/nav.js"></script>
  <main>
    <div class="space-between">
      <h2>分类列表</h2>
      <a style="display: flex; align-items: center;" href="type-input.php">添加分类</a>
    </div>
    <table>
      <thead>
        <tr>
          <th>分类名</th>
          <th>更新时间</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
          <?php
          $sql = "SELECT `id`, `name`, `update_time` FROM `type` ORDER BY `update_time` DESC ";
          $result = $conn->query($sql);
          if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                  $id = $row["id"];
                  $name = $row["name"];
                  $update_time = $row["update_time"];
                  echo "<tr>";
                  echo "<td>$name</td>";
                  echo "<td>$update_time</td>";
                  echo "<td style='padding: 0;'><a href='type-input.php?id=$id'>修改</a>&nbsp;
                        <a href='types.php?id=$id' '>删除</a></td>";
                  echo "</tr>";
              }
          }
          ?>
      </tbody>
    </table>
  </main>
</body>
</html>

<?php
$conn->close();
?>
