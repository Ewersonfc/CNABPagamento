<?php
/**
 * Created by PhpStorm.
 * User: ewerson
 * Date: 02/08/18
 * Time: 16:33
 */

namespace Ewersonfc\CNABPagamento\Helpers;

/**
 * Class FunctionsHelper
 * @package Ewersonfc\CNABPagamento\Helpers
 */
class FunctionsHelper
{
    /**
     * @param $picture
     * @return false|int
     */
    public static function picture($picture)
    {
        return preg_match('/[X9]\(\d+\)(V9\(\d+\))?/', $picture);
    }
}

