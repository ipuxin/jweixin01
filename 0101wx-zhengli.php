<?php
/**
 * 修改微信SDK,优化判断
 */

//define your token
define("TOKEN", "ipuxin");
$wechatObj = new wechatCallbackapiTest();

/**
 * 如果是接入操作,就执行valid(),
 * 否则就是接入后的操作,执行responseMsg()
 */
if ($_GET['echostr']) {
    $wechatObj->valid();
} else {
    $wechatObj->responseMsg();
}

class wechatCallbackapiTest
{
    /**
     * 接入时的操作
     */
    public function valid()
    {
        /**
         * 如果获取到该参数,就是接入时的操作,
         * 否则就是介入后的操作
         */
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if ($this->checkSignature()) {
            echo $echoStr;
            exit;
        }
    }

    /**
     * 接入成功后的一个操作
     */
    public function responseMsg()
    {
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        //extract post data
        /**
         * 如果有数据提交进行如下处理
         */
        if (!empty($postStr)) {
            /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
               the best way is to check the validity of xml by yourself */
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);

            /**
             * 事件类型，subscribe(订阅)、unsubscribe(取消订阅)
             */
            $Event = $postObj->Event;

            /**
             * 接收到的消息类型
             */
            $MsgType = $postObj->MsgType;

            /**
             * 事件KEY值，qrscene_为前缀，后面为二维码的参数值
             */
            $EventKey = $postObj->EventKey;
            $time = time();

            /**
             * 回复消息模板:文本
             */
            $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";

            /**
             * 关键字回复
             */
//            if (!empty($keyword)) {
//                if ($keyword == '壹朴心') {
//                    $msgType = "text";
//                    $contentStr = "Hello " . $keyword;
//                } else {
//                    $msgType = "text";
//                    $contentStr = "Hello 微信!";
//                }
//                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
//                echo $resultStr;
//            } else {
//                echo "Input something...";
//            }

            /**
             * 多客服,人工处理
             */
            if($keyword == "人工" || $keyword == "问题")
            {
                $textTpl = " <xml>
                         <ToUserName><![CDATA[%s]]></ToUserName>
                         <FromUserName><![CDATA[%s]]></FromUserName>
                         <CreateTime>%s</CreateTime>
                         <MsgType><![CDATA[transfer_customer_service]]></MsgType>
                         </xml>";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time);
                echo $resultStr;
            }

            /**
             * 订阅事件:文本回复
             */
//            if ($Event == "subscribe") {
//                $msgType = "text";
//                $contentStr = "欢迎关注:壹朴心公众账号!";
//                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
//                echo $resultStr;
//            }

            /**
             * 订阅事件:图文回复
             */
            if ($Event == "subscribe") {
                $textImgTpl = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[news]]></MsgType>
                <ArticleCount>1</ArticleCount>
                <Articles>
                <item>
                <Title><![CDATA[壹朴心欢迎你]]></Title>
                <Description><![CDATA[壹朴心,提供有质感的信息]]></Description>
                <PicUrl><![CDATA[http://www.ipuxin.com/images/zdql.jpg]]></PicUrl>
                <Url><![CDATA[http://www.ipuxin.com]]></Url>
                </item>
                </Articles>
                </xml>";
                $resultStr = sprintf($textImgTpl, $fromUsername, $toUsername, $time);
                echo $resultStr;
            }

            /**
             * 判断接收的消息类型
             */
            if ($MsgType == "image") {
                $MsgType = "text";
                $Content = "这是一张极好的图片!";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $MsgType, $Content);
                echo $resultStr;
            }

        } else {
            echo "";
            exit;
        }
    }

    private function checkSignature()
    {
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }

        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }
}

?>