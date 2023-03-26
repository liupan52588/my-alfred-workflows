<?php

class Src_BdTransApi
{

    private $curlTimeout = 10;
    private $apiUrl = "http://api.fanyi.baidu.com/api/trans/vip/translate";
    private $appId = '';
    private $secKey = '';

    /**
     * @param     $appId
     * @param     $secKey
     * @param int $curlTimeout
     * @throws Exception
     */
    public function __construct($appId, $secKey, $curlTimeout = 10)
    {
        if (empty($appId) || empty($secKey)) {
            throw new Exception("请配置appId和secrect", 1);
        }
        $this->appId = $appId;
        $this->secKey = $secKey;
        $this->curlTimeout = $curlTimeout;
    }

    /**
     * 翻译入口
     * @param string $query
     * @param string $from
     * @param string $to
     * @param bool   $format
     * @return array|false|mixed
     */
    public function translate($query = '', $from = 'auto', $to = 'zh', $format = true)
    {
        if (preg_match("/([\x81-\xfe][\x40-\xfe])/", $query)) {
            // 有汉字，翻译成英文
            $to = 'en';
        }
        $args = array(
            'q' => $query,
            'appid' => $this->appId,
            'salt' => rand(10000, 99999),
            'from' => $from,
            'to' => $to,
        );
        $quickLookUrl = "https://fanyi.baidu.com/#{$from}/{$to}/{$query}";
        $args['sign'] = $this->buildSign($query, $this->appId, $args['salt'], $this->secKey);
        $ret = $this->call($this->apiUrl, $args);
        if ($format) {
            return $this->formatRes($ret,$quickLookUrl);
        }
        return $ret;
    }

    /**
     * 格式化结果到workflows
     * @param $ret
     * @param $quickLookUrl
     * @return array
     */
    private function formatRes($ret,$quickLookUrl): array
    {
        if (!$ret['success']) {
            return [];
        }
        $list = [];
        $data = json_decode($ret['data'], true);
        if (!empty($data['error_code']) || empty($data['trans_result'])) {
            $list[] = [
                'uid' => 'bdTrans-fail',
                'arg' => '翻译失败',
                'title' => '翻译失败',
                'subTitle' => !empty($data['error_msg']) ? $data['error_msg'] : '未获取到翻译结果',
                'icon' => 'icon.png',
                'valid' => false,
            ];
            return $list;
        }

        foreach ($data['trans_result'] as $key => $value) {
            $list[] = [
                'uid' => 'bdTrans-' . $key,
                'arg' => $value['dst'],
                'title' => $value['dst'],
                'subTitle' => $value['src'],
                'icon' => 'icon.png',
                'valid' => true,
                'quickLookUrl' => $quickLookUrl,
                'mods' => [
                    [
                        'mod' => 'cmd',
                        'subTitle' => '🔊 '.$value['dst'],
                        'arg' => $value['dst'],
                        'valid' => true,
                    ],
                    [
                        'mod' => 'alt',
                        'subTitle' => '📣 '.$value['dst'],
                        'arg' => $value['dst'],
                        'valid' => true,
                    ],
                ]
            ];
        }
        return $list;
    }

    /**
     * 加密
     * @param $query
     * @param $appID
     * @param $salt
     * @param $secKey
     * @return string
     */
    private function buildSign($query, $appID, $salt, $secKey): string
    {
        $str = $appID . $query . $salt . $secKey;
        $ret = md5($str);
        return $ret;
    }

    /**
     * 发起网络请求
     * @param        $url
     * @param null   $args
     * @param string $method
     * @param int    $timeout
     * @param array  $headers
     * @return array|false|mixed
     */
    private function call($url, $args = null, $method = "post", $timeout = 0, $headers = array())
    {
        $ret = false;
        $i = 0;
        while ($ret === false) {
            if ($i > 1)
                break;
            if ($i > 0) {
                sleep(1);
            }
            $ret = $this->callOnce($url, $args, $method, false, $timeout > 0 ? $timeout : $this->curlTimeout, $headers);
            $i++;
        }
        return $ret;
    }

    /**
     * @param        $url
     * @param null   $args
     * @param string $method
     * @param bool   $withCookie
     * @param int    $timeout
     * @param array  $headers
     * @return array
     */
    private function callOnce($url, $args = null, $method = "post", $withCookie = false, $timeout = 0, $headers = array()): array
    {
        $ch = curl_init();
        if ($method == "post") {
            $data = $this->convert($args);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_POST, 1);
        } else {
            $data = $this->convert($args);
            if ($data) {
                if (stripos($url, "?") > 0) {
                    $url .= "&$data";
                } else {
                    $url .= "?$data";
                }
            }
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout > 0 ? $timeout : $this->curlTimeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        if ($withCookie) {
            curl_setopt($ch, CURLOPT_COOKIEJAR, $_COOKIE);
        }
        $data = curl_exec($ch);
        if (empty($data)) {
            $error = '';
            if (curl_errno($ch)) {
                $error = 'Error:' . curl_error($ch);
            }
            curl_close($ch);
            return [
                'success' => false,
                'error' => $error,
                'data' => [],
            ];
        } else {
            curl_close($ch);
            return [
                'success' => true,
                'data' => $data,
            ];
        }
    }

    /**
     * @param $args
     * @return string
     */
    private function convert(&$args): string
    {
        $data = '';
        if (is_array($args)) {
            foreach ($args as $key => $val) {
                if (is_array($val)) {
                    foreach ($val as $k => $v) {
                        $data .= $key . '[' . $k . ']=' . rawurlencode($v) . '&';
                    }
                } else {
                    $data .= "$key=" . rawurlencode($val) . "&";
                }
            }
            return trim($data, "&");
        }
        return $args;
    }

}

