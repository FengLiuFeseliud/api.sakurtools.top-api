<?php
    require_once("/web/api/sql.php");

    $link = new Link();
    $sql = new Sql("api", "vilipix");

    $time = time();
    $dy = date("Y", $time);

    $y_img_list = $sql -> read("SHOW TABLES WHERE Tables_in_vilipix LIKE '".$dy."%'");
    $data = [];
    foreach($y_img_list as $key => $img_list_name){
        array_push($data, $img_list_name[0]);
    }
    $link -> json(array_reverse($data));