<?php

namespace api;

use api\Core\Http\Code;
use api\Core\Http\Request;
use api\Core\Http\Response;
use api\Core\Utils\Log;

class Rpc
{
    private static $CONTROLLER_ROOT = "\\api\\Controller\\";
    private static $CONTROLLER_FILE_EXT = "Controller";

    public static function handle()
    {
        try {
            $request = Request::post();
            if (empty($request)) {
                $request = Request::complexData();
            }

            $method = $request['method'] ?? null;
            if (is_null($method))
                return Response::sendDefaultNotFound();

            $parts = explode('.', $method);
            if (count($parts) != 2)
                return Response::sendDefaultNotFound();
            $namespace = self::$CONTROLLER_ROOT.$parts[0].self::$CONTROLLER_FILE_EXT;
            $function = $parts[1];

            $args = array_filter($request, function ($key) {
                return $key != 'method';
            },ARRAY_FILTER_USE_KEY);

            if ($controller = [new $namespace, $function])
                return $controller($args);

            return Response::sendDefaultNotFound();
        }
        catch (\Throwable $e)
        {
            Log::error($e->getMessage()." in ".$e->getFile()." on line ".$e->getLine(), "Rpc::handle");
            return Response::send
            (
                Code::OK_200,
                [
                    "ok" => "false",
                    "error" => "true",
                    "message" => "An unexpected error has occurred"
                ]
            );
        }
    }
}