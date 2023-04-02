<?php

class Tools_StrDecode extends Tools_Base
{

    public static function getWorkFlowsRes(string $query): array
    {
        $workFlowsRes = [];

        $decodeCount = 0;
        // urlDecode
        $urlDecode = urldecode($query);
        if ($urlDecode != $query) {
            $decodeCount++;
            $workFlowsRes[] = [
                'uid' => 'urlDecode',
                'title' => $urlDecode,
                'subTitle' => 'urlDecode',
                'arg' => $urlDecode,
                'valid' => true,
            ];
        }

        // HTML
        $htmlDecode = htmlspecialchars_decode($query);
        if ($htmlDecode != $query) {
            $decodeCount++;
            $workFlowsRes[] = [
                'uid' => 'htmlDecode',
                'title' => $htmlDecode,
                'subTitle' => 'html实体转标签(specialchars)',
                'arg' => $htmlDecode,
                'valid' => true,
            ];
        }


        $htmlEntityDecode = html_entity_decode($query, ENT_QUOTES, 'UTF-8');
        if ($htmlEntityDecode != $query) {
            $decodeCount++;
            $workFlowsRes[] = [
                'uid' => 'htmlEntityDecode',
                'title' => $htmlEntityDecode,
                'subTitle' => 'html实体转标签(entity)',
                'arg' => $htmlEntityDecode,
                'valid' => true,
            ];
        }

        // base64
        $base64Decode = base64_decode($query, true);
        if ($base64Decode && $base64Decode != $query) {
            $decodeCount++;
            $workFlowsRes[] = [
                'uid' => 'base64Decode',
                'title' => $base64Decode,
                'subTitle' => 'base64Decode',
                'arg' => $base64Decode,
                'valid' => true,
            ];
        }

        // unicode
        $unicodeDecode = Src_Common::unicodeDecode($query);
        if ($unicodeDecode != $query) {
            $decodeCount++;
            $workFlowsRes[] = [
                'uid' => 'unicodeDecode',
                'title' => $unicodeDecode,
                'subTitle' => 'unicodeDecode',
                'arg' => $unicodeDecode,
                'valid' => true,
            ];
        }

        if ($decodeCount == 0) {
            $workFlowsRes[] = [
                'uid' => 'decode',
                'title' => '没有可用结果',
                'subTitle' => '请检查输入字符串是否需要解码',
                'arg' => empty($query) ? '没有可用结果' : $query,
                'valid' => true,
            ];
        }
        return $workFlowsRes;
    }
}
