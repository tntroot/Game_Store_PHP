<?php
include('../include/include.php');

$token = $_POST['token'] ?? '';
$shopHistId = $_POST['shopHistId'] ?? '';
if(empty($token) || empty($shopHistId)){
    return_error(400, DATA_NOT_EXIST, null);
}

$getAccGame = shoppingGame($conn, "", $getToken($token));
$getAccountRes = $getAccGame["accountRes"];

$shopHistSQL = "SELECT `shop_history`.*, `game_data`.* FROM shop_history INNER JOIN game_data ON shop_history.game_id = game_data.game_id
                     where user_id = ? and `shop_history`.order_id = ?";
// $shopHistRes = mysqli_execute_query($conn, $shopHistSQL, [$getAccountRes["user_id"], $shopHistId]);
$shopHistRes = mysqli_prepare($conn, $shopHistSQL);
mysqli_stmt_bind_param($shopHistRes, "ii", $getAccountRes["user_id"], $shopHistId);
mysqli_stmt_execute($shopHistRes);
$shopHistRes = mysqli_stmt_get_result($shopHistRes);

if(!mysqli_num_rows($shopHistRes)){
    return_error(400, DATA_NOT_EXIST, null);
}

$data = [];
while($row = mysqli_fetch_assoc($shopHistRes)){
    $row["img"] = $genUploadPath("img", $row["img"]);
    $row["files"] = $genUploadPath("file", $row["files"]);
    $data[] = $row;
}
return_success(200, $data);

?>