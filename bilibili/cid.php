<?php
    include "../link.php";

    $link = new Link($_COOKIE);
    $bvid = $link -> censor("bvid");

    $post_data = [
        "bvid" => $bvid,
        "json" => "json"
    ];
    $video_data = $link -> get("https://api.bilibili.com/x/player/pagelist", $post_data);
    if($video_data["code"] != 0){
        $link -> json([], 4002, "cid 获取失败 b站错误码:".$video_data["code"]);
        exit();
    }

    $return_data = array();
    foreach($video_data["data"] as $key => $val){
        $return_data[$key]["cid"] = $val["cid"];
        $return_data[$key]["title"] = $val["part"];
    }

    $link -> json($return_data);