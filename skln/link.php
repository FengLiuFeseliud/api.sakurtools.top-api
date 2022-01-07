<?php
    require_once("/web/api/sql.php");

    $sql = new Sql("api", "api");
    $link = new Link();

    $short_url = $link -> censor("short_url");

    $url_data = $sql -> read("SELECT * FROM `skln` WHERE short_url = '".$short_url."'");
    
    if($url_data == array()){
        header("Location: https://api.sakuratools.top/skln/err.html");
        exit();
    }
    
    header("Location: ".$url_data[0]["url"]);
