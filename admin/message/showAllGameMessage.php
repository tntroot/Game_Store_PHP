<?php 
include('../../include/include.php');

$getGameMessageSQL = "SELECT game_data.game_id, game_data.img, game_data.`name`, count(*) AS `count`
                        FROM comments INNER JOIN game_data ON comments.game_id = game_data.game_id
                        GROUP BY comments.game_id"; 
$res = mysqli_execute_query($conn, $getGameMessageSQL);

if(!mysqli_num_rows($res)){
    return_error(400, DATA_NOT_EXIST, null);
}

$data = [];

while($row = mysqli_fetch_assoc($res)){
    $row["img"] = $genUploadPath("img", $row["img"]);
    $data[] = $row;
}
return_success(200, $data);
?>