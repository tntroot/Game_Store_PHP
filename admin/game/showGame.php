<?php 
include('../../include/include.php');

$sql = "SELECT `game_id`, `name`, `img`, `price`, `sale_price`, `date` FROM `game_data` ORDER BY `game_id` DESC, `date`";
$res = mysqli_query($conn, $sql);
// $data = mysqli_fetch_all($res, MYSQLI_ASSOC);

if(mysqli_num_rows($res)){
    while($row = mysqli_fetch_assoc($res)){
        $row["img"] = $genUploadPath("img", $row["img"]);
        $data[] = $row;
    }
    return_success(200, $data);
}else{
    return_error(400, DATA_NOT_EXIST, null);
}
?>