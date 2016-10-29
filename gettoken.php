<?php
/*
 *获取微信token
*/
$appid = "wx78478e595939c538";
$secret = "5540e8ccab4f71dfad752f73cfb85780";
$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $secret . "";

/**
 * 从URL中获取数据
 */
$token = json_decode(file_get_contents($url), true);
//print_r($token);

/**
 * 获取token
 */
echo $token['access_token'];
//echo $token['access_token'];


//function gettoken($url)
//{
//    $ch = curl_init();
//    curl_setopt($ch, CURLOPT_URL, $url);
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//    curl_setopt($ch, CURLOPT_HEADER, 0);
//    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22");
//    curl_setopt($ch, CURLOPT_ENCODING, 'gzip'); //加入gzip解析
//    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//    $output = curl_exec($ch);
//    curl_close($ch);
//    return $output;
//}

?>