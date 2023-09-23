<?php
namespace app\helpers;

/**
 * Класс helper для работы с различными статусами модулей
 *
 * Class StatusHelper
 * @package core\helpers
 */
abstract class ListHelper
{
    public static function getList()
    {

    }

    public static function getHtmlList()
    {

    }

    const EMPTY_STATUS_HTML = '<span class="label label-default">*неизвестно*</span>';

    const EMPTY_STATUS = '*неизвестно*';


    /**
     * @param string $key
     * @param bool $asHtml
     * @return string
     */
    public static function getValue($key, $asHtml = false)
    {
        $list = $asHtml
            ? static::getHtmlList()
            : static::getList();

        if (isset($list[$key])) return $list[$key];

        return $asHtml ? self::EMPTY_STATUS_HTML : self::EMPTY_STATUS;
    }
}