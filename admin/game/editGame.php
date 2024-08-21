<?php
include ("../../include/include.php");

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

/** 取得遊戲編號 */
$id = $_POST['id'] ?? 0;

if (empty($name) || empty($content) || empty($type)) {
    return_error(400, COLLAPSE_ERROR, null);
}
if (empty($price)) {
    $price = 0;
} elseif ($price < 0) {
    return_error(400, PRICE_ERROR, null);
}
if (!$sale_price) {
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
function genFileNewGamePath($fileName, $arr, $uploadPath)
{
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

    return [$newImgName, $targetImgPath];
}

$newIMGArr = [];
/** 確認檔案類型 */
if (!empty($img['name'])) {
    for ($i = 0; $i < count($img['name']); $i++) {

        $gen = genFileNewGamePath($img['name'][$i], $imgArray, $uploadImg);

        /** 確認是否有上傳檔案 */
        if (!move_uploaded_file($img['tmp_name'][$i], $gen[1])) {
            return_error(400, IMG_ERROR, null);
        }

        /** 上傳檔案到陣列，方便後續處理 */
        $newIMGArr[count($newIMGArr)] = $gen[0];
    }
}

if(!empty($file['name'])){
    /** 上傳檔案 */
    $gen = genFileNewGamePath($file['name'], $zipArray, $uploadFile);
    if (!move_uploaded_file($file['tmp_name'], $gen[1])) {
        return_error(400, FILE_ERROR, null);
    }
    $newFileArr = $gen[0];
}


$selectGameSql = "SELECT * FROM `game_data` WHERE `game_id` = ?";
// $result = mysqli_execute_query($conn, $selectGameSql, [$id]);
$result = mysqli_prepare($conn, $selectGameSql);
mysqli_stmt_bind_param($result, "i", $id);
mysqli_stmt_execute($result);
$result = mysqli_stmt_get_result($result);

$gameData = mysqli_fetch_assoc($result);
/** 將遊戲更新至資料庫 */
if(empty($img)){
    $newIMGArr[count($newIMGArr)] = $gameData['img'];
}
if(empty($file)){
    $newFileArr = $gameData['files'];
}
$gameSql = "UPDATE `game_data` SET `name` = ?, `price` = ?, `sale_price` = ?, `img` = ?, `files` = ?, `type` = ? WHERE `game_id` = ?";
// $result = mysqli_execute_query($conn, $gameSql, [$name, $price, $sale_price, $newIMGArr[0], $newFileArr, $type, $id]);
$result = mysqli_prepare($conn, $gameSql);
mysqli_stmt_bind_param($result, "sddssss", $name, $price, $sale_price, $newIMGArr[0], $newFileArr, $type, $id);
mysqli_stmt_execute($result);


if ($result) {

    if (!empty($img)) {
        /** 先將舊圖片刪除 */
        $getGamePhotoSql = "SELECT `name` FROM photo_data WHERE `game_id` = ?;";
        // $getGamePhotoRes = mysqli_execute_query($conn, $getGamePhotoSql, [$id]);
        $getGamePhotoRes = mysqli_prepare($conn, $getGamePhotoSql);
        mysqli_stmt_bind_param($getGamePhotoRes, "i", $id);
        mysqli_stmt_execute($getGamePhotoRes);
        $getGamePhotoRes = mysqli_stmt_get_result($getGamePhotoRes);

        if (mysqli_num_rows($getGamePhotoRes)) {
            while ($row = mysqli_fetch_assoc($getGamePhotoRes)) {
                if (file_exists($uploadImg . $row["name"])) {
                    unlink($uploadImg . $row["name"]);
                }
            }
        }

        /** 將新圖片上傳 */
        $delImgSQL = "DELETE FROM `photo_data` WHERE `game_id` = ?";
        // $delImgResult = mysqli_execute_query($conn, $delImgSQL, [$id]);
        $delImgResult = mysqli_prepare($conn, $delImgSQL);
        mysqli_stmt_bind_param($delImgResult, "i", $id);
        mysqli_stmt_execute($delImgResult);

        for ($i = 0; $i < count($newIMGArr); $i++) {
            $imgSql = "INSERT INTO `photo_data` (`name`, `game_id`) VALUES (?, ?)";
            // $imgResult = mysqli_execute_query($conn, $imgSql, [$newIMGArr[$i], $id]);
            $imgResult = mysqli_prepare($conn, $imgSql);
            mysqli_stmt_bind_param($imgResult, "si", $newIMGArr[$i], $id);
            mysqli_stmt_execute($imgResult);
        }
    }
    if (!empty($file)) {
        /** 先將舊檔案刪除 */
        if (file_exists($uploadFile . $gameData['files'])) {
            unlink($uploadFile . $gameData['files']);
        }
        /** 將新檔案上傳 */
        $delFileSQL = "UPDATE `game_data` SET `files` = ? WHERE `game_id` = ?";
        // $delFileResult = mysqli_execute_query($conn, $delFileSQL, [$newFileArr, $id]);
        $delFileResult = mysqli_prepare($conn, $delFileSQL);
        mysqli_stmt_bind_param($delFileResult, "si", $newFileArr, $id);
        mysqli_stmt_execute($delFileResult);
    }


    $game_article_data = "UPDATE `game_article_data` SET `content` = ?, `system` = ?, `cpu` = ?, `ram` = ?, `display_card` = ?, `directX` = ?, `rom` = ? WHERE `game_id` = ?";
    // $game_article_result = mysqli_execute_query($conn, $game_article_data, [$content, $system, $cpu, $ram, $display_card, $directX, $rom, $id]);
    $game_article_result = mysqli_prepare($conn, $game_article_data);
    mysqli_stmt_bind_param($game_article_result, "isssssss", $content, $system, $cpu, $ram, $display_card, $directX, $rom, $id);
    mysqli_stmt_execute($game_article_result);
    
} else {
    return_error(400, INSERT_ERROR, null);
}

return_success(200, null);
?>