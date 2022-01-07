<?php
    require_once("/web/api/sql.php");

    $sql = new Sql("api", "api");
    $link = new Link();

    $url = $link -> censor("url");

    $url_data = $sql -> read("SELECT * FROM `skln` WHERE short_url = '".$url."'");
    
    if($url_data == array()){
        header("Location: https://api.skln.xyz/err.html");
        exit();
    }
    
    header("Location: ".$url_data[0]["url"]);
