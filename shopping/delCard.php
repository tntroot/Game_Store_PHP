<?php 
include('../include/include.php');

$token = $_POST['token'] ?? '';
$game_id = $_POST['game_id'] ?? '';

if(empty($token) || empty($game_id)){
    return_error(400, DATA_NOT_EXIST, 0);
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
    $delSQL = "DELETE FROM `shop_cart_data` WHERE `user_id` = ? AND `game_id` = ?";
    // $delRes = mysqli_execute_query($conn, $delSQL, [$getAccountData["user_id"], $getGameIdData["game_id"]]);
    $delRes = mysqli_prepare($conn, $delSQL);
    mysqli_stmt_bind_param($delRes, "ii", $getAccountData["user_id"], $getGameIdData["game_id"]);
    mysqli_stmt_execute($delRes);

    if($delRes){
        $selectDelSQL = "SELECT `shop_cart_data`.*, `game_data`.* FROM shop_cart_data INNER JOIN game_data ON shop_cart_data.game_id = game_data.game_id 
                where user_id = ?";
        // $selectDelRes = mysqli_execute_query($conn, $selectDelSQL, [$getAccountData["user_id"]]);
        $selectDelRes = mysqli_prepare($conn, $selectDelSQL);
        mysqli_stmt_bind_param($selectDelRes, "i", $getAccountData["user_id"]);
        mysqli_stmt_execute($selectDelRes);
        $selectDelRes = mysqli_stmt_get_result($selectDelRes);


        $data = [];
        while($row = mysqli_fetch_assoc($selectDelRes)){
            $row["img"] = $genUploadPath("img", $row["img"]);
            unset($row["files"]);
            $data[] = $row;
        }
        return_success(200, $data);
    }else{
        return_error(400, DATA_NOT_EXIST, 1);
    }
}else{
    return_error(400, DATA_NOT_EXIST, 2);
}
?>