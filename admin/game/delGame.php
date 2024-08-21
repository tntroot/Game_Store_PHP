<?php 
include('../../include/include.php');

$game_id = $_POST['game_id'] ?? '';

if(empty($game_id)){
    return_error(400, DATA_NOT_EXIST, null);
}
$sql = "SELECT * FROM `game_data` WHERE `game_id` = ?";
// $res = mysqli_execute_query($conn, $sql, [$game_id]);
$res = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($res, "i", $game_id);
mysqli_stmt_execute($res);
$res = mysqli_stmt_get_result($res);

if(!mysqli_num_rows($res)){
    return_error(400, DATA_NOT_EXIST, null);
}else{
    $data = mysqli_fetch_assoc($res);
    if(file_exists($uploadFile . $data["files"])){
        unlink($uploadFile . $data["files"]);
    }
}

$selectGamePhoto = "SELECT `name` FROM `photo_data` WHERE `game_id` = ?";
// $selectGamePhotoRes = mysqli_execute_query($conn, $selectGamePhoto, [$game_id]);
$selectGamePhotoRes = mysqli_prepare($conn, $selectGamePhoto);
mysqli_stmt_bind_param($selectGamePhotoRes, "i", $game_id);
mysqli_stmt_execute($selectGamePhotoRes);
$selectGamePhotoRes = mysqli_stmt_get_result($selectGamePhotoRes);

if(mysqli_num_rows($selectGamePhotoRes)){
    while($row = mysqli_fetch_assoc($selectGamePhotoRes)){
        if(file_exists($uploadImg . $row["name"])){
            unlink($uploadImg . $row["name"]);
        }
    }
    $deleteGamePhoto = "DELETE FROM `photo_data` WHERE `game_id` = ?";
    // $deleteGamePhotoRes = mysqli_execute_query($conn, $deleteGamePhoto, [$game_id]);
    $deleteGamePhotoRes = mysqli_prepare($conn, $deleteGamePhoto);
    mysqli_stmt_bind_param($deleteGamePhotoRes, "i", $game_id);
    mysqli_stmt_execute($deleteGamePhotoRes);
}

$selectGameAritst = "SELECT * FROM `game_article_data` WHERE `game_id` = ?";
// $selectGameAritstRes = mysqli_execute_query($conn, $selectGameAritst, [$game_id]);
$selectGameAritstRes = mysqli_prepare($conn, $selectGameAritst);
mysqli_stmt_bind_param($selectGameAritstRes, "i", $game_id);
mysqli_stmt_execute($selectGameAritstRes);
$selectGameAritstRes = mysqli_stmt_get_result($selectGameAritstRes);

if(mysqli_num_rows($selectGameAritstRes)){
    $deleteGameAritst = "DELETE FROM `game_article_data` WHERE `game_id` = ?";
    // $deleteGameAritstRes = mysqli_execute_query($conn, $deleteGameAritst, [$game_id]);
    $deleteGameAritstRes = mysqli_prepare($conn, $deleteGameAritst);
    mysqli_stmt_bind_param($deleteGameAritstRes, "i", $game_id);
    mysqli_stmt_execute($deleteGameAritstRes);
}

$deleteGame = "DELETE FROM `game_data` WHERE `game_id` = ?";
// $deleteGameRes = mysqli_execute_query($conn, $deleteGame, [$game_id]);
$deleteGameRes = mysqli_prepare($conn, $deleteGame);
mysqli_stmt_bind_param($deleteGameRes, "i", $game_id);
mysqli_stmt_execute($deleteGameRes);

if($deleteGameRes){
    return_success(200, null);
}else{
    return_error(400, ERROR, null);
}

?>