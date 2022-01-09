import requests
import aiohttp
import asyncio
import os

chunk_size = 1024
dow_path = "./download_img"

with requests.get("https://api.sakuratools.top/pixiv/list.php") as req:
    data = req.json()

    img_list = data["data"]["list"]
    time = data["data"]["day"]

    dow_path = f"{dow_path}\\{time}"
    if not os.path.isdir(dow_path):
        os.makedirs(dow_path)

    print("下载 %s pixiv综合榜 %s 张" % (time, len(img_list)))


headers = {
    "referer": "https://www.vilipix.com/",
    "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.71 Safari/537.36"
}

png_list = []
async def run_task():
    tasks = []
    conn = aiohttp.TCPConnector(limit=10)
    async with aiohttp.ClientSession(connector=conn ,headers=headers) as session:

        async def dow_img(url, file_path):
            async with session.get(url) as req:
                if req.status != 200:
                    print("下载失败, 尝试png下载... %s" % url)

                    url = url.rsplit(".", maxsplit=1)[0] + ".png"
                    file_path = file_path.rsplit(".", maxsplit=1)[0] + ".png"
                    png_list.append({"url": url, "file_path": file_path})
                    return

                with open(file_path, "wb") as file:
                    while True:
                        chunk = await req.content.read(chunk_size)
                        if not chunk:
                            print("下载完成! %s" % url)
                            break
                        
                        file.write(chunk)
        
        for img in img_list:
            file_path = os.path.join(dow_path, img["url"].split("/")[-1])
            tasks.append(asyncio.create_task(dow_img(img["url"], file_path)))

        await asyncio.wait(tasks)

        tasks = []
        for img in png_list:
            tasks.append(asyncio.create_task(dow_img(img["url"], img["file_path"])))

        await asyncio.wait(tasks)

asyncio.run(run_task())
input("link end...")
