<?php 
    include "/web/api/link.php";

    $link = new Link();

    $file = fopen("/web/py/wthrcdn.txt", "r");
    $wthrcdn = fread($file, filesize("/web/py/wthrcdn.txt"));

    $time = time();
    $stime = date("Y年m月d日", $time);

    $w = (int)date("w", $time);
    $wstr = [
        0 => "星期天",
        1 => "星期一",
        2 => "星期二",
        3 => "星期三",
        4 => "星期四",
        5 => "星期五",
        6 => "星期六",
    ];

    $wstr = $wstr[$w];

    $link -> json([
        "timeStr" => "".$stime." ".$wstr,
        "time" => $time,
        "wthrcdn" => $wthrcdn
    ]);
