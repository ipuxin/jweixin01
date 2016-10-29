<?php
/*
 *获取微信token
*/
$appid = "wx78478e595939c538";
$secret = "5540e8ccab4f71dfad752f73cfb85780";
$urlToken = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $secret . "";

function getCURL($url)
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
$urlTokenData = json_decode(getCURL($urlToken), true);
$access_token = $urlTokenData['access_token'];

/**
 * 通过curl获取已关注的用户列表,获取所有用户的openid
 */
//1.将接口赋值给一个变量
$userUrl = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$access_token;
//2.通过getCURL()进行接口调用
$userArr = json_decode(getCURL($userUrl), true);
//3.处理得到的数据
foreach ($userArr['data']['openid'] as $value) {
    echo $value;
    echo "<br />";
}

print_r($userArr);
