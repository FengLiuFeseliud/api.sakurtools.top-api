<?php 
    include "/web/api/link.php";
    $link = new Link();

    $status_code = $link -> censor("code");
    $request_path = $link -> censor("url");

    $http_status_code_msg = [
        400 => "Bad Request", 
        401 => "Unauthorized",
        402 => "Payment Required",
        403 => "Forbidden",
        404 => "not api 没有这个接口",
        405 => "Method Not Allowed",
        500 => "Internal Server Error",
        501 => "Not Implemented",
        502 => "Bad Gateway"
    ][$status_code];


    $link -> json([
        "url" => $request_path
    ], $status_code, $http_status_code_msg);
