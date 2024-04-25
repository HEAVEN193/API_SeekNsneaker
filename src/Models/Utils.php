<?php
namespace Matteomcr\ApiSeekSneaker\Models;
use Matteomcr\ApiSeekSneaker\Models\Database;
use PDO;

class Utils {
    public static function sanitizeString(string $txt): string
    {
        $txt = filter_var($txt, FILTER_UNSAFE_RAW);
        $txt = preg_replace('/\x00|<[^>]*>?/', "", $txt);
        return str_replace(["'", '"'], ["&#39;", "&#34;"], $txt);
    }

    public static function returnResponse($response)
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");
        echo json_encode($response);
    }
}