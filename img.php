<?php
    include "/web/api/link.php";

    $link = new Link();

    $img_path = $link -> censor("path");
    @list($img_path, $set_size)=explode("@", $img_path);

    $Etag = md5($img_path);
    if(array_key_exists('HTTP_IF_NONE_MATCH', $_SERVER) and $_SERVER['HTTP_IF_NONE_MATCH'] == $Etag){
        header("HTTP/1.1 304 Not Modified");
        exit();
    } else {
        header("Etag:" . $Etag);
    }
    
    $img_file_path = "/web/img/".$img_path;
    @$img_data = getimagesize($img_file_path);
    if($img_data == false){
        $link -> json([], "404", "不存在的图片");
        exit();
    }

    $img_size = [
        "w" => $img_data[0],
        "h" => $img_data[1]
    ];

    $img_type = $img_data["mime"];
    @$img = [
        "image/png" => imagecreatefrompng($img_file_path),
        "image/jpeg" => imagecreatefromjpeg($img_file_path),
        "image/gif" => imagecreatefromgif($img_file_path),
        "image/webp"=> imagecreatefromwebp($img_file_path)
    ][$img_type];

    if($img_type = false){
        $link -> json([], "4003", "打开图片失败");
        exit();
    }
    
    if(empty($set_size)){
        header('Content-type:'.$img_type);
        
        if($img_type = "image/png"){
            imagepng($img);
        }elseif($img_type = "image/jpeg"){
            imagejpeg($img);
        }elseif($img_type = "image/gif"){
            imagegif($img);
        }elseif($img_type = "image/webp"){
            imagewebp($img); 
        }else{
            $link -> json([], "4003", "不支持的格式");
        }

    }else{
        $set_size = explode("x", $set_size);
        $new_w = $set_size[0];
        $new_h = $set_size[1];
        $qsize = $set_size[2];
        $quality = $set_size[3];
        
        if(count($set_size) != 4){
            $link -> json([

            ], "4000", "图片格式为 图片@宽x高x比例x质量");
            exit();
        }
        if($new_w <= 0){
            $new_w = $img_size["w"];
        }

        if($new_h <= 0){
            $new_h = $img_size["h"];
        }
        
        if($qsize <= 0 or $qsize >= 3){
            $qsize = 1;
        }

        if($quality > 100 or $quality < 0){
            $quality = 80;
        }

        $new_w = $new_w * $qsize;
        $new_h = $new_h * $qsize;

        $new_img = imagecreatetruecolor($new_w, $new_h);
        imagecopyresized($new_img, $img, 0, 0, 0, 0, $new_w, $new_h, $img_size["w"], $img_size["h"]);

        header('Content-type:image/webp');
        imagewebp($new_img, quality:$quality);
    }

    imagedestroy($img);