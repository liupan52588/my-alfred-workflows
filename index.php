<?php

use Alfred\Workflows\Workflow;

ini_set('date.timezone', 'Asia/Shanghai');
require_once('./autoload.php');
$workflow = new Workflow();

$toolClassName = $queryWhole = $query = $clipboard = '';
$useClipboard = false;
$returnText = false;

foreach ($argv as $argvKey => $argvValue) {
    if ($argvKey >= 1) {
        if (mb_substr($argvValue, 0, 4) === '--c=') {
            $toolClassName = trim(mb_substr($argvValue, 4));
        } elseif (mb_substr($argvValue, 0, 4) === '--d=') {
            $queryWhole = mb_substr($argvValue, 4);
            $query = trim($queryWhole);
        } elseif ($argvValue === '--useClipboard=1') {
            $useClipboard = true;
        } elseif ($argvValue === '--returnText=1') {
            $returnText = true;
        }
    }
}

$clipboard = getenv('__CLIPBOARD__');

if ($queryWhole === '' && $useClipboard) {
    $query = $clipboard;
}

try {
    $toolClass = 'Tools_' . $toolClassName;
    $resList = $toolClass::getWorkFlowsRes($query);
} catch (Exception $e) {
    $resList[] = [
        'uid' => 'catchError',
        'title' => '处理异常',
        'subTitle' => $e->getMessage(),
        'arg' => '处理异常',
        'icon' => 'icon.png',
        'valid' => false,
    ];
}

if ($returnText && count($resList) === 1) {
    $item = $resList[0];
    if (!isset($item['type'])) {
        $item['type'] = 'default';
    }
    $arg = $item['type'] . '__TYPE_SPLIT__' . $item['arg'];
    echo $arg;
    die;
}

foreach ($resList as $item) {
    $res = $workflow->result();
    if (!isset($item['icon'])) {
        $item['icon'] = 'icon.png';
    }
    if (!isset($item['copy'])) {
        $item['copy'] = $item['title'];
    }
    if (!isset($item['type'])) {
        $item['type'] = 'default';
    }
    $item['arg'] = $item['type'] . '__TYPE_SPLIT__' . $item['arg'];
    foreach ($item as $key => $value) {
        if (empty($value)) {
            continue;
        }
        switch ($key) {
            case 'uid':
                $res->uid($value);
                break;
            case 'title':
                $res->title($value);
                break;
            case 'subTitle':
                $res->subtitle($value);
                break;
            case 'arg':
                $res->arg($value);
                break;
            case 'icon':
                $res->icon($value);
                break;
            case 'valid':
                $res->valid($value);
                break;
            case 'quickLookUrl':
                $res->quicklookurl($value);
                break;
            case 'type':
                $res->type($value);
                break;
            case 'copy':
                $res->copy($value);
                break;
            case 'largeType':
                $res->largetype($value);
                break;
            case 'mods':
                $mods = isset($value[0]) ? $value : [$value];
                foreach ($mods as $mod) {
                    $res->mod($mod['mod'], $mod['subTitle'], $mod['arg'], $mod['valid']);
                }
                break;
            case 'autoComplete':
                $res->autocomplete($value);
                break;
        }
    }
}
echo $workflow->output();
