<?php 
include('../include/include.php');

$tk = $_POST['token'] ?? '';
$oldPwd = $_POST['oldPwd'] ?? '';
$newPwd = $_POST['newPwd'] ?? '';

if (empty($tk)){ return_error(400, ERRPR_MESSAGE, null);};
if (!empty($oldPwd) && !empty($newPwd)){
    $isToken = $getToken($tk);
    if (!$isToken["success"]) {
        return_error(410, $isToken["data"], null);
    }

    $objArray = get_object_vars($isToken["data"]);
    $sql = "SELECT `password` FROM `user_data` WHERE `account` = ?";
    // $result = mysqli_execute_query($conn, $sql, [$objArray["account"]]);
    $result = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($result, "s", $objArray["account"]);
    mysqli_stmt_execute($result);
    $result = mysqli_stmt_get_result($result);

    $row = mysqli_fetch_assoc($result);

    if (password_verify($oldPwd, $row["password"])) {

        // 驗證新密碼與舊密碼是否相同
        if (password_verify($newPwd, $row["password"])) {
            return_error(400, PWD_SAME, null);
        }

        $sql = "UPDATE `user_data` SET `password` = ? WHERE `account` = ?";
        $password = password_hash($newPwd, PASSWORD_DEFAULT);
        // $result = mysqli_execute_query($conn, $sql, [$password, $objArray["account"]]);
        $result = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($result, "ss", $password, $objArray["account"]);
        mysqli_stmt_execute($result);
        
        if ($result) {
            $replyToken = $genToken($objArray);
            return_success(200, $replyToken);
        } else {
            return_error(400, PWD_ERROR, null);
        }
    } else {
        return_error(400, PWD_ERROR, null);
    }
}else{
    return_error(400, COLLAPSE_ERROR, null);
}


?>