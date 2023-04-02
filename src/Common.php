<?php

// error_reporting(0);
class Src_Common
{
    /**
     * 将数组字符串
     * @param array $arr
     * @param bool  $useBrackets
     * @return string|string[]|null
     */
    public static function arrStrFormat($arr = [], $useBrackets = false)
    {
        if (!$useBrackets) {
            // 不用处理成中括号的形式
            return var_export($arr, true);
        }
        $str = json_encode($arr);
        // var_dump($str);
        $replaceSearchArr = ['=>', 'array', '),'];
        $replaceStrArr = [];

        // var_export之后是array的形式，换成[]的形式，需要根据上述字符去替换字符串，先把原数据中包含这些字符的字符串给替换掉，避免影响
        foreach ($replaceSearchArr as $search) {
            $repeatTimes = 1;
            while ($repeatTimes <= 100) {
                $replace = str_repeat('{', $repeatTimes) . $search . str_repeat('}', $repeatTimes);
                if (mb_strpos($str, $replace) === false) {
                    $replaceStrArr[] = $replace;
                    break;
                }
                $repeatTimes++;
            }
        }
        // var_dump($replaceStrArr);

        $strNew = str_replace($replaceSearchArr, $replaceStrArr, $str);
        $arrNew = json_decode($strNew, true);
        $strNew = var_export($arrNew, true);
        $strNew = preg_replace("/=>\s+\n\s*/i", '=> ', $strNew);
        $strNew = preg_replace('/\d+\s*=>\s*/i', '', $strNew);
        $strNew = preg_replace('/array\s*\(/i', '[', $strNew);
        $strNew = str_replace('),', '],', $strNew);
        $strNew = rtrim($strNew, ')') . ']';

        $strNew = str_replace($replaceStrArr, $replaceSearchArr, $strNew);

        return $strNew;
    }

    /**
     *
     * XML编码
     * @param mixed  $data     数据
     * @param string $encoding 数据编码
     * @param string $root     根节点名
     * @return string|fixed
     */
    public static function arrayToXml($data, $root = 'data', $encoding = 'utf-8')
    {
        $xml = '<?xml version="1.0" encoding="' . $encoding . '"?>';
        $xml .= '<' . $root . '>';
        $xml .= self::dataToXml($data);
        $xml .= '</' . $root . '>';
        return $xml;
    }

    /**
     * 数据XML编码
     * @param mixed $data 数据
     * @return string|fixed
     */
    public static function dataToXml($data)
    {
        $xml = '';
        foreach ($data as $key => $val) {
            if (!is_numeric($key)) {
                $xml .= "<$key>";
            }
            $xml .= (is_array($val) || is_object($val)) ? self::dataToXml($val) : ($val == null ? '' : $val);
            list($key,) = explode(' ', $key);
            if (!is_numeric($key)) {
                $xml .= "</$key>";
            }
        }
        return $xml;
    }

    /**
     * 将UNICODE编码后的内容进行解码，编码后的内容格式：\u56fe\u7247 （原始：图片）
     * @param string $name
     * @return string
     */
    public static function unicodeDecode($name = ''): string
    {
        // 转换编码，将Unicode编码转换成可以浏览的utf-8编码
        $pattern = '/([\w]+)|(\\\u([\w]{4}))/i';
        preg_match_all($pattern, $name, $matches);
        if (!empty($matches)) {
            $name = '';
            for ($j = 0; $j < count($matches[0]); $j++) {
                $str = $matches[0][$j];
                if (strpos($str, '\\u') === 0) {
                    $code = base_convert(substr($str, 2, 2), 16, 10);
                    $code2 = base_convert(substr($str, 4), 16, 10);
                    $c = chr($code) . chr($code2);
                    $name .= $c;
                } else {
                    $name .= $str;
                }
            }
        }
        return $name;
    }

    /**
     * 将内容进行UNICODE编码，编码后的内容格式：\u56fe\u7247 （原始：图片）
     * @param string $name
     * @return string|fixed
     */
    public static function unicodeEncode($name = '')
    {
        $name = mb_convert_encoding($name, 'utf8', 'AUTO');
        $len = strlen($name);
        $str = '';
        for ($i = 0; $i < $len - 1; $i = $i + 2) {
            $c = $name[$i];
            $c2 = $name[$i + 1];
            if (ord($c) > 0) {    // 两个字节的文字
                $str .= '\u' . base_convert(ord($c), 10, 16) . base_convert(ord($c2), 10, 16);
            } else {
                $str .= $c2;
            }
        }
        return $str;
    }

    /**
     * 获取随机字符串
     * @param int $length
     * @param int $mode
     * @return string
     */
    public static function getRandomCode($length = 10, $mode = 1)
    {
        switch ($mode) {
            case '1':
                $modeStr = '数字';
                $str = '1234567890';
                break;
            case '2':
                $modeStr = '小写字母';
                $str = 'abcdefghijklmnopqrstuvwxyz';
                break;
            case '3':
                $modeStr = '大写字母';
                $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case '4':
                $modeStr = '大小写字母';
                $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
                break;
            case '5':
                $modeStr = '大写字母加数字';
                $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
                break;
            case '6':
                $modeStr = '小写字母加数字';
                $str = 'abcdefghijklmnopqrstuvwxyz1234567890';
                break;
            case '7':
                $modeStr = '大小写字母加数字';
                $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
                break;
            case '8':
            default:
                $modeStr = '大小写字母加特殊符号';
                $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890!@#$%^&*()_+=><[{}]';
                break;
        }
        $checkStr = '';
        $len = strlen($str) - 1;
        for ($i = 0; $i < $length; $i++) {
            // $num=rand(0,$len);//产生一个0到$len之间的随机数
            $num = mt_rand(0, $len); // 产生一个0到$len之间的随机数
            $checkStr .= $str[$num];
        }
        return [
            'mode' => $mode,
            'modeStr' => $modeStr,
            'randomStr' => $checkStr,
        ];
    }

    /**
     * Json数据格式化
     * @param array  $data   数据
     * @param int $indentSpaceNum 缩进字符，默认4个空格
     * @return string|fixed
     */
    public static function jsonFormat(array $data, $indentSpaceNum = 4)
    {
        // 对数组中每个元素递归进行urlencode操作，保护中文字符
        array_walk_recursive($data, function(&$val)
        {
            if ($val !== true && $val !== false && $val !== null) {
                $val = urlencode($val);
            }
        });
        // json encode
        $data = json_encode($data,JSON_UNESCAPED_UNICODE);
        // 将urlencode的内容进行urldecode
        $data = urldecode($data);
        // 缩进处理
        $ret = '';
        $pos = 0;
        $length = strlen($data);
        $indent = str_repeat(' ', $indentSpaceNum);
        $newline = "\n";
        $prevChar = '';
        $outOfQuotes = true;
        for ($i = 0; $i <= $length; $i++) {
            $char = substr($data, $i, 1);
            if ($char == '"' && $prevChar != '\\') {
                $outOfQuotes = !$outOfQuotes;
            } elseif (($char == '}' || $char == ']') && $outOfQuotes) {
                $ret .= $newline;
                $pos--;
                for ($j = 0; $j < $pos; $j++) {
                    $ret .= $indent;
                }
            }
            $ret .= $char;
            if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
                $ret .= $newline;
                if ($char == '{' || $char == '[') {
                    $pos++;
                }
                for ($j = 0; $j < $pos; $j++) {
                    $ret .= $indent;
                }
            }
            $prevChar = $char;
        }
        // $ret = self::unicodeDecode($ret);
        return $ret;
    }

}


