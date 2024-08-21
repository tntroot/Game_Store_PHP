<?php 
include('../../include/include.php');

$sql = "SELECT `user_id`, `name`, `account`, `email`, `phone`, `permission`, `date` FROM `user_data`";
$res = mysqli_query($conn, $sql);
$data = mysqli_fetch_all($res, MYSQLI_ASSOC);
if(mysqli_num_rows($res)){
    return_success(200, $data);
}else{
    return_error(400, DATA_NOT_EXIST, null);
}
?>