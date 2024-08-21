<?php 
    require dirname(__DIR__).'/vendor/autoload.php';

    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    // 設定密鑰
    $secretKey = 'bab77650408456c89b22fb9c4ccf9eb29f060a0de912a76363a8206300ad06ae';

    // 產生 token
    $genToken = function($account) use($secretKey) {
        $payload = [
            "account" => $account['account'],
            "permission" => $account['permission'],
            "iat" => time(),  // 發行時間
            "exp" => time() + 60*60*24 // 有效時間 (秒)
        ];
        // 產生 JWT
        $jwt = JWT::encode($payload, $secretKey, 'HS256');
        return $jwt;
    };

    // 驗證 token
    $getToken = function($tk) use ($secretKey) {
        
        // 解析 字串
        list($jwt) = sscanf($tk, 'Bearer %s');

        if($jwt) {
            try {
                /* 
                    * 驗證並解碼 JWT token 
                    * 解碼失效會拋出異常到 catch
                */
                $key = new Key($secretKey, 'HS256');
                $decoded = JWT::decode($jwt, $key);
                return [
                    "success" => true,
                    "data" => $decoded
                ];
            }catch (Exception $e) {
                return [
                    "success" => false,
                    "data" => "token 驗證失敗"
                ];
            }
        }else{
            return [
                "success" => false,
                "data" => "token 不存在"
            ];
        }
    }
?>