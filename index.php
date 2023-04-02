<?php

use Alfred\Workflows\Workflow;
ini_set('date.timezone', 'Asia/Shanghai');
require_once('./autoload.php');
$workflow = new Workflow();

$toolClassName = $queryWhole = $query = $clipboard = '';
if (!empty($argv[1]) && mb_substr($argv[1], 0, 4) === '--c=') {
    $toolClassName = trim(mb_substr($argv[1], 4));
}
if (!empty($argv[2]) && mb_substr($argv[2], 0, 4) === '--d=') {
    $queryWhole = mb_substr($argv[2], 4);
    $query = trim($queryWhole);
}
$useClipboard = false;
if(!empty($argv[3]) && $argv[3] === '--useClipboard=1'){
    $useClipboard = true;
}
$clipboard = getenv('__CLIPBOARD__');

if($queryWhole === '' && $useClipboard){
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
    foreach ($item as $key => $value) {
        if(empty($value)) {
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
                foreach ($mods as $mod){
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