<?php
/**
 * Created by PhpStorm.
 * User: ewerson
 * Date: 27/07/18
 * Time: 15:12
 */
namespace Ewersonfc\CNABPagamento\Entities;

/**
 * Class DataFile
 * @package Ewersonfc\CNABPagamento\Entities
 */
class DataFile
{
    /**
     * @var
     */
    public $header;

    /**
     * @var array
     */
    public $detail = [];

    /**
     * @var
     */
    public $trailer;
}