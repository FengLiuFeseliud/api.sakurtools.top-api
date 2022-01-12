<?php
    require_once("/web/api/sql.php");

    $link = new Link();
    $sql = new Sql("api", "vilipix");

    $time = time();
    $dtime = date("Ymd", $time);
    
    if(empty($_REQUEST["date"])){
        $img_name_list = [];
        for($day = 0; $day < 5; $day++){
            $sun_day = $day + 2;
            $img_list_name = date("Ymd", strtotime("-".$sun_day." days", $time));
            array_push($img_name_list, $img_list_name);
        }
    }else{
        $img_name_list = explode(",", $_REQUEST["date"]);
        if(count($img_name_list) > 5){
            $link -> json([], 4000, "day 不能指定5天以上");
            exit();
        }
    }

    if(empty($_REQUEST["count"])){
        $count = 10;
    }else{
        $count = $_REQUEST["count"];
        if($count > 100){
            $link -> json([], 4000, "count 不能指定100以上");
            exit();
        }elseif($count <= 0){
            $link -> json([], 4000, "count 不能小于等于0");
            exit();
        }
    }

    $dy = date("Y", $time);
    $y_img_list = $sql -> read("SHOW TABLES WHERE Tables_in_vilipix LIKE '".$dy."%'");

    $data = array();
    foreach($img_name_list as $list_in => $img_list_name){
        foreach($y_img_list as $key => $y_img_list_name){
            if($img_list_name == $y_img_list_name[0]){
                $img_list_data = $sql -> read("SELECT id,url FROM `".$img_list_name."`");
                foreach($img_list_data as $key => $img){
                    array_push($data, [
                        "id" => $img["id"],
                        "url" => $img["url"]
                    ]);
                }
            }
        }
    }

    $img_list_count = count($data) -1;
    $random_num_list = [];
    $random_data = [];
    for($random = 0; $random < $count; $random++){
        $num_in = false;
        while(true){
            $random_num = rand(0, $img_list_count );
            foreach($random_num_list as $key => $num){
                if($random_num == $num){
                    $num_in = true;
                }
            }

            if(!$num_in){
                array_push($random_num_list, $random_num);
                array_push($random_data, $data[$random_num]);
                break;
            }
            $num_in = false;
        }

    }

    $link -> json([
        "day" => $img_name_list,
        "list" => $random_data
    ]);