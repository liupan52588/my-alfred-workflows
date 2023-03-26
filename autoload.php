<?php

require_once('./vendor/autoload.php');

spl_autoload_register('_class_autoload');
//存储已加载的类
static $_loadedClass = array();
/**
 * 类的自动加载函数
 * @param string $className
 * @return bool
 */
function _class_autoload(string $className): bool
{
    global $_loadedClass;

    if (!in_array($className, $_loadedClass)) {
        //把类名根据"_",转换成数组
        $pathArr = explode('_', $className);
        $filePath = __DIR__ . '/' . implode('/', $pathArr) . '.php';
        if (!file_exists($filePath)) {
            return false;
        } else {
            require_once($filePath);
            $_loadedClass[$className] = $filePath;
            return true;
        }
    }
    return false;
}