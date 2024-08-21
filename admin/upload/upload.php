<?php 
include('../../include/include.php');

$image = $_FILES['image'] ?? '';

$data = [];
if(empty($image)){
    $data["errorCode"] = 400;
    $data["data"]["src"] = "";
    $data["alt"]["image alt"] = "";
    
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit();
}


if ($image['error'] === UPLOAD_ERR_OK) {
    $uploadFile = basename($image['name']);

    /** 新檔案名稱 */
    $newImgName = pathinfo($uploadFile, PATHINFO_FILENAME) . '_' . genRandStr(16) . '.' . $fileExtension;
    /** 新檔案路徑 */
    $targetImgPath = $uploadPath . $newImgName;
    
    
    // 移动上传文件到指定目录
    if (move_uploaded_file($image['tmp_name'], $targetImgPath)) {
        // 返回文件的 URL
        $data["errorCode"] = 0;
        $data["data"]["src"] = $genUploadPath("img", $newImgName);
        $data["alt"]["image alt"] = "image alt";
        echo json_encode($data, JSON_NUMERIC_CHECK);
    } else {
        echo json_encode(['error' => 'Failed to move uploaded file.']);
    }
} else {
    echo json_encode(['error' => 'File upload error.']);
}
?>