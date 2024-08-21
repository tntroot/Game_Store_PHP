<?php 
include('../../include/include.php');

$sql = "SELECT `name` FROM `game_type`";
// $res = mysqli_execute_query($conn, $sql);
$res = mysqli_query($conn, $sql);
if(mysqli_num_rows($res)){
    $data = mysqli_fetch_all($res, MYSQLI_ASSOC);
    $dataArr = array_column($data, 'name');
    return_success(200, $dataArr);
}else{
    return_error(400, DATA_NOT_EXIST, null);
}
?>