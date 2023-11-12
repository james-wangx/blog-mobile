<?php
require_once "../utils/mysql.php";

$conn = connect_mysql();
$username = $_POST["username"];
$password = $_POST["password"];

// 避免用户名重复
$sql = "SELECT `id` FROM `user` WHERE `username` = '$username'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $response = ["status" => 400, "message" => "用户名重复", "data" => null];
    echo json_encode($response);
} else {
    $sql = "INSERT INTO `user` (`id`, `username`, `password`)
        VALUE (REPLACE(UUID(), '-', ''),'$username', '$password')";
    $result = $conn->query($sql);
    $response = ["status" => 200, "message" => "success", "data" => null];
    echo json_encode($response);
}

$conn->close();
