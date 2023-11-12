<?php
require_once "../utils/mysql.php";

session_start();

$login = isset($_SESSION['user_id']) ?? die("请先登录");
$conn = connect_mysql();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <title>后台</title>
  <link rel="stylesheet" href="../static/css/normalize.css">
  <link rel="stylesheet" href="../static/css/style.css">
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
    <h2>欢迎来到博客后台页面</h2>
    <h3>您可以进行如下操作：</h3>
    <div class="space-between">
        <?php
        $user_id = $_SESSION["user_id"];
        $sql = "SELECT `role` FROM `user` WHERE `id` = '$user_id'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $role = $row["role"];
        if ($role === "admin") {
          echo "<button onclick=" . "window.location.href='users.php'" . ">用户管理</button>";
        }
        ?>
      <button>文章管理</button>
      <button>分类管理</button>
    </div>
  </main>
</body>
</html>

<?php
$conn->close();
?>
