import requests

data = {
    "url": "https://sakuratools.top",
    "short_url": "/sk",
    "day": 1
}

with requests.post("https://api.sakuratools.top/skln/updata.php", data=data) as req:
    print(req.text)