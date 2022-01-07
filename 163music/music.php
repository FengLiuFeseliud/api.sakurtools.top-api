<?php 
    include "../linl.php";

    $link = new Link($_COOKIE);
    $lnnk -> censor("id");

    $get_data = [
        "ids" => $_REQUEST["id"],
        "br" => 999000
    ];
    $music_data = $link -> get("https://interface3.music.163.com/api/song/enhance/player/url", $get_data);