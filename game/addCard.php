<?php 
include('../include/include.php');

$game_id = $_POST['game_id'] ?? '';
$token = $_POST['token'] ?? '';

if(empty($game_id) || empty($token)){
    return_error(400, DATA_NOT_EXIST, null);
}

$getAccGame = shoppingGame($conn, $game_id, $getToken($token));
$getGameIdData = $getAccGame["gmaeIdRes"];
$getAccountData = $getAccGame["accountRes"];

$getCard = "SELECT * FROM `shop_cart_data` WHERE `user_id` = ? AND `game_id` = ?";
// $getCardRes = mysqli_execute_query($conn, $getCard, [$getAccountData["user_id"], $getGameIdData["game_id"]]);
$getCardRes = mysqli_prepare($conn, $getCard);
mysqli_stmt_bind_param($getCardRes, "ii", $getAccountData["user_id"], $getGameIdData["game_id"]);
mysqli_stmt_execute($getCardRes);
$getCardRes = mysqli_stmt_get_result($getCardRes);

if(mysqli_num_rows($getCardRes)){
    return_error(410, DATA_EXIST.", 購物車已有該遊戲", null);
}

$date = date("Y-m-d");

$addCard = "INSERT INTO `shop_cart_data` VALUES (?, ?, ?)";
// $addCardRes = mysqli_execute_query($conn, $addCard, [$getGameIdData["game_id"],$getAccountData["user_id"] , date("Y-m-d")]);
$addCardRes = mysqli_prepare($conn, $addCard);
mysqli_stmt_bind_param($addCardRes, "iis", $getGameIdData["game_id"],$getAccountData["user_id"] , $date);
mysqli_stmt_execute($addCardRes);

if($addCardRes){
    $getGameIdData["img"] = $genUploadPath("img", $getGameIdData["img"]);
    return_success(200, $getGameIdData);
}else{
    return_error(400, DATA_NOT_EXIST.", 加入購物車失敗", null);
}
?>