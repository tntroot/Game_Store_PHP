<?php 
include('../include/include.php');

$token = $_POST['token'] ?? '';

if(empty($token)){
    return_error(400, DATA_NOT_EXIST, 0);
}

$getAccGame = shoppingGame($conn, "", $getToken($token));
$accountRes = $getAccGame["accountRes"];

$sql = "SELECT game_data.name, game_data.img ,game_data.files, `shop_history`.price, `shop_history`.payment, `shop_history`.`date`  FROM `game_data` INNER JOIN `shop_history` ON `game_data`.`game_id` = `shop_history`.`game_id`
        WHERE `shop_history`.`user_id` = ? ORDER BY `shop_history`.`date` DESC";
// $res = mysqli_execute_query($conn, $sql, [$accountRes["user_id"]]);
$res = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($res, "i", $accountRes["user_id"]);
mysqli_stmt_execute($res);
$res = mysqli_stmt_get_result($res);

if(!mysqli_num_rows($res)){
    return_error(400, "尚無購買紀錄", null);
}

$data = [];
while($row = mysqli_fetch_assoc($res)){
    $row["img"] = $genUploadPath("img", $row["img"]);
    $row["files"] = $genUploadPath("file", $row["files"]);
    // $row["date"] = $row["date"];
    $data[] = $row;
}

return_success(200, $data);
?>