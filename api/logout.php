<?php
session_start();

unset($_SESSION["user_id"]);
$response = ["status" => 200, "message" => "退出登录成功", "data" => null];
echo json_encode($response);
