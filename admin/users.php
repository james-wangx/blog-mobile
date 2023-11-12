<?php
require_once "../utils/mysql.php";

session_start();

$login = isset($_SESSION['user_id']) ?? die("请先登录");
$conn = connect_mysql();

if (isset($_GET["id"])) {
    $userid = $_GET["id"];
    $sql = "DELETE FROM `user` WHERE `id` = '$userid'";
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
  <title>用户管理</title>
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
      <h2>用户列表</h2>
        <?php
        $userid = $_SESSION["user_id"];
        $sql = "SELECT `role` FROM `user` WHERE `id` = '$userid'";
        $role = $conn->query($sql)->fetch_assoc()["role"];

        // 非管理员不可以添加用户
        if ($role === "admin") {
            echo "<a style='display: flex; align-items: center;' href='user-input.php'>添加用户</a>";
        }
        ?>
    </div>

    <table>
      <thead>
        <tr>
          <th>用户名</th>
          <th>密码</th>
          <th>角色</th>
          <th>加入时间</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
          <?php
          // 非管理员不能修改其他用户
          if ($role === "admin") {
              $sql = "SELECT * FROM `user` ORDER BY `join_time`";
          } else {
              $sql = "SELECT * FROm `user` WHERE `id` = '$userid'";
          }
          $result = $conn->query($sql);
          if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                  $id = $row["id"];
                  $username = $row["username"];
                  $password = $row["password"];
                  $role = $row["role"];
                  $join_time = $row["join_time"];
                  echo "<tr>";
                  echo "<td>$username</td>";
                  echo "<td>$password</td>";
                  echo "<td>$role</td>";
                  echo "<td>$join_time</td>";
                  echo "<td style='padding: 0;'><a href='user-input.php?id=$id'>修改</a>&nbsp;
                        <a href='users.php?id=$id' '>删除</a></td>";
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
