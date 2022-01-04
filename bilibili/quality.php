<?php
    include "../link.php";

    $link = new Link($_COOKIE);
    $link -> censor("bvid");
    $link -> censor("cid");

    if(!(isset($_REQUEST["vip"]))){   
        $user_data = $link -> get("https://api.bilibili.com/x/web-interface/nav");
        if($user_data['code'] == -101){
            $link -> json([], 4001, "b站返回未登录, 请检查cookie...");
            exit();
        }

        $vip = $user_data["data"]["vipType"];
    }else{
        $vip = $_REQUEST["vip"];
    }

    $video_data = [
        "bvid" => $_REQUEST["bvid"],
        "cid" => $_REQUEST["cid"],
        "qn" => 32
    ];
    $video_data = $link -> get("https://api.bilibili.com/x/player/playurl", $video_data);
    
    if($video_data["code"] != 0){
        $link -> json([], 4002, "无法获取视频地址 b站错误码:".$video_data["code"]);
        exit();
    }

    $video_quality = $video_data["data"]["support_formats"];
    $return_data = array();
    foreach($video_quality as $key => $val){
        if($vip == 0 and $val["quality"] <= 32){
            array_push($return_data, $val);
        }
        if($vip == 1 and $val["quality"] <=80){
            array_push($return_data, $val);
        }
        if($vip != 0 and $vip != 1){
            array_push($return_data, $val);
        }
    }
    $link -> json($return_data);