<?php

class Tools_JsonTo extends Tools_Base
{

    public static function getWorkFlowsRes(string $query): array
    {
        $workFlowsRes = [];
        $queryArr = json_decode($query, true);

        if (is_array($queryArr)) {
            $res = Src_Common::arrStrFormat($queryArr, true);
            $workFlowsRes[] = [
                'uid' => "jsonToArr",
                'title' => $res,
                'subTitle' => '转换PHP数组',
                'arg' => $res,
                'valid' => true,
            ];
            $xml = Src_Common::arrayToXml($queryArr);
            $workFlowsRes[] = [
                'uid' => "jsonToXml",
                'title' => $xml,
                'subTitle' => '转换xml',
                'arg' => $xml,
                'valid' => true,
            ];
            $httpQuery = http_build_query($queryArr);
            $workFlowsRes[] = [
                'uid' => "httpQuery",
                'title' => $httpQuery,
                'subTitle' => 'http query',
                'arg' => $httpQuery,
                'valid' => true,
            ];
        } else {
            $res = $query;
            $workFlowsRes[] = [
                'uid' => "jsonToArr-fail",
                'title' => $res,
                'subTitle' => '非json格式',
                'arg' => $res,
                'valid' => false,
            ];
        }

        return $workFlowsRes;
    }
}
