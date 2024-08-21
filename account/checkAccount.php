<?php 

include('../include/include.php');

$token = isset($_POST['token']) ? $_POST['token']: "";

$isTokenGet = $getToken($token);
if($isTokenGet["success"]){
    return_success(200, $isTokenGet["data"]);
}else{
    return_error(400, $isTokenGet["data"], null);
}

?>