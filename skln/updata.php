<?php
    include "../sql.php";

    $sql = new Sql("api", "api");
    $link = new Link();

    $url = $link -> censor("url");
    $short_url = $link -> censor("short_url");
    $day = $link -> censor("day");

    $sql -> write("INSERT INTO `skln` (`url`, `short_url`, `time`, `endtime`, `day`) 
                        VALUES ('".$url."', '".$short_url."', '1', '1', '".$day."')"
                        , "短链接已经存在了");

    $link -> json([]);