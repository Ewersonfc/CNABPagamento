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

    /**
     * @param $picture
     * @return array
     */
    public static function explodePicture($picture)
    {
        $pictureExploded = explode("-", preg_replace("/[^0-9A-Z]/", "-", $picture));

        return [
            'firstType' => $pictureExploded[0],
            'firstQuantity' => $pictureExploded[1],
            'secondType' => !isset($pictureExploded[2])?: $pictureExploded[2],
            'secondQuantity' => !isset($pictureExploded[3])?: $pictureExploded[3]
        ];
    }

    public static function treatField($value, $positionInit, $positionFinish, $quantityCharacter, $defaultValue = null)
    {

    }
}
