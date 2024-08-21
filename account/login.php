<?php 
include('../include/include.php');

// $account = "cisco";
// $password = "cisco";

$account = isset($_POST['account']) ? $_POST['account'] : "";
$password = isset($_POST['password']) ? $_POST['password'] : "";

try {
    // 判斷帳號密碼是否為空
    if (!$account && !$password){
        return_error(400, ACCOUNT_OR_PWD_ERROR, 0);
        exit();
    }

    // 找到該帳號
    $sql = select_userOne('account');
    // $result = mysqli_execute_query($conn, $sql, [$account]);
    $result = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($result, "s", $account);
    mysqli_stmt_execute($result);
    $result = mysqli_stmt_get_result($result);

    // 判斷帳號是否存在
    if (!mysqli_num_rows($result)) {
        return_error(400, ACCOUNT_OR_PWD_ERROR, 1);
        exit();
    }

    // 驗證密碼
    $row = mysqli_fetch_assoc($result);
    if (!password_verify($password, $row['password'])) {
        return_error(400, ACCOUNT_OR_PWD_ERROR, 0);
        exit();
    }

    $token = $genToken($row);
    return_success(200, $token);

    // session_start();
    // $_SESSION['account'] = $account;
    // return_success('success', '');
} catch (Exception $e) {
    return_error(400, ACCOUNT_OR_PWD_ERROR, $e->getMessage());
}

?>