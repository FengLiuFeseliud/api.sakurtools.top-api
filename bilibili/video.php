<?php
    include "../link.php";

    $link = new Link($_COOKIE);
    $bvid = $link -> censor("bvid");
    $cid = $link -> censor("cid");
    $qn = $link -> censor("qn");

    $video_data = [
        "bvid" => $bvid,
        "cid" => $cid,
        "qn" => $qn
    ];
    $video_data = $link -> get("https://api.bilibili.com/x/player/playurl", $video_data);
    
    if($video_data["code"] != 0){
        $link -> json([], 4002, "无法获取视频地址 b站错误码:".$video_data["code"]);
        exit();
    }

    $return_data = array();
    array_push($return_data, $video_data["data"]["durl"][0]["url"]);
    array_push($return_data, $video_data["data"]["durl"][0]["backup_url"][0]);
    array_push($return_data, $video_data["data"]["durl"][0]["backup_url"][1]);

    $link -> json($return_data);