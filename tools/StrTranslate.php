<?php

class Tools_StrTranslate extends Tools_Base
{
    public static function getWorkFlowsRes(string $query): array
    {
        $workFlowsRes = [];
        $bdAppId = getenv('BDFY_APP_ID');
        $bdSecKey = getenv('BDFY_SECRET');

        if (empty($bdAppId) || empty($bdSecKey)) {
            $workFlowsRes[] = [
                'uid' => 'bdfy-config-error',
                'title' => '配置异常',
                'subTitle' => '请检查配置BDFY_APP_ID/BDFY_SECRET是否为空',
                'arg' => '配置异常',
                'valid' => false,
            ];
        } else {
            try {
                $bdTransApi = new Src_BdTransApi($bdAppId, $bdSecKey);
                $list = $bdTransApi->translate($query);
                foreach ($list as $item) {
                    $workFlowsRes[] = [
                        'uid' => $item['uid'],
                        'title' => $item['title'],
                        'subTitle' => $item['subTitle'],
                        'arg' => $item['arg'],
                        'icon' => !empty($item['icon']) ? $item['icon'] : '',
                        'valid' => $item['valid'],
                        'quickLookUrl' => !empty($item['quickLookUrl']) ? $item['quickLookUrl'] : '',
                        'mods' => !empty($item['mods']) ? $item['mods'] : '',
                    ];
                }
            } catch (Exception $e) {
                $workFlowsRes[] = [
                    'uid' => 'bdfy-class-error',
                    'title' => '配置异常',
                    'subTitle' => $e->getMessage(),
                    'arg' => '配置异常',
                    'valid' => false,
                ];
            }
        }

        return $workFlowsRes;
    }

}