<?php
/**
 * Created by PhpStorm.
 * User: ewerson
 * Date: 02/08/18
 * Time: 16:05
 */

namespace Ewersonfc\CNABPagamento\Factories;
use Ewersonfc\CNABPagamento\Helpers\FunctionsHelper;

/**
 * Class RemessaFactory
 * @package Ewersonfc\CNABPagamento\Factories
 */
class RemessaFactory
{
    function __construct()
    {

    }



    public function generateFile(array $header, array $detail)
    {

        FunctionsHelper::picture('9(40)');

        echo '<pre>';
        print_r($header);
        print_r($detail);
        echo '</pre>';
    }


}