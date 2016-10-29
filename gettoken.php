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
//print_r($access_token);

/**
 * 获取微信服务器IP
 */
$urlDataIP = $ipurl = "https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=" . $access_token . "";
$DataIP = json_decode(gettoken($urlDataIP), true);
$DataIPList = $DataIP['ip_list'];

/**
 * 判断获取IP是否来自微信服务器
 */
$nowIP = '180.163.15.1597';
if (in_array($nowIP, $DataIPList)) {
    echo "来自微信!";
}