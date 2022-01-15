<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>随机涩图</title>
</head>
<body>
    
</body>
</html>


<?php 
    include "/web/api/link.php";

    $link = new Link();

    $data = $link -> get("https://api.sakuratools.top/pixiv/random.php?count=1");
    $img_url = $data["data"]["list"][0]["url"];

    $code = $link -> get($img_url, json:false);
    if($code == 404){
        $img_url = rtrim($img_url, ".jpg");
        header("Location: ".$img_url.".png");
        exit();
    }

    header("Location: ".$img_url);
?>