<?php

class Tools_StrToArr extends Tools_Base
{
    public static function getWorkFlowsRes(string $query): array
    {
        $workFlowsRes = [];
        $queryArr = explode('::', $query);
        $str = $query;
        $splitArr = ["\n", ',', '|'];
        if (count($queryArr) == 2) {
            $str = $queryArr[1];
            $splitArr = [$queryArr[0]];
        }

        // http query 转数组
        if (preg_match('/\??(\&?[^\=\&]+\=[^\=\&]+)+$/i', $query)) {
            parse_str($query, $res3);
            if (!empty($res3) && is_array($res3)) {
                $res4 = json_encode($res3, JSON_UNESCAPED_UNICODE);
                $workFlowsRes[] = [
                    'uid' => 'httpQuery-toJson',
                    'title' => $res4,
                    'subTitle' => 'http query转为json',
                    'arg' => $res4,
                    'valid' => true,
                ];
                $res3 = Src_Common::arrStrFormat($res3, true);
                // $res3 = str_replace("\n", '', $res3);
                $workFlowsRes[] = [
                    'uid' => 'httpQuery-toArr',
                    'title' => $res3,
                    'subTitle' => 'http query转为数组',
                    'arg' => $res3,
                    'valid' => true,
                ];
            }
        } else {

            if (count($splitArr) > 1) {
                $splitAllStr = str_replace("\n", '换行符', implode(' ', $splitArr));
                $str2 = str_replace($splitArr, '__SPLIT__', $str);
                $arr2 = explode('__SPLIT__', $str2);
                $res2 = Src_Common::arrStrFormat($arr2, true);
                // $res2 = str_replace("\n", '', $res2);
                // $res2 = json_encode($res2, JSON_UNESCAPED_UNICODE);
                $workFlowsRes[] = [
                    'uid' => 'strToArr-all',
                    'title' => $res2,
                    'subTitle' => "字符串以【{$splitAllStr}】切割为数组",
                    'arg' => $res2,
                    'valid' => true,
                ];
                $res3 = json_encode($arr2, JSON_UNESCAPED_UNICODE);
                $workFlowsRes[] = [
                    'uid' => 'strToJson-all',
                    'title' => $res3,
                    'subTitle' => "字符串以【{$splitAllStr}】切割为数组并转json",
                    'arg' => $res3,
                    'valid' => true,
                ];
            }

            foreach ($splitArr as $split) {
                if (mb_strpos($str, $split) !== false) {
                    $arr = explode($split, $str);
                    $res = Src_Common::arrStrFormat($arr, true);
                    // $res = str_replace("\n", '', $res);
                    // $res = json_encode($res,JSON_UNESCAPED_UNICODE);
                    $splitStr = $split == "\n" ? '换行符' : $split;
                    $workFlowsRes[] = [
                        'uid' => 'strToArr-' . str_replace("\\", '', $split),
                        'title' => $res,
                        'subTitle' => "字符串以【{$splitStr}】切割为数组",
                        'arg' => $res,
                        'valid' => true,
                    ];
                    $res2 = json_encode($arr, JSON_UNESCAPED_UNICODE);
                    $workFlowsRes[] = [
                        'uid' => 'strToJson-' . str_replace("\\", '', $split),
                        'title' => $res2,
                        'subTitle' => "字符串以【{$splitStr}】切割为数组并转json",
                        'arg' => $res2,
                        'valid' => true,
                    ];
                }
            }


            if (mb_strpos($str, "\n") !== false) {
                // 有换行符的情况下，加一个逗号隔开的处理
                $strArr = explode("\n", $str);
                $strArr = array_filter($strArr, function ($item) {
                    return !empty($item);
                });
                $res = implode(',', $strArr);
                $workFlowsRes[] = [
                    'uid' => 'strToComma',
                    'title' => $res,
                    'subTitle' => '字符串以换行符切换为逗号隔开',
                    'arg' => $res,
                    'valid' => true,
                ];
                $strArr2 = array_map(function ($item) {
                    return "'{$item}'";
                }, $strArr);
                $res2 = implode(',', $strArr2);
                $workFlowsRes[] = [
                    'uid' => 'strToComma2',
                    'title' => $res2,
                    'subTitle' => '字符串以换行符切换为逗号隔开带引号',
                    'arg' => $res2,
                    'valid' => true,
                ];
            }
        }

        if (count($workFlowsRes) == 0) {
            $workFlowsRes[] = [
                'uid' => 'strToFail',
                'title' => empty($query) ? '请检查字符串' : $query,
                'subTitle' => '请检查字符串',
                'arg' => empty($query) ? '请检查字符串' : $query,
                'valid' => true,
            ];
        }
        return $workFlowsRes;
    }
}