<?php 
include('../include/include.php');

$token = $_POST['token'] ?? '';
$game_id = $_POST['game_id'] ?? '';

if (empty($token) || empty($game_id)) {
    return_error(400, DATA_NOT_EXIST, null);
}

$getAccGame = shoppingGame($conn, $game_id, $getToken($token));
$getGameIdRes = $getAccGame["gmaeIdRes"];
$getAccountRes = $getAccGame["accountRes"];

$sql = "select * from shop_history where user_id = ? and game_id = ?";
// $res = mysqli_execute_query($conn, $sql, [$getAccountRes["user_id"], $getGameIdRes["game_id"]]);
$res = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($res, "ii", $getAccountRes["user_id"], $getGameIdRes["game_id"]);
mysqli_stmt_execute($res);
$res = mysqli_stmt_get_result($res);

if (mysqli_num_rows($res)) {
    return_success(200, null);
} else {
    return_error(400, DATA_NOT_EXIST, null);
}
?>