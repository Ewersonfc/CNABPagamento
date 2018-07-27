<?php
/**
 * Created by PhpStorm.
 * User: ewerson
 * Date: 27/07/18
 * Time: 15:02
 */
namespace Ewersonfc\CNABPagamento\Exceptions;

use Exception;
use Throwable;

/**
 * Class CNABPagamentoException
 * @package Ewersonfc\CNABPagamento\Exceptions
 */
class CNABPagamentoException extends Exception
{
    /**
     * CNABPagamentoException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        if(!$message)
            $message = 'Não foi possível gerar arquivo de remessa.';

        parent::__construct($message, $code, $previous);
    }
}