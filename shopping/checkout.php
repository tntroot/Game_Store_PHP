<?php
include('../include/include.php');

$token = $_POST['token'] ?? '';
$payment = $_POST['payment'] ?? '';

if(empty($token)){
    return_error(400, DATA_NOT_EXIST, $token);
}

$getAccGame = shoppingGame($conn, "", $getToken($token));
$getAccountRes = $getAccGame["accountRes"];

$selectCard = "SELECT `shop_cart_data`.*, `game_data`.* FROM shop_cart_data INNER JOIN game_data ON shop_cart_data.game_id = game_data.game_id 
                where user_id = ?";
// $selectCardRes = mysqli_execute_query($conn, $selectCard, [$getAccountRes["user_id"]]);
$selectCardRes = mysqli_prepare($conn, $selectCard);
mysqli_stmt_bind_param($selectCardRes, "i", $getAccountRes["user_id"]);
mysqli_stmt_execute($selectCardRes);
$selectCardRes = mysqli_stmt_get_result($selectCardRes);

if(!mysqli_num_rows($selectCardRes)){
    return_error(400, DATA_NOT_EXIST, $token);
}

/** 產生購買歷史ID */
function genShopHistId($cnn, $id, $acc) {
    $date = date("Ymd");
    $selectShopHistUserId = "SELECT count(*) as count FROM `shop_history` WHERE `user_id` = ?";
    // $selectShopHistUserIdRes = mysqli_execute_query($cnn, $selectShopHistUserId, [$id]);
    $selectShopHistUserIdRes = mysqli_prepare($cnn, $selectShopHistUserId);
    mysqli_stmt_bind_param($selectShopHistUserIdRes, "i", $id);
    mysqli_stmt_execute($selectShopHistUserIdRes);
    $selectShopHistUserIdRes = mysqli_stmt_get_result($selectShopHistUserIdRes);

    $count = mysqli_fetch_assoc($selectShopHistUserIdRes)["count"] + 1;
    return "GM". $acc. $date . str_pad($count, 5, "0", STR_PAD_LEFT);
};

$genShopHistId = genShopHistId($conn, $getAccountRes["user_id"], $getAccountRes["account"]);

/** 新增購買歷史 */
while($row = mysqli_fetch_assoc($selectCardRes)){
    $date = date("Y-m-d");
    $insertShopHist = "INSERT INTO `shop_history` VALUES (id, ?, ?, ?, ?, ?, ?)";
    // $insertShopHistRes = mysqli_execute_query($conn, $insertShopHist, [$genShopHistId, $row["game_id"], $row["user_id"], $payment, $row["price"], date("Y-m-d H:i")]);
    $insertShopHistRes = mysqli_prepare($conn, $insertShopHist);
    mysqli_stmt_bind_param($insertShopHistRes, "siisid", $genShopHistId, $row["game_id"], $row["user_id"], $payment, $row["price"], $date);
    mysqli_stmt_execute($insertShopHistRes);
}

/** 刪除購物車 */
$delShopCard = "DELETE FROM `shop_cart_data` WHERE `user_id` = ?";
// $delShopCardRes = mysqli_execute_query($conn, $delShopCard, [$getAccountRes["user_id"]]);
$delShopCardRes = mysqli_prepare($conn, $delShopCard);
mysqli_stmt_bind_param($delShopCardRes, "i", $getAccountRes["user_id"]);
mysqli_stmt_execute($delShopCardRes);

return_success(200, $genShopHistId);
?>