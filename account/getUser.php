<?php
include('../include/include.php');

$account = $_POST['account'] ?? "";

if ($account) {
    $sql = "SELECT `user_id`, `name`, `email`, `account`, `sex`, `phone`, `birthday`, `permission` FROM `user_data` WHERE `account` = ?;";
    // $result = mysqli_execute_query($conn, $sql, [$account]);
    $result = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($result, "s", $account);
    mysqli_stmt_execute($result);
    $result = mysqli_stmt_get_result($result);
    
    if(!mysqli_num_rows($result)){
        return_error(400, ERRPR_MESSAGE, $account);
    }

    $data = mysqli_fetch_assoc($result);
    return_success(200, $data);
}else{
    return_error(400, ERRPR_MESSAGE, null);
}

?>