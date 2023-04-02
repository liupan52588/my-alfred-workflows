<?php

class Tools_HtmlToJs extends Tools_Base
{

    public static function getWorkFlowsRes(string $query): array
    {
        $workFlowsRes = [];
        $queryArr = explode("\n", $query);
        $str = 'let str=\'\';';
        foreach ($queryArr as $key => $value) {
            $str .= "\n" . 'str += \'' . str_replace('\'', '"', $value) . '\';';
        }
        $workFlowsRes[] = [
            'uid' => "htmlToJs",
            'title' => $str,
            'subTitle' => 'HTML转成js',
            'arg' => $str,
            'valid' => true,
        ];
        return $workFlowsRes;
    }
}
