<?php
include("../include/include.php");

$search = $_POST['search'] ?? '';
$tags = $_POST['tags'] ?? '';
$type = $_POST['type'] ?? '';

$sql = "SELECT `game_id`, `name`, `img`, `price`, `sale_price`, `date` FROM game_data WHERE 1=1";

$getUrl = $_GET['url'] ?? '';
if ($getUrl == "search") {
    if (!empty($search)) {
        $sql .= " AND `name` LIKE '%{$search}%'";
    }
    if (!empty($tags)) {
        $tags = explode(",", $tags);
        $sql .= " AND `type` LIKE '%{$tags[0]}%'";
        for ($i = 1; $i < count($tags); $i++) {
            $sql .= " AND `type` LIKE '%{$tags[$i]}%'";
        }
    }
    if (!empty($type)) {
        switch ($type) {
            case "新遊戲":
                $sql .= " ORDER BY `date`, `game_id` DESC";
                break;
            case "免費遊戲":
                $sql .= " AND game_data.sale_price = 0";
                break;
            case "促銷":
                $sql .= " AND game_data.price != game_data.sale_price";
                break;
        }
    }
    $res = mysqli_query($conn, $sql);

    $data = [];
    if (mysqli_num_rows($res)) {
        while ($row = mysqli_fetch_assoc($res)) {
            $row["img"] = $genUploadPath("img", $row["img"]);
            $data[] = $row;
        }
        return_success(200, $data);
    } else {
        return_error(400, DATA_NOT_EXIST, null);
    }
} elseif ($getUrl == "home") {

    /** 最新上架 */
    $dateDesc_sql = $sql . " ORDER BY `date`, `game_id` DESC";

    /** 促銷價 */
    $sale_Price_sql = $sql . " AND game_data.price != game_data.sale_price";


    $dateDesc_res = mysqli_query($conn, $dateDesc_sql);
    $sale_Price_res = mysqli_query($conn, $sale_Price_sql);
    $all = mysqli_query($conn, $sql);

    $data = [];
    if (mysqli_num_rows($dateDesc_res) || mysqli_num_rows($sale_Price_res) || mysqli_num_rows($all)) {
        while ($row = mysqli_fetch_assoc($dateDesc_res)) {
            $row["img"] = $genUploadPath("img", $row["img"]);
            $data["dateDesc"][] = $row;
        }
        while ($row = mysqli_fetch_assoc($sale_Price_res)) {
            $row["img"] = $genUploadPath("img", $row["img"]);
            $data["sale_Price"][] = $row;
        }
        while ($row = mysqli_fetch_assoc($all)) {
            $row["img"] = $genUploadPath("img", $row["img"]);
            $data["all"][] = $row;
        }
        return_success(200, $data);
    } else {
        return_error(400, DATA_NOT_EXIST, null);
    }
}
