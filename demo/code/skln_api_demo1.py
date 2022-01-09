import requests

data = {
    "url": "https://space.bilibili.com/34394509",
    "short_url": "/bili",
    "day": 30
}

with requests.post("https://api.sakuratools.top/skln/updata.php", data=data) as req:
    data = req.json()
    if data["code"] != 200:
        print("短链接生成失败... 错误信息:%s" % data["msg"])
        exit()

    print("短链接生成成功! :%s" % data["data"]["short_url"])