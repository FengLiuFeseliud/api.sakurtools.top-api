<?php
    require_once("/web/api/sql.php");

    function set_short_url($str){
        $str = str_ireplace( "\\", "/", $str );
        $str = str_ireplace( "&", "", $str );
        $str = str_ireplace( "?", "", $str );
        $str = str_ireplace( "|", "", $str );
        $str = str_ireplace( "'", "", $str );
        $str = str_ireplace( "%", "%", $str );
        $str = str_ireplace( "_", "_", $str );
        return $str;
    }

    $sql = new Sql("api", "api");
    $link = new Link();

    $url = $link -> censor("url");
    $short_url = $link -> censor("short_url");
    $day = $link -> censor("day");

    if($day > 30){
        $link -> json([], 4000, "有效时间不能大于30天");
        exit();
    }

    if($day <= 0){
        $link -> json([], 4000, "有效时间不能小于0天");
        exit();
    }

    $code = $link -> get($url, json:false);
    if($code >= 400){
        $link -> json([], 4000, "长链接状态码大于等于400, 请检查长链接有效性");
        exit();
    }

    $short_url = set_short_url($short_url);
    $url_list = $sql -> read("SELECT * FROM `skln` WHERE short_url = '".$short_url."'");
    if($url_list != array()){
        $time = date_timestamp_get(date_create());
        
        if($time > $url_list[0]["endtime"]){
            $sql -> write("DELETE FROM `skln` WHERE short_url = '".$short_url."'");
        }else{
            $link -> json([], 4004, "短链接已经存在了");
            exit();
        }
    }

    try{
        $time = date_create();
        $stime = date_timestamp_get($time);
        $etime = date_timestamp_get(date_modify($time, "+".intval($day)." days"));
    }catch(Exception $err){
        $link -> json([], 4004, "有效时间生成失败...");
        exit();
    }

    $sql -> write("INSERT INTO `skln` (`url`, `short_url`, `time`, `endtime`, `day`) 
                        VALUES ('".$url."', '".$short_url."', '".$stime."', '".$etime."', '".$day."')"
                        , "短链接已经存在了");

    $link -> json(["short_url" => "https://skln.xyz".$short_url]);