<?php

abstract class Tools_Base
{

    /**
     * 获取workflows返回列表信息
     * @param string $query
     * @return array
     */
    abstract public static function getWorkFlowsRes(string $query): array;

}