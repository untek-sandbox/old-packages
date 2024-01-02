<?php

namespace Untek\Lib\Components\Cors\Helpers;

use Untek\Core\Code\Helpers\DeprecateHelper;
use Untek\Lib\Components\Http\Enums\HttpHeaderEnum;
use Untek\Lib\Components\Http\Enums\HttpMethodEnum;
use Untek\Lib\Components\Http\Enums\HttpServerEnum;
use Untek\Core\Enum\Helpers\EnumHelper;

DeprecateHelper::hardThrow();

class CorsHelper
{

    public static function autoload(array $origins = null): void
    {
        if(empty($origins)) {
            $envOrigins = getenv('CORS_ALLOW_ORIGINS') ?: null;
            $origins = explode(',', $envOrigins);
        }

        // Allow from any origin
        $allowOrigin = self::getAllowOrigin($origins);
        if (!$allowOrigin) {
            return;
        }

        self::header(HttpHeaderEnum::ACCESS_CONTROL_ALLOW_ORIGIN, $allowOrigin);
        self::header(HttpHeaderEnum::ACCESS_CONTROL_ALLOW_CREDENTIALS, 'true');
        if (getenv('CORS_MAX_AGE')) {
            self::header(HttpHeaderEnum::ACCESS_CONTROL_MAX_AGE, getenv('CORS_MAX_AGE'));
        }
        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER[HttpServerEnum::REQUEST_METHOD] == HttpMethodEnum::OPTIONS) {
            if (isset($_SERVER[HttpServerEnum::HTTP_ACCESS_CONTROL_REQUEST_METHOD])) {
                self::header(HttpHeaderEnum::ACCESS_CONTROL_ALLOW_METHODS, "GET, POST, PUT, DELETE, OPTIONS");
            }
            if (isset($_SERVER[HttpServerEnum::HTTP_ACCESS_CONTROL_REQUEST_HEADERS])) {
//                dump(date(\DateTime::ISO8601));
                $headers = $_SERVER[HttpServerEnum::HTTP_ACCESS_CONTROL_REQUEST_HEADERS];
//                dump($headers);
                self::header(HttpHeaderEnum::ACCESS_CONTROL_ALLOW_HEADERS, $headers);
                self::header(HttpHeaderEnum::ACCESS_CONTROL_EXPOSE_HEADERS, $headers);
            }
            exit;
        }


        /*$headers = self::generateHeaders();
        foreach ($headers as $headerKey => $headerValue) {
            header("$headerKey: $headerValue");
        }
        //$response = new Response('', 200, $headers);
        //$response->sendHeaders();
        if ($_SERVER[HttpServerEnum::REQUEST_METHOD] == HttpMethodEnum::OPTIONS) {
            exit;
        }*/
    }

    protected static function getAllowOrigin(array $origins): ?string
    {
        $clientOrigin = $_SERVER[HttpServerEnum::HTTP_ORIGIN] ?? null;
        if (empty($clientOrigin) || empty($origins)) {
            return null;
        }
        // should do a check here to match $_SERVER['HTTP_ORIGIN'] to a whitelist of safe domains
        $isAllow = in_array('*', $origins) || in_array($clientOrigin, $origins);
        if ($isAllow) {
            return $clientOrigin;
        }
    }

    protected static function header($key, $value): void
    {
//        dump("$key: $value");
        header("$key: $value");
    }

//    private static function generateHeaders($forceOrigin = false): array
//    {
//        //$headers = ArrayHelper::getValue($_SERVER, HttpServerEnum::HTTP_ACCESS_CONTROL_REQUEST_HEADERS);
//        if (empty($headers)) {
//            $headers = implode(', ', EnumHelper::getValues(HttpMethodEnum::class));
//            $headers = mb_strtolower($headers);
//        }
//        $headers = [
//            HttpHeaderEnum::ACCESS_CONTROL_ALLOW_ORIGIN => $_SERVER['HTTP_ORIGIN'],
//            HttpHeaderEnum::ACCESS_CONTROL_ALLOW_HEADERS => $headers,
//            HttpHeaderEnum::ACCESS_CONTROL_ALLOW_METHODS => implode(', ', EnumHelper::getValues(HttpMethodEnum::class)),
//            HttpHeaderEnum::ACCESS_CONTROL_ALLOW_CREDENTIALS => 'true',
//            /*
//            HttpHeaderEnum::ACCESS_CONTROL_ALLOW_ORIGIN => ArrayHelper::getValue($_SERVER, HttpServerEnum::HTTP_ORIGIN),
//
//            HttpHeaderEnum::ACCESS_CONTROL_MAX_AGE => 3600,
//            HttpHeaderEnum::ACCESS_CONTROL_EXPOSE_HEADERS => [
//                HttpHeaderEnum::CONTENT_TYPE,
//                HttpHeaderEnum::LINK,
//                HttpHeaderEnum::ACCESS_TOKEN,
//                HttpHeaderEnum::AUTHORIZATION,
//                HttpHeaderEnum::TIME_ZONE,
//                HttpHeaderEnum::TOTAL_COUNT,
//                HttpHeaderEnum::PAGE_COUNT,
//                HttpHeaderEnum::CURRENT_PAGE,
//                HttpHeaderEnum::PER_PAGE,
//                HttpHeaderEnum::X_ENTITY_ID,
//                HttpHeaderEnum::X_AGENT_FINGERPRINT,
//            ],
//            */
//        ];
//        return $headers;
//    }

}
