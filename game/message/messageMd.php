<?php
include('../../include/include.php');

$token = $_POST['token'] ?? '';
$game_id = $_POST['game_id'] ?? 8;
$text = $_POST['text'] ?? '';
$isreoly = $_POST['isreoly'] ?? 0;


$selectGameId = "SELECT * FROM `game_data` WHERE `game_id` = ?";
// $selectGameIdRes = mysqli_execute_query($conn, $selectGameId, [$game_id]);
$selectGameIdRes = mysqli_prepare($conn, $selectGameId);
mysqli_stmt_bind_param($selectGameIdRes, "i", $game_id);
mysqli_stmt_execute($selectGameIdRes);
$selectGameIdRes = mysqli_stmt_get_result($selectGameIdRes);

if (!mysqli_num_rows($selectGameIdRes)) {
    return_error(400, DATA_NOT_EXIST . "，查無此遊戲", null);
}

$action = $_GET['action'] ?? '';
if ($action == 'add') {

    if (empty($token) || empty($game_id) || empty($text)) {
        return_error(400, DATA_NOT_EXIST, null);
    }
    if ($isreoly <0) {
        $isreoly = 0;
    }

    $getAccGame = shoppingGame($conn, $game_id, $getToken($token));
    $getGameIdRes = $getAccGame["gmaeIdRes"];
    $getAccountRes = $getAccGame["accountRes"];

    $date = date("Y-m-d H:i:s");

    $sql = "INSERT INTO `comments` (`game_id`, `user_id`, `text`, `reply`, `created_at`) VALUES (?, ?, ?, ?, ?)";
    // $res = mysqli_execute_query($conn, $sql, [$getGameIdRes["game_id"], $getAccountRes["user_id"], $text, $isreoly, date("Y-m-d H:i:s")]);
    $res = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($res, "iisid", $getGameIdRes["game_id"], $getAccountRes["user_id"], $text, $isreoly, $date);
    mysqli_stmt_execute($res);

    if (!$res) {
        return_error(400, INSERT_ERROR, null);
    }
}

$showComments = " SELECT `comments`.id, `user_data`.`name`, `comments`.`text`, `comments`.`reply`,`comments`.`created_at` 
                    FROM `comments` INNER JOIN `user_data` ON `comments`.`user_id` = `user_data`.`user_id`
                    WHERE `comments`.`game_id` = ? ";
// $showCommentsRes = mysqli_execute_query($conn, $showComments, [$game_id]);
$showCommentsRes = mysqli_prepare($conn, $showComments);
mysqli_stmt_bind_param($showCommentsRes, "i", $game_id);
mysqli_stmt_execute($showCommentsRes);
$showCommentsRes = mysqli_stmt_get_result($showCommentsRes);

$data = [];
while ($row = mysqli_fetch_assoc($showCommentsRes)) {
    if ($row["reply"] != 0) {
        $data[$row["reply"]]["replyUser"][] = $row;
    } else {
        $data[$row["id"]] = $row;
        $data[$row["id"]]["replyUser"] = [];
    }
}

// $data[] = ;

return_success(200, $data);
