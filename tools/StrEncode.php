<?php

class Tools_StrEncode extends Tools_Base
{

    public static function getWorkFlowsRes(string $query): array
    {
        $workFlowsRes = [];

        $encodeCount = 0;

        // urlEncode
        $urlEncode = urlencode($query);
        if ($urlEncode != $query) {
            $encodeCount++;
            $workFlowsRes[] = [
                'uid' => 'urlEncode',
                'title' => $urlEncode,
                'subTitle' => 'urlEncode编码',
                'arg' => $urlEncode,
                'valid' => true,
            ];
        }

        // HTML
        if (strip_tags($query) != $query) {
            $htmlEncode = htmlspecialchars($query);
            if ($htmlEncode != $query) {
                $encodeCount++;
                $workFlowsRes[] = [
                    'uid' => 'htmlEncode',
                    'title' => $htmlEncode,
                    'subTitle' => 'html实体转义(specialchars)',
                    'arg' => $htmlEncode,
                    'valid' => true,
                ];
            }

            $htmlEntity = htmlentities($query, ENT_QUOTES, 'UTF-8');
            if ($htmlEntity != $query) {
                $encodeCount++;
                $workFlowsRes[] = [
                    'uid' => 'htmlEntity',
                    'title' => $htmlEntity,
                    'subTitle' => 'html实体转义(entity)',
                    'arg' => $htmlEntity,
                    'valid' => true,
                ];
            }
        }

        // base64
        $base64Encode = base64_encode($query);
        if ($base64Encode != $query) {
            $encodeCount++;
            $workFlowsRes[] = [
                'uid' => 'base64Encode',
                'title' => $base64Encode,
                'subTitle' => 'base64编码',
                'arg' => $base64Encode,
                'valid' => true,
            ];
        }


        // unicode
        $unicode = Src_Common::unicodeEncode($query);
        if ($unicode != $query) {
            $encodeCount++;
            $workFlowsRes[] = [
                'uid' => 'unicode',
                'title' => $unicode,
                'subTitle' => 'unicode编码',
                'arg' => $unicode,
                'valid' => true,
            ];
        }

        if ($encodeCount == 0) {
            $workFlowsRes[] = [
                'uid' => 'encode',
                'title' => '没有可用的结果',
                'subTitle' => '参数有问题，请重试',
                'arg' => $query,
                'valid' => false,
            ];
        }

        return $workFlowsRes;
    }
}
