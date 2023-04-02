<?php

class Tools_DateTime extends Tools_Base
{

    public static function getWorkFlowsRes(string $query): array
    {
        $workFlowsRes = [];

        // 先把+-日期的提取出来
        $timeInput = str_replace(['years', 'months', 'days', 'hours', 'minutes', 'seconds'], ['year', 'month', 'day', 'hour', 'minute', 'second'], $query);
        $regTag = '/\s*(\+?|\-)\d+(year|month|day|hour|minute|second)/';
        preg_match_all($regTag, $timeInput, $matches);

        $tags = [];
        if (!empty($matches[0])) {
            foreach ($matches[0] as $tag) {
                $tags[] = trim($tag);
            }
            $timeInput = trim(str_replace($matches[0], '', $timeInput));
        }

        $regDateTime = '/^[1-9]\d{3}(\.|-|\/)(0?[1-9]|1[0-2])(\.|-|\/)(0?[1-9]|[1-2][0-9]|3[0-1])(\s+((2[0-3])|\d|([0-1]\d))((\.|:)((0?\d)|([1-5]\d))){0,2})?$/';
        $regTimestamp = '/^[0-9]{10}([0-9]{3})?$/i';

        $timeType = 0;
        $timeBase = time();
        $formatErr = false;
        if (!empty($timeInput)) {
            if (preg_match($regDateTime, $timeInput)) {
                $timeType = 1;
                $timeInputArr = explode(' ', $timeInput);
                $timeInput = str_replace(['.', '/'], '-', $timeInputArr[0]);
                if (!empty($timeInputArr[1])) {
                    $timeInput2 = str_replace('.', ':', $timeInputArr[1]);
                    $timeInput2Arr = explode(':', $timeInput2);
                    $timeInput2ArrCount = count($timeInput2Arr);
                    if ($timeInput2ArrCount < 3) {
                        for ($i = 1; $i <= 3 - $timeInput2ArrCount; ++$i) {
                            $timeInput2Arr[] = '00';
                        }
                    }
                    $timeInput .= ' ' . implode(':', $timeInput2Arr);
                }
                $timeBase = strtotime($timeInput);
            } elseif (preg_match($regTimestamp, $timeInput)) {
                $timeBase = intval(substr($timeInput,0,10));
                $timeType = 2;
            } else {
                $formatErr = true;
            }
        }

        if ($formatErr) {
            $workFlowsRes[] = [
                'uid' => 'timeInputFormatError',
                'title' => empty($timeInput) ? '格式有问题，请检查' : $timeInput,
                'subTitle' => '格式有问题，请检查',
                'arg' => empty($timeInput) ? '格式有问题，请检查' : $timeInput,
                'valid' => true,
            ];
        } else {
            if (empty($tags)) {
                if ($timeType == 0 || $timeType == 1) {
                    $workFlowsRes[] = [
                        'uid' => 'timeBase',
                        'title' => $timeBase,
                        'subTitle' => '时间戳',
                        'arg' => $timeBase,
                        'valid' => true,
                    ];
                }
                if ($timeType == 0 || $timeType == 2) {
                    $timeBaseFormat = date('Y-m-d H:i:s', $timeBase);
                    $workFlowsRes[] = [
                        'uid' => 'timeBaseFormat',
                        'title' => $timeBaseFormat,
                        'subTitle' => '格式化时间',
                        'arg' => $timeBaseFormat,
                        'valid' => true,
                    ];
                }
            } else {
                $timeFinish = strtotime(implode(' ', $tags), $timeBase);
                $workFlowsRes[] = [
                    'uid' => 'timeFinish',
                    'title' => $timeFinish,
                    'subTitle' => '时间戳',
                    'arg' => $timeFinish,
                    'valid' => true,
                ];
                $timeFinishFormat = date('Y-m-d H:i:s', $timeFinish);
                $workFlowsRes[] = [
                    'uid' => 'timeFinishFormat',
                    'title' => $timeFinishFormat,
                    'subTitle' => '格式化时间',
                    'arg' => $timeFinishFormat,
                    'valid' => true,
                ];
            }
        }
        return $workFlowsRes;
    }
}