<?php
namespace app\helpers;

use yii\base\Module;

/**
 * Класс helper для получения из url
 * текущий роутинг module/controller/action
 *
 * Class RouterUrlHelper
 * @package core\helpers
 */
class RouterUrlHelper
{
    /**
     * @param string $url
     */
    private static function _parseUrl($url): array
    {
        if (is_array($url)) {

            $url = array_shift($url);
        }

        $data = explode('/', ltrim($url, '/'));

        if ($data[0] == 'gii') {

            $data[] = 'index';
        }

        return $data;
    }
    
    /**
     * Получение роутинга из URL
     * @param array|string|mixed $url
     *
     */
    public static function to($url): ?string
    {
        $route = self::_parseUrl($url);

        $controller = app()->controller;

        $m = $controller->module->id;
        $c = $controller->id;
        $a = $controller->action->id;

        if (empty($route)) {

            return '/' . implode('/', [$m, $c, $a]);
        }

        switch (count($route)) {

            case 3:
                break;

            case 2:

                if ($controller->module instanceof Module) {

                    array_unshift($route, $m);
                }
                break;

            case 1:

                array_unshift($route, $c);
                if ($controller->module instanceof Module) {

                    array_unshift($route, $m);
                }
                break;

            default:
                return null;
        }

        return '/' . implode('/', $route);
    }

    /**
     * Определение активности URL
     */
    public static function isActiveRoute($url): bool|array|null
    {
        $route = self::_parseUrl($url);

        if (empty($route)) return true;

        $controller = app()->controller;
        $m = $controller->module->id;
        $c = $controller->id;
        $a = $controller->action->id;

        return match (count($route)) {
            3 => $route == [$m, $c, $a],
            2 => $route = [$c, $a],
            1 => $route == [$a],
            default => null,
        };
    }

    /**
     * @param string $url
     */
    public static function getAction($url): ?string
    {
        $url = self::_parseUrl($url);

        return !empty($url) ? $url[count($url) - 1] : null;
    }
}