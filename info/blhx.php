<?php 
    require_once("/web/api/sql.php");
    #error_reporting(E_ALL &~ E_NOTICE);

    $sql = new Sql("api", "api");
    $link = new Link();
    
    $offset = $link -> censor("offset");
    $count = $link ->censor("count");
    if((int)$count === 0){
        $link -> json([], "4004", "count 不能为0");
        exit();
    }

    if(!empty($_REQUEST["short"])){
        $data = $sql -> read("SELECT text,url FROM `blhx_dynamic` ORDER BY `time` DESC LIMIT ".(int)$offset.",".(int)$count."");
    }else{
        $data = $sql -> read("SELECT * FROM `blhx_dynamic` ORDER BY `time` DESC LIMIT ".(int)$offset.",".(int)$count."");
    }

    $return_data = [];
    foreach($data as $key => $dynamic){
        foreach($dynamic as $dynamic_key => $dynamic_data){
            if(is_numeric($dynamic_key)){
                continue;
            }
            $return_data[$key][$dynamic_key] = $dynamic_data;
        }
    }

    $link -> json($return_data);