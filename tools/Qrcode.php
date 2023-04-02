<?php

require_once(__DIR__ . '/../extend/QRcodeLib.php');

class Tools_Qrcode extends Tools_Base
{
    public static function getWorkFlowsRes(string $query): array
    {
        $workFlowsRes = [];

        $filePath = __DIR__ . '/../tmp/';
        $filePathEnv = trim(getenv('QR_CODE_FILE_PATH'));
        if ($filePathEnv) {
            $filePath = $filePathEnv;
        }
        $fileName = md5($query) . '.png';

        $file = $filePath . $fileName;
        QRcode::png($query, $file, QR_ECLEVEL_H, 10);

        $success = file_exists($file);
        if ($success) {
            $workFlowsRes[] = [
                'uid' => 'qrcode',
                'type' => 'file',
                'title' => '生成二维码',
                'subTitle' => $query,
                'arg' => $file,
                'valid' => true,
            ];
        }

        if (count($workFlowsRes) == 0) {
            $workFlowsRes[] = [
                'uid' => 'qrcodeFail',
                'title' => empty($query) ? '请检查字符串' : $query,
                'subTitle' => '请检查字符串',
                'arg' => empty($query) ? '请检查字符串' : $query,
                'valid' => true,
            ];
        }
        return $workFlowsRes;
    }
}
