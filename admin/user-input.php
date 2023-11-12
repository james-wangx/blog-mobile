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
        $sql = "SELECT * FROM `user` WHERE `id` = '$id'";
        $result = $conn->query($sql);

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $username = $row["username"];
            $password = $row["password"];
            $role = $row["role"];
            $age = $row["age"];
            $gender = $row["gender"];
            $join_time = $row["join_time"];
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $role = $_POST["role"];
    $age = $_POST["age"];
    $gender = $_POST["gender"];

    // 判断提交的表单将进行 修改 还是 新增
    if (isset($_POST["id"])) {
        $id = $_POST["id"];
        $join_time = $_POST["join_time"];
        $sql = "UPDATE `user` SET 
                  `username` = '$username', 
                  `password` = '$password', 
                  `role` = '$role', 
                  `age` = $age, 
                  `gender` = '$gender', 
                  `join_time` = '$join_time' 
              WHERE `id` = '$id'";
        $conn->query($sql);
    } else {
        $sql = "INSERT INTO `user` (`id`, `username`, `password`, `role`, `age`, `gender`) 
                VALUE (REPLACE(UUID(), '-', ''), '$username', '$password', '$role', $age, '$gender')";
        $conn->query($sql);
    }
    header("location: users.php");
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title><?php if ($update) echo "更新"; else echo "添加"; ?>用户信息</title>
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
  <h2><?php if ($update) echo "更新"; else echo "添加"; ?>用户信息</h2>
  <main>
    <form action="user-input.php" method="post">
        <?php
        if ($update) {
            echo "<input type='hidden' name='id' value='$id'>";
        }
        ?>
      <label for="username">
        用户名
        <input id="username" type="text" name="username" value="<?php if ($update) echo $username; ?>" required>
      </label>
      <br>
      <br>
      <label for="password">
        密码
        <input id="password" type="text" name="password" value="<?php if ($update) echo $password; ?>" required>
      </label>
      <br>
      <br>
      角色
      <label for="user">
        <input id="user" type="radio" name="role" value="user" required
            <?php if ($update && $role === "user") echo "checked"; ?>>
        user
      </label>
      <label for="administrator">
        <input id="administrator" type="radio" name="role" value="admin" required
            <?php if ($update && $role === "admin") echo "checked"; ?>>
        admin
      </label>
      <br>
      <br>
      <label for="age">
        年龄
        <input id="age" type="number" name="age" value="<?php if ($update) echo $age; ?>" required>
      </label>
      <br>
      <br>
      性别
      <label for="male">
        <input id="male" type="radio" name="gender" value="男"
            <?php if ($update && $gender === '男') echo "checked"; ?> required>
        男
      </label>
      <label for="female">
        <input id="female" type="radio" name="gender" value="女"
            <?php if ($update && $gender === '女') echo "checked"; ?> required>
        女
      </label>
        <?php
        if ($update) {
            echo "
                  <br>
                  <br>
                  <label for='join-time'>
                    <input id='join-time' type='datetime-local' name='join_time' value='$join_time'>
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
