<?php
include("../include/include.php");

$game_id = $_POST['game_id'] ?? '';

if(empty($game_id)){
    return_error(400, DATA_NOT_EXIST, 0);
}

$getGameSql = "SELECT `name`, `price`, `sale_price`, `type`, content, `system`, `cpu`, `ram`, `display_card`, `directX`, `rom`, game_data.`date`
        FROM game_data INNER join game_article_data ON 
        game_data.game_id = game_article_data.game_id WHERE game_data.game_id = ?;";

// $res = mysqli_execute_query($conn, $getGameSql, [$game_id]);
$res = mysqli_prepare($conn, $getGameSql);
mysqli_stmt_bind_param($res, "i", $game_id);
mysqli_stmt_execute($res);
$res = mysqli_stmt_get_result($res);

if(!$res){
    return_error(400, DATA_NOT_EXIST, 1);
}

$data = mysqli_fetch_assoc($res);
$data["type"] = explode(",", $data["type"]);

$getGamePhotoSql = "SELECT `name` FROM photo_data WHERE `game_id` = ?;";
// $res = mysqli_execute_query($conn, $getGamePhotoSql, [$game_id]);
$res = mysqli_prepare($conn, $getGamePhotoSql);
mysqli_stmt_bind_param($res, "i", $game_id);
mysqli_stmt_execute($res);
$res = mysqli_stmt_get_result($res);

if(!$res){
    $data['img'][] = $genUploadPath("img", "404.png");
}else{
    while($row = mysqli_fetch_assoc($res)){
        $data['img'][] = $genUploadPath("img", $row["name"]);
    }
}
return_success(200, $data);
?>