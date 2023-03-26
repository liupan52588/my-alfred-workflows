<?php

class Tools_StrRandom extends Tools_Base
{

    public static function getWorkFlowsRes(string $query): array
    {
        $workFlowsRes = [];
        $length = intval($query);
        $length = $length > 0 ? $length : 10;
        $mode = 1;
        while ($mode <= 8) {
            $res = Src_Common::getRandomCode($length, $mode);
            $workFlowsRes[] = [
                'uid' => 'strRandom',
                'title' => $res['randomStr'],
                'subTitle' => '随机字符源：' . $res['modeStr'],
                'arg' => $res['randomStr'],
                'valid' => true,
            ];
            $mode++;
        }
        return $workFlowsRes;
    }
}
