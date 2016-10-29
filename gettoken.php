<?php
/*
 *获取微信token
*/
$appid = "wx78478e595939c538";
$secret = "5540e8ccab4f71dfad752f73cfb85780";
$urlToken = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $secret . "";

function gettoken($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22");
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip'); //加入gzip解析
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

/**
 * 通过curl获取数据,元素名为:
 * access_token
 * expires_in
 */
$urlData = json_decode(gettoken($urlToken), true);
$access_token = $urlData['access_token'];

/**
 * 通过curl的post方式,把长连接转换为短连接
 * curl -d "{\"action\":\"long2short\",\"long_url\":\"http://wap.koudaitong.com/v2/showcase/goods?alias=128wi9shh&spm=h56083&redirect_count=1\"}"
 * "https://api.weixin.qq.com/cgi-bin/shorturl?access_token=ACCESS_TOKEN"
 */
$date = '{"action":"long2short","long_url":"https://mp.weixin.qq.com/advanced/advanced?action=dev&t=advanced/dev&token=1182161746&lang=zh_CN"}';
$shortUrl = "https://api.weixin.qq.com/cgi-bin/shorturl?access_token=" . $access_token . "";

//Array ( [errcode] => 0 [errmsg] => ok [short_url] => http://w.url.cn/s/Ammcalw )
$shortUrlArr = json_decode(getShort($date, $shortUrl), true);
print_r($shortUrlArr);

function getShort($data, $url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $tmpInfo = curl_exec($ch);
    if (curl_errno($ch)) {
        return curl_error($ch);
    }
    curl_close($ch);
    return $tmpInfo;
}