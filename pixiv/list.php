<?php 
    require_once("/web/api/sql.php");

    $sql = new Sql("api", "vilipix");
    $link = new Link();
    if(!(empty($_REQUEST["shield"]))){
        $shields = explode(",", $_REQUEST["shield"]);
    }

    if(!(empty($_REQUEST["date"]))){
        $day = $_REQUEST["date"];
    }else{
        $day = date("Ymd", strtotime("-2 day"));
    }

    $data = $sql -> read("SELECT * FROM `".$day."`");

    $new_data = array();
    foreach($data as $key => $img){
        if(!(empty($_REQUEST["shield"]))){
            $not = false;
            $tags = explode(",", $img["tag"]);
            foreach($tags as $kay => $tag){
                foreach($shields as $kay => $not_tag){
                    if($tag == $not_tag){
                        $not = true;
                    }
                }
            }
            
            if(!$not){
                array_push($new_data, [
                    "id" => $img["id"],
                    "url" => $img["url"]
                ]);
            }
            
        }else{
            array_push($new_data, [
                "id" => $img["id"],
                "url" => $img["url"]
            ]);
        }
    }

    $link -> json([
        "day" => $day,
        "list" => $new_data
    ]);

