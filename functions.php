<?php
/** @noinspection PhpUndefinedClassInspection */
/** @noinspection PhpUndefinedNamespaceInspection */

use app\components\WebUser;
use yii\caching\CacheInterface;
use yii\console\Application as ConsoleApplication;
use yii\helpers\VarDumper;
use yii\web\Application as WebApplication;

/**
 * debug function
 * @param $data
 * @param bool $isDie
 *
 */
function printr($data, $isDie = false)
{

    //if (!((defined('YII_ENV') && YII_ENV === 'dev') && (defined('YII_DEBUG') && YII_DEBUG))) return;

    if ($data === null) {

        echo '<pre>Parameter is null</pre>';
    } else {

        echo '<pre>', print_r(VarDumper::dumpAsString($data), 1), '</pre>';
    }

    if ($isDie) die();
}

/**
 * @return ConsoleApplication|WebApplication the application instance
 */
function app()
{

    return Yii::$app;
}


/**
 * @return WebUser
 */
function user()
{

    return app()->user;
}


/**
 * @return CacheInterface
 */
function cache()
{

    return app()->cache;
}


/**
 * @param $file
 * @return int
 */
function file_crc32($file)
{

    if (!file_exists($file)) return 0;

    return crc32(file_get_contents($file));
}


/**
 * @param array $data
 * @param int $rows
 * @return array
 */
function divideDataBatchInsert(array $data, $rows = 5000)
{
    $divide_array = [];
    if (count($data) > $rows) {

        $i = 0;
        $j = 0;
        foreach ($data as $r) {

            $divide_array[$j][$i++] = $r;

            if ($i > $rows) {

                $i = 0;
                $j += 1;
            }
        }
    } else {
        $divide_array[] = $data;
    }
    return $divide_array;
}



/**
 * @return mixed
 */
function array_orderby()
{
    $args = func_get_args();
    $data = array_shift($args);
    foreach ($args as $n => $field) {
        if (is_string($field)) {
            $tmp = array();
            foreach ($data as $key => $row)
                $tmp[$key] = $row[$field];
            $args[$n] = $tmp;
        }
    }
    $args[] = &$data;
    call_user_func_array('array_multisort', $args);
    return array_pop($args);
}


/**
 * @param $millimeters
 * @return float
 */
function m2t($millimeters)
{
    return floor($millimeters*56.7); //1 твип равен 1/567 сантиметра
}//m2t

/**
 * @param $date
 * @return false|string
 */
function apiDate($date)
{
    return date('Y-m-d', strtotime($date));
}

function viewException(Exception $e)
{
    printr($e->getMessage());
    printr($e->getFile());
    printr('Строка: ' . $e->getLine());
    printr($e->getTraceAsString(), 1);
}
