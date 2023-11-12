<?php
require_once "../utils/mysql.php";

session_start();

$login = isset($_SESSION['user_id']) ?? die("请先登录");
$conn = connect_mysql();
$update = false;

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    // 判断当前页面是 修改页面 还是 新增页面
    if (isset($_GET["id"])) {
        $update = true;
        $id = $_GET["id"];
        $sql = "SELECT * FROM `type` WHERE `id` = '$id'";
        $result = $conn->query($sql);

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $name = $row["name"];
            $desc = $row["desc"];
            $create_time = $row["create_time"];
            $update_time = $row["update_time"];
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $desc = $_POST["desc"];

    // 判断提交的表单将进行 修改 还是 新增
    if (isset($_POST["id"])) {
        $id = $_POST["id"];
        $create_time = $_POST["create_time"];
        $sql = "UPDATE `type` SET 
                  `name` = '$name', 
                  `desc` = '$desc', 
                  `create_time` = '$create_time', 
                  `update_time` = NOW()
              WHERE `id` = '$id'";
        echo $sql;
        $conn->query($sql);
    } else {
        $sql = "INSERT INTO `type` (`id`, `name`, `desc`) 
                VALUE (REPLACE(UUID(), '-', ''), '$name', '$desc')";
        $conn->query($sql);
    }
    header("location: types.php");
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title><?php if ($update) echo "更新"; else echo "添加"; ?>分类信息</title>
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
  <h2><?php if ($update) echo "更新"; else echo "添加"; ?>分类信息</h2>
  <main>
    <form action="type-input.php" method="post">
        <?php
        if ($update) {
            echo "<input type='hidden' name='id' value='$id'>";
        }
        ?>
      <label for="name">
        分类名
        <input id="name" type="text" name="name" value="<?php if ($update) echo $name; ?>" required>
      </label>
      <br>
      <br>
      <label for="desc">
        分类描述
        <input id="desc" type="text" name="desc" value="<?php if ($update) echo $desc; ?>" required>
      </label>
        <?php
        if ($update) {
            echo "
                  <br>
                  <br>
                  <label for='create-time'>
                    创建时间
                    <input id='create-time' type='datetime-local' name='create_time' value='$create_time'>
                  </label>
                ";
        }
        ?>
      <br>
      <br>
      <button type="submit">提交</button>
    </form>
  </main>
</body>
</html>

<?php
$conn->close();
?>
