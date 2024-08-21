<?php 
include('../../include/include.php');

$user_id = $_POST['user_id'] ?? '';
$permission = $_POST['permission'] ?? '';


if (empty($user_id) && empty($permission)) {
    return_error(400, ERRPR_MESSAGE, null);
}

$sql = "UPDATE `user_data` SET `permission` = ? WHERE `user_id` = ?";
// $res = mysqli_execute_query($conn, $sql, [$permission, $user_id]);
$res = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($res, "si", $permission, $user_id);
mysqli_stmt_execute($res);
if ($res) {
    return_success(200, "");
} else {
    return_error(400, ERRPR_MESSAGE, null);
}
?>