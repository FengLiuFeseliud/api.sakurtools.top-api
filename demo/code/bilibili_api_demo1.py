import requests

api = "https://api.sakuratools.top/bilibili"
bvid = "BV1oQ4y1q7DA"
with requests.get(f"{api}/cid.php?bvid={bvid}") as req:
    data = req.json()
    if data["code"] != 200:
        print("cid 获取失败 错误信息: %s" %data["msg"])
        exit()

    cid = data["data"][0]["cid"]

with requests.get(f"{api}/quality.php?bvid={bvid}&cid={cid}&vip=0") as req:
    data = req.json()
    if data["code"] != 200:
        print("画质获取失败 错误信息: %s" %data["msg"])
        exit()
    
    qn = data["data"][-1]["quality"]

with requests.get(f"{api}/video.php?bvid={bvid}&cid={cid}&qn={qn}") as req:
    print(req.url)
    data = req.json()
    if data["code"] != 200:
        print("url获取失败 错误信息: %s" %data["msg"])
        exit()
    
    url = data["data"]
    print(f"成功获得 bvid: {bvid} 的直链!")
    print(url)