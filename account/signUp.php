<?php
include('../include/include.php');

// $name = '索羅';
// $email = 'asdas45d@gmail.com';
// $account = 'admin123';
// $password = 'asd';
// $phone = '1234567890';
// $birthday = '1998-01-01';

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$account = $_POST['account'] ?? '';
$password = $_POST['password'] ?? '';
$phone = $_POST['phone'] ?? '';
$birthday = $_POST['birthday'] ?? '';

// mysqli 執行 SQL
if (!empty($email) && !empty($account) && !empty($password) && !empty($name)) {
    
    // 檢查是否有重複
    dataExist( $conn,array('email' => $email, 'account' => $account, 'name' => $name));
    $date = date('Y-m-d');
    
    $sql = "INSERT INTO `user_data` (`name`, `email`, `account`, `password`, `phone`, `birthday`, `date`) VALUES (?,?, ?, ?, ?, ?, ?);";
    $password = password_hash($password, PASSWORD_DEFAULT);
    // $result = mysqli_execute_query($conn, $sql, [$name, $email, $account, $password, $phone, $birthday, date('Y-m-d')]);
    $result = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($result, "ssssssd", $name, $email, $account, $password, $phone, $birthday, $date);
    mysqli_stmt_execute($result);

    if ($result) {

        $id = mysqli_insert_id($conn);
        $sql = "SELECT * FROM `user_data` WHERE `user_id` = ?";
        // $result = mysqli_execute_query($conn, $sql, [$id]);
        $result = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($result, "i", $id);
        mysqli_stmt_execute($result);
        $result = mysqli_stmt_get_result($result);
        
        $row = mysqli_fetch_assoc($result);
        
        $token = $genToken($row);
        return_success(200, $token);
    }
}else{
    return_error(400, COLLAPSE_ERROR, null);
}
