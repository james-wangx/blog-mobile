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
        $sql = "SELECT * FROM `article` WHERE `id` = '$id'";
        $result = $conn->query($sql);

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $title = $row["title"];
            $content = $row["content"];
            $author_id = $row["author_id"];
            $type_id = $row["type_id"];
            $create_time = $row["create_time"];
            $update_time = $row["update_time"];
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST["title"];
    $content = $_POST["content"];
    $author_id = $_POST["author_id"];
    $type_id = $_POST["type_id"];

    // 判断提交的表单将进行 修改 还是 新增
    if (isset($_POST["id"])) {
        $id = $_POST["id"];
        $create_time = $_POST["create_time"];
        $sql = "UPDATE `article` SET 
                    `title` = '$title', 
                    `content` = '$content', 
                    `author_id` = '$author_id', 
                    `type_id` = '$type_id', 
                    `create_time` = '$create_time', 
                    `update_time` = NOW()
              WHERE `id` = '$id'";
        echo $sql;
        $conn->query($sql);
    } else {
        $sql = "INSERT INTO `article` (`id`, `title`, `content`, `author_id`, `type_id`) 
                VALUE (REPLACE(UUID(), '-', ''), '$title', '$content', '$author_id', '$type_id')";
        echo $sql;
        $conn->query($sql);
    }
    header("location: articles.php");
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title><?php if ($update) echo "更新"; else echo "添加"; ?>文章信息</title>
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
  <h2><?php if ($update) echo "更新"; else echo "添加"; ?>文章信息</h2>
  <main>
    <form action="article-input.php" method="post">
        <?php
        if ($update) {
            echo "<input type='hidden' name='id' value='$id'>";
        }
        ?>
      <label for="title">
        标题
        <input id="title" type="text" name="title" value="<?php if ($update) echo $title; ?>" required>
      </label>
      <br>
      <br>
      <label for="content">
        内容
        <textarea id="content" name="content"><?php if ($update) echo $content; ?></textarea>
      </label>
      <br>
      <br>
      <label for="author">
        作者
        <select id="author" name="author_id">
            <?php
            $userid = $_SESSION["user_id"];
            $sql = "SELECT `role` FROM `user` WHERE `id` = '$userid'";
            $role = $conn->query($sql)->fetch_assoc()["role"];

            // 非管理员不可以添加用户
            if ($role === "admin") {
                $sql = "SELECT `id`, `username` FROM `user` ORDER BY `join_time`";
            } else {
                $sql = "SELECT `id`, `username` FROM `user` WHERE `id` = '$userid' ORDER BY `join_time`";
            }
            $result = $conn->query($sql);
            // 如果是修改页面，默认显示当前作者
            if ($update) {
                $sql = "SELECT `username` FROM `user` WHERE `id` = '$author_id'";
                $author_name = $conn->query($sql)->fetch_assoc()["username"];
                echo "<option value='$author_id'>$author_name</option>";
            }
            while ($row = $result->fetch_assoc()) {
                $author_id_opt = $row["id"];
                $author_name_opt = $row["username"];
                if ($update) {
                    if ($author_name !== $author_name_opt) {
                        echo "<option value='$author_id_opt'>$author_name_opt</option>";
                    }
                } else {
                    echo "<option value='$author_id_opt'>$author_name_opt</option>";
                }
            }
            ?>
        </select>
      </label>&nbsp;&nbsp;&nbsp;&nbsp;
      <label for="type">
        类型
        <select id="type" name="type_id">
            <?php
            $sql = "SELECT `id`, `name` FROM `type` ORDER BY `update_time` DESC";
            $result = $conn->query($sql);
            // 如果是修改页面，默认显示当前分类
            if ($update) {
                $sql = "SELECT `name` FROM `type` WHERE `id` = '$type_id'";
                $type_name = $conn->query($sql)->fetch_assoc()["name"];
                echo "<option value='$type_id'>$type_name</option>";
            }
            while ($row = $result->fetch_assoc()) {
                $type_id_opt = $row["id"];
                $type_name_opt = $row["name"];
                if ($update) {
                    if ($type_name !== $type_name_opt) {
                        echo "<option value='$type_id_opt'>$type_name_opt</option>";
                    }
                } else {
                    echo "<option value='$type_id_opt'>$type_name_opt</option>";
                }

            }
            ?>
        </select>
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
