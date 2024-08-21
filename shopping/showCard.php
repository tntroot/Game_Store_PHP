<?php 
include('../include/include.php');

$game_id = $_POST['game_id'] ?? '';
$token = $_POST['token'] ?? '';


if(empty($token)){
    return_error(400, DATA_NOT_EXIST, $token);
}

$getAccGame = shoppingGame($conn, $game_id, $getToken($token));
$getGameIdRes = $getAccGame["gmaeIdRes"];
$getAccountRes = $getAccGame["accountRes"];

$cardShow = "SELECT `shop_cart_data`.*, `game_data`.* FROM shop_cart_data INNER JOIN game_data ON shop_cart_data.game_id = game_data.game_id 
                where user_id = '". $getAccountRes["user_id"] ."'";
if(!empty($game_id)){
    $cardShow .= " AND shop_cart_data.game_id = '". $game_id ."'";
}

$cardShowRes = mysqli_query($conn, $cardShow);

if(mysqli_num_rows($cardShowRes)){
    $data = [];
    while($row = mysqli_fetch_assoc($cardShowRes)){
        $row["img"] = $genUploadPath("img", $row["img"]);
        unset($row["files"]);
        $data[] = $row;
    }
    return_success(200, $data);
}else{
    return_error(400, DATA_NOT_EXIST, 1);
}

?>