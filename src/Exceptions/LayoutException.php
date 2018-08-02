<?php
/**
 * Created by PhpStorm.
 * User: ewerson
 * Date: 02/08/18
 * Time: 12:28
 */

namespace Ewersonfc\CNABPagamento\Exceptions;

use Throwable;
/**
 * Class LayoutException
 * @package Ewersonfc\CNABPagamento\Exceptions
 */
class LayoutException extends CNABPagamentoException
{
    /**
     * LayoutException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}