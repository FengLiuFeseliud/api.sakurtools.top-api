<?php
    include "/web/api/sql";

    $sql = new Sql("api", "api");
    $link = new Link();

    $url = $link -> censor("url");

    $url_data = $sql -> read("SELECT * FROM `skln` WHERE short_url = '".$url."'");

    $link -> json($url_data);