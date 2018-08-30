<?php
/**
 * Created by PhpStorm.
 * User: ewerson
 * Date: 29/08/18
 * Time: 15:09
 */

namespace Ewersonfc\CNABPagamento\Exceptions;


class FileRetornoException extends CNABPagamentoException
{
    /**
     * FileRetornoException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        if($message == "")
            $message = "Não foi possível ler o arquivo, verifique o caminho ou a permissão de leitura.";

        parent::__construct($message, $code, $previous);
    }
}