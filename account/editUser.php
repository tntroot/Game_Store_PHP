<?php
include('../include/include.php');

$tk = $_POST['token'] ?? '';
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$account = $_POST['account'] ?? '';
$sex = $_POST['sex'] ?? 2;
$phone = $_POST['phone'] ?? '';
$birthday = $_POST['birthday'] ?? '';

if (!empty($email) && !empty($account) && !empty($name)) {

    $isTokenGet = $getToken($tk);
    if (!$isTokenGet["success"]) {
        return_error(410, $isTokenGet["data"], null);
    }
    // 檢查是否有重複 --> 先檢查自己，再檢查其他
    $objArray = get_object_vars($isTokenGet["data"]);
    dataExist($conn, array('email' => $email, 'account' => $account, 'name' => $name), $objArray["account"]);

    $sql = "UPDATE `user_data` SET `name` = ?, `email` = ?, `account` = ?, `sex` = ?, `phone` = ?, `birthday` = ? WHERE `account` = ?";
    // $result = mysqli_execute_query($conn, $sql, [$name, $email, $account, $sex, $phone, $birthday, $objArray["account"]]);
    $result = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($result, "sssssss", $name, $email, $account, $sex, $phone, $birthday, $objArray["account"]);
    mysqli_stmt_execute($result);

    if ($result) {

        $sql = "SELECT * FROM `user_data` WHERE `account` = ?";
        // $result = mysqli_execute_query($conn, $sql, [$account]);
        $result = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($result, "s", $account);
        mysqli_stmt_execute($result);
        $result = mysqli_stmt_get_result($result);
        
        $row = mysqli_fetch_assoc($result);

        $replyToken = $genToken($row);
        return_success(200, $replyToken);
    } else {
        return_error(400, EDIT_FAIL, null);
    }

} else {
    return_error(400, COLLAPSE_ERROR, null);
}
