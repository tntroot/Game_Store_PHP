<?php
include('../../include/include.php');

$id = $_POST['game_id'] ?? 4;

if(empty($id)){
    return_error(400, DATA_NOT_EXIST, 0);
}

$getGameSql = "SELECT `name`, `price`, `sale_price`, `files`, `type`, content, `system`, `cpu`, `ram`, `display_card`, `directX`, `rom`, game_data.`date`
        FROM game_data INNER join game_article_data ON 
        game_data.game_id = game_article_data.game_id WHERE game_data.game_id = ?;";

$getGamePhotoSql = "SELECT `name` FROM photo_data WHERE `game_id` = ?;";

// $res = mysqli_execute_query($conn, $getGameSql, [$id]);
$res = mysqli_prepare($conn, $getGameSql);
mysqli_stmt_bind_param($res, "i", $id);
mysqli_stmt_execute($res);
$res = mysqli_stmt_get_result($res);

$data = mysqli_fetch_assoc($res);

if(mysqli_num_rows($res)){

    $data["type"] = explode(",", $data["type"]);

    // $getGamePhotoRes = mysqli_execute_query($conn, $getGamePhotoSql, [$id]);
    $getGamePhotoRes = mysqli_prepare($conn, $getGamePhotoSql);
    mysqli_stmt_bind_param($getGamePhotoRes, "i", $id);
    mysqli_stmt_execute($getGamePhotoRes);
    $getGamePhotoRes = mysqli_stmt_get_result($getGamePhotoRes);
    
    if(mysqli_num_rows($getGamePhotoRes)){
        while($row = mysqli_fetch_assoc($getGamePhotoRes)){
            $data['img'][] = $genUploadPath("img", $row["name"]);
        }
    }
    return_success(200, $data);
}else{
    return_error(400, DATA_NOT_EXIST, null);
}
?>