<?php
/**
 * Created by PhpStorm.
 * User: ewerson
 * Date: 02/08/18
 * Time: 12:12
 */

namespace Ewersonfc\CNABPagamento\Exceptions;


use Throwable;

/**
 * Class HeaderYamlException
 * @package Ewersonfc\CNABPagamento\Exceptions
 */
class HeaderYamlException extends CNABPagamentoException
{
    /**
     * HeaderYamlException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}