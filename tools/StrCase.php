<?php

class Tools_StrCase extends Tools_Base
{

    public static function getWorkFlowsRes(string $query): array
    {
        $workFlowsRes = [];

        if (preg_match('/^[a-zA-Z0-9_]+$/i', $query)) {
            $lower = strtolower($query);
            $workFlowsRes[] = [
                'uid' => 'lower',
                'title' => $lower,
                'subTitle' => '全小写',
                'arg' => $lower,
                'valid' => true,
            ];
            $upper = strtoupper($query);
            $workFlowsRes[] = [
                'uid' => 'upper',
                'title' => $upper,
                'subTitle' => '全大写',
                'arg' => $upper,
                'valid' => true,
            ];
            if (strpos($query, '_') !== false) {
                $strArr = explode('_', $query);
                $str1 = '';
                foreach ($strArr as $str) {
                    $str1 .= strtoupper($str[0]) . substr($str, 1);
                }
                $workFlowsRes[] = [
                    'uid' => 'bigHump',
                    'title' => $str1,
                    'subTitle' => '大坨峰',
                    'arg' => $str1,
                    'valid' => true,
                ];
                $str2 = strtolower($str1[0]) . substr($str1, 1);
                $workFlowsRes[] = [
                    'uid' => 'smallHump',
                    'title' => $str2,
                    'subTitle' => '小坨峰',
                    'arg' => $str2,
                    'valid' => true,
                ];
            } else {
                $queryLen = strlen($query);
                $strRes = '';
                for ($i = 0; $i < $queryLen; ++$i) {
                    $str = $query[$i];
                    $strUpper = strtoupper($str);
                    $strLower = strtolower($str);
                    if ($strUpper === $str) {
                        $strRes .= '_' . $strLower;
                    } else {
                        $strRes .= $strLower;
                    }
                }
                $strRes = ltrim($strRes, '_');
                if (strpos($strRes, '_') !== false) {
                    $str1 = strtolower($strRes);
                    $workFlowsRes[] = [
                        'uid' => 'smallUnderline',
                        'title' => $str1,
                        'subTitle' => '下划线',
                        'arg' => $str1,
                        'valid' => true,
                    ];
                    $str2 = strtoupper($strRes);
                    $workFlowsRes[] = [
                        'uid' => 'bigUnderline',
                        'title' => $str2,
                        'subTitle' => '常量',
                        'arg' => $str2,
                        'valid' => true,
                    ];
                }
            }
        } else {
            $workFlowsRes[] = [
                'uid' => 'errorQuery',
                'title' => empty($query) ? '只能填写数字、字母、下划线' : $query,
                'subTitle' => '只能填写数字、字母、下划线',
                'arg' => empty($query) ? '只能填写数字、字母、下划线' : $query,
                'valid' => false,
            ];
        }
        return $workFlowsRes;
    }
}
