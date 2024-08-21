<?php
include('../../include/include.php');

$id = $_POST['id'] ?? "";
$game_id = $_POST['game_id'] ?? "";
$token = $_POST['token'] ?? "";

$isTokenGet = $getToken($token);
if (!$isTokenGet["success"]) {
    return_error(400, $isTokenGet["data"], null);
}

$objArray = get_object_vars($isTokenGet["data"]);
if ($objArray["permission"] != 0) {
    return_error(400, PERMISSION_ERROR, null);
}


if (empty($id) || empty($game_id)) {
    return_error(400, DATA_NOT_EXIST, 0);
}


$getGameComment = "select * from comments where game_id = ?";
$getGameCommentRes = mysqli_execute_query($conn, $getGameComment, [$game_id]);
if (!mysqli_num_rows($getGameCommentRes)) {
    return_error(400, DATA_NOT_EXIST, 2);
}

$acction = $_GET['action'] ?? "";
if ($acction == "all") {
    $delCommentSQL = "delete from comments where game_id = ?";
    $delCommentRes = mysqli_execute_query($conn, $delCommentSQL, [$game_id]);

    $getGameMessageSQL = "SELECT game_data.game_id, game_data.img, game_data.`name`, count(*) AS `count`
                        FROM comments INNER JOIN game_data ON comments.game_id = game_data.game_id
                        GROUP BY comments.game_id";
    $res = mysqli_execute_query($conn, $getGameMessageSQL);

    if (!mysqli_num_rows($res)) {
        return_error(400, DATA_NOT_EXIST, null);
    }

    $data = [];

    while ($row = mysqli_fetch_assoc($res)) {
        $row["img"] = $genUploadPath("img", $row["img"]);
        $data[] = $row;
    }
    return_success(200, $data);
    
} else {
    $getCommentSQL = "select * from comments where id = ?";
    $getCommentRes = mysqli_execute_query($conn, $getCommentSQL, [$id]);
    if (!mysqli_num_rows($getCommentRes)) {
        return_error(400, DATA_NOT_EXIST, 1);
    }

    $delCommentSQL = "delete from comments where id = ?";
    $delCommentRes = mysqli_execute_query($conn, $delCommentSQL, [$id]);
    if ($delCommentRes) {
        $delCommentIdAllSQL = "delete from comments where reply = ?";
        $delCommentIdAllRes = mysqli_execute_query($conn, $delCommentIdAllSQL, [$id]);
    }
}

$showComments = " SELECT `comments`.id, `user_data`.`name`, `comments`.`text`, `comments`.`reply`,`comments`.`created_at` 
                    FROM `comments` INNER JOIN `user_data` ON `comments`.`user_id` = `user_data`.`user_id`
                    WHERE `comments`.`game_id` = ? ";
$showCommentsRes = mysqli_execute_query($conn, $showComments, [$game_id]);

$data = [];
while ($row = mysqli_fetch_assoc($showCommentsRes)) {
    if ($row["reply"] != 0) {
        $data[$row["reply"]]["replyUser"][] = $row;
    } else {
        $data[$row["id"]] = $row;
        $data[$row["id"]]["replyUser"] = [];
    }
}

return_success(200, $data);
