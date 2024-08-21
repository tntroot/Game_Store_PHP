<?php
try {
    $localhost = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "fantasy_universe";

    // $localhost = "127.0.0.1";
    // $dbUsername = "113-1-11";
    // $dbPassword = "zpup^*Ff";
    // $dbName = "113-1-11";

    $conn = mysqli_connect($localhost, $dbUsername, $dbPassword, $dbName);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    mysqli_set_charset($conn, "utf8mb4");
    /** 時區更改 */
    date_default_timezone_set("Asia/Taipei");
} catch (Exception $e) {
    $data['status'] = 'fail';
    $data['message'] = $e->getMessage();
    echo json_encode($data);
    exit();
}
?>
