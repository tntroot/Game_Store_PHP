<?php 
include('../../include/include.php');

$name = $_POST['name'];

$sql = "SELECT * FROM `game_type` where `name` = ?";
// $res = mysqli_execute_query($conn, $sql, [$name]);
$res = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($res, "s", $name);
mysqli_stmt_execute($res);
$res = mysqli_stmt_get_result($res);

if (mysqli_num_rows($res)) {
    return_error(400, DATA_EXIST, null);
}

$sql = "INSERT INTO `game_type` (`name`) VALUES (?)";
// $result = mysqli_execute_query($conn, $sql, [$name]);
$result = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($result, "s", $name);
mysqli_stmt_execute($result);

if ($result) {
    return_success(200, $name);
} else {
    return_error(400, INSERT_ERROR, null);
}
?>