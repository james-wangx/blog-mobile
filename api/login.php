<?php
require_once "../utils/mysql.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    session_start();

    $username = $_POST["username"];
    $password = $_POST["password"];

    $conn = connect_mysql();
    $sql = "SELECT `id` FROM `user` WHERE `username` = ? AND `password` = ?";
    $user_id = query_two_str($conn, $sql, $username, $password, "id");

    if ($user_id === null) {
        $response = ["status" => 400, "message" => "用户名或密码错误", "data" => null];
    } else {
        $response = ["status" => 200, "message" => "登录成功", "data" => null];
        $_SESSION["user_id"] = $user_id;
    }

    echo json_encode($response);
}
