<?php
    require_once("/web/api/sql.php");

    $sql = new Sql("api", "api");
    $link = new Link();

    $short_url = $link -> censor("short_url");

    $url_data = $sql -> read("SELECT * FROM `skln` WHERE short_url = '".$short_url."'");

    if($url_data == array()){
        $link -> json([]);
        exit();
    }

    $return_data = [];
    foreach($url_data[0] as $key => $val){
        if(!is_int($key)){
            $return_data[$key] = $val;
        }
    }
    
    $link -> json($return_data);