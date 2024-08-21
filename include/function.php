<?php

// 狀態類表 --> 帳號資訊
define('ACCOUNT_EXIST', '帳號已存在');
define('EMAIL_EXIST', '信箱已存在');
define('NAME_EXIST', '名稱已存在');
define('ACCOUNT_NOT_EXIST', '帳號不存在');
define('EMAIL_NOT_EXIST', '信箱不存在');
define('ACCOUNT_OR_PWD_ERROR', '帳號或密碼輸入錯誤');
define('PWD_ERROR', '原密碼輸入錯誤');
define('PWD_SAME', '新密碼不可與舊密碼相同');
define('COLLAPSE_ERROR', '尚有欄位未輸入');
define('ERROR', '發生錯誤');
define('NOT_LOGIN', '尚未登入，請先登入');
define('SUCCESS_LOGOUT', '登出成功');
define('SUCCESS_LOGIN', '登入驗證成功');
define('ERRPR_MESSAGE', '發生意外錯誤');

// 狀態類表 --> 產品資訊
define('PRODUCT_EXIST', '產品已存在');
define('PRODUCT_NOT_EXIST', '產品不存在');

// 狀態類表 --> 購物車
define('CART_PRODUCT_EXIST', '購物車內已存在該產品');
define("PRICE_ERROR", "價格不得為負數");

// 狀態類表 --> 編輯資訊
define('EDIT_SUCCESS', '編輯成功');
define('EDIT_FAIL', '編輯失敗');
define("INSERT_ERROR", "新增失敗");

// 狀態類表 --> 查詢資訊
define("DATA_NOT_EXIST", "查無資料");
define('DATA_EXIST', '資料已存在');

// 狀態類表 --> 圖片/檔案
define('IMG_EXIST', '圖片已存在');
define('IMG_NOT_EXIST', '圖片不存在');
define('IMG_ERROR', '檔案上傳失敗');
define('FILE_EXIST', '檔案已存在');
define('FILE_NOT_EXIST', '檔案不存在');
define('FILE_ERROR', '檔案上傳失敗');

define('PERMISSION_ERROR', '權限不足，需要管理員權限');

/** 錯誤訊息 
 * @param int $status 狀態碼
 * @param string $message 訊息
 * @param string $e 錯誤
 */
function return_error($status, $message, $e)
{
    $arr = [];
    $arr['status'] = $status;
    $arr['message'] = $message;
    $arr['data'] = $e;
    echo json_encode($arr);
    exit();
}

/** 成功訊息
 * @param int $status 狀態碼
 * @param (string, array) $data 資料
 */
function return_success($status, $data)
{
    $arr = [];
    $arr['status'] = $status;
    $arr['data'] = $data;
    echo json_encode($arr);
    exit();
}

/** 產生隨機碼 */
function genRandStr($length)
{
    return bin2hex(random_bytes($length / 2));
}

// $dataAPIURL = "http://site03.web.digital.gov.tw/113-1-11/PHP";
// $dataAPIURL = "http://localhost/Game_Store_PHP";
$dataAPIURL = $_SERVER['DOCUMENT_ROOT']."/Game_Store_PHP";

/** 上傳檔案路徑 */
$uploadFile = $dataAPIURL. '\\uploads\\file\\';
/** 上傳圖片路徑 */
$uploadImg = $dataAPIURL . '\\uploads\\img\\';

$productPath = "http://localhost/Game_Store_PHP"."/uploads/";



/** 產生圖片路徑到前端 */
$genUploadPath = function ($folder, $path) use ($productPath) {
    return $productPath . $folder . "/" . $path;
};


/* 
    SQL 相關函式
*/

/**
 * 選擇查詢欄位 (email, account, name)
 * @param string $Num 查詢欄位
 */
function select_userOne($Num)
{
    $sql = "SELECT * FROM `user_data` ";
    switch ($Num) {
        case 'email':
            $sql .= "WHERE `email` = ?;";
            break;
        case 'account':
            $sql .= "WHERE `account` = ?;";
            break;
        case 'name':
            $sql .= "WHERE `name` = ?;";
            break;
    }
    return $sql;
}

/**
 * 檢查是否有重複(排除自己)
 * @param mysqli $link 連線
 * @param array $data 要檢查的資料
 * @param string $isCheckMyself 是否檢查自己
 */
function dataExist($link, $data, $isCheckMyself = '')
{
    if (is_array($data)) {

        // 查詢自身帳號
        if ($isCheckMyself) {
            $myselfSql = select_userOne('account');
            // $myselfResult = mysqli_execute_query($link, $myselfSql, [$isCheckMyself]);
            $myselfStmt = mysqli_prepare($link, $myselfSql);
            mysqli_stmt_bind_param($myselfStmt, 's', $isCheckMyself);
            mysqli_stmt_execute($myselfStmt);
            $myselfResult = mysqli_stmt_get_result($myselfStmt);

            if (!mysqli_num_rows($myselfResult)) {
                return_error(400, ERRPR_MESSAGE, $isCheckMyself);
            }
            $myselfselect = mysqli_fetch_assoc($myselfResult);
        }

        foreach ($data as $key => $value) {
            // 檢查是否為自己
            if ($isCheckMyself) {
                if ($myselfselect[$key] == $value) {
                    continue;
                }
            }

            // 檢查是否有重複    
            $sql = select_userOne($key);
            // $result = mysqli_execute_query($link, $sql, [$value]);
            $stmt = mysqli_prepare($link, $sql);
            mysqli_stmt_bind_param($stmt, 's', $value);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
                if ($key == 'email') {
                    return_error(400, EMAIL_EXIST, $value);
                } elseif ($key == 'account') {
                    return_error(400, ACCOUNT_EXIST, $value);
                } else {
                    return_error(400, NAME_EXIST, $value);
                }
            }
        }
    }
}

/** 購買遊戲 、留言
 * 驗證 是否有遊戲/使用者
 * @param mysqli $conn 連接資料庫
 * @param string $gId 遊戲編號
 */
function shoppingGame($conn, $gId, $getTk)
{
    $isTokenGet = $getTk;
    if (!$isTokenGet["success"]) {
        return_error(410, $isTokenGet["data"], null);
    }

    $tokenData = get_object_vars($isTokenGet["data"]);

    $getGameIdSQL = "SELECT * FROM `game_data`";
    if (!empty($gId)) {
        $getGameIdSQL .= " WHERE `game_id` = ?";
        // $getGameIdRes = mysqli_execute_query($conn, $getGameIdSQL, [$gId]);
        $getGameIdRes = mysqli_prepare($conn, $getGameIdSQL);
        mysqli_stmt_bind_param($getGameIdRes, 'i', $gId);
        mysqli_stmt_execute($getGameIdRes);
        $getGameIdRes = mysqli_stmt_get_result($getGameIdRes);

        if (!mysqli_num_rows($getGameIdRes)) {
            return_error(400, DATA_NOT_EXIST . ", 未找到該遊戲", 0);
        }
    }else{
        // $getGameIdRes = mysqli_execute_query($conn, $getGameIdSQL);
        $getGameIdRes = mysqli_prepare($conn, $getGameIdSQL);
        mysqli_stmt_execute($getGameIdRes);
        $getGameIdRes = mysqli_stmt_get_result($getGameIdRes);

        if (!mysqli_num_rows($getGameIdRes)) {
            return_error(400, DATA_NOT_EXIST . ", 未找到該遊戲", 1);
        }
    }

    $getUserAccount = "SELECT * FROM `user_data` WHERE `account` = ?";
    // $getAccountRes = mysqli_execute_query($conn, $getUserAccount, [$tokenData["account"]]);
    $getAccountRes = mysqli_prepare($conn, $getUserAccount);
    mysqli_stmt_bind_param($getAccountRes, 's', $tokenData["account"]);
    mysqli_stmt_execute($getAccountRes);
    $getAccountRes = mysqli_stmt_get_result($getAccountRes);


    if (!mysqli_num_rows($getAccountRes)) {
        return_error(400, DATA_NOT_EXIST . ", 未找到該帳號", 2);
    }

    /** 取得遊戲資料 */
    $getGameIdData = mysqli_fetch_assoc($getGameIdRes);
    /** 取得帳號資料 */
    $getAccountData = mysqli_fetch_assoc($getAccountRes);

    return [
        "gmaeIdRes" => $getGameIdData,
        "accountRes" => $getAccountData
    ];
}

?>