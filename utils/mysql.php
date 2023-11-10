<?php
function connect_mysql(): mysqli
{
    $hostname = "localhost";
    $username = $_SERVER["MYSQL_USERNAME"];
    $password = $_SERVER["MYSQL_PASSWORD"];
    $database = "php_exam";

    return new mysqli($hostname, $username, $password, $database);
}


function query_one_str($conn, $sql, $param, $column)
{
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $param);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_assoc()[$column];
}

function query_two_str($conn, $sql, $param1, $param2, $column)
{
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $param1, $param2);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        return $result->fetch_assoc()[$column];
    } else {
        return null;
    }
}
