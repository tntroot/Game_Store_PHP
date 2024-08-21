<?php 
include("../../include/include.php");

$name = $_POST['name'] ?? "";
$price = $_POST['price'] ?? "";
$sale_price = $_POST['sale_price'] ?? "";
$type = $_POST['type'] ?? "";
$img = $_FILES['img'] ?? "";
$file = $_FILES['file'] ?? "";
$content = $_POST['content'] ?? "";
// $config = $_POST['config'] ?? "";
$system = $_POST['system'] ?? "";
$cpu = $_POST['cpu'] ?? "";
$ram = $_POST['ram'] ?? "";
$display_card = $_POST['display_card'] ?? "";
$directX = $_POST['directX'] ?? "";
$rom = $_POST['rom'] ?? "";

if(empty($name) || empty($file) || empty($file) || empty($content)){
    return_error(400, COLLAPSE_ERROR, null);
}
if(empty($price)){ $price = 0; }
elseif($price < 0){
    return_error(400, PRICE_ERROR, null);
}
if(!$sale_price){
    $sale_price = $price;
}

$imgArray = array("jpg", "png", "jpeg", "gif", "webp");
$zipArray = array("zip", "rar", "7z", "tar.gz");

/** 檢查檔案類型 -> 產生新檔案名稱 
 * @param string $fileName 檔案名稱
 * @param array $arr 確認是否為檔案類型
 * @param string $uploadPath 上傳路徑
 * @return array [新檔案名稱, 新檔案路徑]
*/
function genFileNewGamePath($fileName, $arr, $uploadPath){
    $imgName = basename($fileName);

    /** 副檔名 */
    $fileExtension = pathinfo($imgName, PATHINFO_EXTENSION);

    if (!in_array($fileExtension, $arr)) {
        return_error(400, COLLAPSE_ERROR, null);
    }

    /** 新檔案名稱 */
    $newImgName = pathinfo($imgName, PATHINFO_FILENAME) . '_' . genRandStr(16) . '.' . $fileExtension;
    /** 新檔案路徑 */
    $targetImgPath = $uploadPath . $newImgName;

    return [ $newImgName, $targetImgPath];
}

$newIMGArr = [];
/** 確認檔案類型 */
for ($i = 0; $i < count($img['name']); $i++) {
    
    $gen = genFileNewGamePath($img['name'][$i], $imgArray, $uploadImg);

    /** 確認是否有上傳檔案 */
    if (!move_uploaded_file($img['tmp_name'][$i], $gen[1])) {
        return_error(400, IMG_ERROR, null);
    } 

    /** 上傳檔案到陣列，方便後續處理 */
    $newIMGArr[count($newIMGArr)] = $gen[0];
}


/** 上傳檔案 */
// $fileName = basename($file['name']);
// $targetFilePath = $uploadFile . $fileName;
$gen = genFileNewGamePath($file['name'], $zipArray, $uploadFile);
if (!move_uploaded_file($file['tmp_name'], $gen[1])) {
    return_error(400, FILE_ERROR, null);
}
$newFileArr = $gen[0];

/** 將遊戲新增至資料庫 */
$gameSql = "INSERT INTO `game_data` (`name`, `price`, `sale_price`, `img`, `files`, `type`, `date`) VALUES (?, ?, ?, ?, ?, ?, ?)";
// $result = mysqli_execute_query($conn, $gameSql, [$name, $price, $sale_price, $newIMGArr[0], $gen[0], $type,date("Y-m-d")]);
$result = mysqli_prepare($conn, $gameSql);
mysqli_stmt_bind_param($result, "sdsssss", $name, $price, $sale_price, $newIMGArr[0], $newFileArr, $type, date("Y-m-d"));
mysqli_stmt_execute($result);


if($result){
    $game_id = mysqli_insert_id($conn);
    for ($i = 0; $i < count($newIMGArr); $i++) {
        $imgSql = "INSERT INTO `photo_data` (`name`, `game_id`) VALUES (?, ?)";
        // $imgResult = mysqli_execute_query($conn, $imgSql, [$newIMGArr[$i], $game_id]);
        $imgResult = mysqli_prepare($conn, $imgSql);
        mysqli_stmt_bind_param($imgResult, "si", $newIMGArr[$i], $game_id);
        mysqli_stmt_execute($imgResult);
    }
    $game_article_data = "INSERT INTO `game_article_data` VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    // $game_article_result = mysqli_execute_query($conn, $game_article_data, [$game_id, $content, $system, $cpu, $ram, $display_card, $directX, $rom, date("Y-m-d")]);
    $game_article_result = mysqli_prepare($conn, $game_article_data);
    mysqli_stmt_bind_param($game_article_result, "issssssss", $game_id, $content, $system, $cpu, $ram, $display_card, $directX, $rom, date("Y-m-d"));
    mysqli_stmt_execute($game_article_result);
}else{
    return_error(400, INSERT_ERROR, null);
}

return_success(200, null);


?>